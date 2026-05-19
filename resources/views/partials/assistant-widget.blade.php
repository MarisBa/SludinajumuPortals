{{-- AI Pirkšanas Asistents — peldošs čata logrīks --}}
{{-- Pieejams visās lapās; izmanto --primary un --dark mainīgos no layouts/app.blade.php --}}

<div id="ai-assistant-root">

    {{-- Peldošā poga (apaļa, apakšējā labajā stūrī) --}}
    <button type="button" id="ai-assistant-toggle" aria-label="Atvērt AI asistentu">
        <i class="bi bi-chat-dots-fill"></i>
    </button>

    {{-- Čata panelis (sākotnēji paslēpts) --}}
    <div id="ai-assistant-panel" role="dialog" aria-label="AI Pirkšanas Asistents" aria-hidden="true">

        {{-- Galvene --}}
        <div class="ai-panel-header">
            <div class="ai-panel-title">
                <i class="bi bi-stars"></i>
                <span>AI Pirkšanas Asistents</span>
            </div>
            <button type="button" id="ai-assistant-close" aria-label="Aizvērt">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        {{-- Ziņojumu plūsma --}}
        <div class="ai-panel-stream" id="ai-stream">
            {{-- Sākotnējais sveiciens un ātrās ieteikumu pogas --}}
            <div class="ai-msg ai-msg-bot">
                Sveiks! Es palīdzēšu atrast piemērotāko sludinājumu. Pastāsti, ko meklē — vai izvēlies kādu no piemēriem zemāk.
            </div>
            <div class="ai-quick" id="ai-quick">
                <button type="button" class="ai-quick-btn" data-query="Lēts auto līdz 4000 €">Lēts auto līdz 4000 €</button>
                <button type="button" class="ai-quick-btn" data-query="Telefons fotografēšanai">Telefons fotografēšanai</button>
                <button type="button" class="ai-quick-btn" data-query="Dīvāns mazam dzīvoklim">Dīvāns mazam dzīvoklim</button>
            </div>
        </div>

        {{-- Pieprasījuma ievades laukums --}}
        <form class="ai-panel-footer" id="ai-form" autocomplete="off">
            <input type="text" id="ai-input" placeholder="Ko tu meklē?" maxlength="300" required>
            <button type="submit" id="ai-send" aria-label="Sūtīt">
                <i class="bi bi-send-fill"></i>
            </button>
        </form>
    </div>
</div>

<style>
    /* === Peldošā poga === */
    #ai-assistant-toggle {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 9999;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        border: none;
        background: var(--primary, #2563eb);
        color: #fff;
        font-size: 26px;
        box-shadow: 0 8px 24px rgba(37, 99, 235, 0.35);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.18s ease, box-shadow 0.18s ease;
    }
    #ai-assistant-toggle:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(37, 99, 235, 0.45);
    }
    #ai-assistant-toggle.is-active {
        transform: scale(0.92);
    }

    /* === Čata panelis === */
    #ai-assistant-panel {
        position: fixed;
        bottom: 96px;
        right: 24px;
        z-index: 9999;
        width: 380px;
        height: 560px;
        max-height: calc(100vh - 120px);
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.25);
        display: none;
        flex-direction: column;
        overflow: hidden;
        opacity: 0;
        transform: translateY(12px);
        transition: opacity 0.2s ease, transform 0.2s ease;
    }
    #ai-assistant-panel.is-open {
        display: flex;
        opacity: 1;
        transform: translateY(0);
    }

    /* === Paneļa galvene === */
    .ai-panel-header {
        background: var(--dark, #1e293b);
        color: #fff;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
    }
    .ai-panel-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        font-size: 15px;
    }
    .ai-panel-title i { color: #fde68a; }
    #ai-assistant-close {
        background: transparent;
        border: none;
        color: #fff;
        font-size: 16px;
        cursor: pointer;
        opacity: 0.75;
        padding: 4px 8px;
        border-radius: 6px;
    }
    #ai-assistant-close:hover {
        opacity: 1;
        background: rgba(255, 255, 255, 0.08);
    }

    /* === Ziņojumu plūsma === */
    .ai-panel-stream {
        flex: 1;
        overflow-y: auto;
        padding: 16px;
        background: var(--gray-50, #f8fafc);
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .ai-msg {
        max-width: 85%;
        padding: 10px 14px;
        border-radius: 14px;
        font-size: 14px;
        line-height: 1.4;
        word-wrap: break-word;
    }
    .ai-msg-bot {
        background: #fff;
        color: var(--dark, #1e293b);
        border: 1px solid var(--gray-200, #e2e8f0);
        border-top-left-radius: 4px;
        align-self: flex-start;
    }
    .ai-msg-user {
        background: var(--primary, #2563eb);
        color: #fff;
        border-top-right-radius: 4px;
        align-self: flex-end;
    }
    .ai-msg-notice {
        background: var(--gray-100, #f1f5f9);
        color: var(--gray-600, #475569);
        font-size: 12px;
        align-self: stretch;
        max-width: 100%;
        border-radius: 8px;
        padding: 8px 12px;
    }
    .ai-msg-thinking {
        background: #fff;
        color: var(--gray-600, #475569);
        border: 1px solid var(--gray-200, #e2e8f0);
        border-top-left-radius: 4px;
        align-self: flex-start;
        font-style: italic;
    }
    .ai-msg-thinking::after {
        content: ' ●';
        animation: ai-blink 1.2s infinite;
    }
    @keyframes ai-blink {
        0%, 80%, 100% { opacity: 0.2; }
        40% { opacity: 1; }
    }

    /* === Ātro ieteikumu pogas === */
    .ai-quick {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        align-self: flex-start;
        max-width: 100%;
    }
    .ai-quick-btn {
        background: #fff;
        border: 1px solid var(--primary, #2563eb);
        color: var(--primary, #2563eb);
        font-size: 12px;
        padding: 6px 12px;
        border-radius: 999px;
        cursor: pointer;
        transition: background 0.15s ease;
    }
    .ai-quick-btn:hover {
        background: var(--primary, #2563eb);
        color: #fff;
    }

    /* === Sludinājuma kartiņa === */
    .ai-card {
        align-self: stretch;
        max-width: 100%;
        background: #fff;
        border: 1px solid var(--gray-200, #e2e8f0);
        border-radius: 12px;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        display: flex;
        gap: 10px;
        padding: 10px;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .ai-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 14px rgba(15, 23, 42, 0.08);
        color: inherit;
    }
    .ai-card-img {
        width: 80px;
        height: 80px;
        flex-shrink: 0;
        background: var(--gray-100, #f1f5f9);
        border-radius: 8px;
        object-fit: cover;
    }
    .ai-card-body { flex: 1; min-width: 0; }
    .ai-card-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--dark, #1e293b);
        margin: 0 0 4px 0;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    .ai-card-price {
        font-size: 14px;
        font-weight: 700;
        color: var(--primary, #2563eb);
        margin: 0 0 2px 0;
    }
    .ai-card-loc {
        font-size: 11px;
        color: var(--gray-600, #475569);
        margin: 0;
    }

    /* AI paskaidrojums zem kartiņas (zils citāts) */
    .ai-reason {
        align-self: stretch;
        max-width: 100%;
        background: #eff6ff;
        border-left: 3px solid var(--primary, #2563eb);
        padding: 8px 12px;
        font-size: 12.5px;
        color: var(--dark, #1e293b);
        line-height: 1.4;
        border-radius: 0 8px 8px 0;
        margin-top: -4px;
    }

    /* Padoma bloks (dzeltens) */
    .ai-tip {
        align-self: stretch;
        max-width: 100%;
        background: #fef9c3;
        border: 1px solid #fde047;
        padding: 10px 12px;
        font-size: 13px;
        color: #713f12;
        border-radius: 10px;
        line-height: 1.4;
    }

    /* === Apakšējais ievades panelis === */
    .ai-panel-footer {
        display: flex;
        gap: 8px;
        padding: 12px;
        background: #fff;
        border-top: 1px solid var(--gray-200, #e2e8f0);
        flex-shrink: 0;
    }
    #ai-input {
        flex: 1;
        border: 1px solid var(--gray-200, #e2e8f0);
        border-radius: 10px;
        padding: 10px 12px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.15s ease;
    }
    #ai-input:focus {
        border-color: var(--primary, #2563eb);
    }
    #ai-send {
        background: var(--primary, #2563eb);
        color: #fff;
        border: none;
        border-radius: 10px;
        width: 42px;
        flex-shrink: 0;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.15s ease;
    }
    #ai-send:hover:not(:disabled) { background: #1d4ed8; }
    #ai-send:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* === Mobilā versija (fullscreen) === */
    @media (max-width: 480px) {
        #ai-assistant-panel {
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            max-height: 100%;
            border-radius: 0;
        }
        #ai-assistant-toggle {
            bottom: 16px;
            right: 16px;
        }
    }
</style>

<script>
(function () {
    'use strict';

    // Saites uz galvenajiem DOM elementiem.
    const toggleBtn = document.getElementById('ai-assistant-toggle');
    const panel     = document.getElementById('ai-assistant-panel');
    const closeBtn  = document.getElementById('ai-assistant-close');
    const form      = document.getElementById('ai-form');
    const input     = document.getElementById('ai-input');
    const sendBtn   = document.getElementById('ai-send');
    const stream    = document.getElementById('ai-stream');

    // CSRF marķieris no layout meta taga.
    const csrfMeta  = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.content : '';

    // Endpointa URL no Blade route() palīga.
    const askUrl = @json(route('assistant.ask'));

    let isLoading = false;

    // === Paneļa atvēršana / aizvēršana ===
    function openPanel() {
        panel.classList.add('is-open');
        panel.setAttribute('aria-hidden', 'false');
        setTimeout(() => input.focus(), 150);
    }
    function closePanel() {
        panel.classList.remove('is-open');
        panel.setAttribute('aria-hidden', 'true');
    }
    toggleBtn.addEventListener('click', function () {
        if (panel.classList.contains('is-open')) {
            closePanel();
        } else {
            openPanel();
        }
    });
    closeBtn.addEventListener('click', closePanel);

    // === Ātrās ieteikumu pogas — aizpilda lauku un nekavējoties nosūta ===
    document.querySelectorAll('.ai-quick-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            input.value = btn.dataset.query || '';
            sendQuery();
        });
    });

    // === Formas iesniegšana ===
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        sendQuery();
    });

    // === Galvenā loģika — sūtīt pieprasījumu uz serveri ===
    async function sendQuery() {
        const text = (input.value || '').trim();
        if (text.length < 3 || isLoading) return;

        isLoading = true;
        sendBtn.disabled = true;

        // Drošības princips: lietotāja teksts iet caur textContent, nevis innerHTML.
        appendUserMsg(text);

        // Pēc pirmā jautājuma noslēpjam sākotnējās ieteikumu pogas.
        const quick = document.getElementById('ai-quick');
        if (quick) quick.style.display = 'none';

        input.value = '';

        // "Domāju..." indikators ar dzīvu animāciju.
        const thinking = document.createElement('div');
        thinking.className = 'ai-msg ai-msg-thinking';
        thinking.textContent = 'Domāju';
        stream.appendChild(thinking);
        scrollToBottom();

        try {
            const res = await fetch(askUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ query: text })
            });

            const data = await res.json();
            thinking.remove();
            renderResponse(data);
        } catch (err) {
            thinking.remove();
            appendBotMsg('Neizdevās savienoties. Pārbaudi internetu un mēģini vēlreiz.');
        } finally {
            isLoading = false;
            sendBtn.disabled = false;
            scrollToBottom();
        }
    }

    // === Atbildes renderēšana ===
    function renderResponse(data) {
        // Gadījums: nav atrasts neviens sludinājums vai cita servera ziņa.
        if (data.message) {
            appendBotMsg(data.message);
        }

        // Brīdinājuma josla, ja AI nav pieejams, bet sludinājumi tomēr ir.
        if (data.ai_available === false && Array.isArray(data.advertisements) && data.advertisements.length > 0) {
            appendNotice('AI šobrīd nav pieejams — rādu atbilstošākos sludinājumus.');
        }

        // AI ievada frāze.
        if (data.intro) {
            appendBotMsg(data.intro);
        }

        // Sludinājumu kartiņas.
        if (Array.isArray(data.advertisements)) {
            data.advertisements.forEach(function (ad) {
                appendCard(ad);
                if (ad.ai_reason) {
                    appendReason(ad.ai_reason);
                }
            });
        }

        // Padoma bloks.
        if (data.tip) {
            appendTip(data.tip);
        }
    }

    // === Helperi — visi izmanto textContent, nevis innerHTML (XSS aizsardzība) ===
    function appendUserMsg(text) {
        const el = document.createElement('div');
        el.className = 'ai-msg ai-msg-user';
        el.textContent = text;
        stream.appendChild(el);
        scrollToBottom();
    }
    function appendBotMsg(text) {
        const el = document.createElement('div');
        el.className = 'ai-msg ai-msg-bot';
        el.textContent = text;
        stream.appendChild(el);
    }
    function appendNotice(text) {
        const el = document.createElement('div');
        el.className = 'ai-msg ai-msg-notice';
        el.textContent = text;
        stream.appendChild(el);
    }
    function appendCard(ad) {
        // Saiti veidojam ar setAttribute, lai novērstu jebkādu XSS risku.
        const a = document.createElement('a');
        a.className = 'ai-card';
        a.setAttribute('href', ad.url || '#');

        const img = document.createElement('img');
        img.className = 'ai-card-img';
        img.setAttribute('src', ad.image || '');
        img.setAttribute('alt', '');
        img.setAttribute('loading', 'lazy');

        const body = document.createElement('div');
        body.className = 'ai-card-body';

        const name = document.createElement('p');
        name.className = 'ai-card-name';
        name.textContent = ad.name || '';

        const price = document.createElement('p');
        price.className = 'ai-card-price';
        price.textContent = ad.price ? (ad.price + ' €') : '';

        const loc = document.createElement('p');
        loc.className = 'ai-card-loc';
        loc.innerHTML = ''; // nodrošinām tukšu sākumstāvokli
        const locIcon = document.createElement('i');
        locIcon.className = 'bi bi-geo-alt';
        loc.appendChild(locIcon);
        loc.appendChild(document.createTextNode(' ' + (ad.location || '')));

        body.appendChild(name);
        body.appendChild(price);
        body.appendChild(loc);
        a.appendChild(img);
        a.appendChild(body);
        stream.appendChild(a);
    }
    function appendReason(text) {
        const el = document.createElement('div');
        el.className = 'ai-reason';
        el.textContent = text;
        stream.appendChild(el);
    }
    function appendTip(text) {
        const el = document.createElement('div');
        el.className = 'ai-tip';
        el.textContent = '💡 Padoms: ' + text;
        stream.appendChild(el);
    }
    function scrollToBottom() {
        stream.scrollTop = stream.scrollHeight;
    }
})();
</script>
