<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * RealisticAdvertisementsSeeder
 *
 * Izveido ~300 reālistiskus Latvijas sludinājumus ar īstiem nosaukumiem,
 * cenām, aprakstiem un Unsplash bildēm. Dinamiski atrod kategoriju,
 * valsts un pilsētu ID no datubāzes — nav hardkodētu ID.
 *
 * Palaišana: php artisan db:seed --class=RealisticAdvertisementsSeeder
 * Notīrīšana: php artisan db:seed --class=RealisticAdvertisementsSeeder --fresh
 */
class RealisticAdvertisementsSeeder extends Seeder
{
    public function run(): void
    {
        // ─── 1. Notīrām esošos demo datus (bet saglabājam admin un locations) ───
        DB::table('advertisements')->delete();
        DB::table('subcategories')->delete();
        DB::table('categories')->delete();
        // Dzēšam demo lietotājus, bet saglabājam admin
        DB::table('users')->where('role', '!=', 'admin')->delete();

        // ─── 2. Latvijas lokācijas (dinamiski no DB) ───
        $latviaId = DB::table('countries')->where('code', 'LV')->value('id')
            ?? DB::table('countries')->where('name', 'Latvia')->value('id')
            ?? 120;

        // Latvijas pilsētas ar state_id
        $locations = [
            ['city' => 'Rīga',        'state' => 'Riga'],
            ['city' => 'Rīga',        'state' => 'Rigas'],
            ['city' => 'Jūrmala',     'state' => 'Jurmala City'],
            ['city' => 'Liepāja',     'state' => 'Liepaja'],
            ['city' => 'Jelgava',     'state' => 'Jelgava'],
            ['city' => 'Valmiera',    'state' => 'Valmieras'],
            ['city' => 'Sigulda',     'state' => 'Rigas'],
            ['city' => 'Ventspils',   'state' => 'Ventspils City'],
            ['city' => 'Daugavpils',  'state' => 'Daugavpils City'],
            ['city' => 'Ogre',        'state' => 'Ogres'],
        ];

        // Atrodam state un city ID dinamiski
        $resolvedLocations = [];
        foreach ($locations as $loc) {
            $stateId = DB::table('states')
                ->where('name', $loc['state'])
                ->where('country_id', $latviaId)
                ->value('id');
            $cityId = DB::table('cities')
                ->where('name', 'like', '%' . explode(' ', $loc['city'])[0] . '%')
                ->where('state_id', $stateId)
                ->value('id');
            if ($stateId) {
                $resolvedLocations[] = [
                    'label'    => $loc['city'],
                    'state_id' => $stateId,
                    'city_id'  => $cityId ?? null,
                ];
            }
        }

        // Fallback ja nekas neatrasts
        if (empty($resolvedLocations)) {
            $stateId = DB::table('states')->where('country_id', $latviaId)->value('id') ?? 1;
            $cityId  = DB::table('cities')->where('state_id', $stateId)->value('id') ?? 1;
            $resolvedLocations = [['label' => 'Rīga', 'state_id' => $stateId, 'city_id' => $cityId]];
        }

        // ─── 3. Demo lietotāji ───
        $users = [];
        $demoUsers = [
            ['name' => 'Jānis Bērziņš',   'email' => 'janis@example.com',   'verified' => true,  'phone' => '+37126100001'],
            ['name' => 'Anna Kalniņa',     'email' => 'anna@example.com',    'verified' => true,  'phone' => '+37126100002'],
            ['name' => 'Pēteris Ozols',    'email' => 'peteris@example.com', 'verified' => true,  'phone' => '+37126100003'],
            ['name' => 'Marta Liepiņa',    'email' => 'marta@example.com',   'verified' => true,  'phone' => '+37126100004'],
            ['name' => 'Kārlis Vītols',    'email' => 'karlis@example.com',  'verified' => false, 'phone' => '+37126100005'],
            ['name' => 'Ilze Krūmiņa',    'email' => 'ilze@example.com',    'verified' => true,  'phone' => '+37126100006'],
            ['name' => 'Andris Dūmiņš',   'email' => 'andris@example.com',  'verified' => false, 'phone' => null],
            ['name' => 'Sandra Auzāne',    'email' => 'sandra@example.com',  'verified' => true,  'phone' => '+37126100008'],
            ['name' => 'Māris Kalējs',     'email' => 'maris.k@example.com', 'verified' => true,  'phone' => '+37126100009'],
            ['name' => 'Rūta Stūre',      'email' => 'ruta@example.com',    'verified' => false, 'phone' => '+37126100010'],
        ];

        foreach ($demoUsers as $u) {
            $userId = DB::table('users')->insertGetId([
                'name'              => $u['name'],
                'email'             => $u['email'],
                'password'          => Hash::make('password'),
                'phone'             => $u['phone'],
                'email_verified_at' => $u['verified'] ? now()->subDays(rand(10, 300)) : null,
                'phone_verified_at' => ($u['verified'] && $u['phone']) ? now()->subDays(rand(5, 200)) : null,
                'role'              => 'user',
                'is_blocked'        => false,
                'created_at'        => now()->subDays(rand(30, 500)),
                'updated_at'        => now(),
            ]);
            $users[] = $userId;
        }

        // ─── 4. Kategoriju struktūra ───
        $categoryData = [
            'Transports' => [
                'slug' => 'transports',
                'icon' => 'bi-car-front',
                'subs' => ['Automašīnas', 'Motocikli', 'Velosipēdi', 'Ūdenstransports', 'Rezerves daļas'],
            ],
            'Nekustamais īpašums' => [
                'slug' => 'nekustamais-ipasums',
                'icon' => 'bi-house',
                'subs' => ['Dzīvokļi', 'Mājas', 'Zemes gabali', 'Komercplatības', 'Garāžas'],
            ],
            'Elektronika' => [
                'slug' => 'elektronika',
                'icon' => 'bi-phone',
                'subs' => ['Telefoni', 'Datori un klēpjdatori', 'Televizori', 'Foto un video', 'Audio tehnika'],
            ],
            'Mājai un dārzam' => [
                'slug' => 'majai-un-darzam',
                'icon' => 'bi-house-heart',
                'subs' => ['Mēbeles', 'Sadzīves tehnika', 'Mājas apdare', 'Dārza tehnika', 'Instrumenti'],
            ],
            'Apģērbs un mode' => [
                'slug' => 'apgerbs-un-mode',
                'icon' => 'bi-bag',
                'subs' => ['Sieviešu apģērbs', 'Vīriešu apģērbs', 'Bērnu apģērbs', 'Apavi', 'Somas un aksesuāri'],
            ],
            'Sports un hobiji' => [
                'slug' => 'sports-un-hobiji',
                'icon' => 'bi-bicycle',
                'subs' => ['Sporta inventārs', 'Medības un makšķerēšana', 'Mūzika', 'Kolekcionēšana', 'Grāmatas'],
            ],
            'Dzīvnieki' => [
                'slug' => 'dzivnieki',
                'icon' => 'bi-emoji-smile',
                'subs' => ['Suņi', 'Kaķi', 'Grauzēji', 'Putni', 'Akvāriji'],
            ],
            'Pakalpojumi' => [
                'slug' => 'pakalpojumi',
                'icon' => 'bi-briefcase',
                'subs' => ['IT un tehnoloģijas', 'Celtniecība un remonts', 'Skaistums un veselība', 'Apmācība', 'Citi pakalpojumi'],
            ],
        ];

        $catIds = [];
        $subIds = [];

        foreach ($categoryData as $catName => $catInfo) {
            $catId = DB::table('categories')->insertGetId([
                'name'       => $catName,
                'slug'       => $catInfo['slug'],
                'image'      => 'categories/' . $catInfo['slug'] . '.jpg',
                'icon'       => $catInfo['icon'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $catIds[$catName] = $catId;
            $subIds[$catName] = [];

            foreach ($catInfo['subs'] as $subName) {
                $subId = DB::table('subcategories')->insertGetId([
                    'category_id' => $catId,
                    'name'        => $subName,
                    'slug'        => Str::slug($subName),
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
                $subIds[$catName][] = $subId;
            }
        }

        // ─── 5. Sludinājumu dati pa kategorijām ───
        // Katrā kategorijā ~37-38 sludinājumi → kopā ~300

        $listings = [

            // ═══ TRANSPORTS (~38) ═══
            'Transports' => [
                ['VW Golf 2016 1.6 TDI 115zs', 11500, 'Labi saglabājies',
                 'VW Golf 7 ģenerācija, 1.6 TDI motors, 115 zirgspēki. Nobraukums 148 000 km. Pilns servisa vēstures žurnāls, jauna ķēde un sveces. Izcils stāvoklis.',
                 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=800&q=80'],

                ['Toyota Yaris 2018 1.0', 9800, 'Labi saglabājies',
                 'Toyota Yaris, 2018. gads, 1.0 benzīna motors. Nobraukums 62 000 km. Pilna apkope Toyota servisā. Ekonomisks pilsētas auto — 5.2 l/100km.',
                 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?w=800&q=80'],

                ['BMW 320d xDrive 2017', 19500, 'Labi saglabājies',
                 'BMW 3. sērija, 2.0 dīzelis, 190 zs, xDrive pilnpiedziņa. Nobraukums 112 000 km. M Sport pakete, navigācija, ādas sēdekļi. Rūpīgi kopts.',
                 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800&q=80'],

                ['Audi A4 Avant 2015 2.0 TDI', 14200, 'Labi saglabājies',
                 'Audi A4 Avant universāls, 2.0 TDI, 150 zs. Nobraukums 178 000 km. S-Line ārpakete, panorāmas jumts, LED lukturi. Ideāls ģimenes auto.',
                 'https://images.unsplash.com/photo-1606016159991-dfe4f2746ad5?w=800&q=80'],

                ['Mercedes-Benz C220 CDI 2014', 16800, 'Labi saglabājies',
                 'Mercedes C220 CDI, 170 zs. Nobraukums 165 000 km. Avangarde pakete, automātiskā ātrumkārba, ādas sēdekļi. Pilna MB servisa vēsture.',
                 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=800&q=80'],

                ['Opel Astra K 2017 1.4T', 8900, 'Labi saglabājies',
                 'Opel Astra K, 1.4 Turbo, 125 zs. Nobraukums 89 000 km. Iebūvētā navigācija, IntelliLink sistēma, siltināmie sēdekļi. Ļoti labs stāvoklis.',
                 'https://images.unsplash.com/photo-1541899481282-d53bffe3c35d?w=800&q=80'],

                ['Ford Focus 2019 1.0 EcoBoost', 12300, 'Labi saglabājies',
                 'Ford Focus 4. paaudze, 1.0 EcoBoost, 125 zs. Nobraukums 54 000 km. Pilnas LED lukturi, adaptīvs tempomat, atpakaļgaitas kamera.',
                 'https://images.unsplash.com/photo-1494976388531-d1058494cdd8?w=800&q=80'],

                ['Škoda Octavia 2016 1.8 TSI', 13500, 'Labi saglabājies',
                 'Škoda Octavia III, 1.8 TSI, 180 zs. Nobraukums 131 000 km. DSG automātiskā ātrumkārba, navigācija, parkovšanās sensori. Maz lietots, nevainojams.',
                 'https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?w=800&q=80'],

                ['Renault Megane 2015 1.5 dCi', 7400, 'Apmierinošs',
                 'Renault Megane III, 1.5 dCi, 110 zs. Nobraukums 192 000 km. Labi kalpots, jaunas bremžu kluči. Nelielas skrāpējumu pēdas. Cena saskaņojama.',
                 'https://images.unsplash.com/photo-1567818735868-e71b99932e29?w=800&q=80'],

                ['Honda Civic 2020 1.0T', 14900, 'Jauns',
                 'Honda Civic X, 1.0 VTEC Turbo, 129 zs. Nobraukums 28 000 km. Pilna garantija līdz 2025. gadam. Honda Sensing drošības sistēma. Kā jauns.',
                 'https://images.unsplash.com/photo-1619767886558-efdc259b6e09?w=800&q=80'],

                ['Mazda CX-5 2018 2.0 SkyActiv', 18900, 'Labi saglabājies',
                 'Mazda CX-5 II, 2.0 SkyActiv-G, 165 zs. Nobraukums 78 000 km. AWD, BOSE audio sistēma, ādas sēdekļi, HUD displejs. Nevainojams stāvoklis.',
                 'https://images.unsplash.com/photo-1502877338535-766e1452684a?w=800&q=80'],

                ['Volvo XC60 2017 D4', 23500, 'Labi saglabājies',
                 'Volvo XC60 II paaudze, 2.0 D4, 190 zs, AWD. Nobraukums 98 000 km. Momentum pakete, panorāmas jumts, Pilot Assist. Drošākais SUV klasē.',
                 'https://images.unsplash.com/photo-1544636331-e26879cd4d9b?w=800&q=80'],

                ['Kia Sportage 2016 1.7 CRDI', 13800, 'Labi saglabājies',
                 'Kia Sportage III, 1.7 CRDi, 115 zs. Nobraukums 143 000 km. Navigācija, atpakaļgaitas kamera, siltināmie priekšējie sēdekļi.',
                 'https://images.unsplash.com/photo-1519641471654-76ce0107ad1b?w=800&q=80'],

                ['Nissan Qashqai 2019 1.3 DIG-T', 17200, 'Labi saglabājies',
                 'Nissan Qashqai J11 facelift, 1.3 DIG-T, 160 zs. Nobraukums 61 000 km. N-Connecta pakete, 360° kamera, ProPilot tempomat.',
                 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?w=800&q=80'],

                ['Honda CBR600RR 2012', 5800, 'Labi saglabājies',
                 'Honda CBR600RR sportmotocikls, 600cc, 120 zs. Nobraukums 32 000 km. Pilna komplektācija, jauna ķēde un zobrati. Tikai nopietniem pircējiem.',
                 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&q=80'],

                ['Trek FX3 velosipēds 2021', 680, 'Labi saglabājies',
                 'Trek FX 3 Disc hibrīdvelosipēds, izmērs M. Hydraulic disku bremzes, Shimano Deore pārslēdzēji. Nobraukts ap 800 km. Pilna servisa apkope.',
                 'https://images.unsplash.com/photo-1485965120184-e220f721d03e?w=800&q=80'],

                ['Seat Leon 2018 1.5 TSI', 13900, 'Labi saglabājies',
                 'SEAT Leon III facelift, 1.5 TSI, 150 zs. Nobraukums 72 000 km. FR ārpakete, digitālais kokpits, bezvadu CarPlay. Sportisks un ekonomisks.',
                 'https://images.unsplash.com/photo-1571607388263-1044f9ea01dd?w=800&q=80'],

                ['Peugeot 308 2017 1.6 BlueHDi', 9200, 'Labi saglabājies',
                 'Peugeot 308 II, 1.6 BlueHDi, 120 zs. Nobraukums 124 000 km. Allure pakete, navigācija, sildāmie sēdekļi. Labs stāvoklis, jauna TA.',
                 'https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=800&q=80'],

                ['Subaru Outback 2016 2.5', 16500, 'Labi saglabājies',
                 'Subaru Outback V, 2.5 boxer, CVT, AWD. Nobraukums 108 000 km. EyeSight drošības sistēma, ādas sēdekļi, panorāmas jumts. Izdevīgs lauku auto.',
                 'https://images.unsplash.com/photo-1614026480209-cd9934144671?w=800&q=80'],

                ['Lexus IS300h 2015', 21000, 'Labi saglabājies',
                 'Lexus IS 300h hibrīds, 2.5 +elektro, 223 zs. Nobraukums 87 000 km. F Sport pakete, Mark Levinson audio, ādas sēdekļi. Izcila japāņu kvalitāte.',
                 'https://images.unsplash.com/photo-1593941707882-a5bba14938c7?w=800&q=80'],

                ['VW Transporter T6 2017', 24500, 'Labi saglabājies',
                 'VW Transporter T6 2.0 TDI, 150 zs, DSG. Nobraukums 134 000 km. Pasažieru versija 9 vietas, gaisa kondicionieris, navigācija. Ideāls biznesam.',
                 'https://images.unsplash.com/photo-1566008449719-e4e3b2eb12f4?w=800&q=80'],

                ['Audi Q5 2018 2.0 TDI quattro', 28500, 'Labi saglabājies',
                 'Audi Q5 II, 2.0 TDI, 190 zs, quattro. Nobraukums 89 000 km. S-Line, Matrix LED, virtuālais kokpits, Bang & Olufsen audio.',
                 'https://images.unsplash.com/photo-1606016159991-dfe4f2746ad5?w=800&q=80'],

                ['BMW R1250GS Adventure 2020', 18900, 'Labi saglabājies',
                 'BMW R1250GS Adventure, 136 zs. Nobraukums 24 000 km. Pilna papildu ekipējuma komplektācija — GPS, apsildāmās rokturas, crash bars.',
                 'https://images.unsplash.com/photo-1449426468159-d96dbf08f19f?w=800&q=80'],

                ['Hyundai Tucson 2019 1.6 T-GDI', 19800, 'Labi saglabājies',
                 'Hyundai Tucson III facelift, 1.6 T-GDI, 177 zs, 4WD. Nobraukums 58 000 km. Premium pakete, ventilējami sēdekļi, HUD.',
                 'https://images.unsplash.com/photo-1519641471654-76ce0107ad1b?w=800&q=80'],

                ['Dacia Duster 2020 1.3 TCe', 14500, 'Jauns',
                 'Dacia Duster II, 1.3 TCe, 130 zs. Nobraukums 31 000 km. Comfort pakete, MediaNAV, atpakaļgaitas kamera. Vislabākā cena/kvalitātes attiecība SUV klasē.',
                 'https://images.unsplash.com/photo-1502877338535-766e1452684a?w=800&q=80'],

                ['Mercedes-Benz Sprinter 2019', 32000, 'Labi saglabājies',
                 'Mercedes Sprinter 316 CDI, 163 zs. Nobraukums 112 000 km. Garā bāze, augstais jumts. Ideāls transportam vai pārbūvei mikroautobusā.',
                 'https://images.unsplash.com/photo-1566008449719-e4e3b2eb12f4?w=800&q=80'],

                ['Toyota Land Cruiser 2016 3.0 D', 38500, 'Labi saglabājies',
                 'Toyota Land Cruiser 150 Prado, 3.0 D-4D, 190 zs. Nobraukums 145 000 km. 7-sēdvietu versija, ādas sēdekļi, difbloks. Ideāls braucieniem pa lauku.',
                 'https://images.unsplash.com/photo-1544636331-e26879cd4d9b?w=800&q=80'],

                ['Porsche Cayenne 2015 3.0 Diesel', 42000, 'Labi saglabājies',
                 'Porsche Cayenne II, 3.0 TDI, 245 zs. Nobraukums 98 000 km. Platinum Edition, pielāgotā gaisa piekare, panorāmas jumts, BOSE audio.',
                 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=800&q=80'],

                ['Fiat 500 2019 1.2', 8400, 'Labi saglabājies',
                 'Fiat 500 III facelift, 1.2 69 zs. Nobraukums 43 000 km. Pop Star pakete, panorāmas jumts, Apple CarPlay. Ideāls pilsētas auto, viegla stāvvieta.',
                 'https://images.unsplash.com/photo-1619767886558-efdc259b6e09?w=800&q=80'],

                ['Citroën Berlingo 2018 1.6 HDi', 11200, 'Labi saglabājies',
                 'Citroën Berlingo III, 1.6 BlueHDi, 100 zs. Nobraukums 98 000 km. Multispace versija, 3 sēdvietas priekšā, plašs bagāžnieks. Ideāls mazam biznesam.',
                 'https://images.unsplash.com/photo-1580274455191-1c62238fa333?w=800&q=80'],

                ['Kawasaki Z900 2018', 7900, 'Labi saglabājies',
                 'Kawasaki Z900 streetfighter, 948cc, 125 zs. Nobraukums 18 000 km. Akrapovič izpūtējs, tail tidy, sportisks izskats. Meklē jaunu saimnieku.',
                 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=800&q=80'],

                ['Scott Aspect 770 velosipēds', 320, 'Labi saglabājies',
                 'Scott Aspect 770 kalnu velosipēds, izmērs L. Shimano Altus grupēt, hydraulic disku bremzes. Nobraukts 1 sezonu, labs stāvoklis.',
                 'https://images.unsplash.com/photo-1571333250630-f0230c320b6d?w=800&q=80'],

                ['Tesla Model 3 2021 Long Range', 39000, 'Labi saglabājies',
                 'Tesla Model 3 Long Range AWD, 358 zs. Nobraukums 48 000 km. Pilns autopilots, premium audio, tinted stikli. Lādēts galvenokārt mājās.',
                 'https://images.unsplash.com/photo-1560958089-b8a1929cea89?w=800&q=80'],

                ['Yamaha MT-07 2019', 6500, 'Labi saglabājies',
                 'Yamaha MT-07 689cc twin, 75 zs. Nobraukums 14 000 km. A2 kategorijai piemērots (ar ierobežotāju). Sporta izpūtējs, LED lukturi.',
                 'https://images.unsplash.com/photo-1449426468159-d96dbf08f19f?w=800&q=80'],

                ['Volvo V60 2019 D3', 22800, 'Labi saglabājies',
                 'Volvo V60 II, D3 150 zs automāts. Nobraukums 67 000 km. Momentum Pro pakete, IntelliSafe, BOSE, ādas-Nappa sēdekļi. Luxus universāls.',
                 'https://images.unsplash.com/photo-1606016159991-dfe4f2746ad5?w=800&q=80'],

                ['MINI Cooper S 2018 2.0T', 15900, 'Labi saglabājies',
                 'MINI Cooper S F56, 2.0 Turbo, 192 zs. Nobraukums 58 000 km. JCW sport sēdekļi, Harman Kardon audio, LED Headlights. Unikāls un jautrs.',
                 'https://images.unsplash.com/photo-1571607388263-1044f9ea01dd?w=800&q=80'],

                ['Land Rover Discovery Sport 2017', 24900, 'Labi saglabājies',
                 'Land Rover Discovery Sport 2.0 TD4, 150 zs, AWD. Nobraukums 121 000 km. HSE pakete, panorāmas jumts, 7 vietas. Pilna LR servisa vēsture.',
                 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?w=800&q=80'],

                ['Jeep Wrangler JL 2019 2.0T', 44000, 'Labi saglabājies',
                 'Jeep Wrangler Unlimited Sahara, 2.0 Turbo, 272 zs. Nobraukums 52 000 km. Noņemams jumts un durvis. Hardtop iekļauts. Piedzīvojumu auto.',
                 'https://images.unsplash.com/photo-1519641471654-76ce0107ad1b?w=800&q=80'],

                ['VW Polo 2020 1.0 TSI', 13200, 'Jauns',
                 'VW Polo VI, 1.0 TSI, 95 zs. Nobraukums 22 000 km. Comfortline pakete, App-Connect CarPlay, adaptīvs tempomat. Ideāls pirmo auto pircējiem.',
                 'https://images.unsplash.com/photo-1541899481282-d53bffe3c35d?w=800&q=80'],
            ],

            // ═══ NEKUSTAMAIS ĪPAŠUMS (~38) ═══
            'Nekustamais īpašums' => [
                ['2-istabu dzīvoklis Rīgā, Centrā', 89000, 'Renovēts',
                 'Pilnīgi renovēts 2-istabu dzīvoklis Rīgas centrā, Elizabetes ielā. 52 m², 3. stāvs no 5. Jauna virtuve, vannas istaba, jauni logi. Tūlīt gatavs ievākšanās.',
                 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&q=80'],

                ['3-istabu dzīvoklis Purvciemā', 72000, 'Labi saglabājies',
                 '3-istabu dzīvoklis Rīgas Purvciemā, 68 m². Siltināta māja, nomainītas caurules, jaunas kāpnes. Balkons ar skatu uz parku. Labs transporta savienojums.',
                 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800&q=80'],

                ['1-istabu dzīvoklis Imantā', 38500, 'Apmierinošs',
                 '1-istabu dzīvoklis Imantā, 32 m². Nepieciešams kosmētiskais remonts. Laba atrašanās vieta — tuvumā veikali, skola, transports. Cena pēdējā, steidzams.',
                 'https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=800&q=80'],

                ['Māja Jūrmalā, Lielupē', 285000, 'Renovēts',
                 'Divstāvu māja Jūrmalā, Lielupē, 180 m² uz 9 ariem zemes. 2019. gadā pilnīgi atjaunota. 4 istabas, 2 vannas istabas, sauna, terase. 400m līdz jūrai.',
                 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&q=80'],

                ['Māja Pierīgā, Ādažos', 165000, 'Labi saglabājies',
                 'Vienstāva māja Ādažos, 130 m² uz 12 ariem. 2010. gada būvniecība, gāzes apkure, divkārtīgie logi. Kluss ciems, 25 min līdz Rīgai. Gatavs nekustamais.',
                 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=800&q=80'],

                ['Vasarnīca Tīnūžos pie Ogres', 42000, 'Apmierinošs',
                 'Vasarnīca uz 20 ariem pie Ogres upes. 2 istabas, verandā, aka, āra tualete. Elektroenerģija. Labs vieta atpūtai, makšķerēšanai. Var ieguldīt un uzlabot.',
                 'https://images.unsplash.com/photo-1518780664697-55e3ad937233?w=800&q=80'],

                ['4-istabu dzīvoklis Mežaparkā', 195000, 'Renovēts',
                 '4-istabu dzīvoklis Mežaparkā, 95 m². Pilnīgi renovēts 2022. gadā. Dizainera interjers, premium tehnika, parkets. Vienā no Rīgas prestižākajiem rajoniem.',
                 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800&q=80'],

                ['Zemes gabals Mārupē, 15 ari', 28000, 'Jauns',
                 'Zemes gabals Mārupē, 15 ari. Apbūves atļauja, visi komunikācijas tuvumā (gāze, elektrība, ūdens). Klusa, apzaļumota vieta jaunas mājas celtniecībai.',
                 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=800&q=80'],

                ['Biroja telpas Rīgā, Teikā', 1200, 'Labi saglabājies',
                 'Biroja telpas Teikā, 85 m². Atvērtais plānojums, konferences telpa, virtuvīte. Bezmaksas stāvvieta. Nomas maksa 1200 EUR/mēnesī, visi komunālie iekļauti.',
                 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=800&q=80'],

                ['1-istabu dzīvoklis Jūrmalā', 58000, 'Labi saglabājies',
                 '1-istabu dzīvoklis Jūrmalā, Majoros, 35 m². Labs stāvoklis, renovēta vannas istaba. 600m līdz jūrai. Ideāls kā atpūtas vai ieguldījumu dzīvoklis.',
                 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800&q=80'],

                ['Garāža Rīgā, Pļavniekos', 7500, 'Labi saglabājies',
                 'Mūra garāža Pļavniekos, 20 m². Kanāls, elektrība, apkure. Drošs cietais slēdzis. Izdevīga atrašanās vieta Pļavnieku rajonā. Dokumenti kārtībā.',
                 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=800&q=80'],

                ['2-istabu dzīvoklis Jelgavā', 32000, 'Apmierinošs',
                 '2-istabu dzīvoklis Jelgavā, 48 m². Nepieciešams kosmētiskais remonts. Labs plānojums, liels balkons. Tuvumā skola, tirgus, sabiedriskais transports.',
                 'https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=800&q=80'],

                ['Ekskluzīva penthouse Rīgā', 380000, 'Renovēts',
                 'Penthouse dzīvoklis Rīgas centrā, 180 m², 9. stāvs. 360° panorāmas skats. 3 guļamistabas, 2 vannas istabas, terase 40 m². Premium apdare, smart home.',
                 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800&q=80'],

                ['3-istabu dzīvoklis Valmierā', 55000, 'Labi saglabājies',
                 '3-istabu dzīvoklis Valmierā, 65 m². Siltināta māja, plastikāta logi, jauns jumts. Balkons, noliktava. Laba atrašanās vieta pilsētas centrā.',
                 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&q=80'],

                ['Māja Siguldā ar skatu uz leju', 195000, 'Labi saglabājies',
                 'Divstāvu māja Siguldā, 160 m² uz 14 ariem. Izcils skats uz Gaujas leju. 4 istabas, sauna, divkāršā garāža. Ainavu cienīga vieta.',
                 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&q=80'],

                ['Īre: 2-istabu dzīvoklis Centrā', 550, 'Renovēts',
                 '2-istabu dzīvoklis īrei Rīgas centrā, Brīvības ielā. 58 m², pilnībā renovēts, mēbelēts. Komunālie maksa atsevišķi. Pieejams no 1. maija.',
                 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800&q=80'],

                ['Privātmāja Liepājā', 98000, 'Labi saglabājies',
                 'Privātmāja Liepājā, 120 m² uz 8 ariem. 3 istabas, 2 vannas istabas, garāža. Dabas gāze, autonomā kanalizācija. 2005. gada celtniecība.',
                 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=800&q=80'],

                ['Studio dzīvoklis Āgenskalnā', 45000, 'Renovēts',
                 'Stilīgs studio dzīvoklis Āgenskalnā, 28 m². Pilnīga renovācija 2023. gadā. Lofta stils, betons un koks. 5 min no centra, tuvumā kafejnīcas un parki.',
                 'https://images.unsplash.com/photo-1536376072261-38c75010e6c9?w=800&q=80'],

                ['Komercplatība Tirdzniecības centrā', 3200, 'Labi saglabājies',
                 'Komercplatība iznomāšanai Tirdzniecības centrā Rīgā, 180 m². Liela caurplūde, stāvvietas. Nomas maksa 3200 EUR/mēnesī + komunālie.',
                 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=800&q=80'],

                ['Lauku māja Siguldas novadā', 85000, 'Labi saglabājies',
                 'Lauku īpašums Siguldas novadā, 2 ēkas uz 3 hektāriem. Dzīvojamā māja 90 m², saimniecības ēka. Upes krasts. Ideāls gan dzīvošanai, gan biznesa attīstībai.',
                 'https://images.unsplash.com/photo-1518780664697-55e3ad937233?w=800&q=80'],

                ['2+1 istabu dzīvoklis Agenskalnā', 78000, 'Labi saglabājies',
                 'Divistabu dzīvoklis ar kabinetu Āgenskalnā, 62 m². Renovēta ēka, jaunas komunikācijas. Mājas pagalms, autostāvvieta. Ļoti populārs rajons.',
                 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800&q=80'],

                ['Māja Jūrmalas novadā', 120000, 'Apmierinošs',
                 'Māja Jūrmalas novadā, 140 m² uz 20 ariem. Nepieciešams remonts, bet labs pamats. Gleznaina vieta, tuvumā mežs un ezers. Cena saskaņojama.',
                 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800&q=80'],

                ['Renovēts 4-istabu dzīvoklis Teikā', 145000, 'Renovēts',
                 '4-istabu dzīvoklis Teikā, 88 m². Pilnīgi renovēts 2021. gadā. Kvalitatīva apdare, premium tehnika. Siltināta māja, lifts. Ļoti populārs rajons ģimenēm.',
                 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&q=80'],

                ['Noliktava Rīgas rūpnieciskajā zonā', 2800, 'Labi saglabājies',
                 'Noliktava iznomāšanai Rīgas rūpnieciskajā zonā, 400 m². Augsti griesti (6m), rampa kraušanai, 3-fāzu elektrība. Nomas maksa 7 EUR/m².',
                 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=800&q=80'],

                ['1-istabu dzīvoklis Daugavpilī', 18500, 'Apmierinošs',
                 '1-istabu dzīvoklis Daugavpilī, 34 m². Autonomā apkure, plastikāta logi. Tuvumā tirgus un transports. Ieguldīšanai vai dzīvošanai.',
                 'https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=800&q=80'],

                ['Jaunbūve: 3-istabu dzīvoklis', 168000, 'Jauns',
                 'Jaunbūves dzīvoklis Rīgā, Ziepniekkalnā, 82 m². Nodots 2023. gadā. Enerģoefektīva A klase. Pazemes autostāvvieta, lifts, apsargāts. Gatavs apdare.',
                 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800&q=80'],

                ['Māja Ogres novadā ar dīķi', 135000, 'Labi saglabājies',
                 'Māja Ogres novadā, 150 m² uz 35 ariem. Savs dīķis, mežs uz robežas. 4 istabas, sauna, siltumnīca. Ideāls ģimenei, kas vēlas lauku dzīvi.',
                 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&q=80'],

                ['Dzīvoklis Jūrmalas promenādē', 225000, 'Renovēts',
                 'Ekskluzīvs dzīvoklis Jūrmalas promenādē, 110 m². Pilnīga renovācija, skats uz jūru. 3 guļamistabas, 2 terases. Premium apdare. Ieguldījumu potenciāls.',
                 'https://images.unsplash.com/photo-1536376072261-38c75010e6c9?w=800&q=80'],

                ['Zemes gabals Ādažos, 20 ari', 45000, 'Jauns',
                 'Zemes gabals Ādažos, 20 ari, apbūves atļauja individuālai mājai. Visi komunikācijas robežās. Kluss miers, skola tuvumā. 20 min no Rīgas.',
                 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=800&q=80'],

                ['Komercīpašums Vecrīgā', 320000, 'Renovēts',
                 'Komercīpašums Vecrīgā, 120 m². Vēsturiskā ēka, pilnīgi renovēta. Tūristu zonā, augsta caurplūde. Ideāls restorānam, viesnīcai vai birojam.',
                 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=800&q=80'],

                ['2-istabu dzīvoklis Ventspilī', 42000, 'Labi saglabājies',
                 '2-istabu dzīvoklis Ventspilī, 52 m². Labs stāvoklis, siltināta māja. Tuvumā pludmale un pilsētas centrs. Izdevīga cena pilsētā ar augstu dzīves līmeni.',
                 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800&q=80'],

                ['Savrupmāja Mārupē', 245000, 'Jauns',
                 'Jauna savrupmāja Mārupē, 185 m² uz 10 ariem. Nodota 2022. gadā. A+ enerģijas klase, trijkārt glazēti logi, silta grīda. 4 guļamistabas, 2 vannas istabas.',
                 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=800&q=80'],

                ['Dzīvoklis Rīgā ar Daugavas skatu', 155000, 'Renovēts',
                 '3-istabu dzīvoklis Andrejostā, 78 m², skats uz Daugavu. Pilnīga renovācija dizainera vadībā. Industriāls lofta stils. Viens no Rīgas topošākajiem rajoniem.',
                 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&q=80'],

                ['Māja Jūrmalā, 5 min no jūras', 195000, 'Labi saglabājies',
                 'Māja Jūrmalā, Majoros, 130 m² uz 7 ariem. 4 istabas, veranda, garāža. 5 min kājām līdz jūrai. Laba uzturēšana, atsevišķas viensētas. Cena saskaņojama.',
                 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800&q=80'],

                ['Biroja noma Rīgas biznesa centrā', 2400, 'Jauns',
                 'Reprezentatīvas biroja telpas Rīgas biznesa centrā, 160 m². A klases birojs, fitnesa zāle, konferenču centrs ēkā. Nomas maksa 2400 EUR/mēn.',
                 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=800&q=80'],

                ['Garāžu komplekss Ķengaragā', 35000, 'Labi saglabājies',
                 'Garāžu komplekss Ķengaragā — 3 garāžas blakus. Kopā 60 m². Kanāli, elektrība. Ideāls mehāniķim vai kolekcionāram. Dokumenti kārtībā.',
                 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=800&q=80'],

                ['1-istabu dzīvoklis Siguldā', 35000, 'Labi saglabājies',
                 '1-istabu dzīvoklis Siguldā, 38 m². Labs stāvoklis, siltināta māja. Sigulda — populāra pilsēta ar labu infrastruktūru un ainavu. Ideāls ieguldījumam.',
                 'https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=800&q=80'],

                ['Daudzdzīvokļu māja Rīgā', 850000, 'Labi saglabājies',
                 'Daudzdzīvokļu māja Rīgā, Avotu ielā. 12 dzīvokļi, 600 m² kopā. Pilnīgi iznomāti, stabili ienākumi 3800 EUR/mēn. Ideāls ieguldījumu portfelis.',
                 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800&q=80'],
            ],

            // ═══ ELEKTRONIKA (~38) ═══
            'Elektronika' => [
                ['iPhone 15 Pro 256GB dabas titāns', 890, 'Jauns',
                 'iPhone 15 Pro 256GB, dabas titāna krāsa. Iegādāts 2023. novembrī. Pilna garantija līdz 2025. Kaste, lādētājs, original ekrāna aizsargs. Nekad bez maciņa.',
                 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&q=80'],

                ['Samsung Galaxy S24 Ultra 512GB', 1050, 'Jauns',
                 'Samsung Galaxy S24 Ultra 512GB Titanium Gray. 2024. gada modelis, S Pen iekļauts. Garantija līdz 2026. Pilna komplektācija, nevainojams stāvoklis.',
                 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=800&q=80'],

                ['MacBook Pro 14" M3 Pro 2023', 2100, 'Jauns',
                 'MacBook Pro 14 collu M3 Pro, 18GB RAM, 512GB SSD. Iegādāts 2023. decembrī. Apple garantija līdz 2025. Izmantots tikai mājās, lieliski kopts.',
                 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=800&q=80'],

                ['Dell XPS 15 9530 2023', 1450, 'Jauns',
                 'Dell XPS 15 9530, Intel Core i7-13700H, 32GB RAM, 1TB SSD, NVIDIA RTX 4060. OLED 3.5K displejs. Garantija līdz 2025. Izmantots biroja darbā.',
                 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800&q=80'],

                ['Samsung 65" Neo QLED 8K 2023', 1800, 'Jauns',
                 'Samsung 65 collu Neo QLED QN900C 8K televizors. Iegādāts 2023. Garantija aktīva. Iepakojums saglabāts. Pārdod jo mainās uz lielāku dzīvokli.',
                 'https://images.unsplash.com/photo-1593359677879-a4bb92f4834c?w=800&q=80'],

                ['Sony Alpha A7 IV mirrorless', 2200, 'Labi saglabājies',
                 'Sony Alpha A7 IV + 28-70mm f/3.5-5.6 objektīvs. 33MP pilnformāta matrica. Nobraukts ~8000 eksponēšanu. Labs stāvoklis, komplekts ar sumku un papildu akumulatoru.',
                 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=800&q=80'],

                ['iPad Pro 12.9" M2 256GB WiFi', 1050, 'Jauns',
                 'iPad Pro 12.9 collu M2 čips, 256GB WiFi, Space Gray. Apple Pencil 2. paaudze un Smart Folio iekļauts. Garantija līdz 2025. Ideāls stāvoklis.',
                 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=800&q=80'],

                ['PlayStation 5 Digital Edition', 380, 'Labi saglabājies',
                 'PlayStation 5 Digital Edition + DualSense kontrolieris. Izmantots tikai ģimenē. Labs stāvoklis, viss strādā. Iekļauts arī PS Plus abonements 3 mēneši.',
                 'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=800&q=80'],

                ['AirPods Pro 2. paaudze', 210, 'Jauns',
                 'Apple AirPods Pro 2. paaudze ar MagSafe uzlādes lādētāju. Iegādāts 2023. Garantija līdz 2025. Oriģinālais iepakojums, nevainojams stāvoklis.',
                 'https://images.unsplash.com/photo-1600294037526-72e0f9f084f6?w=800&q=80'],

                ['Gaming PC RTX 4080 i9-13900K', 2800, 'Jauns',
                 'Pielāgots gaming PC — Intel Core i9-13900K, 32GB DDR5 RAM, RTX 4080 16GB, 2TB NVMe SSD, 1000W Platinum PSU. Ūdensdzesēšana. Nopirkts 6 mēnešus atpakaļ.',
                 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=800&q=80'],

                ['Google Pixel 8 Pro 256GB', 680, 'Jauns',
                 'Google Pixel 8 Pro 256GB Obsidian. Iegādāts 2023. oktobrī. Izcila kamera, 7 gadu Android atjauninājumu garantija. Nevainojams stāvoklis, kaste.',
                 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=800&q=80'],

                ['Lenovo ThinkPad X1 Carbon G11', 1650, 'Jauns',
                 'Lenovo ThinkPad X1 Carbon 11. paaudze, Core i7-1365U, 16GB RAM, 512GB SSD. 14" WUXGA IPS. Uzņēmuma klases klēpjdators. Garantija līdz 2025.',
                 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=800&q=80'],

                ['DJI Mini 4 Pro drons', 820, 'Jauns',
                 'DJI Mini 4 Pro Fly More Combo. 3 akumulatori, ND filtri, soma. Lidots tikai 3 reizes demo nolūkos. Pilna garantija. 4K/60fps video, obstacle avoidance.',
                 'https://images.unsplash.com/photo-1527977966376-1c8408f9f108?w=800&q=80'],

                ['Sonos Era 300 skaļrunis', 380, 'Jauns',
                 'Sonos Era 300 bezvadu mājas kino skaļrunis. Iepakojums atvērts, izmantots 2 nedēļas. Pilnīga Dolby Atmos un spatial audio. Garantija aktīva.',
                 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=800&q=80'],

                ['Apple Watch Series 9 45mm', 420, 'Jauns',
                 'Apple Watch Series 9 45mm, Midnight, alumīnija. Sport Band iekļauts + papildu Milan Loop. Garantija līdz 2025. Izmantots 3 mēnešus, ideāls stāvoklis.',
                 'https://images.unsplash.com/photo-1434494878577-86c23bcb06b9?w=800&q=80'],

                ['Nikon Z6 II + 24-70mm f/4 S', 1850, 'Labi saglabājies',
                 'Nikon Z6 II mirrorless kamera + Nikkor Z 24-70mm f/4 S kit objektīvs. ~15 000 eksponēšanu. Labs profesionāls stāvoklis. Iekļauts FTZ II adapters.',
                 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=800&q=80'],

                ['ASUS ROG Strix G16 gaming', 1380, 'Jauns',
                 'ASUS ROG Strix G16 2023, AMD Ryzen 9 7945HX, 16GB RAM, RTX 4070, 1TB SSD. 165Hz QHD displejs. Garantija līdz 2025. Perfekts gaming klēpjdators.',
                 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=800&q=80'],

                ['LG OLED C3 55" televizors', 950, 'Jauns',
                 'LG OLED C3 55 collu EVO panelis. 2023. gada modelis, iepakojums saglabāts. Garantija aktīva. 120Hz, VRR, Dolby Vision. Vienkārši nav sienas, kur pakārt.',
                 'https://images.unsplash.com/photo-1593359677879-a4bb92f4834c?w=800&q=80'],

                ['Meta Quest 3 512GB', 580, 'Jauns',
                 'Meta Quest 3 512GB. Izmantots tikai 5 reizes. Pilna komplektācija — ielāde, kabeļi, Quest Touch Plus kontrolieri. Garantija aktīva. Iespaidīgs VR piedzīvojums.',
                 'https://images.unsplash.com/photo-1617802690992-15d93263d3a9?w=800&q=80'],

                ['Samsung Galaxy Tab S9+ 512GB', 820, 'Jauns',
                 'Samsung Galaxy Tab S9+ 12.4 collu, 512GB WiFi + S Pen iekļauts. Keyboard Cover arī iekļauts. Garantija līdz 2025. Ideāls stāvoklis, lieliski kopts.',
                 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=800&q=80'],

                ['iPhone 13 128GB Starlight', 480, 'Labi saglabājies',
                 'iPhone 13 128GB Starlight. Izmantots 2 gadus, labs stāvoklis, nelielas skrāpējumu pēdas uz aizmugures. Akumulatora kapacitāte 89%. Maciņš iekļauts.',
                 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&q=80'],

                ['Garmin Fenix 7 Pro Solar', 520, 'Jauns',
                 'Garmin Fenix 7 Pro Solar pulkstenis. Nopirkts 2023. Garantija aktīva. Solar uzlāde, multisports, kartes. Sporta entuziastiem. Ideāls stāvoklis.',
                 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&q=80'],

                ['Nintendo Switch OLED + spēles', 320, 'Labi saglabājies',
                 'Nintendo Switch OLED White + 8 spēles (Mario Kart, Zelda, Pokémon u.c.). Labs stāvoklis, nelielas skrāpējumu pēdas. Komplektā soma un papildu kontrolieris.',
                 'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=800&q=80'],

                ['HP LaserJet Pro M428fdw printeris', 280, 'Jauns',
                 'HP LaserJet Pro M428fdw — lāzerprinteris/skeneris/kopētājs. Duplex, WiFi, ADF. Izmantots tikai biroja testēšanai. Garantija aktīva. Pilna komplektācija.',
                 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?w=800&q=80'],

                ['Rode NT-USB+ mikrofons', 185, 'Jauns',
                 'Rode NT-USB+ kondensatora mikrofons. Iepakojums atvērts, izmantots ierakstīšanai 5 reizes. Ideāls podkāstiem, streaming, ierakstīšanai. Garantija aktīva.',
                 'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?w=800&q=80'],

                ['Xiaomi 14 Ultra 512GB', 1150, 'Jauns',
                 'Xiaomi 14 Ultra 512GB Black. Leica Summilux kamera sistēma, Snapdragon 8 Gen 3. Iepakojums saglabāts. Garantija aktīva. Labākais Android foto telefons.',
                 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=800&q=80'],

                ['Canon EOS R6 Mark II + RF 50mm', 2600, 'Jauns',
                 'Canon EOS R6 Mark II + RF 50mm f/1.8 STM. ~3000 eksponēšanu. Ideāls stāvoklis. Pilna garantija. 40fps burst, 6K RAW video. Profesionāls fotogrāfs pārdod pēc nomaiņas.',
                 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=800&q=80'],

                ['Bose QuietComfort Ultra austiņas', 280, 'Jauns',
                 'Bose QuietComfort Ultra austiņas, Midnight Blue. 2023. gada modelis. Labākā trokšņu slāpēšana tirgū. Garantija aktīva. Izmantots 1 mēnesi.',
                 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&q=80'],

                ['Steam Deck OLED 1TB', 620, 'Jauns',
                 'Valve Steam Deck OLED 1TB Limited Edition. Iegādāts 2024. janvārī. Izmantots 10 reizes. Pilna Valve garantija. Carrying case iekļauts.',
                 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=800&q=80'],

                ['Sony WH-1000XM5 austiņas', 240, 'Labi saglabājies',
                 'Sony WH-1000XM5 bezvadu austiņas. Izmantots 1 gadu, labs stāvoklis. Akumulators tur 12+ stundas. Izcila trokšņu slāpēšana. Cena iekļauj soma.',
                 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&q=80'],

                ['Asus ProArt PA329CRV monitors', 680, 'Jauns',
                 'ASUS ProArt PA329CRV 32 collu 4K IPS monitors. 99% DCI-P3, Calman Verified. USB-C 90W. Garantija aktīva. Ideāls fotografēšanai un video montāžai.',
                 'https://images.unsplash.com/photo-1527443224154-c4a573d5f5be?w=800&q=80'],

                ['GoPro Hero 12 Black', 320, 'Jauns',
                 'GoPro Hero 12 Black + Accessories Bundle (papildu akumulators, selfie stick, ūdensizturīgs korpuss). Garantija aktīva. Izmantots tikai 3 reizes.',
                 'https://images.unsplash.com/photo-1527977966376-1c8408f9f108?w=800&q=80'],

                ['OnePlus 12 256GB', 580, 'Jauns',
                 'OnePlus 12 256GB Silky Black. Snapdragon 8 Gen 3, 100W SUPERVOOC uzlāde. Iepakojums saglabāts. Garantija aktīva. Nevainojams stāvoklis.',
                 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=800&q=80'],

                ['Anker 757 PowerStation 1229Wh', 850, 'Jauns',
                 'Anker 757 PowerStation 1229Wh portatīvā elektrostacija. 2400W jauda. Iegādāts 2023. Garantija aktīva. Ideāls dodoties uz vasarnīcu vai kamping.',
                 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=800&q=80'],

                ['AMD Ryzen 9 7950X3D + Motherboard', 650, 'Jauns',
                 'AMD Ryzen 9 7950X3D + ASUS ROG Crosshair X670E Hero mātes plate. Komplektā. Nopirkts 2023., izmantos tikai 4 mēnešus. Ideāls PC builders.',
                 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=800&q=80'],

                ['Pixel Watch 2 WiFi', 280, 'Jauns',
                 'Google Pixel Watch 2 WiFi + LTE versija. Matte Black. Izmantots 3 mēnešus. Akumulators lielisks. Garantija aktīva. Eco leather band iekļauts.',
                 'https://images.unsplash.com/photo-1434494878577-86c23bcb06b9?w=800&q=80'],

                ['Panasonic Lumix S5 Mark II', 1950, 'Jauns',
                 'Panasonic Lumix S5 Mark II body. ~5000 eksponēšanu. Pilna garantija. L-Mount sistēma, 6K video, Phase Hybrid AF. Dokumentālo filmētāju iecienītākais.',
                 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=800&q=80'],
            ],

            // ═══ MĀJAI UN DĀRZAM (~37) ═══
            'Mājai un dārzam' => [
                ['Ādas dīvāns 3+2 komplekts antracīts', 680, 'Labi saglabājies',
                 'Liels ādas dīvānu komplekts 3-vietīgais + 2-vietīgais, antracīta krāsa. Itāļu ādas imitācija, augsta kvalitāte. Pārdod jo mainās uz jaunāku modeli. Pašizņemšana Rīgā.',
                 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800&q=80'],

                ['IKEA PAX skapju sistēma ar spogliem', 420, 'Labi saglabājies',
                 'IKEA PAX skapju sistēma — 3 sekcijas, 2,4m platums, 2,36m augstums. Iekļauti spoguļu bīdāmie durvis (Auli). Labs stāvoklis, viss komplektā. Pašizņemšana.',
                 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=800&q=80'],

                ['Bosch Serie 8 veļas mašīna', 580, 'Jauns',
                 'Bosch WGB254A0SN veļas mašīna, 10 kg, 1400 apgr. A klase, i-DOS automātiskā dozēšana. Iegādāta 2023. Garantija aktīva. Pārdod jo mainās uz kolumnas risinājumu.',
                 'https://images.unsplash.com/photo-1626806787461-102c1bfaaea1?w=800&q=80'],

                ['Samsung Side-by-Side ledusskapis', 780, 'Labi saglabājies',
                 'Samsung RS68A8820S9 Side-by-Side ledusskapis, 617L. Saldēšana zem 5 gadiem. No Wifi, SpaceMax, Metal Cooling. Labs stāvoklis. Dzesē nevainojami.',
                 'https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=800&q=80'],

                ['Husqvarna Automower 430X', 1800, 'Labi saglabājies',
                 'Husqvarna Automower 430X robotu zāles pļāvējs, līdz 3200 m². Izmantots 3 sezonas, labs stāvoklis. GPS navigācija, anti-theft sistēma. Komplekts ar instalācijas komplektu.',
                 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=800&q=80'],

                ['Ēdamgalds 6 personām + krēsli', 650, 'Labi saglabājies',
                 'Masīvkoka ēdamgalds 160x90cm ar 6 ādas krēsliem. Ozola finiera virsma. Lietotas 4 gadus, labs stāvoklis. Pārdod jo mainās uz mazāku dzīvokli.',
                 'https://images.unsplash.com/photo-1449247709967-d4461a6a6103?w=800&q=80'],

                ['Bosch Professional perforators komplekts', 380, 'Labi saglabājies',
                 'Bosch Professional GBH 18V-26F perforators + GSB 18V akumulatora urbis. 2x akumulatori 5Ah, lādētājs, koferis. Izmantots periodiski, labs stāvoklis.',
                 'https://images.unsplash.com/photo-1504148455328-c376907d081c?w=800&q=80'],

                ['Miele trauku mazgājamā mašīna', 620, 'Labi saglabājies',
                 'Miele G 7310 SCi trauku mazgājamā mašīna, iebūvējama, 45cm. AutoDos tabs dozēšana. Izmantota 2 gadus, lielisks stāvoklis. Pārdod jo virtuve tiek pārbūvēta.',
                 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=800&q=80'],

                ['Tempur matracis 160x200 Medium', 1200, 'Labi saglabājies',
                 'Tempur Sensation matracis 160x200, Medium cietums. Izmantots 3 gadus, labs stāvoklis. Mainās uz cieto modeli. Cena oriģinālā — 2400 EUR.',
                 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800&q=80'],

                ['Trex Premium siltumnīca 3x4m', 480, 'Labi saglabājies',
                 'Alumīnija un polkarbonāta siltumnīca 3x4m ar pamatnēm. Izmantota 2 sezonas. Montāžas instrukcija un visi dzelži iekļauti. Stāvoklis labs.',
                 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=800&q=80'],

                ['Villeroy & Boch vannas istabas komplekts', 890, 'Jauns',
                 'V&B Subway 3.0 vannas istabas komplekts — izlietne 65cm, tualetes pods ar bidē funkciju, dušas komplekts. Iepakojumā, nedaudz skrāpēts kastīte. Garantija aktīva.',
                 'https://images.unsplash.com/photo-1552321554-5fefe8c9ef14?w=800&q=80'],

                ['Weber Genesis EPX-435 grils', 1450, 'Jauns',
                 'Weber Genesis EPX-435 gāzes grils Smart 4 degli degšanas zona. Iebūvēts termometrs, side burner. Nopirkts 2023., izmantots 1 sezonu. Garantija aktīva.',
                 'https://images.unsplash.com/photo-1529511582893-2d7e684dd128?w=800&q=80'],

                ['Philips Hue sākuma komplekts', 180, 'Jauns',
                 'Philips Hue Starter Kit — Hue Bridge + 3 E27 White & Color spuldzes. Nopirkts 2023., iepakojums atvērts, izmantots 1 mēnesi. Ideāls smart home sākumam.',
                 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=800&q=80'],

                ['Nespresso Vertuo Next kafijas automāts', 150, 'Labi saglabājies',
                 'Nespresso Vertuo Next kafijas automāts ar piena putotāju. Izmantots 2 gadus, labs stāvoklis. Iekļauts 20 kapsulas izmēģināšanai. Pārdod jo pārejam uz malta.',
                 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=800&q=80'],

                ['KitchenAid Artisan stāvmiksers', 380, 'Labi saglabājies',
                 'KitchenAid Artisan 6.9L stāvmiksers, Contour Silver krāsa. Izmantots 4 gadus, perfekts stāvoklis. Visas oriģinālās pieliknes. Cena oriģinālā — 700 EUR.',
                 'https://images.unsplash.com/photo-1588165171080-c89acfa5ee83?w=800&q=80'],

                ['Dyson V15 Detect putekļsūcējs', 480, 'Jauns',
                 'Dyson V15 Detect Absolute bezvada putekļsūcējs. Nopirkts 2023. Garantija aktīva. Pieliknes koplektā, Laser Detect galva, HEPA filtrs. Nevainojams.',
                 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=800&q=80'],

                ['Eglo āra apgaismojuma komplekts', 280, 'Jauns',
                 'Eglo āra apgaismojuma komplekts — 2 sienas gaismekļi + 2 malas gaismekļi + 4 zāles stabiņi. LED GU10. Nopirkts 2023., māja tiek pārdota.',
                 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=800&q=80'],

                ['Skandināvu stila gulta 140x200', 320, 'Labi saglabājies',
                 'Auduma polsterēta gulta ar galvgali, izmērs 140x200. Gaiši pelēka krāsa, skandināvu dizains. Matracis nav iekļauts. Pašizņemšana Rīgā, Teikā.',
                 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800&q=80'],

                ['Herde indukcijas plīts 90cm', 1100, 'Jauns',
                 'Bosch indukcijas plīts HID99IF50L 90cm, 5 degšanas zona, FlexInduction. Iegādāta 2023. Garantija aktīva. Pārdod jo virtuvē mainās plīts izmērs.',
                 'https://images.unsplash.com/photo-1556909114-44e3e9a4a607?w=800&q=80'],

                ['Bestway baseins 4.57m komplekts', 380, 'Labi saglabājies',
                 'Bestway Steel Pro baseins 4.57x1.07m + filtru sūknis + pārsegs + tīrīšanas komplekts. Izmantots 2 sezonas. Labs stāvoklis. Izcils vasaras prieks.',
                 'https://images.unsplash.com/photo-1575429198097-0414ec08e8cd?w=800&q=80'],

                ['Atvērtā plauktu sistēma 2m', 180, 'Labi saglabājies',
                 'Rūpnieciskā stila atvērtā plauktu sistēma 2m x 0.9m, melns metāls + koka dēļi. Demontēti, visi dzelži iekļauti. Pašizņemšana Rīgā.',
                 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=800&q=80'],

                ['Siemens iQ700 tvaika nosūcējs', 420, 'Labi saglabājies',
                 'Siemens iQ700 integrētais tvaika nosūcējs 90cm, iQ Drive motors. Izmantots 3 gadus. Labs stāvoklis, tīrīts regulāri. Pārdod jo virtuve tiek pārbūvēta.',
                 'https://images.unsplash.com/photo-1556909114-44e3e9a4a607?w=800&q=80'],

                ['Dārza šūpuļkrēsls ar jumtiņu', 280, 'Labi saglabājies',
                 'Dārza šūpuļkrēsls ar jumtiņu un spilveniem, pelēks alumīnijs. Izmantots 3 sezonas, labs stāvoklis. Lieliski piemērots terasei vai dārzam.',
                 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=800&q=80'],

                ['Roborock S8 Pro Ultra robots putekļsūcējs', 950, 'Jauns',
                 'Roborock S8 Pro Ultra — robots putekļsūcējs + grīdu mazgāšana + automātiska iztīrīšana. Nopirkts 2023. Garantija aktīva. Ideāls lieliem dzīvokļiem.',
                 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=800&q=80'],

                ['Birzs 5 apses 2-3m augstums', 80, 'Jauns',
                 '5 apses koki 2-3m augstumā, konteineros. Gatavi stādīšanai. Dabas aizsargs no vēja un skaļiem kaimiņiem. Pašizņemšana Ogres novadā.',
                 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=800&q=80'],

                ['IKEA Kallax plaukts + kastes', 120, 'Labi saglabājies',
                 'IKEA Kallax 4x4 plaukts (147x147cm), melni brūns + 8 Drona kastes. Labs stāvoklis. Demontēts. Pašizņemšana Rīgā, Purvciemā.',
                 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=800&q=80'],

                ['Hisense 60cm trauku mazgājamā', 320, 'Labi saglabājies',
                 'Hisense HS60240XUK trauku mazgājamā mašīna, 14 komplekti, Wi-Fi. Izmantota 2 gadus, lielisks stāvoklis. Enerģijas efektivitāte A+++.',
                 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=800&q=80'],

                ['Zāles traktors MTD Optima 46 HL', 680, 'Labi saglabājies',
                 'MTD Optima 46 HL zāles traktors ar groza uztvērēju. 83cc motors, 46cm pļaušanas platums. Izmantots 5 sezonas, apkope veikta regulāri. Labs stāvoklis.',
                 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=800&q=80'],

                ['Iekštelpu augi — palmēta un fikus', 85, 'Jauns',
                 '2 lieli iekštelpu augi — Washingtonia palma 1.8m + Ficus Lyrata 1.2m. Keramikas podā. Veseli un kupli. Pašizņemšana Rīgā. Cena par abiem.',
                 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=800&q=80'],

                ['Mīkstais stūra dīvāns pelēks', 580, 'Labi saglabājies',
                 'Stūra dīvāns auduma polsterējums, pelēks. Maināma stūra pozīcija. Gulēšanas funkcija, veļas kaste. Izmantots 2 gadus. Pašizņemšana Rīgā.',
                 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800&q=80'],

                ['Brīvstāvoša vanna 170cm melna', 650, 'Jauns',
                 'Brīvstāvoša vanna 170x75cm, melna matēta akrila virsma. Iepakojumā, piegādāta bet neuzstādīta. Pārdod jo mainās remonts. Cena oriģinālā — 1100 EUR.',
                 'https://images.unsplash.com/photo-1552321554-5fefe8c9ef14?w=800&q=80'],

                ['Milwaukee akumulatora leņķdzirksle', 180, 'Jauns',
                 'Milwaukee M18 FUEL FHSAG 125mm leņķdzirksle. Nopirkta 2023., bez akumulatora un lādētāja (atsevišķi). Iepakojumā. Garantija aktīva.',
                 'https://images.unsplash.com/photo-1504148455328-c376907d081c?w=800&q=80'],

                ['Bosch Atino līmeņrādis + lāzers', 95, 'Jauns',
                 'Bosch Atino sienas attēlojamais līmeņrādis ar lāzeru. Nopirkts 2023., izmantots 2 reizes renovācijā. Pilna kaste. Garantija aktīva.',
                 'https://images.unsplash.com/photo-1504148455328-c376907d081c?w=800&q=80'],

                ['Koka bērnu gulta FLEXA', 280, 'Labi saglabājies',
                 'FLEXA bērnu gulta 90x200cm, dabīgs koks. Regulējams apakšējais stāvs. Izmantots 4 gadus, labs stāvoklis. Var pievienot 2. stāvu. Pašizņemšana Rīgā.',
                 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800&q=80'],

                ['Karcher K7 Premium augstspiediena mazgātājs', 380, 'Labi saglabājies',
                 'Kärcher K7 Premium augstspiediena mazgātājs, 180 bar. Komplets — šļūtene, pistole, Home Kit uzgalis. Izmantots 3 sezonas. Labs stāvoklis.',
                 'https://images.unsplash.com/photo-1504148455328-c376907d081c?w=800&q=80'],
            ],

            // ═══ APĢĒRBS UN MODE (~37) ═══
            'Apģērbs un mode' => [
                ['Canada Goose Expedition parka L', 480, 'Labi saglabājies',
                 'Canada Goose Expedition Parka vīriešu, izmērs L, Black. Izmantots 2 ziemas, labs stāvoklis. Ideāls Latvijas bargajām ziemām. Cena oriģinālā — 1200 EUR.',
                 'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=800&q=80'],

                ['Nike Air Jordan 1 Retro High', 180, 'Jauns',
                 'Nike Air Jordan 1 Retro High OG "Chicago" US10 / EU44. Nopirkts 2023., izmantots 3 reizes. Iepakojums un papildu šņores iekļauts. Nevainojams stāvoklis.',
                 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&q=80'],

                ['Zara sieviešu ziemas mētelis M', 65, 'Labi saglabājies',
                 'Zara sieviešu paplašināts mētelis, izmērs M, kamieļbrūna krāsa. Izmantots 1 ziemu, labs stāvoklis. Vilnas maisījums. Stilīgs un silts.',
                 'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=800&q=80'],

                ['Adidas Originals Samba OG EU43', 95, 'Jauns',
                 'Adidas Samba OG White/Black, EU43. Nopirktas 2023., izmantotas 5 reizes. Nevainojams stāvoklis. Kulta modelis, uz topa kādu gadu. Iepakojums.',
                 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&q=80'],

                ['H&M bērnu apģērbu komplekts 92cm', 35, 'Labi saglabājies',
                 'H&M bērnu apģērbu komplekts izmēram 92 (1.5-2 gadi) — 12 gabali: džemperi, bikses, krekli. Labs stāvoklis, mazgāti. Mazs uzglabāts mīlestībā.',
                 'https://images.unsplash.com/photo-1518831959646-742c3a14ebf7?w=800&q=80'],

                ['Louis Vuitton Neverfull MM soma', 850, 'Labi saglabājies',
                 'Louis Vuitton Neverfull MM Damier Ebene soma. Izmantots 4 gadus, labs stāvoklis, nelielas nodiluma pēdas. Ar originalajiem dokumentiem un soma.',
                 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=800&q=80'],

                ['Hugo Boss uzvalks 50 izmērs', 180, 'Labi saglabājies',
                 'Hugo Boss Huge uzvalks EU50, melns. Izmantots dažreiz, labs stāvoklis. Augsta kvalitātes audums. Ideāls darba sanāksmēm vai kāzām.',
                 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=800&q=80'],

                ['Timberland 6" Premium zābaki EU42', 85, 'Labi saglabājies',
                 'Timberland 6 collu Premium zābaki EU42, Wheat/Gainsboro. Izmantots 2 ziemas, labs stāvoklis. Mitrumizturīgi, silts pamatiņš. Ikoniskais modelis.',
                 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&q=80'],

                ['Levi\'s 501 džinsi W30 L32', 45, 'Labi saglabājies',
                 'Levi\'s 501 Original Fit džinsi, W30 L32, Mid Rise. Izmantots 2 gadus, labs stāvoklis, nenovalkāti. Klasisks taisns griezums. Denim zilā krāsā.',
                 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&q=80'],

                ['Ralph Lauren polo krekls komplekts', 85, 'Jauns',
                 '3 Ralph Lauren Polo krekli — balts, zils, jūras zils. Izmērs M. Oriģināli, ar etiķetēm. Dāvana, kas nepiestāv. Cena par visiem trim.',
                 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=800&q=80'],

                ['Sieviešu sporta apavi Nike Air Max', 75, 'Labi saglabājies',
                 'Nike Air Max 270 sieviešu, EU38, Triple White. Izmantots 1 sezonu, labs stāvoklis. Izcils komforts ikdienas staigāšanai.',
                 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&q=80'],

                ['Moncler parka sieviešu S', 650, 'Labi saglabājies',
                 'Moncler Grenoble Aiguille parka sieviešu S/36, Navy Blue. Izmantots 3 ziemas, ļoti labs stāvoklis. Oriģināls, dokumenti. Cena oriģinālā — 1500 EUR.',
                 'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=800&q=80'],

                ['New Balance 990v6 EU44', 155, 'Jauns',
                 'New Balance 990v6 EU44 Grey/White. Nopirktas 2023., izmantotas 2 reizes. USA ražojums. Iepakojums. Nevainojams stāvoklis.',
                 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&q=80'],

                ['Vīriešu peldmētelis L Terry', 45, 'Jauns',
                 'Frotē peldmētelis vīriešu L izmērs, balts. Egyptian Cotton 100%. Saņemts kā dāvana, nav piemērots izmērs. Ar etiķeti, iepakojumā.',
                 'https://images.unsplash.com/photo-1544966503-7cc5ac882d5f?w=800&q=80'],

                ['Gucci GG Marmont plecu soma', 680, 'Labi saglabājies',
                 'Gucci GG Marmont Small plecu soma, Black Matelassé. Izmantots 2 gadus, ļoti labs stāvoklis. Oriģinālā kaste, soma, dokumenti.',
                 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=800&q=80'],

                ['Bērnu slidas Roces Combi EU32', 35, 'Labi saglabājies',
                 'Bērnu ledus slidas Roces Combi EU32, baltas. Izmantots 2 sezonas, labs stāvoklis. Regulējamas no EU29 līdz EU32.',
                 'https://images.unsplash.com/photo-1518831959646-742c3a14ebf7?w=800&q=80'],

                ['Stone Island Sweatshirt M', 180, 'Labi saglabājies',
                 'Stone Island Shadow Project džemperis izmērs M, Dust. Izmantots 1 sezonu, labs stāvoklis. Oriģināls ar kompasu. Cena oriģinālā — 380 EUR.',
                 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=800&q=80'],

                ['Sieviešu žakete Massimo Dutti S', 55, 'Jauns',
                 'Massimo Dutti sieviešu žakete S izmērs, Camel. Ar etiķeti, nēsāts 1 reizi. Augstas kvalitātes vilnas maisījums. Perfekti pieguļošs griezums.',
                 'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=800&q=80'],

                ['Birkenstock Arizona EU41 Suede', 65, 'Labi saglabājies',
                 'Birkenstock Arizona EU41 vasaras sandaļi, Suede Nubuck Caramel. Izmantots 2 vasaras, labi saglabājies. Ikoniskais komforts ikdienas staigāšanai.',
                 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&q=80'],

                ['Fjallraven Kanken mugursoma', 55, 'Labi saglabājies',
                 'Fjällräven Kånken Original 16L, Dark Navy. Izmantots 3 gadus, labs stāvoklis, nelielas nolietojuma pēdas. Ikoniska skandināvu dizaina mugursoma.',
                 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=800&q=80'],

                ['Sieviešu maxi kleita vasaras S/M', 28, 'Jauns',
                 'Sieviešu Boho stila maxi kleita, S/M, Boho White ar puķu ornamentiem. Ar etiķeti, tīrīta saglabāta. Vasaras atvaļinājumam nepiemērota.',
                 'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?w=800&q=80'],

                ['Tommy Hilfiger jaka vīriešu M', 95, 'Labi saglabājies',
                 'Tommy Hilfiger Signature pūkainā jaka vīriešu M, Navy. Izmantots 2 ziemas, labs stāvoklis. Silta, viegla. Ikoniskais Tommy logo.',
                 'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=800&q=80'],

                ['Dr. Martens 1460 EU42 Black', 85, 'Labi saglabājies',
                 'Dr. Martens 1460 klasiskie zābaki EU42, Black Smooth. Izmantots 3 gadus, labs stāvoklis. Autīgs punk/grunge stils, mūžīgais modelis.',
                 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&q=80'],

                ['Acne Studios šalle Mohair', 85, 'Labi saglabājies',
                 'Acne Studios Bansy Mohair šalle, Dusty Rose. Izmantots 1 sezonu, labs stāvoklis. Ori ģināls ar etiķeti. Cena oriģinālā — 220 EUR.',
                 'https://images.unsplash.com/photo-1544966503-7cc5ac882d5f?w=800&q=80'],

                ['Sieviešu zābaki Stuart Weitzman EU38', 180, 'Labi saglabājies',
                 'Stuart Weitzman Tieland over-the-knee zābaki EU38, Black Suede. Izmantots 2 sezonas, labs stāvoklis. Ikoniskais modelis. Cena oriģinālā — 450 EUR.',
                 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&q=80'],

                ['Patagonia Nano Puff jaka L', 145, 'Labi saglabājies',
                 'Patagonia Nano Puff Hoody jaka vīriešu L, Black. Izmantots 3 gadus, labs stāvoklis. Viegla, silta, iepakojama. PrimaLoft pildījums. Outdoor klasisks.',
                 'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=800&q=80'],

                ['Bērnu apģērbu pakets 104-110cm', 45, 'Labi saglabājies',
                 'Bērnu apģērbu pakets 3-5 gadiem (104-110cm) — 15+ gabali: džemperi, T-krekli, bikses, pidžamas. Zēnam. Labi saglabājies, tīrīti uzglabāti.',
                 'https://images.unsplash.com/photo-1518831959646-742c3a14ebf7?w=800&q=80'],

                ['Calvin Klein vīriešu apakšveļa L', 35, 'Jauns',
                 'Calvin Klein vīriešu bokseršorti 3-pack, L izmērs. Nopirkts, nepareizs izmērs. Ar etiķetēm, iepakojumā. Premium modalā kokvilna.',
                 'https://images.unsplash.com/photo-1544966503-7cc5ac882d5f?w=800&q=80'],

                ['ALDO sieviešu rokassoma Black', 45, 'Labi saglabājies',
                 'ALDO Cador strukturēta rokassoma, Black. Izmantots 1 gadu, labs stāvoklis. Zeltīta furnitūra, ādas imitācija. Ideāla darba vai vakariem.',
                 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=800&q=80'],

                ['Rolex Oyster Perpetual 36 2021', 7800, 'Labi saglabājies',
                 'Rolex Oyster Perpetual 36mm Oystersteel, Turquoise Blue ciparnīca. 2021. gads, oriģinālie dokumenti, kaste. 1 īpašnieks. Viens no meklētākajiem modeļiem.',
                 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&q=80'],

                ['Sieviešu jumpsuit Party S', 18, 'Jauns',
                 'Sieviešu melns jumpsuit ar platu jostasvietu, S izmērs. Ar etiķeti, valkāts 1 reizi uz ballīti. Stils: modernais vienkāršais elegants.',
                 'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?w=800&q=80'],

                ['Arc\'teryx Beta AR jaka M', 380, 'Labi saglabājies',
                 'Arc\'teryx Beta AR jaka vīriešu M, Black. Izmantots 2 sezonas tūristikā. Labs stāvoklis, ūdensnecaurlaidīgums atjaunots. Labākā hardshell jaka.',
                 'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=800&q=80'],

                ['Swatch Bioceramic kolekc. 2023', 85, 'Jauns',
                 'Swatch x MoMA Art Special Edition pulkstenis, bioceramic kauliņš. Ierobežots izlaidums 2023. Nopirkts dāvanā — ciparnīcas dizains nepatīk. Iepakojumā.',
                 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&q=80'],

                ['Sieviešu flip flops Havaianas EU39', 18, 'Jauns',
                 'Havaianas Top Fashion flip flops EU39, Coral Punch. Nopirkts 2023., nepareizs izmērs. Ar etiķeti, iepakojumā. Brazīlijas oriģināls.',
                 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&q=80'],
            ],

            // ═══ SPORTS UN HOBIJI (~37) ═══
            'Sports un hobiji' => [
                ['Slēpošanas komplekts Rossignol 168cm', 280, 'Labi saglabājies',
                 'Rossignol Experience 80 slēpes 168cm + Rossignol Axia bindingi + Atomic Hawx Ultra 100 zābaki EU43. Izmantots 5 sezonas, labs stāvoklis. Ideāls vidēja līmeņa slēpotājam.',
                 'https://images.unsplash.com/photo-1551698618-1dfe5d97d256?w=800&q=80'],

                ['Makšķerēšanas komplekts Shimano', 185, 'Labi saglabājies',
                 'Shimano Nexave CI4+ 4000 spole + Shimano Yasei Spinning 2.7m muca + pāris strūklaku. Izmantots 3 sezonas. Labs stāvoklis. Komplekts iesācējam vai hobijistam.',
                 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800&q=80'],

                ['Fender Player Stratocaster ģitāra', 580, 'Labi saglabājies',
                 'Fender Player Stratocaster 3-Color Sunburst + Frontman 20G pastiprinātājs. Izmantots 3 gadus. Labs stāvoklis, jauni stīgas. Ideāls sākot gitāras apguvi.',
                 'https://images.unsplash.com/photo-1510915361894-db8b60106cb1?w=800&q=80'],

                ['Wilson Pro Staff 97 v14 tenisa rakete', 195, 'Jauns',
                 'Wilson Pro Staff 97 v14 315g tenisa rakete. Nopirkta 2023., izmantota 5 reizes. Pilns Luxilon ALU Power stīgas komplekts. Garantija aktīva.',
                 'https://images.unsplash.com/photo-1617083934555-9db5c1cddce9?w=800&q=80'],

                ['NordicTrack Commercial skrejceliņš', 1200, 'Labi saglabājies',
                 'NordicTrack Commercial 1750 skrejceliņš, 22km/h, līdz 159kg. Izmantots 2 gadus, lielisks stāvoklis. iFit abonements 6 mēneši iekļauts. Pašizņemšana Rīgā.',
                 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&q=80'],

                ['Antīks Omega Seamaster 1968', 1800, 'Labi saglabājies',
                 'Omega Seamaster DeVille 1968. gads, manuāls mehānisms, cal.565. Oriģināls ciparplāksnes, arābu cipari. Labs darbotājam stāvoklis. Kolekcionāriem ideāls.',
                 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&q=80'],

                ['Yamaha P-125B digitālās klavieres', 580, 'Labi saglabājies',
                 'Yamaha P-125B digitālās klavieres, melnas + statīvs + pēdāļu bloks + mūzikas pults. Izmantots 3 gadus. Svērtie taustiņi, 88 taustiņi. Labs stāvoklis.',
                 'https://images.unsplash.com/photo-1520523839897-bd0b52f945a0?w=800&q=80'],

                ['Burton Ripcord snovbords 156W', 320, 'Labi saglabājies',
                 'Burton Ripcord snovbords 156W + Freestyle bindingi + snovborda zābaki EU43. Izmantots 4 sezonas, labs stāvoklis. Wax un tuneris veikts pirms pārdošanas.',
                 'https://images.unsplash.com/photo-1551698618-1dfe5d97d256?w=800&q=80'],

                ['Čella Stradivarius kopija 4/4', 1200, 'Labi saglabājies',
                 'Meistara darbnīcas čella Stradivarius kopija 4/4. Iegādāts 2015., labs stāvoklis, regulāri uzturēts. Komplektā soma un loks. Piemērots studentiem un amatieriem.',
                 'https://images.unsplash.com/photo-1520523839897-bd0b52f945a0?w=800&q=80'],

                ['LEGO Technic Bugatti Chiron 42083', 380, 'Jauns',
                 'LEGO Technic Bugatti Chiron 42083, 3599 gabali. Iepakojumā, neatvērts. Kolekcionāru kopija. Izlaists 2018. Reti pieejams veikalos. Investīcija ar potenciālu.',
                 'https://images.unsplash.com/photo-1587654780291-39c9404d746b?w=800&q=80'],

                ['Bowflex SelectTech 552 hanteles', 280, 'Labi saglabājies',
                 'Bowflex SelectTech 552 regulējamās hanteles pāris, 2-24 kg. Izmantots 2 gadus, labs stāvoklis. Aizstāj 15 hanteļu pārus. Ideāls mājas sporta zālei.',
                 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=800&q=80'],

                ['Pingponga galds Butterfly', 380, 'Labi saglabājies',
                 'Butterfly Centrefold 25 iekštelpu pingponga galds. Izmantots 5 gadus, labs stāvoklis. Vieglai salocīšanai. Komplektā 4 raketes un 12 bumbiņas.',
                 'https://images.unsplash.com/photo-1554068865-24cecd4e34b8?w=800&q=80'],

                ['Specialized Allez Sprint 54cm', 1800, 'Labi saglabājies',
                 'Specialized Allez Sprint karbona velosipēds, 54cm, Gloss Red. Shimano 105 R7000, DT Swiss R460 riteņi. Nobraukts 3000 km. Lielisks stāvoklis.',
                 'https://images.unsplash.com/photo-1485965120184-e220f721d03e?w=800&q=80'],

                ['Daiwa Ninja LT 3000 spole', 65, 'Jauns',
                 'Daiwa Ninja LT 3000D-C spole makšķerēšanai. Nopirkta 2023., izmantota 2 reizes. Iepakojumā. ZAION v rokturs, Light & Tough tehnoloģija.',
                 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800&q=80'],

                ['Taktiskās spēles War Hammer kolekc.', 320, 'Labi saglabājies',
                 'Warhammer 40K Space Marines kolekc. — 85+ miniatūri, daļēji krāsotas. Starter set + kodekss + pamatkrāsas. Ideāls tiem, kas sāk hobijā.',
                 'https://images.unsplash.com/photo-1587654780291-39c9404d746b?w=800&q=80'],

                ['GoPro suction cup + 2 akumulatori', 45, 'Labi saglabājies',
                 'GoPro Suction Cup Mount + 2 oriģinālā akumulatori Hero 8/9/10/11 saderīgi. Labs stāvoklis. Ideāls auto ierakstīšanai vai braukšanas video.',
                 'https://images.unsplash.com/photo-1527977966376-1c8408f9f108?w=800&q=80'],

                ['Spalding NBA basketbola bumba 7', 35, 'Labi saglabājies',
                 'Spalding NBA Zip basketbola bumba, izmērs 7. Izmantots 2 sezonas iekštelpu zālē. Labs stāvoklis, normāls spiediens. Ideāls treniņiem.',
                 'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=800&q=80'],

                ['Padeltenisa rakete Bullpadel', 120, 'Labi saglabājies',
                 'Bullpadel Vertex 04 padeltenisa rakete. Izmantots 1 sezonu, labs stāvoklis. Carbon fiber face. Padelteniss arvien populārāks Latvijā. Komplektā soma.',
                 'https://images.unsplash.com/photo-1617083934555-9db5c1cddce9?w=800&q=80'],

                ['Šaha galds ar figūrām Staunton', 85, 'Labi saglabājies',
                 'Premium šaha komplekts — koka galds 45cm + Staunton koka figūras 3.75 Zoll. Turku valrieksts un zirgu kastaņkoks. Izmantots 5 gadus. Ideāls kolekcionēšanai.',
                 'https://images.unsplash.com/photo-1586165368502-1bad197a6461?w=800&q=80'],

                ['Guitarra Takamine GN51CE akustiska', 380, 'Labi saglabājies',
                 'Takamine GN51CE Neo Classic akustiskā-elektriskā ģitāra. Sedrs + indīga koks. Izmantots 3 gadus, labs stāvoklis. Komplektā soma un kapo.',
                 'https://images.unsplash.com/photo-1510915361894-db8b60106cb1?w=800&q=80'],

                ['Šautuves tālummērs Nikon Monarch', 280, 'Labi saglabājies',
                 'Nikon Monarch 5 8x42 tālummērs medībām un vērojumiem. ED stikls, ūdensnecaurlaidīgs. Izmantots 4 gadus, labs stāvoklis. Komplektā soma un siksna.',
                 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=800&q=80'],

                ['Kaklasaite vīriešu BMW M Sport', 12, 'Jauns',
                 'Oriģināla BMW M Sport kaklasaite, sarkana/zila. Nopirkta dāvanā, nepareizais. Ar etiķeti, iepakojumā. Prece no BMW boutique.',
                 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=800&q=80'],

                ['Trek FX Sport 5 hibrīdvelosipēds 56cm', 1450, 'Jauns',
                 'Trek FX Sport 5 hibrīdvelosipēds 2023, izmērs 56cm. Shimano 105 pārslēdzēji, TRP disku bremzes. Nobraukts 150 km. Garantija aktīva. Nevainojams stāvoklis.',
                 'https://images.unsplash.com/photo-1485965120184-e220f721d03e?w=800&q=80'],

                ['Crossfit treniņu aprīkojums komplekts', 380, 'Labi saglabājies',
                 'Crossfit komplekts — 20kg bumbbells x2 + 10kg kettlebells x2 + pull-up bar + jump rope + resistance bands. Labs stāvoklis. Viss mājas treniņiem.',
                 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=800&q=80'],

                ['Hokeja spēlētāja ekipējums L', 180, 'Labi saglabājies',
                 'Hokejista ekipējums L izmērs — komplekts: cimdi, plecu polsterējums, kāju aizsargi, nūja (Bauer Nexus). Izmantots 3 sezonas. Labs stāvoklis.',
                 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=800&q=80'],

                ['Ūdens sērfošanas dēlis Naish 9\'6"', 850, 'Labi saglabājies',
                 'Naish Mana 9\'6" SUP dēlis + Naish padele + glābšanas veste. Izmantots 3 sezonas. Labs stāvoklis. Stabils iesācēju dēlis. Pašizņemšana Jūrmalā.',
                 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800&q=80'],

                ['Skaude modeļu automobiļi 1:18 kolekcija', 280, 'Labi saglabājies',
                 '12 modeļu automobiļi 1:18 mērogā — AutoArt, Minichamps, Kyosho ražotāji. Starp tiem Ferrari, Porsche, Mercedes modeļi. Oriģinālajās kastēs. Kolekcijas gabals.',
                 'https://images.unsplash.com/photo-1587654780291-39c9404d746b?w=800&q=80'],

                ['Marshall Emberton II skaļrunis', 95, 'Jauns',
                 'Marshall Emberton II Bluetooth skaļrunis, Black & Brass. Nopirkts 2023., izmantots mājās 3 mēnešus. Garantija aktīva. 30 stundas akumulators.',
                 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=800&q=80'],

                ['Roland TD-17KVX e-bungas komplekts', 1200, 'Labi saglabājies',
                 'Roland TD-17KVX elektronisko bungu komplekts. Izmantots 2 gadus, labs stāvoklis. Ideāls mājas mūziķiem — kluss treniņš ar austiņām. Komplektā sēdeklis.',
                 'https://images.unsplash.com/photo-1520523839897-bd0b52f945a0?w=800&q=80'],

                ['PS5 spēļu kolekcija 15 spēles', 180, 'Labi saglabājies',
                 '15 PS5 spēles: God of War Ragnarök, Elden Ring, FIFA 24, Gran Turismo 7 u.c. Labs stāvoklis, visi diski strādā. Pārdod komplektā vai atsevišķi. Interesantajiem.',
                 'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=800&q=80'],

                ['Fly fishing komplekts Hardy', 320, 'Labi saglabājies',
                 'Hardy Ultralite CAFL 9\' #5 šūkšanas makšķere + Hardy Ultralite CAFL spole + SA Mastery SBT aukla. Izmantots 3 sezonas. Labs stāvoklis.',
                 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800&q=80'],

                ['Inline slidas Rollerblade Hydrogen', 185, 'Labi saglabājies',
                 'Rollerblade Hydrogen Carbon 100 EU43. Izmantots 2 sezonas, labs stāvoklis. Karbona kamana, 100mm riteņi. Ideāls pieredzējušiem inline skater.',
                 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=800&q=80'],
            ],

            // ═══ DZĪVNIEKI (~37) ═══
            'Dzīvnieki' => [
                ['Labradoru kucēni ar dokumentiem', 850, 'Jauns',
                 'Labradoru retrīvera kucēni — 3 tēviņi, 2 meitenes. Dzimušie 15. martā. Abi vecāki ar FCI dokumentiem, PRA testēti. Vakcinēti, mikročipoti. Ģimenēm ar bērniem.',
                 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=800&q=80'],

                ['Vācu aitu suns apmācīts 2 gadi', 650, 'Jauns',
                 'Vācu aitu suns tēviņš, 2 gadi. FCI dokumenti. Pamatkomandas apgūtas, draudzīgs, nav agresīvs. Jānodod jo mainās dzīvesvieta uz dzīvokli.',
                 'https://images.unsplash.com/photo-1589941013453-ec89f33b5e95?w=800&q=80'],

                ['Britu īsspalvains kaķēns 2 mēn.', 420, 'Jauns',
                 'Britu īsspalvains kaķēns, tēviņš, 2 mēneši, Blue. Vecāki ar dokumentiem, WCF reģistrēti. Vakcinēts, mikročips. Audzēts mājās ar bērniem.',
                 'https://images.unsplash.com/photo-1574144611937-0df059b5ef3e?w=800&q=80'],

                ['Kaķis meklē mājas — pieaugušais', 0, 'Jauns',
                 'Pieaugušs kaķis tēviņš "Rūdis", 4 gadi, kastrēts, vakcinēts, mikročips. Ļoti maigs un mīlīgs. Meklē jaunu saimnieku jo īpašnieks izceļo. Simboliska maksa vai bez.',
                 'https://images.unsplash.com/photo-1548681528-6a5c45b66b42?w=800&q=80'],

                ['Šveices zirgs pārdošanā Pierīgā', 5500, 'Jauns',
                 'KWPN Sport zirgs ķēve, 7 gadi, 165cm. Apmācīta dresūrā un šķērsļos līdz 115cm. Labas manises, mierīga rakstura. Jānodod jo nav laika.',
                 'https://images.unsplash.com/photo-1553284965-83fd3e82fa5a?w=800&q=80'],

                ['Trusis pāris Holland Lop', 85, 'Jauns',
                 'Holland Lop trušu pāris — tēviņš un meitene, 6 mēneši. Dažādas krāsas. Abi vakcinēti. Komplektā būris, palīgmateriāli. Bērnu iecienītākie mazie audzēkņi.',
                 'https://images.unsplash.com/photo-1585110396000-c9ffd4e4b308?w=800&q=80'],

                ['Āfrikas pelēkais papagailis Žako', 1200, 'Labi saglabājies',
                 'Āfrikas pelēkais papagailis "Einšteins", 8 gadi. Runā ~30 vārdi, zina dažas melodijas. Vakcinēts, CITES dokumenti. Jānodod papiedienu dēļ.',
                 'https://images.unsplash.com/photo-1552728089-57bdde30beb3?w=800&q=80'],

                ['Akvārijs 200L ar visu komplektu', 280, 'Labi saglabājies',
                 'Stikla akvārijs 200L + filtrs + apgaismojums + siltinātājs + substrāts + akmeņi + 15 dažādas zivis. Pilns komplekts gatavs novietošanai. Pašizņemšana.',
                 'https://images.unsplash.com/photo-1520301255226-bf5f144451c1?w=800&q=80'],

                ['Borderkollijas kucēni 3 pieejami', 550, 'Jauns',
                 'Border Collie kucēni, dzimušie 5. aprīlī. 3 pieejami — 2 tēviņi, 1 meitene. Abi vecāki sertificēti. Vakcinēti, mikročipoti. Aktīvām ģimenēm.',
                 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=800&q=80'],

                ['Kaķu mājiņa daudzstāvu XL', 65, 'Labi saglabājies',
                 'Daudzstāvu kaķu mājiņa 1.6m augstums, sizaļa stabu, 4 platformas, hamaks. Izmantots 3 gadus, labs stāvoklis. Pārdod jo iegādājam jaunu.',
                 'https://images.unsplash.com/photo-1548681528-6a5c45b66b42?w=800&q=80'],

                ['Suņu budas liela izmēra āra', 75, 'Labi saglabājies',
                 'Koka suņu buda lielam sunim, 90x70x80cm. Bitumena jumts, augstas drošības pakāpe. Izmantots 3 gadus. Pārdod jo suns pārcelts iekšā.',
                 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=800&q=80'],

                ['Jaukteņu kucēni bez dokumentiem', 50, 'Jauns',
                 'Jaukteņu kucēni, 8 nedēļas veci. 4 pieejami. Māte ir labradors/sētnieksunis, audzis laukos. Vakcinēti, mikročipoti. Simboliska maksa.',
                 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=800&q=80'],

                ['Persieši kaķi - tēviņš 5 gadi', 180, 'Labi saglabājies',
                 'Persieši kaķis tēviņš "Leopolds", 5 gadi, kastrēts, vakcinēts. Ilggara balta spalva, zila acs. Ļoti mierīgs. WCF dokumenti. Meklē mājas, mums ir alerģija.',
                 'https://images.unsplash.com/photo-1574144611937-0df059b5ef3e?w=800&q=80'],

                ['Akvarijistam: 50L saldūdens komplekts', 95, 'Labi saglabājies',
                 '50L saldūdens akvārijs + tetra internal filtrs + LED apgaismojums + siltinātājs + substrāts + dekorācijas + 8 zivis (neons, gupiji). Iesācējam ideāls.',
                 'https://images.unsplash.com/photo-1520301255226-bf5f144451c1?w=800&q=80'],

                ['Suņu apmācības komplekts', 35, 'Labi saglabājies',
                 'Suņu apmācības komplekts — krāsainās ciceri + arka + toneļi + balansa dēlis. Izmantots 1 gadu, labs stāvoklis. Agility sporta sākumam.',
                 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=800&q=80'],

                ['Zivtiņas gupiji 20 gabali', 15, 'Jauns',
                 'Gupiju zivtiņas — 20 gabali, dažādu asti un krāsu vīrieši + meitenes. No vairošanās pāris. Ideāla sākuma zivtiņa akvārijistam.',
                 'https://images.unsplash.com/photo-1520301255226-bf5f144451c1?w=800&q=80'],

                ['Šveices zīme kaķis — savvaļas krustojums', 280, 'Jauns',
                 'Bengālijas kaķis tēviņš, 1 gads, kastrēts. Leoparda raksts, aktīvs un interesants. Vakcinēts, mikročips. Piemērots aktīvai mājai.',
                 'https://images.unsplash.com/photo-1574144611937-0df059b5ef3e?w=800&q=80'],

                ['Rati suņu pārvietošanai, L', 95, 'Labi saglabājies',
                 'Suņu rati lielas sunis (līdz 25 kg), L izmērs, melni. Izmantots 2 gadus vecos suņa pastaigas. Labs stāvoklis. Viegls salocīšanai.',
                 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=800&q=80'],

                ['Hamesters ar visu aprīkojumu', 20, 'Jauns',
                 'Hamesters sīrietis ar lielu sprostu, riteni, mājiņu, pudeli, barību 2 kg. Kopā ar visu aprīkojumu. Meklē laipnu saimnieku.',
                 'https://images.unsplash.com/photo-1585110396000-c9ffd4e4b308?w=800&q=80'],

                ['Kaķu barība Royal Canin krājums', 55, 'Jauns',
                 'Royal Canin Persian Adult 4kg x3 maisos + 24 konservētas bankas. Nopirkts lielā partijā. Kaķis nomainījis diētu. Derīguma termiņš 2025.',
                 'https://images.unsplash.com/photo-1574144611937-0df059b5ef3e?w=800&q=80'],

                ['Putnubūris papagailim XL', 180, 'Labi saglabājies',
                 'Papagaiļu būris XL, 100x60x150cm, inox + antikrāsa. Horizontālie stieņi rāpošanai, 2 durvis. Izmantots 5 gadus kakardu pārim. Labs stāvoklis.',
                 'https://images.unsplash.com/photo-1552728089-57bdde30beb3?w=800&q=80'],

                ['Allauts suns — Samoieds 3 gadi', 450, 'Jauns',
                 'Samoieds tēviņš "Bjaška", 3 gadi. FCI dokumenti. Ļoti maigs, aktīvs, mīl bērnus. Kastrēts. Meklē saimnieku ar lielu pagalmu vai lauku māju.',
                 'https://images.unsplash.com/photo-1589941013453-ec89f33b5e95?w=800&q=80'],

                ['Zivju barošanas automāts akvārijam', 45, 'Jauns',
                 'Eheim Everyday automātiskais barotājs akvārijam. Nopirkts 2023., izmantots 3 mēnešus. Programmējams 1-4 reizes dienā. Ideāls atvaļinājumiem.',
                 'https://images.unsplash.com/photo-1520301255226-bf5f144451c1?w=800&q=80'],

                ['Dakstiņš kaķis — tīrasiņis 10 gadus', 0, 'Jauns',
                 'Dakstiņš (Dachshund) kaķis "Žuks", 10 gadi, kastrēts, vakcinēts, mikročips. Saimnieks aizgājis. Lūgums dot labu māju vecam un mierīgam sunim.',
                 'https://images.unsplash.com/photo-1589941013453-ec89f33b5e95?w=800&q=80'],

                ['Kaķu tualete Catit Jumbo Plus', 55, 'Jauns',
                 'Catit Jumbo Plus noslēgta kaķu tualete ar oglekļa filtru. Nopirkta 2023., izmantota 6 mēnešus, ļoti labs stāvoklis. Liela, ērta kaķiem.',
                 'https://images.unsplash.com/photo-1574144611937-0df059b5ef3e?w=800&q=80'],

                ['Stintes un mazsikspārnis — komplekts', 120, 'Jauns',
                 'Stintes pāris un mazais sikspārnis triplets kāzu puķu aranžēšanai. Dzimis 3. martā. 2 baltas stintes meitenes + 1 mazais sikspārnis. Pieejami no maija.',
                 'https://images.unsplash.com/photo-1585110396000-c9ffd4e4b308?w=800&q=80'],

                ['Dzegužkaķis Maine Coon kucēni', 680, 'Jauns',
                 'Maine Coon kaķēni — 2 tēviņi, 1 meitene, 10 nedēļas veci. FIFe reģistrēti, abi vecāki sertificēti. Vakcinēti, mikročipoti. Karaliskais raksturs.',
                 'https://images.unsplash.com/photo-1574144611937-0df059b5ef3e?w=800&q=80'],

                ['Suņu elektrozogs bezvadu 500m2', 95, 'Labi saglabājies',
                 'PetSafe YardMax bezvadu elektrisks suns žogs, 500m2 rādiuss. Komplektā stabu, akumulatora kaklasiksna. Izmantots 3 gadus. Labs stāvoklis.',
                 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=800&q=80'],

                ['Čihuahua tēviņš 2 gadi', 350, 'Jauns',
                 'Čihuahua tēviņš "Dāvis", 2 gadi, kastrēts. Garmatains, krēmbalts. Vakcinēts, mikročips, dokumenti. Jānodod jo mainās dzīvesvieta.',
                 'https://images.unsplash.com/photo-1589941013453-ec89f33b5e95?w=800&q=80'],

                ['Suņu bērnu ratiņi — maziem suņiem', 65, 'Jauns',
                 'Zooplus suņu ratiņi maziem suņiem (līdz 8 kg), rožainā krāsā. Nopirkti dāvanā, nav vajadzīgs izmērs. Ar etiķeti, iepakojumā.',
                 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=800&q=80'],

                ['Kaķu nākotnes māja ar sildītāju', 45, 'Jauns',
                 'Kaķu āra māja ar elektrisko sildītāju, izolācija, nobīdāmās durvis. Piemērota arī ziemas aukstumam. Nopirkta 2023., kaķis nomira.',
                 'https://images.unsplash.com/photo-1548681528-6a5c45b66b42?w=800&q=80'],

                ['Leopard geko audzēšanas komplekts', 95, 'Labi saglabājies',
                 'Leoparda geko "Sunny" + terārijs 60x40x30 + apgaismojums + siltums + barošanas kastes. 3 gadus vecs. Pieradis pie cilvēkiem. Pamats hobijistam.',
                 'https://images.unsplash.com/photo-1585110396000-c9ffd4e4b308?w=800&q=80'],

                ['Suņu apģērbu komplekts S izmērs', 25, 'Jauns',
                 'Suņu apģērbu komplekts S izmēram (līdz 5 kg) — 4 gabali: džemperis, lietusmētelis, kombinezons, svīteri. Nopirkts dāvanā, sunim nepareizs izmērs.',
                 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=800&q=80'],
            ],

            // ═══ PAKALPOJUMI (~35) ═══
            'Pakalpojumi' => [
                ['Tīmekļa vietnes izstrāde WordPress', 450, 'Jauns',
                 'Profesionāla tīmekļa vietnes izstrāde WordPress platformā. Pilnīgi responsīvs dizains, SEO optimizācija, ātra ielāde. No 5 lapām. Portfolio: skatīt saiti. Pieredze 8 gadi.',
                 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=800&q=80'],

                ['Elektriķis — māju un biroju elektrika', 25, 'Jauns',
                 'Sertificēts elektriķis. Rozetes, slēdži, elektriskās plītis, dūmu sensori, vadu nomaiņa. Strādāju Rīgā un Pierīgā. Ātri, droši, ar garantiju. Zvanīt darba dienās.',
                 'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?w=800&q=80'],

                ['Santehniķis — ūdensvads un kanalizācija', 30, 'Jauns',
                 'Santehniķis ar 15 gadu pieredzi. Ūdensvads, kanalizācija, vannas istabas aprīkojums, boileri. Ārkārtas bojājumi — pieejams visu diennakti. Ātri un kvalitatīvi.',
                 'https://images.unsplash.com/photo-1585771724684-38269d6639fd?w=800&q=80'],

                ['Kravas pārvadājumi 3.5t Rīgā', 35, 'Jauns',
                 'Kravas auto Mercedes Sprinter 3.5t. Pārcelšanās, mēbeļu pārvadājumi, veikalu piegādes. Rīga + visa Latvija. 2 cilvēki iekļauti, kravā. Zvanīt 24/7.',
                 'https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?w=800&q=80'],

                ['Angļu valodas apmācība', 18, 'Jauns',
                 'Angļu valodas apmācība visiem līmeņiem (A1-C1). Sertificēts skolotājs, Cambridge CELTA. Online vai klātienē Rīgā. Konversācija, bizness, eksāmenu sagatavošanās.',
                 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800&q=80'],

                ['Frizētāja izbrauc mājās', 22, 'Jauns',
                 'Frizētāja izbrauc mājās pa visu Rīgu. Griezums, krāsošana, stailošana. Arī veciem ļaudīm un bērniem. Nopirktas tikai augstas kvalitātes krāsas. Zvanīt iepriekš.',
                 'https://images.unsplash.com/photo-1560066984-138dadb4c035?w=800&q=80'],

                ['Matemātikas privātstundas abitūrientiem', 15, 'Jauns',
                 'Matemātikas privātstundas 8.-12. klasei un abitūrientiem. Pieredze gatavošanā centralizētajam eksāmenam. Labs rezultāts garantēts. Online vai klātienē.',
                 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800&q=80'],

                ['Flīzētājs — vannas istabas remonts', 22, 'Jauns',
                 'Flīzētājs ar 12 gadu pieredzi. Vannas istabas, virtuve, grīdas. Kvalitatīvs darbs, tīra darbavieta. Rīga un Pierīga. Atskaites fotoattēli pieejami.',
                 'https://images.unsplash.com/photo-1572535461492-c5e660d22e40?w=800&q=80'],

                ['Foto un video kāzām 2024', 800, 'Jauns',
                 'Profesionāls fotogrāfs un operators kāzām. 10+ gadu pieredze. Pilna diena, 2 fotogrāfi, video montāža + drone kadri. Portfolio pēc pieprasījuma. Brīvas dienas 2024.',
                 'https://images.unsplash.com/photo-1519741497674-611481863552?w=800&q=80'],

                ['Datoru remonts un uzturēšana', 20, 'Jauns',
                 'Datoru remonts mājās vai jūsu uzņēmumā. Windows, Mac, Linux. Vīrusi, lēni datori, ekrāni, tastatūras. IT atbalsts uzņēmumiem. Rīga + Pierīga.',
                 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=800&q=80'],

                ['Jumta darbi — Rīgā un Pierīgā', 18, 'Jauns',
                 'Jumta darbi — seguma nomaiņa, remonts, noteces sistēmas. Bitumena, metāla, kārniņu jumti. Rīga un Pierīga. Bezmaksas tāme. 10 gadu darba garantija.',
                 'https://images.unsplash.com/photo-1572535461492-c5e660d22e40?w=800&q=80'],

                ['Grāmatvede — maziem uzņēmumiem', 80, 'Jauns',
                 'Grāmatvedes pakalpojumi maziem un vidējiem uzņēmumiem. Grāmatvedība, algu aprēķins, VID deklarācijas, gada pārskati. Pieredze 15 gadi. Saprātīgas cenas.',
                 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=800&q=80'],

                ['Logo un zīmola dizaina izstrāde', 180, 'Jauns',
                 'Profesionāls grafiskais dizainers. Logo, vizītkartes, brošūras, sociālo tīklu banneri. 3 koncepcijas un neierobežotas labojumu kārtas. Termiņš 5 darba dienas.',
                 'https://images.unsplash.com/photo-1626785774573-4b799315345d?w=800&q=80'],

                ['Ģenerālā uzkopšana pēc remonta', 85, 'Jauns',
                 'Profesionāla uzkopšana pēc remonta. Putekļu un netīrumu tīrīšana, logu mazgāšana, grīdas tīrīšana. Rīga un Pierīga. 3 cilvēku brigāde. Pieredze 7 gadi.',
                 'https://images.unsplash.com/photo-1585771724684-38269d6639fd?w=800&q=80'],

                ['SEO optimizācija un Google ADS', 250, 'Jauns',
                 'SEO optimizācija un Google Ads kampaņu pārvaldība. Rūpīga atslēgvārdu izpēte, tehniskā optimizācija, satura mārketings. Atskaite katru mēnesi.',
                 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=800&q=80'],

                ['Kosmētologs — sejas kopšana mājās', 35, 'Jauns',
                 'Sertificēta kosmētoloģe izbrauc mājās. Sejas tīrīšana, masāža, maskas. Rīga un tuvākā Pierīga. Hyalurona fillery pēc pieprasījuma. Pieredze 10 gadi.',
                 'https://images.unsplash.com/photo-1560066984-138dadb4c035?w=800&q=80'],

                ['Mācīšana peldēt — bērniem', 20, 'Jauns',
                 'Peldēšanas apmācība bērniem no 3 gadiem. Sertificēts peldēšanas treneris, pieredze 12 gadi. Rīgas baseini. 10 nodarbību kurss. Individuāli vai grupas.',
                 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800&q=80'],

                ['Personas trenērs sporta zālē', 25, 'Jauns',
                 'Personas trenērs Rīgas sporta zālēs. Svara zaudēšana, muskuļu audzēšana, rehabilitācija. Uztura konsultācija iekļauta. Pieredze 8 gadi. ERSP sertifikāts.',
                 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=800&q=80'],

                ['Auto mehāniķis — mājās izbraukšana', 30, 'Jauns',
                 'Auto mehāniķis ar izbraukšanu uz mājām. Eļļas maiņa, bremzes, akumulatori, burteni. Rīga. Saveicami pārbaudes pirms pirkuma! Pieredze 20 gadi.',
                 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&q=80'],

                ['Tulkošana LV-EN-RU dokumentiem', 15, 'Jauns',
                 'Sertificēts tulkotājs. Latvieši-angļu-krievu dokumentu tulkojumi. Juridiskie, medicīniskie, tehniskie teksti. Zvērināts tulkojums pieejams. Ātri un precīzi.',
                 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=800&q=80'],

                ['Ēdiena piegāde — mājas virtuve', 12, 'Jauns',
                 'Mājas virtuve — svaigi pagatavotas pusdienas piegāde birojiem un privātpersonām Rīgas centrā. 3 ēdienkartes katru dienu. Pasūtīt līdz 10.00 piegāde 12.00.',
                 'https://images.unsplash.com/photo-1556761175-b413da4baf72?w=800&q=80'],

                ['Sniega frēzēšana ziemā Pierīgā', 35, 'Jauns',
                 'Sniega frēzēšana mājvietu iebrauktuvēs un ceļos Pierīgā (Ādaži, Mārupi, Babīte). Sezonāls abonements vai atsevišķi izsaukumi. Sazinies jau tagad!',
                 'https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?w=800&q=80'],

                ['Fotogrāfs portretiem un LinkedIn', 80, 'Jauns',
                 'Profesionāla portretfotogrāfija biznesam, LinkedIn profilam, CV foto. Rīgā vai studijā. 1 stunda, 30+ apstrādātas fotogrāfijas. Portfolio pieejams.',
                 'https://images.unsplash.com/photo-1519741497674-611481863552?w=800&q=80'],

                ['Dārza kopšana sezonāli', 15, 'Jauns',
                 'Dārza kopšana sezonāli — zāles pļaušana, koku apgriešana, lapas savākšana, dārza uzkopšana. Rīgas apkārtne. Sezonāls abonements vai reizi.',
                 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=800&q=80'],

                ['Apdrošināšanas brokeri — auto un māja', 0, 'Jauns',
                 'Neatkarīgs apdrošināšanas brokeris. Salīdzinām visu tirgus piedāvājumu — OCTA, KASKO, mājas apdrošināšana. Bezmaksas konsultācija. Ietaupam jūsu naudu.',
                 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=800&q=80'],

                ['Bārenis makšķerēšanas gids Latvijā', 120, 'Jauns',
                 'Makšķerēšanas gids Latvijas ezeros un upēs. Viss aprīkojums iekļauts, vietas zināmas un rezultatīvas. 1-4 personas, visa diena. Foto dokumentācija iekļauta.',
                 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800&q=80'],

                ['Mājas gids senioru aprūpei', 12, 'Jauns',
                 'Mājas gids vecākiem cilvēkiem — iepirkšanās, pavadīšana uz poliklīniku, palīdzīga roka ikdienas darbos. Rīgā. Pieredze aprūpē 5 gadi.',
                 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&q=80'],

                ['IT drošības audits maziem uzņēmumiem', 280, 'Jauns',
                 'IT drošības audits maziem un vidējiem uzņēmumiem. Tīkla ievainojamību pārbaude, paroles drošība, backup politika. Ziņojums + ieteikumi. Sertificēts speciālists.',
                 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=800&q=80'],

                ['Psihologs — tiešsaistē un klātienē', 45, 'Jauns',
                 'Sertificēts psihologs. Individuālās konsultācijas pieaugušajiem, stresa, trauksmes, attiecību jautājumos. Tiešsaistē vai klātienē Rīgā. Pirmā konsultācija — bezmaksas.',
                 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&q=80'],

                ['Interjera dizainers — konsultācija', 80, 'Jauns',
                 'Interjera dizainera konsultācija jūsu mājā vai dzīvoklī. 3D vizualizācija, materiālu izvēle, finansiāli pamatots plāns. Rīgā un Pierīgā. Portfolio pieejams.',
                 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=800&q=80'],

                ['Kresls nagu galerijā — bezgalīgi', 25, 'Jauns',
                 'Nagu kopšana un manikīrs Rīgas centrā. Šellaka, gel-laka, akrils, nail art. Prenotēties vismaz 2 dienas iepriekš. Arī mājviesītes pieejams.',
                 'https://images.unsplash.com/photo-1560066984-138dadb4c035?w=800&q=80'],

                ['Kāzu vadītājs un koordinators', 350, 'Jauns',
                 'Profesionāls kāzu koordinators un vadītājs. Plānošana no sākuma līdz beigām, piegādātāju koordinācija, kāzu ceremonija. 8+ kāzu pieredze. Portfolio pieejams.',
                 'https://images.unsplash.com/photo-1519741497674-611481863552?w=800&q=80'],

                ['Logu mazgāšana augstceltnes', 3, 'Jauns',
                 'Logu mazgāšana augstceltnēs un daudzstāvu ēkās. Industriāls alpinisms, visas drošības prasības ievērotas. Rīga. Cena no 3 EUR par logu. Grupas atlaides.',
                 'https://images.unsplash.com/photo-1585771724684-38269d6639fd?w=800&q=80'],

                ['Bērnu uzraudzītāja ar pieredzi', 8, 'Jauns',
                 'Bērnu uzraudzītāja (babysitter) ar pieredzi. Arī vakari un nedēļas nogales. Pirmās palīdzības kurss nokārtots. Rīgā. Atsauksmes pieejamas.',
                 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800&q=80'],
            ],
        ];

        // ─── 6. Ievieto sludinājumus ───
        $total = 0;
        $now = now();
        $conds = ['Jauns', 'Lietots', 'Labi saglabājies', 'Apmierinošs', 'Renovēts'];
        $pstats = ['Fiksēta', 'Runājama', 'Piedāvājiet'];

        foreach ($listings as $catName => $catListings) {
            $catId = $catIds[$catName] ?? null;
            if (!$catId) continue;

            $catSubIds = $subIds[$catName] ?? [];

            foreach ($catListings as $index => $listing) {
                [$name, $price, $cond, $description, $image] = $listing;

                // Randomizē atrašanās vietu
                $loc = $resolvedLocations[array_rand($resolvedLocations)];

                // Randomizē lietotāju (ar dažādiem uzticamības līmeņiem)
                $userId = $users[array_rand($users)];

                // Randomizē apakškategoriju
                $subId = !empty($catSubIds) ? $catSubIds[$index % count($catSubIds)] : null;

                // Randomizē publicēšanas datumu (pēdējie 6 mēneši)
                $createdAt = $now->copy()->subDays(rand(1, 180))->subHours(rand(0, 23));

                DB::table('advertisements')->insert([
                    'user_id'          => $userId,
                    'feature_image'    => $image,
                    'first_image'      => null,
                    'second_image'     => null,
                    'images'           => null,
                    'category_id'      => $catId,
                    'subcategory_id'   => $subId,
                    'childcategory_id' => null,
                    'name'             => $name,
                    'slug'             => Str::slug($name) . '-' . ($total + 1),
                    'description'      => $description,
                    'price'            => (string) $price,
                    'price_status'     => $pstats[array_rand($pstats)],
                    'product_condition' => $cond,
                    'listing_location' => $loc['label'],
                    'country_id'       => $latviaId,
                    'state_id'         => $loc['state_id'],
                    'city_id'          => $loc['city_id'],
                    'phone_number'     => '+3712' . rand(1000000, 9999999),
                    'link'             => null,
                    'published'        => 1,
                    'created_at'       => $createdAt,
                    'updated_at'       => $createdAt,
                ]);

                $total++;
            }
        }

        $this->command->info("✅ Izveidoti {$total} reālistiski Latvijas sludinājumi.");
        $this->command->info("👥 Izveidoti " . count($users) . " demo lietotāji.");
        $this->command->info("📂 Izveidotas " . count($catIds) . " kategorijas ar apakškategorijām.");
        $this->command->info("🏙  Izmantotas " . count($resolvedLocations) . " Latvijas pilsētas.");
    }
}