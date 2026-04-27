@extends('admin.layout')

@section('title', 'Sākums')

@section('content')
    <div class="row g-3 mb-3">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#eff6ff;color:#2563EB;"><i class="bi bi-people-fill"></i></div>
                <div>
                    <div class="stat-label">Lietotāji kopā</div>
                    <div class="stat-value">{{ $stats['total_users'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#fee2e2;color:#dc2626;"><i class="bi bi-person-fill-slash"></i></div>
                <div>
                    <div class="stat-label">Bloķēti</div>
                    <div class="stat-value">{{ $stats['blocked_users'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#fef3c7;color:#b45309;"><i class="bi bi-megaphone-fill"></i></div>
                <div>
                    <div class="stat-label">Sludinājumi kopā</div>
                    <div class="stat-value">{{ $stats['total_ads'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#dcfce7;color:#15803d;"><i class="bi bi-check-circle-fill"></i></div>
                <div>
                    <div class="stat-label">Aktīvi sludinājumi</div>
                    <div class="stat-value">{{ $stats['active_ads'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#ede9fe;color:#6d28d9;"><i class="bi bi-grid-fill"></i></div>
                <div>
                    <div class="stat-label">Kategorijas</div>
                    <div class="stat-value">{{ $stats['total_categories'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel mb-3">
        <div class="panel-head">
            <h2><i class="bi bi-file-earmark-pdf me-1"></i> Atskaites</h2>
        </div>
        <div class="panel-body">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.reports.ads') }}" class="btn btn-outline-primary" target="_blank">
                    <i class="bi bi-file-pdf me-1"></i> PDF: Sludinājumi pa kategorijām
                </a>
                <a href="{{ route('admin.reports.users') }}" class="btn btn-outline-primary" target="_blank">
                    <i class="bi bi-file-pdf me-1"></i> PDF: Lietotāju saraksts
                </a>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-xl-6">
            <div class="panel">
                <div class="panel-head">
                    <h2><i class="bi bi-people me-1"></i> Jaunākie lietotāji</h2>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">Skatīt visus</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Vārds</th>
                                <th>E-pasts</th>
                                <th>Reģistrēts</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stats['recent_users'] as $u)
                                <tr>
                                    <td class="fw-semibold">{{ $u->name }}</td>
                                    <td>{{ $u->email }}</td>
                                    <td>{{ optional($u->created_at)->format('d.m.Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="empty-state">Nav lietotāju.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="panel">
                <div class="panel-head">
                    <h2><i class="bi bi-megaphone me-1"></i> Jaunākie sludinājumi</h2>
                    <a href="{{ route('admin.ads.index') }}" class="btn btn-sm btn-outline-primary">Skatīt visus</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Nosaukums</th>
                                <th>Autors</th>
                                <th>Datums</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stats['recent_ads'] as $ad)
                                <tr>
                                    <td class="fw-semibold text-truncate" style="max-width:240px;">{{ $ad->name }}</td>
                                    <td>{{ $ad->user->name ?? '—' }}</td>
                                    <td>{{ optional($ad->created_at)->format('d.m.Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="empty-state">Nav sludinājumu.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
