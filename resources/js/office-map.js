/* ---------------------------------------------------------------------------
 * Click-to-load office map gate (Google Maps).
 *
 * Nothing is requested from Google until the visitor presses "Load Google Map":
 * the server renders each <iframe> with no src, and this module fills it in.
 * Google renders the map inside its own frame, so there is no map library to
 * fetch and nothing else to initialise.
 *
 * The component is only in the markup when the server found at least one office
 * with a verified coordinate pair (see App\Support\OfficeMap), so this module
 * does nothing on pages — or installations — without a verified location.
 * ------------------------------------------------------------------------- */

import { loadGoogleMap } from './office-map-render';

const LOADING = 'Loading Google Map…';
const LOADED = 'Google Map loaded.';
const SLOW = 'The Google Map is taking longer than expected. You can still use the directions link below.';
const FAILED = 'The Google Map could not be loaded. Use the directions link below instead.';

/* Guards against a second call wiring the same component twice. */
const mounted = new WeakSet();

export function initOfficeMaps() {
    document.querySelectorAll('[data-office-map]').forEach(setUp);
}

function setUp(root) {
    if (mounted.has(root)) return;
    mounted.add(root);

    root.querySelectorAll('[data-office-map-item]').forEach(setUpItem);
}

function setUpItem(item) {
    const button = item.querySelector('[data-office-map-load]');
    const gate = item.querySelector('[data-office-map-gate]');
    const embed = item.querySelector('[data-office-map-embed]');
    const status = item.querySelector('[data-office-map-status]');

    if (!button || !embed) return;

    const src = embed.getAttribute('data-src') || '';

    button.addEventListener('click', () => {
        if (button.disabled) return;

        /* Reveal the frame and hide the gate first, so the iframe already has a
           size when Google starts rendering into it. */
        if (gate) gate.hidden = true;
        embed.hidden = false;
        button.disabled = true;
        setStatus(status, LOADING, false);

        try {
            loadGoogleMap(embed, src, {
                onLoad: () => setStatus(status, LOADED, false),
                onSlow: () => setStatus(status, SLOW, false),
            });
        } catch (error) {
            /* Put the control back so the visitor can retry; the always visible
               directions link remains the way to reach the office. */
            embed.hidden = true;
            if (gate) gate.hidden = false;
            button.disabled = false;
            setStatus(status, FAILED, true);
            console.error('[office-map]', error);
            return;
        }

        /* The button now sits inside a hidden gate, so the browser would drop
           focus to the document body and send a keyboard user back to the top
           of the page. Move it to the map that replaced the button instead. */
        embed.focus({ preventScroll: true });
    });
}

function setStatus(node, message, isError) {
    if (!node) return;

    node.textContent = message;
    node.classList.toggle('office-map__status--error', Boolean(isError));
}
