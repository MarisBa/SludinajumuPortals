<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="utf-8">
    <title>Reģistrēto lietotāju saraksts</title>
    <style>
        @page { margin: 18mm; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #1f2937; font-size: 11px; line-height: 1.4; margin: 0; }
        h1 { color: #2563EB; border-bottom: 2px solid #2563EB; padding-bottom: 8px; margin: 0 0 6px 0; font-size: 20px; letter-spacing: .04em; }
        .stats { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 14px; margin: 12px 0 18px 0; font-size: 11px; }
        .stats .stat { display: inline-block; margin-right: 22px; }
        .stats .stat strong { color: #0f172a; font-size: 14px; }
        table.users { width: 100%; border-collapse: collapse; }
        table.users th { background: #2563EB; color: #fff; font-size: 10px; text-transform: uppercase; letter-spacing: .03em; text-align: left; padding: 7px 8px; }
        table.users td { padding: 6px 8px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
        table.users td.num { color: #94a3b8; width: 32px; }
        .role-user { color: #475569; }
        .role-admin { color: #b91c1c; font-weight: bold; }
        .status-active { color: #166534; font-weight: bold; }
        .status-blocked { color: #b91c1c; font-weight: bold; }
        .footer {
            position: fixed; bottom: -8mm; left: 0; right: 0;
            font-size: 10px; color: #6b7280; border-top: 1px solid #e5e7eb; padding-top: 8px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>REĢISTRĒTO LIETOTĀJU SARAKSTS</h1>

    <div class="stats">
        <span class="stat">Kopā: <strong>{{ $totalUsers }}</strong></span>
        <span class="stat">No tiem bloķēti: <strong>{{ $blockedUsers }}</strong></span>
        <span class="stat">Administratori: <strong>{{ $adminUsers }}</strong></span>
        <span class="stat">Eksportēts: <strong>{{ $generatedAt }}</strong></span>
    </div>

    <table class="users">
        <thead>
            <tr>
                <th>Nr</th>
                <th>Vārds</th>
                <th>E-pasts</th>
                <th>Tālrunis</th>
                <th>Loma</th>
                <th>Statuss</th>
                <th>Reģistrēts</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $i => $user)
                <tr>
                    <td class="num">{{ $i + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone ?: '—' }}</td>
                    <td class="{{ $user->role === 'admin' ? 'role-admin' : 'role-user' }}">
                        {{ $user->role === 'admin' ? 'Administrators' : 'Lietotājs' }}
                    </td>
                    <td class="{{ $user->is_blocked ? 'status-blocked' : 'status-active' }}">
                        {{ $user->is_blocked ? 'Bloķēts' : 'Aktīvs' }}
                    </td>
                    <td>{{ optional($user->created_at)->format('d.m.Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Atskaite ģenerēta {{ $generatedAt }} — {{ config('app.name') }}
    </div>
</body>
</html>
