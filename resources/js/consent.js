/* ---------------------------------------------------------------------------
 * Cookie / analytics consent.
 *
 * Nothing is requested from Google until the visitor accepts. If they decline
 * (or never answer), no analytics script is loaded at all — not even a
 * cookieless ping — and Google's consent state stays "denied".
 *
 * The decision is remembered in localStorage rather than a cookie, so the
 * record of the choice does not itself require consent. If localStorage is
 * unavailable the decision is simply not remembered, and the default (no
 * analytics) stands.
 * ------------------------------------------------------------------------- */

export function initConsent() {
    const banner = document.getElementById('consent-banner');
    if (!banner) return; // Analytics is off — there is nothing to consent to.

    const storageKey = banner.dataset.consentKey || 'hansa.consent';
    const version = parseInt(banner.dataset.consentVersion, 10) || 1;
    const measurementId =
        window.hansaAnalytics && window.hansaAnalytics.id ? window.hansaAnalytics.id : null;

    let loaded = false;

    /* --- the remembered decision ------------------------------------------ */

    const readDecision = () => {
        try {
            const raw = window.localStorage.getItem(storageKey);
            if (!raw) return null;
            const parsed = JSON.parse(raw);
            if (!parsed || parsed.version !== version) return null;
            return parsed.analytics === true;
        } catch (err) {
            return null;
        }
    };

    const saveDecision = (analytics) => {
        try {
            window.localStorage.setItem(
                storageKey,
                JSON.stringify({ version, analytics, decidedAt: new Date().toISOString() }),
            );
        } catch (err) {
            /* Storage unavailable — the choice is not remembered. */
        }
    };

    /* --- loading and unloading the tag ----------------------------------- */

    const loadAnalytics = () => {
        if (loaded || !measurementId || typeof window.gtag !== 'function') return;
        loaded = true;

        const script = document.createElement('script');
        script.async = true;
        script.src = `https://www.googletagmanager.com/gtag/js?id=${encodeURIComponent(measurementId)}`;
        document.head.appendChild(script);

        window.gtag('consent', 'update', { analytics_storage: 'granted' });
        window.gtag('js', new Date());
        window.gtag('config', measurementId);
    };

    const revokeAnalytics = () => {
        if (typeof window.gtag === 'function') {
            window.gtag('consent', 'update', { analytics_storage: 'denied' });
        }

        // Expire any Google Analytics cookies already on the device.
        document.cookie.split(';').forEach((entry) => {
            const name = entry.split('=')[0].trim();
            if (name !== '_ga' && !name.startsWith('_ga_')) return;
            const expire = 'expires=Thu, 01 Jan 1970 00:00:00 GMT';
            document.cookie = `${name}=; path=/; ${expire}`;
            document.cookie = `${name}=; path=/; domain=.${location.hostname}; ${expire}`;
        });
    };

    /* --- showing and hiding the banner ----------------------------------- */

    const syncSpacing = () => {
        document.body.style.paddingBottom = banner.hidden
            ? ''
            : `${banner.getBoundingClientRect().height}px`;
    };

    const hide = () => {
        banner.hidden = true;
        syncSpacing();
    };

    const show = (moveFocus) => {
        banner.hidden = false;
        syncSpacing();
        if (!moveFocus) return;
        const title = banner.querySelector('.consent__title');
        if (title) title.focus();
    };

    banner.querySelector('[data-consent-accept]').addEventListener('click', () => {
        saveDecision(true);
        loadAnalytics();
        hide();
    });

    banner.querySelector('[data-consent-decline]').addEventListener('click', () => {
        const wasRunning = loaded;
        saveDecision(false);
        revokeAnalytics();
        hide();
        // Reload so the rest of the session continues without the tag, and any
        // in-flight measurement is dropped.
        if (wasRunning) window.location.reload();
    });

    // "Cookie settings" in the footer re-opens the banner. The choice is
    // revocable, which is why this control exists rather than a one-off banner.
    document.querySelectorAll('[data-consent-open]').forEach((trigger) => {
        trigger.addEventListener('click', (event) => {
            event.preventDefault();
            show(true);
        });
    });

    window.addEventListener('resize', syncSpacing);

    const decision = readDecision();
    if (decision === true) loadAnalytics();
    else if (decision === null) show(false);
}
