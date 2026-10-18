/* ---------------------------------------------------------------------------
 * The Google Maps half of the office map.
 *
 * There is no map library any more: Google renders the map inside its own
 * iframe, so all this module does is point that iframe at the embed URL — which
 * is the moment the browser first contacts Google. It is deliberately separate
 * from the gate in office-map.js, so the accessibility/state handling and the
 * embed mechanics can be read and changed on their own.
 * ------------------------------------------------------------------------- */

/* How long to wait before hinting that the embed is slow. It is only a hint:
   the iframe is left in place and the directions link stays available. */
const SLOW_AFTER_MS = 12000;

/**
 * Point one office's iframe at its Google Maps embed URL.
 *
 * @param {HTMLIFrameElement} frame
 * @param {string} src  the absolute embed URL rendered by the server
 * @param {{onLoad?: Function, onSlow?: Function}} [callbacks]
 */
export function loadGoogleMap(frame, src, { onLoad, onSlow } = {}) {
    if (!frame) {
        throw new Error('The Google Maps embed has no iframe to load into.');
    }

    if (typeof src !== 'string' || src.trim() === '') {
        throw new Error('The Google Maps embed has no URL.');
    }

    /* Loading twice (a double click, or a second initOfficeMaps() call) would
       throw away the map view and position the visitor already has. */
    if (frame.getAttribute('data-office-map-loaded') === 'true') return;

    let slowTimer = null;

    const clearSlowTimer = () => {
        if (slowTimer === null) return;
        window.clearTimeout(slowTimer);
        slowTimer = null;
    };

    frame.addEventListener('load', () => {
        clearSlowTimer();
        if (typeof onLoad === 'function') onLoad();
    }, { once: true });

    if (typeof onSlow === 'function') {
        slowTimer = window.setTimeout(() => {
            slowTimer = null;
            onSlow();
        }, SLOW_AFTER_MS);
    }

    frame.setAttribute('data-office-map-loaded', 'true');

    /* The one and only request this feature makes to Google, and it happens
       here — after the visitor asked for it. */
    frame.setAttribute('src', src);
}
