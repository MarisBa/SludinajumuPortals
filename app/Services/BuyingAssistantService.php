<?php

namespace App\Services;

use App\Models\Advertisement;
use App\Models\Category;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BuyingAssistantService
{
    /**
     * Galvenā metode — saņem lietotāja pieprasījumu un atgriež
     * līdz 3 ieteiktajiem sludinājumiem ar AI paskaidrojumu.
     *
     * Atgriežamais masīvs vienmēr satur:
     *   - ai_available   (bool)   vai AI atbilde tika veiksmīgi saņemta
     *   - intro          (?string) īsa ievada frāze no AI
     *   - tip            (?string) noderīgs padoms no AI
     *   - advertisements (Collection|array) atlasītie sludinājumi
     *   - message        (?string) ziņa lietotājam (piem., ja nekas nav atrasts)
     */
    public function recommend(string $userQuery): array
    {
        // 2.1 — Kandidātu izgūšana no datubāzes
        $query = Advertisement::query()
            ->where('published', 1)
            ->whereNotNull('feature_image')
            ->with(['user', 'category', 'city']);

        // Kategorijas noteikšana pēc atslēgvārdiem.
        // Latviešu vārdiem ir locījumi, tāpēc salīdzinām pēc saknes (4-5 burti).
        $lowerQuery = mb_strtolower($userQuery);
        $categories = Category::select('id', 'name')->get();

        foreach ($categories as $cat) {
            $stem = mb_strtolower(mb_substr($cat->name, 0, 5));
            if ($stem !== '' && Str::contains($lowerQuery, $stem)) {
                $query->where('category_id', $cat->id);
                break;
            }
        }

        // Cenas filtrs — meklējam skaitli ar regex (piem., "līdz 4000", "4000 eur", "4000€").
        if (preg_match('/(\d{2,7})/u', $lowerQuery, $matches)) {
            $maxPrice = (float) $matches[1];
            // price kolonna ir STRING tipa, tāpēc SQL pusē veicam CAST.
            $query->whereRaw('CAST(price AS DECIMAL(12,2)) <= ?', [$maxPrice]);
        }

        // Maksimums 25 kandidāti — gan AI izsaukumam, gan fallback ceļam.
        $candidates = $query->latest()->limit(25)->get();

        if ($candidates->isEmpty()) {
            return [
                'ai_available'   => false,
                'intro'          => null,
                'tip'            => null,
                'advertisements' => collect(),
                'message'        => 'Pēc šī pieprasījuma neatradu nevienu sludinājumu. Pamēģini formulēt citādi.',
            ];
        }

        // 2.2 — Kompakta payload izveide AI modelim
        $list = $candidates->map(function ($ad) {
            return [
                'id'           => $ad->id,
                'name'         => $ad->name,
                'price'        => $ad->price,
                'price_status' => $ad->price_status,
                'condition'    => $ad->product_condition,
                'location'     => $ad->listing_location ?? optional($ad->city)->name,
                'description'  => Str::limit(strip_tags((string) $ad->description), 200),
                'seller_trust' => optional($ad->user)->verification_level,
                'seller_ads'   => optional($ad->user)
                    ? $ad->user->advertisements()->where('published', 1)->count()
                    : 0,
            ];
        })->all();

        $candidatesJson = json_encode($list, JSON_UNESCAPED_UNICODE);

        // Sistēmas prompts — stingri noteikumi AI modelim.
        $systemPrompt = <<<'PROMPT'
Tu esi sludinājumu portāla pirkšanas asistents. Tev tiek dots lietotāja
pieprasījums un pieejamo sludinājumu saraksts JSON formātā. Tavs uzdevums:
izvēlēties līdz 3 vispiemērotākajiem sludinājumiem un īsi pamatot izvēli
latviešu valodā.

Stingri noteikumi:
- Izmanto TIKAI dotos sludinājumus. Nekad neizdomā sludinājumus vai datus.
- Atsaucies uz sludinājumiem pēc to "id" lauka.
- Ņem vērā pārdevēja uzticamību (seller_trust: gold=augsta, silver=vidēja,
  bronze=zema) — priekšroku dod uzticamākiem pārdevējiem.
- Brīdini lietotāju, ja kāds variants šķiet riskants (ļoti īss apraksts vai
  cena daudz zemāka par citiem līdzīgiem sludinājumiem).
- Atbildi TIKAI ar derīgu JSON. Bez koda blokiem, bez teksta ārpus JSON.

JSON formāts:
{
  "intro": "viens draudzīgs teikums latviski",
  "recommendations": [
    {"id": 12, "reason": "kāpēc tieši šis, 1-2 teikumi latviski"}
  ],
  "tip": "viens noderīgs pirkšanas padoms latviski"
}
PROMPT;

        $userPrompt = "Lietotāja pieprasījums: \"{$userQuery}\"\n\nPieejamie sludinājumi (JSON):\n{$candidatesJson}";

        $apiKey = config('services.anthropic.key');
        $model  = config('services.anthropic.model');
        $response = null;

        try {
            if (empty($apiKey)) {
                throw new \RuntimeException('ANTHROPIC_API_KEY nav konfigurēts (tukšs vai null).');
            }

            // 2.3 — Anthropic API izsaukums
            $response = Http::timeout(25)
                ->withHeaders([
                    'x-api-key'         => $apiKey,
                    'anthropic-version' => '2023-06-01',
                    'content-type'      => 'application/json',
                ])
                ->post('https://api.anthropic.com/v1/messages', [
                    'model'      => $model,
                    'max_tokens' => 1200,
                    'system'     => $systemPrompt,
                    'messages'   => [[
                        'role'    => 'user',
                        'content' => $userPrompt,
                    ]],
                ]);

            if ($response->failed()) {
                Log::error('Assistant API response: ' . $response->body());
                throw new \RuntimeException('Anthropic API neveiksmīgs HTTP statuss: ' . $response->status() . ' (model=' . $model . ')');
            }

            // 2.4 — Atbildes parsēšana
            $rawText = (string) $response->json('content.0.text');
            $clean = trim($rawText);
            // Noņem iespējamos markdown koda blokus.
            $clean = preg_replace('/^```(?:json)?\s*/i', '', $clean);
            $clean = preg_replace('/\s*```$/', '', $clean);
            $clean = trim($clean);

            $parsed = json_decode($clean, true);
            if (!is_array($parsed) || !isset($parsed['recommendations']) || !is_array($parsed['recommendations'])) {
                throw new \RuntimeException('AI atbilde nav derīgs JSON vai trūkst recommendations.');
            }

            // Drošības princips: ID atlase notiek AI pusē, bet datus
            // vienmēr no jauna izgūstam no datubāzes — modelim neuzticamies.
            $ids = collect($parsed['recommendations'])
                ->pluck('id')
                ->filter()
                ->map(fn ($id) => (int) $id)
                ->all();

            // AI var pamatoti atgriezt tukšu sarakstu (piem., neviens variants
            // neatbilst budžetam). Tādā gadījumā saglabājam tā intro un padomu,
            // nevis krītam SQL fallbackā — lietotājs saņem godīgu skaidrojumu.
            if (empty($ids)) {
                return [
                    'ai_available'   => true,
                    'intro'          => $parsed['intro'] ?? null,
                    'tip'            => $parsed['tip'] ?? null,
                    'advertisements' => collect(),
                    'message'        => null,
                ];
            }

            $ads = Advertisement::with(['user', 'category', 'city'])
                ->whereIn('id', $ids)
                ->where('published', 1)
                ->get()
                ->sortBy(fn ($a) => array_search($a->id, $ids))
                ->values();

            // Katram sludinājumam piesaistām AI paskaidrojumu pēc ID.
            $reasonsById = collect($parsed['recommendations'])
                ->keyBy('id');

            $ads->each(function ($ad) use ($reasonsById) {
                $rec = $reasonsById->get($ad->id);
                $ad->ai_reason = is_array($rec) ? ($rec['reason'] ?? null) : null;
            });

            return [
                'ai_available'   => true,
                'intro'          => $parsed['intro'] ?? null,
                'tip'            => $parsed['tip'] ?? null,
                'advertisements' => $ads,
                'message'        => null,
            ];
        } catch (\Throwable $e) {
            // 2.5 — Drošības tīkls: ja AI nav pieejams vai atbild nepareizi,
            // tomēr atgriežam SQL atlasītus kandidātus bez paskaidrojumiem.
            Log::error('Assistant API error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            if ($response !== null) {
                Log::error('Assistant API response: ' . $response->body());
            }

            return [
                'ai_available'   => false,
                'intro'          => null,
                'tip'            => null,
                'advertisements' => $candidates->take(6),
                'message'        => null,
            ];
        }
    }
}
