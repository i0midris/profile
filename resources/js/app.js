import './bootstrap';

// Alpine.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// AOS - Animate On Scroll
import AOS from 'aos';
import 'aos/dist/aos.css';

document.addEventListener('DOMContentLoaded', () => {
    const reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Progressive enhancement: the HTML marks blocks with data-animate="fade-up" etc.
    // and is fully visible on its own. Only when JS runs (and the visitor has not asked
    // for reduced motion) are those turned into AOS attributes, so content is never
    // hidden for search engines, slow devices or if a script fails to load.
    if (!reduceMotion) {
        const isRtl = document.documentElement.dir === 'rtl';
        const mirror = { 'fade-left': 'fade-right', 'fade-right': 'fade-left' };

        document.querySelectorAll('[data-animate]').forEach((el) => {
            const name = el.getAttribute('data-animate');
            el.setAttribute('data-aos', isRtl && mirror[name] ? mirror[name] : name);
        });

        AOS.init({
            duration: 700,
            easing: 'ease-out-cubic',
            once: true,
            offset: 40,
        });
    }

    const statCounters = document.querySelectorAll('[data-counter-target]');
    if (!statCounters.length) {
        return;
    }

    const language = document.documentElement.lang || 'en';
    const formatter = new Intl.NumberFormat(language);

    const normalizeDigits = (value) =>
        String(value)
            .replace(/[٠-٩]/g, (digit) => String('٠١٢٣٤٥٦٧٨٩'.indexOf(digit)))
            .replace(/[۰-۹]/g, (digit) => String('۰۱۲۳۴۵۶۷۸۹'.indexOf(digit)));

    const parseCounterTarget = (element) => {
        const rawValue = element.getAttribute('data-counter-target') || '0';
        const normalized = normalizeDigits(rawValue).replace(/[^\d-]/g, '');
        const parsed = Number.parseInt(normalized, 10);
        return Number.isFinite(parsed) ? Math.max(0, parsed) : 0;
    };

    const animateCounter = (element) => {
        if (element.dataset.counterAnimated === 'true') {
            return;
        }

        element.dataset.counterAnimated = 'true';
        const safeTarget = parseCounterTarget(element);
        const duration = Math.min(2400, 1400 + (safeTarget * 4));
        const startTime = performance.now();

        const tick = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const eased = progress < 0.5
                ? 4 * progress * progress * progress
                : 1 - Math.pow(-2 * progress + 2, 3) / 2;
            const value = Math.round(safeTarget * eased);

            element.textContent = formatter.format(value);

            if (progress < 1) {
                requestAnimationFrame(tick);
            }
        };

        requestAnimationFrame(tick);
    };

    // Counters keep their real value in the HTML. They only count up from 0 at the
    // moment they scroll into view; otherwise (no observer, reduced motion) they stay as-is.
    if (reduceMotion || !('IntersectionObserver' in window)) {
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                animateCounter(entry.target);
                observer.unobserve(entry.target);
            });
        },
        {
            threshold: 0.2,
            rootMargin: '0px 0px -10% 0px',
        },
    );

    statCounters.forEach((counter) => {
        observer.observe(counter);
    });
});

// -- Analytics events (plan step 3.7) -----------------------------------------
// Sent only when Google Analytics is configured in the dashboard (window.gtag exists).
const track = (name, params = {}) => {
    if (typeof window.gtag === 'function') {
        window.gtag('event', name, params);
    }
};

document.addEventListener('DOMContentLoaded', () => {
    const lead = document.querySelector('meta[name="lead-event"]');
    if (lead) {
        track('generate_lead', { form: lead.getAttribute('content') });
    }
});

document.addEventListener('click', (event) => {
    const link = event.target.closest('a[href]');
    if (!link) {
        return;
    }
    const href = link.getAttribute('href') || '';
    if (href.includes('wa.me/')) {
        track('whatsapp_click', { page: location.pathname, placement: link.id || link.dataset.track || 'link' });
    } else if (href.startsWith('tel:')) {
        track('phone_click', { page: location.pathname });
    } else if (href.startsWith('mailto:')) {
        track('email_click', { page: location.pathname });
    }
});
