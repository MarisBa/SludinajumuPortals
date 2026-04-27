<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="utf-8">
    <title>Sludinājums #{{ $ad->id }}</title>
    <style>
        @page { margin: 20mm; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #1f2937; font-size: 12px; line-height: 1.5; margin: 0; }
        h1 { color: #2563EB; border-bottom: 2px solid #2563EB; padding-bottom: 8px; margin: 0 0 6px 0; font-size: 22px; letter-spacing: .04em; }
        h2 { color: #0f172a; font-size: 16px; margin: 0 0 18px 0; }
        h3 { color: #0f172a; font-size: 13px; margin: 18px 0 8px 0; text-transform: uppercase; letter-spacing: .05em; }
        .feature-wrap { text-align: center; margin: 14px 0; }
        img.feature { max-width: 100%; max-height: 300px; }
        .meta-grid { width: 100%; border-collapse: collapse; margin: 10px 0 14px 0; }
        .meta-grid td { padding: 6px 0; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
        .label { color: #6b7280; width: 38%; }
        .value { font-weight: bold; color: #0f172a; }
        .description { padding: 12px 14px; background: #f3f4f6; border-radius: 6px; white-space: pre-wrap; }
        .price-tag { display: inline-block; background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 4px; font-weight: bold; }
        .footer {
            position: fixed;
            bottom: -10mm; left: 0; right: 0;
            font-size: 10px; color: #6b7280; border-top: 1px solid #e5e7eb;
            padding-top: 8px;
        }
        .footer .row { display: block; }
    </style>
</head>
<body>
    <h1>SLUDINĀJUMS</h1>
    <h2>{{ $ad->name }}</h2>

    @php
        $imgPath = null;
        if ($ad->feature_image) {
            $candidate = storage_path('app/' . $ad->feature_image);
            if (is_file($candidate)) {
                $imgPath = $candidate;
            }
        }
    @endphp
    @if($imgPath)
        <div class="feature-wrap">
            <img class="feature" src="{{ $imgPath }}" alt="">
        </div>
    @endif

    <table class="meta-grid">
        <tr>
            <td class="label">Cena:</td>
            <td class="value"><span class="price-tag">{{ number_format((float) $ad->price, 2, ',', ' ') }} EUR</span></td>
        </tr>
        <tr>
            <td class="label">Kategorija:</td>
            <td class="value">{{ $ad->category->name ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Stāvoklis:</td>
            <td class="value">{{ $ad->product_condition ?: '—' }}</td>
        </tr>
        <tr>
            <td class="label">Atrašanās vieta:</td>
            <td class="value">
                @php
                    $loc = collect([
                        $ad->listing_location,
                        $ad->city->name ?? null,
                        $ad->state->name ?? null,
                        $ad->country->name ?? null,
                    ])->filter()->unique()->implode(', ');
                @endphp
                {{ $loc ?: '—' }}
            </td>
        </tr>
        <tr>
            <td class="label">Tālrunis:</td>
            <td class="value">{{ $ad->phone_number ?: '—' }}</td>
        </tr>
        <tr>
            <td class="label">Publicēts:</td>
            <td class="value">{{ optional($ad->created_at)->format('d.m.Y') }}</td>
        </tr>
    </table>

    <h3>Apraksts</h3>
    <div class="description">{{ $ad->description ?: 'Apraksts nav norādīts.' }}</div>

    <div class="footer">
        <span class="row">Pārdevējs: {{ $ad->user->name ?? '—' }} | E-pasts: {{ $ad->user->email ?? '—' }}</span>
        <span class="row">Eksportēts: {{ now()->format('d.m.Y H:i') }}</span>
    </div>
</body>
</html>
