// Torns theme — vanilla JS. No jQuery, no frameworks.
// Handles: mobile drawer, sticky header shadow, closing <details> menus,
// the contact form confirmation screen with confetti.

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
    // Sticky header — subtle shadow once the page scrolls. On a page that
    // opens with the hero (the home page), the bar is the hero's white
    // until then, so the top of the page is one colour.
    // ------------------------------------------------------------------
    const header = document.querySelector('[data-header]')
    if (header) {
        const hero = document.querySelector('[data-hero]')
        const onScroll = () => {
            const scrolled = window.scrollY > 8
            header.classList.toggle('shadow-sm', scrolled)
            header.classList.toggle('header-on-hero', Boolean(hero) && !scrolled)
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

    // ------------------------------------------------------------------
    // Home: «30 días de Pro» announcement, at most once every 10 days per
    // browser. Counted from when it is shown, closed or not.
    // ------------------------------------------------------------------
    const promo = document.querySelector('[data-promo-pro]')
    if (promo) {
        const KEY = 'torns_promo_pro_shown_at'
        const EVERY = 10 * 24 * 60 * 60 * 1000
        let last = 0
        try {
            last = Number(window.localStorage.getItem(KEY)) || 0
        } catch {
            last = Date.now() // storage blocked: do not show it on every visit
        }
        let opener = null

        const hide = () => {
            promo.hidden = true
            document.documentElement.style.overflow = ''
            opener?.focus?.({ preventScroll: true })
        }

        const show = () => {
            opener = document.activeElement
            promo.hidden = false
            document.documentElement.style.overflow = 'hidden'
            promo.querySelector('#promo-pro-title')?.focus({ preventScroll: true })
            try {
                window.localStorage.setItem(KEY, String(Date.now()))
            } catch {
                // nothing to remember it in; it simply shows again next time
            }
        }

        promo.querySelector('[data-promo-pro-close]')?.addEventListener('click', hide)
        promo.addEventListener('click', (event) => {
            if (!event.target.closest('[data-promo-pro-card]')) hide()
        })
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !promo.hidden) hide()
        })
        // On a first visit the cookie notice comes up too: wait until it has
        // been answered (its consent cookie exists) so the two never stack.
        // Looked up on each check: the notice's markup comes after this script.
        const consentGiven = () => {
            const name = document.querySelector('[data-site-cookie-name]')?.getAttribute('data-site-cookie-name')
            return !name || document.cookie.split(';').some((item) => item.trim().startsWith(name + '='))
        }
        if (Date.now() - last >= EVERY) {
            const started = Date.now()
            const wait = window.setInterval(() => {
                if (document.readyState !== 'loading' && consentGiven()) {
                    window.clearInterval(wait)
                    window.setTimeout(show, 800)
                } else if (Date.now() - started > 5 * 60 * 1000) {
                    window.clearInterval(wait)
                }
            }, 500)
        }
    }

    // ------------------------------------------------------------------
    // Contact form: confirmation screen with confetti
    // ------------------------------------------------------------------
    const sent = document.querySelector('[data-contact-sent]')
    if (sent) {
        const canvas = sent.querySelector('[data-confetti]')
        const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches
        let frame = null

        const confetti = () => {
            if (reduced || !canvas?.getContext) return
            const ctx = canvas.getContext('2d')
            const ratio = window.devicePixelRatio || 1
            canvas.width = window.innerWidth * ratio
            canvas.height = window.innerHeight * ratio
            ctx.scale(ratio, ratio)
            const colors = ['#227A96', '#5BC0DE', '#F5A623', '#C2185B', '#34C759', '#1B2A4A']
            const pieces = Array.from({ length: 180 }, () => ({
                x: Math.random() * window.innerWidth,
                y: -20 - Math.random() * window.innerHeight * 0.6,
                w: 6 + Math.random() * 6,
                h: 8 + Math.random() * 8,
                vx: -1.5 + Math.random() * 3,
                vy: 2 + Math.random() * 3.5,
                angle: Math.random() * Math.PI,
                spin: -0.15 + Math.random() * 0.3,
                color: colors[Math.floor(Math.random() * colors.length)],
            }))
            const started = performance.now()
            cancelAnimationFrame(frame)
            const draw = (now) => {
                ctx.clearRect(0, 0, window.innerWidth, window.innerHeight)
                const fading = Math.max(0, Math.min(1, (6000 - (now - started)) / 1500))
                pieces.forEach((p) => {
                    p.x += p.vx + Math.sin((now + p.y) / 400) * 0.6
                    p.y += p.vy
                    p.angle += p.spin
                    ctx.save()
                    ctx.globalAlpha = fading
                    ctx.translate(p.x, p.y)
                    ctx.rotate(p.angle)
                    ctx.fillStyle = p.color
                    ctx.fillRect(-p.w / 2, -p.h / 2, p.w, p.h)
                    ctx.restore()
                })
                if (fading > 0) frame = requestAnimationFrame(draw)
                else ctx.clearRect(0, 0, window.innerWidth, window.innerHeight)
            }
            frame = requestAnimationFrame(draw)
        }

        const show = () => {
            sent.hidden = false
            document.documentElement.style.overflow = 'hidden'
            sent.querySelector('#contact-sent-title')?.focus({ preventScroll: true })
            confetti()
        }

        const hide = () => {
            sent.hidden = true
            document.documentElement.style.overflow = ''
            cancelAnimationFrame(frame)
        }

        // Botble's contact-public.js sends the form by AJAX and fires this
        // event after the answer; its success box has text only on success.
        document.addEventListener('contact-form.submitted', () => {
            if (document.querySelector('.contact-success-message')?.textContent.trim()) show()
        })
        sent.querySelector('[data-contact-sent-close]')?.addEventListener('click', hide)
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !sent.hidden) hide()
        })
        // Sent without JavaScript: the page came back with the screen open.
        if (!sent.hidden) show()
    }
})()
