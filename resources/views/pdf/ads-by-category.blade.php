<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="utf-8">
    <title>Sludinājumi pa kategorijām</title>
    <style>
        @page { margin: 20mm; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #1f2937; font-size: 11px; line-height: 1.4; margin: 0; }
        h1 { color: #2563EB; border-bottom: 2px solid #2563EB; padding-bottom: 8px; margin: 0 0 6px 0; font-size: 20px; letter-spacing: .04em; }
        .meta { color: #6b7280; font-size: 11px; margin-bottom: 18px; }
        h2.cat-title { color: #0f172a; font-size: 14px; margin: 16px 0 6px 0; padding: 6px 10px; background: #eff6ff; border-left: 3px solid #2563EB; }
        table.ads { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        table.ads th { background: #f1f5f9; color: #475569; font-size: 10px; text-transform: uppercase; letter-spacing: .03em; text-align: left; padding: 6px 8px; border-bottom: 1px solid #e2e8f0; }
        table.ads td { padding: 6px 8px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
        table.ads td.num { color: #94a3b8; width: 32px; }
        table.ads td.price { white-space: nowrap; font-weight: bold; color: #166534; }
        .empty { color: #94a3b8; font-style: italic; padding: 6px 10px; font-size: 10px; }
        .page-break { page-break-after: always; }
        .footer {
            position: fixed; bottom: -10mm; left: 0; right: 0;
            font-size: 10px; color: #6b7280; border-top: 1px solid #e5e7eb; padding-top: 8px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>SLUDINĀJUMI PA KATEGORIJĀM</h1>
    <div class="meta">
        Eksportēts: <strong>{{ $generatedAt }}</strong> &nbsp;|&nbsp;
        Aktīvi sludinājumi kopā: <strong>{{ $totalAds }}</strong>
    </div>

    @php $renderedCats = 0; @endphp
    @foreach($categories as $cat)
        @if($cat->ads->count() === 0)
            @continue
        @endif
        @php $renderedCats++; @endphp

        <h2 class="cat-title">{{ $cat->name }} ({{ $cat->ads->count() }})</h2>
        <table class="ads">
            <thead>
                <tr>
                    <th>Nr</th>
                    <th>Nosaukums</th>
                    <th>Cena</th>
                    <th>Autors</th>
                    <th>Datums</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cat->ads as $i => $ad)
                    <tr>
                        <td class="num">{{ $i + 1 }}</td>
                        <td>{{ $ad->name }}</td>
                        <td class="price">{{ number_format((float) $ad->price, 2, ',', ' ') }} €</td>
                        <td>{{ $ad->user->name ?? '—' }}</td>
                        <td>{{ optional($ad->created_at)->format('d.m.Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    @if($renderedCats === 0)
        <div class="empty">Nav publicētu sludinājumu nevienā kategorijā.</div>
    @endif

    <div class="footer">
        Atskaite ģenerēta {{ $generatedAt }} — {{ config('app.name') }}
    </div>
</body>
</html>
