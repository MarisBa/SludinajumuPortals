@php use Illuminate\Support\Str; @endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Saruna ar {{ $otherParty->name ?? 'Lietotāju' }} — {{ config('app.name', 'SludinajumuPortals') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; background: #F8FAFC; color: #334155; -webkit-font-smoothing: antialiased; }
        .chat-grid { display: grid; grid-template-columns: 320px 1fr; gap: 1rem; }
        @media (max-width: 991.98px) {
            .chat-grid { grid-template-columns: 1fr; }
            .chat-side { display: none; }
            .chat-side.show { display: block; }
        }

        .panel { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
        .panel-head { padding: 14px 16px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; gap: .75rem; }
        .panel-head h2 { font-size: .98rem; font-weight: 700; margin: 0; color: #0f172a; display: flex; align-items: center; gap: .4rem; }

        .conv-list { max-height: 600px; overflow-y: auto; }
        .conv-item { display: block; padding: 12px 16px; border-bottom: 1px solid #f1f5f9; text-decoration: none; color: inherit; transition: .15s; }
        .conv-item:last-child { border-bottom: none; }
        .conv-item:hover { background: #f8fafc; color: inherit; }
        .conv-item.active { background: #2563EB; color: #fff; }
        .conv-item.active .conv-sub, .conv-item.active small { color: rgba(255,255,255,.85) !important; }
        .conv-item .conv-name { font-weight: 600; font-size: .9rem; display: flex; justify-content: space-between; align-items: center; gap: .5rem; }
        .conv-item .conv-sub { font-size: .78rem; color: #64748b; margin-top: .15rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .badge-unread { background: #ef4444; color: #fff; border-radius: 99px; padding: .1rem .45rem; font-size: .7rem; font-weight: 700; flex-shrink: 0; }

        .chat-header { padding: 14px 16px; border-bottom: 1px solid #e2e8f0; }
        .chat-header .row1 { display: flex; align-items: center; gap: .75rem; }
        .av { width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0; background: #2563EB; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; overflow: hidden; }
        .av img { width: 100%; height: 100%; object-fit: cover; }
        .chat-header .name { font-weight: 700; color: #0f172a; }
        .chat-header .ad-meta { margin-top: .5rem; padding: .5rem .75rem; background: #f8fafc; border-radius: 8px; font-size: .82rem; color: #475569; display: flex; align-items: center; gap: .4rem; }

        #messageList { height: 500px; overflow-y: auto; padding: 20px; background: #f9fafb; }
        .msg-bubble-wrap { margin-bottom: 12px; display: flex; }
        .msg-bubble-wrap.mine { justify-content: flex-end; }
        .msg-bubble { padding: .55rem .85rem; border-radius: 12px; max-width: 70%; box-shadow: 0 1px 2px rgba(0,0,0,.04); }
        .msg-bubble .body { white-space: pre-wrap; word-wrap: break-word; line-height: 1.4; font-size: .92rem; }
        .msg-bubble small { font-size: .72rem; opacity: .85; display: inline-block; margin-top: .15rem; }
        .msg-bubble.mine { background: #2563EB; color: #fff; }
        .msg-bubble.theirs { background: #fff; color: #1e293b; border: 1px solid #e2e8f0; }

        .chat-input { padding: 12px 16px; border-top: 1px solid #e2e8f0; background: #fff; }
        .chat-input textarea { resize: none; }
        .chat-hint { font-size: .72rem; color: #94a3b8; margin-top: .25rem; }

        .empty-chat { padding: 3rem 1rem; text-align: center; color: #94a3b8; font-size: .9rem; }
    </style>
</head>
<body>
    @include('partials.navbar')

    <div class="container-fluid py-4" style="max-width: 1300px;">
        <div class="chat-grid">

            <aside class="chat-side panel">
                <div class="panel-head">
                    <h2><i class="bi bi-chat-dots"></i> Sarunas</h2>
                    <a href="{{ route('messages.index') }}" class="btn btn-sm btn-outline-secondary">Visas</a>
                </div>
                <div class="conv-list">
                    @forelse($allConversations as $conv)
                        @php
                            $other = $conv->otherParty(auth()->user());
                            $unread = $conv->unreadCountFor(auth()->user());
                            $isActive = $conv->id === $conversation->id;
                        @endphp
                        <a href="{{ route('messages.show', $conv->id) }}" class="conv-item {{ $isActive ? 'active' : '' }}">
                            <div class="conv-name">
                                <span class="text-truncate">{{ $other->name ?? 'Lietotājs' }}</span>
                                @if($unread > 0 && !$isActive)
                                    <span class="badge-unread">{{ $unread }}</span>
                                @endif
                            </div>
                            <div class="conv-sub">
                                {{ Str::limit($conv->advertisement->name ?? 'Dzēsts sludinājums', 30) }}
                            </div>
                        </a>
                    @empty
                        <div class="empty-chat">Nav citu sarunu.</div>
                    @endforelse
                </div>
            </aside>

            <section class="panel">
                <div class="chat-header">
                    <div class="row1">
                        <div class="av">
                            @if($otherParty && $otherParty->avatar)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url(str_replace('public/', '', $otherParty->avatar)) }}" alt="">
                            @else
                                {{ strtoupper(mb_substr($otherParty->name ?? '?', 0, 1)) }}
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <div class="name">{{ $otherParty->name ?? 'Lietotājs' }}</div>
                        </div>
                        @if($conversation->advertisement)
                            <a href="{{ url('/product-detail/' . $conversation->advertisement->id . '/' . ($conversation->advertisement->slug ?? 'ad')) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-tag"></i> Skatīt sludinājumu
                            </a>
                        @endif
                    </div>
                    @if($conversation->advertisement)
                        <div class="ad-meta">
                            <i class="bi bi-tag-fill"></i>
                            <strong>{{ $conversation->advertisement->name }}</strong>
                            <span class="ms-auto">{{ number_format((float) $conversation->advertisement->price, 2, ',', ' ') }} €</span>
                        </div>
                    @endif
                </div>

                <div id="messageList">
                    @foreach($messages as $msg)
                        @php $isMine = $msg->sender_id === auth()->id(); @endphp
                        <div class="msg-bubble-wrap {{ $isMine ? 'mine' : '' }}" data-message-id="{{ $msg->id }}">
                            <div class="msg-bubble {{ $isMine ? 'mine' : 'theirs' }}">
                                <div class="body">{{ $msg->body }}</div>
                                <small>
                                    {{ $msg->created_at->format('H:i') }}
                                    @if($isMine && $msg->read_at)
                                        <i class="bi bi-check2-all"></i>
                                    @elseif($isMine)
                                        <i class="bi bi-check2"></i>
                                    @endif
                                </small>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="chat-input">
                    <form id="messageForm">
                        @csrf
                        <div class="input-group">
                            <textarea id="messageInput"
                                      class="form-control"
                                      rows="2"
                                      placeholder="Raksti ziņu..."
                                      maxlength="2000"
                                      required></textarea>
                            <button class="btn btn-primary" type="submit" id="sendButton">
                                <i class="bi bi-send"></i> Sūtīt
                            </button>
                        </div>
                        <div class="chat-hint">Enter — sūtīt, Shift+Enter — jauna rinda</div>
                    </form>
                </div>
            </section>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    (function() {
        const conversationId = {{ $conversation->id }};
        const currentUserId = {{ auth()->id() }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
                        || document.querySelector('input[name="_token"]')?.value;

        if (!csrfToken) {
            console.error('CSRF token not found! Add <meta name="csrf-token" content="{{ csrf_token() }}"> to layout head');
        }

        const messageList = document.getElementById('messageList');
        const messageForm = document.getElementById('messageForm');
        const messageInput = document.getElementById('messageInput');
        const sendButton = document.getElementById('sendButton');

        let lastMessageId = 0;
        let isSending = false;

        const existingMessages = messageList.querySelectorAll('[data-message-id]');
        existingMessages.forEach(el => {
            const id = parseInt(el.dataset.messageId);
            if (id > lastMessageId) lastMessageId = id;
        });

        function scrollToBottom() {
            messageList.scrollTop = messageList.scrollHeight;
        }
        scrollToBottom();

        function formatTime(isoString) {
            const d = new Date(isoString);
            const hh = String(d.getHours()).padStart(2, '0');
            const mm = String(d.getMinutes()).padStart(2, '0');
            return hh + ':' + mm;
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function appendMessage(msg) {
            if (document.querySelector('[data-message-id="' + msg.id + '"]')) {
                return;
            }

            const isMine = msg.sender_id === currentUserId;
            const wrapper = document.createElement('div');
            wrapper.className = 'msg-bubble-wrap' + (isMine ? ' mine' : '');
            wrapper.dataset.messageId = msg.id;

            wrapper.innerHTML = `
                <div class="msg-bubble ${isMine ? 'mine' : 'theirs'}">
                    <div class="body">${escapeHtml(msg.body)}</div>
                    <small>${formatTime(msg.created_at)}</small>
                </div>
            `;

            messageList.appendChild(wrapper);
            scrollToBottom();

            if (msg.id > lastMessageId) {
                lastMessageId = msg.id;
            }
        }

        async function sendMessage() {
            const body = messageInput.value.trim();
            if (!body || isSending) return;

            isSending = true;
            sendButton.disabled = true;
            sendButton.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

            try {
                const response = await fetch('/messages/' + conversationId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ body: body }),
                });

                if (!response.ok) {
                    throw new Error('HTTP ' + response.status);
                }

                const data = await response.json();
                if (data.success && data.message) {
                    appendMessage(data.message);
                    messageInput.value = '';
                } else {
                    throw new Error('Invalid response');
                }
            } catch (error) {
                console.error('Send error:', error);
                alert('Neizdevās nosūtīt ziņu: ' + error.message);
            } finally {
                isSending = false;
                sendButton.disabled = false;
                sendButton.innerHTML = '<i class="bi bi-send"></i> Sūtīt';
            }
        }

        async function pollMessages() {
            if (document.hidden) return;

            try {
                const response = await fetch('/messages/' + conversationId + '/poll?after_id=' + lastMessageId, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (!response.ok) return;

                const data = await response.json();
                if (data.messages && data.messages.length > 0) {
                    data.messages.forEach(msg => appendMessage(msg));
                }
            } catch (error) {
                console.error('Poll error:', error);
            }
        }

        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            sendMessage();
        });

        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });

        setInterval(pollMessages, 5000);

        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) pollMessages();
        });
    })();
    </script>
</body>
</html>
