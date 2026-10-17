import './bootstrap';
import { initConsent } from './consent';

function initStickyHeader() {
    const header = document.querySelector('#header');

    const sync = () => {
        const sticky = window.scrollY > 80;
        const was = document.body.classList.contains('header_sticky');
        if (sticky === was) return;
        const wasInFlow =
            header &&
            getComputedStyle(header).position !== 'fixed' &&
            getComputedStyle(header).position !== 'absolute';
        document.body.classList.toggle('header_sticky', sticky);
        if (sticky && wasInFlow && header && getComputedStyle(header).position === 'fixed') {
            document.body.style.paddingTop = `${header.getBoundingClientRect().height}px`;
        } else {
            document.body.style.paddingTop = '';
        }
    };

    const onScroll = () => {
        const sticky = window.scrollY > 80;
        if (sticky === document.body.classList.contains('header_sticky')) return;
        sync();
    };
    document.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', () => {
        if (document.body.classList.contains('header_sticky') && header && getComputedStyle(header).position === 'fixed') {
            document.body.style.paddingTop = `${header.getBoundingClientRect().height}px`;
        } else {
            document.body.style.paddingTop = '';
        }
    });
    sync();
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
    initRailLinks();
    initConsent();
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
