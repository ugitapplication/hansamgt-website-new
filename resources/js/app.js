import './bootstrap';

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
});
