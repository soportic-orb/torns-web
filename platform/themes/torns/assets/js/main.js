// Torns theme — vanilla JS. No jQuery, no frameworks.
// Handles: mobile drawer, sticky header shadow, closing <details> menus.

(() => {
    'use strict'

    // ------------------------------------------------------------------
    // Mobile drawer
    // ------------------------------------------------------------------
    const drawer = document.querySelector('[data-drawer]')
    const backdrop = document.querySelector('[data-drawer-backdrop]')
    const openBtn = document.querySelector('[data-drawer-open]')

    let lastFocused = null

    const focusableIn = (el) =>
        el.querySelectorAll('a[href], button:not([disabled]), input, select, textarea, [tabindex]:not([tabindex="-1"])')

    const openDrawer = () => {
        if (!drawer || !backdrop) return
        lastFocused = document.activeElement
        drawer.hidden = false
        backdrop.hidden = false
        // Double rAF so the transition runs after unhiding.
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                drawer.classList.add('is-open')
                backdrop.classList.add('is-open')
            })
        })
        document.body.classList.add('drawer-locked')
        openBtn?.setAttribute('aria-expanded', 'true')
        const focusables = focusableIn(drawer)
        if (focusables.length) focusables[0].focus()
    }

    const closeDrawer = () => {
        if (!drawer || !backdrop) return
        drawer.classList.remove('is-open')
        backdrop.classList.remove('is-open')
        document.body.classList.remove('drawer-locked')
        openBtn?.setAttribute('aria-expanded', 'false')
        const hide = () => {
            drawer.hidden = true
            backdrop.hidden = true
        }
        const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches
        if (reduced) hide()
        else drawer.addEventListener('transitionend', hide, { once: true })
        if (lastFocused) lastFocused.focus()
    }

    openBtn?.addEventListener('click', openDrawer)
    backdrop?.addEventListener('click', closeDrawer)
    drawer?.querySelector('[data-drawer-close]')?.addEventListener('click', closeDrawer)

    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') return
        if (drawer && !drawer.hidden) closeDrawer()
    })

    // Basic focus trap while the drawer is open.
    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Tab' || !drawer || drawer.hidden) return
        const focusables = focusableIn(drawer)
        if (!focusables.length) return
        const first = focusables[0]
        const last = focusables[focusables.length - 1]
        if (event.shiftKey && document.activeElement === first) {
            event.preventDefault()
            last.focus()
        } else if (!event.shiftKey && document.activeElement === last) {
            event.preventDefault()
            first.focus()
        }
    })

    // ------------------------------------------------------------------
    // Sticky header — subtle shadow once the page scrolls.
    // ------------------------------------------------------------------
    const header = document.querySelector('[data-header]')
    if (header) {
        const onScroll = () => {
            header.classList.toggle('shadow-sm', window.scrollY > 8)
        }
        onScroll()
        window.addEventListener('scroll', onScroll, { passive: true })
    }

    // ------------------------------------------------------------------
    // <details> menus (language switcher) — close on outside click/Escape.
    // ------------------------------------------------------------------
    const detailsMenus = document.querySelectorAll('[data-lang-menu]')
    if (detailsMenus.length) {
        document.addEventListener('click', (event) => {
            detailsMenus.forEach((menu) => {
                if (menu.open && !menu.contains(event.target)) menu.open = false
            })
        })
        document.addEventListener('keydown', (event) => {
            if (event.key !== 'Escape') return
            detailsMenus.forEach((menu) => {
                if (menu.open) menu.open = false
            })
        })
    }
})()
