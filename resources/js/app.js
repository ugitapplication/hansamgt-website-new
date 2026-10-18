import './bootstrap';
import { initConsent } from './consent';
import { initOfficeMaps } from './office-map';

/* The header is a real CSS `position: sticky` element (see app.css), so it is
   already pinned correctly on the first paint — there is no geometry to manage
   here. This only flips the cosmetic "scrolled" styling (solid background and
   shadow), which cannot shift the layout. */
function initStickyHeader() {
    const onScroll = () => {
        document.body.classList.toggle('header_sticky', window.scrollY > 80);
    };

    document.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
}

function initMobileMenu() {
    const mobileHeader = document.querySelector('#header .mobile_header');
    const toggleWrap = document.querySelector('#menu_toggle');
    const toggle = toggleWrap ? toggleWrap.querySelector('button') : null;
    if (!mobileHeader || !toggleWrap || !toggle) return;

    toggle.addEventListener('click', () => {
        const isOpen = mobileHeader.classList.toggle('open');
        toggleWrap.classList.toggle('open', isOpen);
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    // Mirror old-site theme behavior: parent items get an .arrow toggle.
    mobileHeader.querySelectorAll('.menu-item-has-children').forEach((item) => {
        const parentLink = item.querySelector(':scope > a');
        const submenu = item.querySelector(':scope > .sub-menu');
        if (!parentLink || !submenu) return;

        const arrow = document.createElement('span');
        arrow.className = 'arrow';
        arrow.setAttribute('aria-hidden', 'true');
        arrow.textContent = '+';
        parentLink.insertAdjacentElement('afterend', arrow);

        const toggleSubmenu = (event) => {
            event.preventDefault();
            event.stopPropagation();
            const isOpen = item.classList.toggle('open');
            arrow.textContent = isOpen ? '\u00D7' : '+';
            slideSubmenu(submenu, isOpen);
        };

        arrow.addEventListener('click', toggleSubmenu);
        parentLink.addEventListener('click', toggleSubmenu);
    });
}


function slideSubmenu(submenu, open) {
    submenu._animId = (submenu._animId || 0) + 1;
    const id = submenu._animId;

    submenu.style.maxHeight = submenu.getBoundingClientRect().height + 'px';
    void submenu.offsetHeight;

    if (open) {
        submenu.style.paddingTop = '6px';
        submenu.style.paddingBottom = '6px';
        submenu.style.maxHeight = Math.ceil(submenu.scrollHeight) + 12 + 'px';
    } else {
        submenu.style.paddingTop = '0px';
        submenu.style.paddingBottom = '0px';
        submenu.style.maxHeight = '0px';
    }

    const done = (event) => {
        if (event.propertyName !== 'max-height' || submenu._animId !== id) return;
        submenu.removeEventListener('transitionend', done);
        submenu.style.maxHeight = open ? 'none' : '';
        if (open) {
            submenu.style.paddingTop = '';
            submenu.style.paddingBottom = '';
        }
    };
    submenu.addEventListener('transitionend', done);
}

document.addEventListener('DOMContentLoaded', () => {
    initStickyHeader();
    initMobileMenu();
    initHomeHero();
    initHomeCarousels();
    initSloganParticles();
    initCountUp();
    initScrollReveal();
    initRailLinks();
    initConsent();
    initOfficeMaps();
});

function initHomeHero() {
    const slidesWrap = document.querySelector('#home-hero-slides');
    if (!slidesWrap) return;
    const slides = Array.from(slidesWrap.querySelectorAll('.home-hero__slide'));
    const prev = document.querySelector('[data-hero-prev]');
    const next = document.querySelector('[data-hero-next]');
    const bar = document.querySelector('#home-hero-progress');
    const hero = slidesWrap.closest('.home-hero');
    if (slides.length < 2) return;

    const DURATION = 5000;
    let current = 0;
    let timer = null;
    let dueAt = 0;
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const show = (index) => {
        current = (index + slides.length) % slides.length;
        slides.forEach((slide, i) => {
            const active = i === current;
            slide.classList.toggle('is-active', active);
            if (active) slide.removeAttribute('aria-hidden');
            else slide.setAttribute('aria-hidden', 'true');
        });
    };

    const paintBar = (fromFraction, ms) => {
        if (!bar) return;
        bar.style.transition = 'none';
        bar.style.width = `${fromFraction * 100}%`;
        void bar.offsetWidth;
        if (ms > 0) {
            bar.style.transition = `width ${ms}ms linear`;
            bar.style.width = '100%';
        }
    };

    const stop = () => {
        if (timer !== null) {
            window.clearTimeout(timer);
            timer = null;
        }
    };

    const schedule = (ms) => {
        stop();
        dueAt = Date.now() + ms;
        paintBar(1 - ms / DURATION, ms);
        timer = window.setTimeout(() => go(current + 1), ms);
    };

    const go = (index) => {
        show(index);
        if (!reduceMotion) schedule(DURATION);
        else if (bar) {
            bar.style.transition = 'none';
            bar.style.width = '0';
        }
    };

    const pause = () => {
        if (timer === null) return;
        stop();
        if (bar) {
            const track = bar.parentElement.getBoundingClientRect().width || 1;
            const frozen = Math.min(1, bar.getBoundingClientRect().width / track);
            bar.style.transition = 'none';
            bar.style.width = `${frozen * 100}%`;
        }
    };

    const resume = () => {
        if (timer !== null || reduceMotion) return;
        const remaining = Math.max(0, dueAt - Date.now());
        dueAt = Date.now() + remaining;
        paintBar(1 - remaining / DURATION, remaining);
        timer = window.setTimeout(() => go(current + 1), remaining);
    };

    if (prev) prev.addEventListener('click', () => go(current - 1));
    if (next) next.addEventListener('click', () => go(current + 1));

    if (hero) {
        hero.addEventListener('mouseenter', pause);
        hero.addEventListener('mouseleave', resume);
        hero.addEventListener('focusin', pause);
        hero.addEventListener('focusout', resume);
    }

    show(0);
    if (!reduceMotion) schedule(DURATION);
}

function initSloganParticles() {
    const canvas = document.querySelector('#slogan-particles');
    if (!canvas || typeof window.requestAnimationFrame !== 'function') return;
    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    let points = [];

    const resize = () => {
        const rect = canvas.parentElement.getBoundingClientRect();
        const dpr = Math.min(window.devicePixelRatio || 1, 2);
        canvas.width = Math.max(1, Math.floor(rect.width * dpr));
        canvas.height = Math.max(1, Math.floor(rect.height * dpr));
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        seed();
    };

    const seed = () => {
        const w = canvas.clientWidth;
        const h = canvas.clientHeight;
        const count = Math.max(30, Math.min(110, Math.floor(w / 16)));
        points = [];
        for (let i = 0; i < count; i += 1) {
            points.push({
                x: Math.random() * w,
                y: Math.random() * h,
                vx: (Math.random() - 0.5) * 0.4,
                vy: (Math.random() - 0.5) * 0.4,
                r: Math.random() * 1.8 + 1,
            });
        }
    };

    const draw = () => {
        const w = canvas.clientWidth;
        const h = canvas.clientHeight;
        ctx.clearRect(0, 0, w, h);
        points.forEach((p) => {
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.r, 0, 7);
            ctx.fillStyle = 'rgba(255, 255, 255, 0.85)';
            ctx.fill();
        });
        ctx.lineWidth = 1;
        for (let i = 0; i < points.length; i += 1) {
            for (let j = i + 1; j < points.length; j += 1) {
                const a = points[i];
                const b = points[j];
                const dx = a.x - b.x;
                const dy = a.y - b.y;
                const d = dx * dx + dy * dy;
                if (d < 14400) {
                    ctx.strokeStyle = `rgba(255, 255, 255, ${(0.28 * (1 - d / 14400)).toFixed(3)})`;
                    ctx.beginPath();
                    ctx.moveTo(a.x, a.y);
                    ctx.lineTo(b.x, b.y);
                    ctx.stroke();
                }
            }
        }
    };

    window.addEventListener('resize', resize);
    resize();

    if (reduceMotion) {
        draw();
        return;
    }

    let running = true;
    if ('IntersectionObserver' in window) {
        new IntersectionObserver((entries) => {
            running = entries[0].isIntersecting;
        }, { threshold: 0 }).observe(canvas);
    }

    const step = () => {
        if (running) {
            const w = canvas.clientWidth;
            const h = canvas.clientHeight;
            points.forEach((p) => {
                p.x += p.vx;
                p.y += p.vy;
                if (p.x < 0 || p.x > w) p.vx *= -1;
                if (p.y < 0 || p.y > h) p.vy *= -1;
            });
            draw();
        }
        window.requestAnimationFrame(step);
    };
    window.requestAnimationFrame(step);
}

function initHomeCarousels() {
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    document.querySelectorAll('[data-carousel]').forEach((carousel) => {
        const track = carousel.querySelector('.logo-carousel__track');
        const prev = carousel.querySelector('[data-carousel-prev]');
        const next = carousel.querySelector('[data-carousel-next]');
        if (!track) return;

        const step = () => Math.max(track.clientWidth * 0.8, 220);

        if (prev) prev.addEventListener('click', () => track.scrollBy({ left: -step(), behavior: 'smooth' }));
        if (next) next.addEventListener('click', () => track.scrollBy({ left: step(), behavior: 'smooth' }));

        /* Autoplay (legacy: OwlCarousel autoplay, clients 3000ms, concerns 950ms, loop) */
        const timeout = parseInt(carousel.getAttribute('data-autoplay') || '0', 10);
        if (!timeout || reduceMotion) return;

        let timer = null;
        let visible = true;

        const itemStep = () => {
            const item = track.querySelector('.logo-carousel__item');
            return item ? item.getBoundingClientRect().width + 16 : step();
        };

        const advance = () => {
            const max = track.scrollWidth - track.clientWidth - 10;
            if (track.scrollLeft >= max) track.scrollTo({ left: 0, behavior: 'smooth' });
            else track.scrollBy({ left: itemStep(), behavior: 'smooth' });
        };

        const start = () => {
            if (timer !== null) return;
            timer = window.setInterval(() => {
                if (visible && !document.hidden) advance();
            }, timeout);
        };

        const stop = () => {
            if (timer !== null) {
                window.clearInterval(timer);
                timer = null;
            }
        };

        carousel.addEventListener('mouseenter', stop);
        carousel.addEventListener('mouseleave', start);
        carousel.addEventListener('focusin', stop);
        carousel.addEventListener('focusout', start);

        if ('IntersectionObserver' in window) {
            new IntersectionObserver((entries) => {
                visible = entries[0].isIntersecting;
            }, { threshold: 0 }).observe(carousel);
        }

        start();
    });
}

/* Floating rail links: smooth-scroll to sections without touching the URL hash
   (legacy: page-scroll-to-id with appendHash disabled) */
function initRailLinks() {
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    document.querySelectorAll('[data-rail-link]').forEach((link) => {
        link.addEventListener('click', (event) => {
            let hash = '';
            try {
                hash = new URL(link.getAttribute('href'), window.location.href).hash;
            } catch (err) {
                return;
            }
            const target = hash ? document.querySelector(hash) : null;
            if (!target) return;
            event.preventDefault();
            target.scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth', block: 'start' });
        });
    });
}

/* HANSA in Numbers count-up */
function initCountUp() {
    const els = Array.from(document.querySelectorAll('[data-countup]'));
    if (!els.length) return;

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const targetOf = (el) => parseFloat(el.getAttribute('data-target')) || 0;

    const render = (el, value) => {
        const prefix = el.getAttribute('data-prefix') || '';
        const suffix = el.getAttribute('data-suffix') || '';
        el.textContent = prefix + Math.round(value) + suffix;
    };

    const animate = (el) => {
        const target = targetOf(el);
        if (reduceMotion) {
            render(el, target);
            return;
        }
        const DURATION = 2500;
        let start = null;
        const step = (now) => {
            if (start === null) start = now;
            const t = Math.min(1, (now - start) / DURATION);
            const eased = t === 1 ? 1 : 1 - Math.pow(2, -10 * t);
            render(el, target * eased);
            if (t < 1) window.requestAnimationFrame(step);
        };
        window.requestAnimationFrame(step);
    };

    if (reduceMotion || !('IntersectionObserver' in window)) {
        els.forEach((el) => render(el, targetOf(el)));
        return;
    }

    const seen = new WeakSet();
    const io = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting && !seen.has(entry.target)) {
                seen.add(entry.target);
                animate(entry.target);
                io.unobserve(entry.target);
            }
        });
    }, { threshold: 0.4 });
    els.forEach((el) => io.observe(el));
}

/* Homepage scroll reveal: one shared IntersectionObserver fades and lifts the
   [data-reveal] cards as they enter the viewport, then stops observing each one
   so a card animates only once. The hidden "reveal-pending" class is added here
   and never in the HTML, so the content stays visible with JavaScript disabled
   or when IntersectionObserver is unavailable. Cards already on screen at load
   are left untouched, so the first paint cannot flash. Same shape as the
   initCountUp() observer above. */
function initScrollReveal() {
    const els = Array.from(document.querySelectorAll('[data-reveal]'));
    if (!els.length) return;

    const showAll = () => els.forEach((el) => el.classList.remove('reveal-pending'));

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduceMotion || !('IntersectionObserver' in window)) {
        showAll();
        return;
    }

    try {
        const io = new IntersectionObserver((entries, observer) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('reveal-ready');
                entry.target.classList.remove('reveal-pending');
                observer.unobserve(entry.target);
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -8% 0px' });

        /* Cards sharing a container (a grid) stagger in DOM order, capped so a
           long row never waits more than 350ms for its last card. */
        const counted = new Map();

        els.forEach((el) => {
            const parent = el.parentElement;
            const index = counted.get(parent) || 0;
            counted.set(parent, index + 1);
            el.style.setProperty('--reveal-delay', `${Math.min(index * 70, 350)}ms`);

            const rect = el.getBoundingClientRect();
            const onScreen = rect.top < window.innerHeight && rect.bottom > 0;
            if (onScreen) return;

            el.classList.add('reveal-pending');
            io.observe(el);
        });
    } catch (error) {
        /* Whatever happens, never leave content hidden. */
        showAll();
    }
}
