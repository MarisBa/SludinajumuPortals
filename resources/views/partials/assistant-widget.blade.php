{{-- AI Pirkšanas Asistents — peldošs čata logrīks --}}
{{-- Pieejams visās lapās; izmanto --primary un --dark mainīgos no layouts/app.blade.php --}}

<div id="ai-assistant-root">

    {{-- Peldošā poga (apaļa, apakšējā labajā stūrī) --}}
    <button type="button" id="ai-assistant-toggle" aria-label="Atvērt AI asistentu">
        <i class="bi bi-stars"></i>
    </button>

    {{-- Čata panelis (sākotnēji paslēpts) --}}
    <div id="ai-assistant-panel" role="dialog" aria-label="AI Pirkšanas Asistents" aria-hidden="true">

        {{-- Galvene --}}
        <div class="ai-panel-header">
            <div class="ai-panel-title">
                <span class="ai-panel-icon"><i class="bi bi-stars"></i></span>
                <div>
                    <div class="ai-panel-title-main">AI Pirkšanas Asistents</div>
                    <div class="ai-panel-subtitle">Atrod tev piemērotāko sludinājumu</div>
                </div>
            </div>
            <button type="button" id="ai-assistant-close" aria-label="Aizvērt">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        {{-- Ziņojumu plūsma --}}
        <div class="ai-panel-stream" id="ai-stream">
            {{-- Sākotnējais sveiciens un ātrās ieteikumu pogas --}}
            <div class="ai-row ai-row-bot">
                <span class="ai-avatar"><i class="bi bi-stars"></i></span>
                <div class="ai-msg ai-msg-bot">
                    Sveiks! Es palīdzēšu atrast piemērotāko sludinājumu. Pastāsti, ko meklē — vai izvēlies kādu no piemēriem zemāk.
                </div>
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
    /* === Dizaina mainīgie (lokāli widgetam) === */
    #ai-assistant-root {
        --aw-primary: var(--primary, #2563eb);
        --aw-primary-dark: #1d4ed8;
        --aw-primary-soft: #eff6ff;
        --aw-success: #10b981;
        --aw-success-soft: #ecfdf5;
        --aw-amber: #f59e0b;
        --aw-amber-soft: #fef3c7;
        --aw-amber-text: #78350f;
        --aw-dark: var(--dark, #0f172a);
        --aw-text: #1e293b;
        --aw-muted: #64748b;
        --aw-faint: #94a3b8;
        --aw-bdr: #e2e8f0;
        --aw-bg-soft: #f8fafc;
        --aw-shadow-sm: 0 1px 2px rgba(15, 23, 42, .06);
        --aw-shadow-md: 0 4px 12px rgba(15, 23, 42, .08);
        --aw-shadow-lg: 0 12px 40px rgba(15, 23, 42, .15);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

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
        background: linear-gradient(135deg, var(--aw-primary), var(--aw-primary-dark));
        color: #fff;
        font-size: 26px;
        box-shadow: 0 10px 28px rgba(37, 99, 235, 0.38);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    #ai-assistant-toggle:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 32px rgba(37, 99, 235, 0.5);
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
        height: 580px;
        max-height: calc(100vh - 120px);
        background: #fff;
        border-radius: 20px;
        box-shadow: var(--aw-shadow-lg);
        display: none;
        flex-direction: column;
        overflow: hidden;
        opacity: 0;
        transform: translateY(12px);
        transition: opacity 0.22s ease, transform 0.22s ease;
        font-family: inherit;
    }
    #ai-assistant-panel.is-open {
        display: flex;
        opacity: 1;
        transform: translateY(0);
    }

    /* === Paneļa galvene === */
    .ai-panel-header {
        background: linear-gradient(135deg, var(--aw-dark), #1e293b);
        color: #fff;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
        gap: 10px;
    }
    .ai-panel-title { display: flex; align-items: center; gap: 10px; min-width: 0; }
    .ai-panel-icon {
        width: 36px; height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--aw-primary), var(--aw-primary-dark));
        display: inline-flex; align-items: center; justify-content: center;
        color: #fff; font-size: 16px;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
        flex-shrink: 0;
    }
    .ai-panel-title-main { font-weight: 600; font-size: 14.5px; line-height: 1.2; }
    .ai-panel-subtitle { font-size: 11.5px; color: rgba(255, 255, 255, 0.65); margin-top: 2px; }
    #ai-assistant-close {
        background: transparent;
        border: none;
        color: rgba(255, 255, 255, 0.7);
        font-size: 16px;
        cursor: pointer;
        padding: 6px 10px;
        border-radius: 8px;
        transition: background .15s, color .15s;
    }
    #ai-assistant-close:hover { color: #fff; background: rgba(255, 255, 255, 0.08); }

    /* === Ziņojumu plūsma === */
    .ai-panel-stream {
        flex: 1;
        overflow-y: auto;
        padding: 18px 16px;
        background: var(--aw-bg-soft);
        display: flex;
        flex-direction: column;
        gap: 12px;
        scroll-behavior: smooth;
    }
    .ai-panel-stream::-webkit-scrollbar { width: 6px; }
    .ai-panel-stream::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 6px; }
    .ai-panel-stream::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

    /* === Rinda ar avatāru (botiņš pa kreisi, lietotājs pa labi) === */
    .ai-row {
        display: flex;
        align-items: flex-end;
        gap: 8px;
        max-width: 100%;
    }
    .ai-row-bot { align-self: flex-start; max-width: 88%; }
    .ai-row-user { align-self: flex-end; max-width: 85%; flex-direction: row-reverse; }

    .ai-avatar {
        width: 30px; height: 30px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--aw-primary), var(--aw-primary-dark));
        display: inline-flex; align-items: center; justify-content: center;
        color: #fff; font-size: 13px;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.28);
    }

    /* === Ziņojuma burbuļi === */
    .ai-msg {
        padding: 10px 14px;
        border-radius: 16px;
        font-size: 14px;
        line-height: 1.45;
        word-wrap: break-word;
        max-width: 100%;
    }
    .ai-msg-bot {
        background: #fff;
        color: var(--aw-text);
        border: 1px solid var(--aw-bdr);
        border-bottom-left-radius: 4px;
        box-shadow: var(--aw-shadow-sm);
    }
    .ai-msg-user {
        background: linear-gradient(135deg, var(--aw-primary), var(--aw-primary-dark));
        color: #fff;
        border-bottom-right-radius: 4px;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.25);
    }
    .ai-msg-notice {
        align-self: stretch;
        max-width: 100%;
        background: #f1f5f9;
        color: var(--aw-muted);
        font-size: 12px;
        border-radius: 8px;
        padding: 8px 12px;
        border: 1px dashed #cbd5e1;
    }

    /* === Rakstīšanas indikators (3 punktiņi) === */
    .ai-typing {
        background: #fff;
        border: 1px solid var(--aw-bdr);
        border-radius: 16px;
        border-bottom-left-radius: 4px;
        padding: 12px 14px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        box-shadow: var(--aw-shadow-sm);
    }
    .ai-typing span {
        width: 7px; height: 7px;
        border-radius: 50%;
        background: var(--aw-faint);
        display: inline-block;
        animation: aw-bounce 1.3s infinite ease-in-out;
    }
    .ai-typing span:nth-child(1) { animation-delay: 0s; }
    .ai-typing span:nth-child(2) { animation-delay: 0.18s; }
    .ai-typing span:nth-child(3) { animation-delay: 0.36s; }
    @keyframes aw-bounce {
        0%, 60%, 100% { transform: translateY(0); opacity: 0.4; }
        30% { transform: translateY(-6px); opacity: 1; }
    }

    /* === Ātro ieteikumu pogas === */
    .ai-quick {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        align-self: flex-start;
        max-width: 100%;
        margin-left: 38px; /* atstāj vietu avatāra slejai */
    }
    .ai-quick-btn {
        background: #fff;
        border: 1px solid var(--aw-bdr);
        color: var(--aw-text);
        font-family: inherit;
        font-size: 12px;
        font-weight: 500;
        padding: 6px 12px;
        border-radius: 999px;
        cursor: pointer;
        transition: all .15s ease;
    }
    .ai-quick-btn:hover {
        background: var(--aw-primary-soft);
        border-color: var(--aw-primary);
        color: var(--aw-primary);
    }

    /* === Sludinājuma kartiņa === */
    .ai-card {
        align-self: stretch;
        max-width: 100%;
        background: #fff;
        border: 1px solid var(--aw-bdr);
        border-radius: 14px;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        display: flex;
        gap: 12px;
        padding: 10px;
        transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
    }
    .ai-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--aw-shadow-md);
        border-color: #cbd5e1;
        color: inherit;
    }
    .ai-card-img {
        width: 60px;
        height: 60px;
        flex-shrink: 0;
        background: var(--aw-bg-soft);
        border-radius: 10px;
        object-fit: cover;
    }
    .ai-card-body { flex: 1; min-width: 0; display: flex; flex-direction: column; justify-content: center; gap: 2px; }
    .ai-card-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--aw-text);
        margin: 0;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        line-height: 1.3;
    }
    .ai-card-price {
        font-size: 14px;
        font-weight: 700;
        color: var(--aw-primary);
        margin: 0;
        letter-spacing: -.01em;
    }
    .ai-card-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        font-size: 11px;
        color: var(--aw-muted);
        margin: 0;
    }
    .ai-card-loc { display: inline-flex; align-items: center; gap: 3px; }
    .ai-card-trust {
        display: inline-flex; align-items: center; gap: 3px;
        background: var(--aw-success-soft);
        color: var(--aw-success);
        font-weight: 600;
        padding: 1px 7px;
        border-radius: 999px;
        font-size: 10.5px;
    }

    /* === AI paskaidrojums (zils citāts zem kartiņas) === */
    .ai-reason {
        align-self: stretch;
        max-width: 100%;
        background: var(--aw-primary-soft);
        border-left: 3px solid var(--aw-primary);
        padding: 8px 12px;
        font-size: 12.5px;
        color: var(--aw-text);
        line-height: 1.45;
        border-radius: 0 8px 8px 0;
        margin-top: -4px; /* piesien pie iepriekšējās kartiņas */
    }

    /* === Padoma bloks === */
    .ai-tip {
        align-self: stretch;
        max-width: 100%;
        background: var(--aw-amber-soft);
        border-left: 4px solid var(--aw-amber);
        padding: 10px 14px;
        font-size: 13px;
        font-style: italic;
        color: var(--aw-amber-text);
        line-height: 1.5;
        border-radius: 0 10px 10px 0;
        display: flex;
        gap: 8px;
        align-items: flex-start;
    }
    .ai-tip-icon { flex-shrink: 0; font-style: normal; font-size: 15px; line-height: 1.4; }

    /* === Apakšējais ievades panelis === */
    .ai-panel-footer {
        display: flex;
        gap: 8px;
        padding: 12px;
        background: #fff;
        border-top: 1px solid var(--aw-bdr);
        flex-shrink: 0;
        box-shadow: 0 -2px 8px rgba(15, 23, 42, 0.04);
    }
    #ai-input {
        flex: 1;
        border: 1px solid var(--aw-bdr);
        border-radius: 12px;
        padding: 10px 14px;
        font-family: inherit;
        font-size: 14px;
        color: var(--aw-text);
        outline: none;
        transition: border-color .15s ease, box-shadow .15s ease, background .15s ease;
        background: var(--aw-bg-soft);
    }
    #ai-input::placeholder { color: var(--aw-faint); }
    #ai-input:focus {
        border-color: var(--aw-primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        background: #fff;
    }
    #ai-send {
        background: linear-gradient(135deg, var(--aw-primary), var(--aw-primary-dark));
        color: #fff;
        border: none;
        border-radius: 12px;
        width: 44px;
        flex-shrink: 0;
        font-size: 16px;
        cursor: pointer;
        transition: transform .15s ease, box-shadow .15s ease, opacity .15s ease;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.28);
    }
    #ai-send:hover:not(:disabled) {
        transform: translateY(-1px) scale(1.03);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
    }
    #ai-send:active:not(:disabled) { transform: scale(0.96); }
    #ai-send:disabled { opacity: 0.55; cursor: not-allowed; }

    /* === Mobilā versija (fullscreen) === */
    @media (max-width: 480px) {
        #ai-assistant-panel {
            top: 0; right: 0; bottom: 0; left: 0;
            width: 100%; height: 100%;
            max-height: 100%;
            border-radius: 0;
        }
        #ai-assistant-toggle { bottom: 16px; right: 16px; }
        .ai-row-bot, .ai-row-user { max-width: 92%; }
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
        if (panel.classList.contains('is-open')) closePanel();
        else openPanel();
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

        // Trīs punktiņu rakstīšanas indikators.
        const typing = appendTyping();

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
            typing.remove();
            renderResponse(data);
        } catch (err) {
            typing.remove();
            appendBotMsg('Neizdevās savienoties. Pārbaudi internetu un mēģini vēlreiz.');
        } finally {
            isLoading = false;
            sendBtn.disabled = false;
            scrollToBottom();
        }
    }

    // === Atbildes renderēšana ===
    function renderResponse(data) {
        if (data.message) appendBotMsg(data.message);

        if (data.ai_available === false && Array.isArray(data.advertisements) && data.advertisements.length > 0) {
            appendNotice('AI šobrīd nav pieejams — rādu atbilstošākos sludinājumus.');
        }

        if (data.intro) appendBotMsg(data.intro);

        if (Array.isArray(data.advertisements)) {
            data.advertisements.forEach(function (ad) {
                appendCard(ad);
                if (ad.ai_reason) appendReason(ad.ai_reason);
            });
        }

        if (data.tip) appendTip(data.tip);
    }

    // === DOM palīgi (visi izmanto textContent / setAttribute, XSS aizsardzība) ===
    function makeBotRow() {
        const row = document.createElement('div');
        row.className = 'ai-row ai-row-bot';
        const av = document.createElement('span');
        av.className = 'ai-avatar';
        const icon = document.createElement('i');
        icon.className = 'bi bi-stars';
        av.appendChild(icon);
        row.appendChild(av);
        return row;
    }

    function appendUserMsg(text) {
        const row = document.createElement('div');
        row.className = 'ai-row ai-row-user';
        const msg = document.createElement('div');
        msg.className = 'ai-msg ai-msg-user';
        msg.textContent = text;
        row.appendChild(msg);
        stream.appendChild(row);
        scrollToBottom();
    }

    function appendBotMsg(text) {
        const row = makeBotRow();
        const msg = document.createElement('div');
        msg.className = 'ai-msg ai-msg-bot';
        msg.textContent = text;
        row.appendChild(msg);
        stream.appendChild(row);
        scrollToBottom();
    }

    function appendNotice(text) {
        const el = document.createElement('div');
        el.className = 'ai-msg-notice';
        el.textContent = text;
        stream.appendChild(el);
        scrollToBottom();
    }

    function appendTyping() {
        const row = makeBotRow();
        const typing = document.createElement('div');
        typing.className = 'ai-typing';
        for (let i = 0; i < 3; i++) typing.appendChild(document.createElement('span'));
        row.appendChild(typing);
        stream.appendChild(row);
        scrollToBottom();
        return row;
    }

    function appendCard(ad) {
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
        price.textContent = ad.price ? (parseFloat(ad.price).toFixed(2).replace(/\.00$/, '') + ' €') : '';

        const meta = document.createElement('p');
        meta.className = 'ai-card-meta';

        if (ad.location) {
            const loc = document.createElement('span');
            loc.className = 'ai-card-loc';
            const locIcon = document.createElement('i');
            locIcon.className = 'bi bi-geo-alt';
            loc.appendChild(locIcon);
            loc.appendChild(document.createTextNode(' ' + ad.location));
            meta.appendChild(loc);
        }

        if (ad.seller_verified) {
            const trust = document.createElement('span');
            trust.className = 'ai-card-trust';
            const trustIcon = document.createElement('i');
            trustIcon.className = 'bi bi-patch-check-fill';
            trust.appendChild(trustIcon);
            trust.appendChild(document.createTextNode(' Apstiprināts'));
            meta.appendChild(trust);
        }

        body.appendChild(name);
        body.appendChild(price);
        if (meta.childNodes.length) body.appendChild(meta);

        a.appendChild(img);
        a.appendChild(body);
        stream.appendChild(a);
        scrollToBottom();
    }

    function appendReason(text) {
        const el = document.createElement('div');
        el.className = 'ai-reason';
        el.textContent = text;
        stream.appendChild(el);
        scrollToBottom();
    }

    function appendTip(text) {
        const el = document.createElement('div');
        el.className = 'ai-tip';
        const icon = document.createElement('span');
        icon.className = 'ai-tip-icon';
        icon.textContent = '💡';
        const body = document.createElement('span');
        body.textContent = text;
        el.appendChild(icon);
        el.appendChild(body);
        stream.appendChild(el);
        scrollToBottom();
    }

    function scrollToBottom() {
        // Native smooth scroll uz pēdējo ziņojumu.
        stream.scrollTo({ top: stream.scrollHeight, behavior: 'smooth' });
    }
})();
</script>
