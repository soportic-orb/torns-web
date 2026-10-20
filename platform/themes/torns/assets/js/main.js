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
    // Screens carousel: the current screen large in the middle, its
    // neighbours smaller on each side; arrows, swipe and arrow keys.
    // ------------------------------------------------------------------
    document.querySelectorAll('[data-carousel]').forEach((carousel) => {
        const slides = Array.from(carousel.querySelectorAll('[data-carousel-slide]'))
        const caption = carousel.querySelector('[data-carousel-caption]')
        const count = slides.length
        if (!count) return
        let current = 0

        const render = () => {
            slides.forEach((slide, index) => {
                let offset = index - current
                if (offset > count / 2) offset -= count
                if (offset < -count / 2) offset += count
                const distance = Math.abs(offset)
                slide.style.setProperty('--offset', offset)
                slide.style.setProperty('--scale', distance === 0 ? 1 : distance === 1 ? 0.74 : 0.56)
                slide.style.zIndex = String(10 - distance)
                slide.dataset.position = distance === 0 ? 'current' : distance <= 2 ? 'side' : 'hidden'
                slide.setAttribute('aria-hidden', distance === 0 ? 'false' : 'true')
                slide.tabIndex = distance === 1 ? 0 : -1
            })
            if (caption) caption.textContent = slides[current].dataset.caption || ''
        }
        const go = (index) => {
            current = (index + count) % count
            render()
        }

        carousel.querySelector('[data-carousel-prev]')?.addEventListener('click', () => go(current - 1))
        carousel.querySelector('[data-carousel-next]')?.addEventListener('click', () => go(current + 1))
        slides.forEach((slide, index) => slide.addEventListener('click', () => go(index)))
        carousel.addEventListener('keydown', (event) => {
            if (event.key === 'ArrowLeft') go(current - 1)
            if (event.key === 'ArrowRight') go(current + 1)
        })

        let startX = null
        carousel.addEventListener('touchstart', (event) => { startX = event.touches[0].clientX }, { passive: true })
        carousel.addEventListener('touchend', (event) => {
            if (startX === null) return
            const delta = event.changedTouches[0].clientX - startX
            if (Math.abs(delta) > 40) go(current + (delta < 0 ? 1 : -1))
            startX = null
        })

        render()
    })

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
