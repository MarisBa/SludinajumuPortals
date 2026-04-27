@extends('admin.layout')

@section('title', 'Lietotāji')

@section('content')
    <div class="panel mb-3">
        <div class="panel-body">
            <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-6">
                    <label class="form-label small text-muted mb-1">Meklēt</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Vārds vai e-pasts...">
                </div>
                <div class="col-8 col-md-3">
                    <label class="form-label small text-muted mb-1">Filtrs</label>
                    <select name="filter" class="form-select">
                        <option value="">Visi</option>
                        <option value="blocked" @selected(request('filter')==='blocked')>Bloķēti</option>
                        <option value="admin" @selected(request('filter')==='admin')>Administratori</option>
                    </select>
                </div>
                <div class="col-4 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-funnel me-1"></i> Atlasīt</button>
                    @if(request('search') || request('filter'))
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Notīrīt</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <h2><i class="bi bi-people me-1"></i> Lietotāju saraksts</h2>
            <span class="text-muted small">Kopā: {{ $users->total() }}</span>
        </div>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Vārds</th>
                        <th>E-pasts</th>
                        <th>Tālrunis</th>
                        <th>Loma</th>
                        <th>Statuss</th>
                        <th>Reģistrēts</th>
                        <th class="text-end">Darbības</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="text-muted">#{{ $user->id }}</td>
                            <td class="fw-semibold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?: '—' }}</td>
                            <td>
                                @if($user->isAdmin())
                                    <span class="badge-soft bg-dan">Administrators</span>
                                @else
                                    <span class="badge-soft bg-grey">Lietotājs</span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_blocked)
                                    <span class="badge-soft bg-dan">Bloķēts</span>
                                @else
                                    <span class="badge-soft bg-suc">Aktīvs</span>
                                @endif
                            </td>
                            <td>{{ optional($user->created_at)->format('d.m.Y') }}</td>
                            <td class="text-end">
                                @if(!$user->isAdmin())
                                    @if($user->is_blocked)
                                        <form method="POST" action="{{ route('admin.users.unblock', $user) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success btn-icon" title="Atbloķēt">
                                                <i class="bi bi-unlock"></i> Atbloķēt
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.users.block', $user) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning btn-icon" title="Bloķēt">
                                                <i class="bi bi-lock"></i> Bloķēt
                                            </button>
                                        </form>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-danger btn-icon"
                                            data-bs-toggle="modal" data-bs-target="#delUser{{ $user->id }}">
                                        <i class="bi bi-trash"></i> Dzēst
                                    </button>

                                    <div class="modal fade" id="delUser{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Dzēst lietotāju?</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Aizvērt"></button>
                                                </div>
                                                <div class="modal-body text-start">
                                                    Vai tiešām vēlies dzēst lietotāju <strong>{{ $user->name }}</strong>?
                                                    Šī darbība arhivēs lietotāja kontu.
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Atcelt</button>
                                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="m-0">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger"><i class="bi bi-trash me-1"></i> Dzēst</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="empty-state">Nav atbilstošu lietotāju.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="panel-body">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
