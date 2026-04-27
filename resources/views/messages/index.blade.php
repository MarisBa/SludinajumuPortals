@php use Illuminate\Support\Str; @endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ziņas — {{ config('app.name', 'SludinajumuPortals') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; background: #F8FAFC; color: #334155; -webkit-font-smoothing: antialiased; }
        .msg-wrap { max-width: 900px; }
        .msg-list { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
        .msg-row { display: flex; align-items: center; gap: 1rem; padding: 14px 16px; border-bottom: 1px solid #f1f5f9; transition: .15s; text-decoration: none; color: inherit; }
        .msg-row:last-child { border-bottom: none; }
        .msg-row:hover { background: #f8fafc; }
        .msg-row.unread { background: #eff6ff; }
        .msg-row.unread:hover { background: #dbeafe; }
        .av { width: 50px; height: 50px; border-radius: 50%; flex-shrink: 0; background: #2563EB; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.05rem; overflow: hidden; }
        .av img { width: 100%; height: 100%; object-fit: cover; }
        .msg-meta { flex: 1; min-width: 0; }
        .msg-head { display: flex; justify-content: space-between; align-items: baseline; gap: .5rem; }
        .msg-name { font-weight: 600; color: #0f172a; }
        .msg-name.bold { font-weight: 800; }
        .msg-time { color: #94a3b8; font-size: .78rem; flex-shrink: 0; white-space: nowrap; }
        .msg-ad { font-size: .82rem; color: #64748b; display: flex; align-items: center; gap: .35rem; margin-top: .15rem; }
        .msg-prev { font-size: .88rem; color: #64748b; margin-top: .15rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .msg-prev.bold { color: #0f172a; font-weight: 600; }
        .badge-unread { background: #ef4444; color: #fff; border-radius: 99px; padding: .15rem .5rem; font-size: .72rem; font-weight: 700; flex-shrink: 0; }
        .empty-state { background: #fff; border: 1px dashed #e2e8f0; border-radius: 12px; padding: 3rem 1.5rem; text-align: center; }
        .empty-state .icon { font-size: 4rem; color: #cbd5e1; }
        .h-title { font-size: 1.5rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: .5rem; }
        .h-title .badge-total { background: #ef4444; color: #fff; font-size: .8rem; padding: .25rem .55rem; border-radius: 99px; font-weight: 700; }
    </style>
</head>
<body>
    @include('partials.navbar')

    <div class="container py-4 msg-wrap">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h-title">
                <i class="bi bi-chat-dots-fill text-primary"></i> Ziņas
                @if($totalUnread > 0)
                    <span class="badge-total">{{ $totalUnread }}</span>
                @endif
            </h1>
        </div>

        @if(session('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-1"></i> {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Aizvērt"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Aizvērt"></button>
            </div>
        @endif

        @if($conversations->isEmpty())
            <div class="empty-state">
                <i class="bi bi-chat-square-text icon"></i>
                <h4 class="mt-3 text-muted">Nav nevienas sarunas</h4>
                <p class="text-muted mb-4">
                    Sāc sarunu, sazinoties ar pārdevēju sludinājuma lapā.
                </p>
                <a href="{{ url('/browse') }}" class="btn btn-primary">
                    <i class="bi bi-search"></i> Pārlūkot sludinājumus
                </a>
            </div>
        @else
            <div class="msg-list shadow-sm">
                @foreach($conversations as $conv)
                    @php
                        $other = $conv->otherParty(auth()->user());
                        $unread = $conv->unreadCountFor(auth()->user());
                    @endphp
                    <a href="{{ route('messages.show', $conv->id) }}" class="msg-row {{ $unread > 0 ? 'unread' : '' }}">
                        <div class="av">
                            @if($other && $other->avatar)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url(str_replace('public/', '', $other->avatar)) }}" alt="">
                            @else
                                {{ strtoupper(mb_substr($other->name ?? '?', 0, 1)) }}
                            @endif
                        </div>

                        <div class="msg-meta">
                            <div class="msg-head">
                                <span class="msg-name {{ $unread > 0 ? 'bold' : '' }}">
                                    {{ $other->name ?? 'Lietotājs' }}
                                </span>
                                <span class="msg-time">
                                    {{ $conv->last_message_at?->diffForHumans() ?? '' }}
                                </span>
                            </div>
                            <div class="msg-ad">
                                <i class="bi bi-tag"></i>
                                <span class="text-truncate">{{ $conv->advertisement->name ?? 'Dzēsts sludinājums' }}</span>
                            </div>
                            <div class="msg-prev {{ $unread > 0 ? 'bold' : '' }}">
                                {{ $conv->last_message_preview ?? '...' }}
                            </div>
                        </div>

                        @if($unread > 0)
                            <span class="badge-unread">{{ $unread }}</span>
                        @endif
                    </a>
                @endforeach
            </div>

            @if($conversations->hasPages())
                <div class="mt-4">
                    {{ $conversations->links() }}
                </div>
            @endif
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
