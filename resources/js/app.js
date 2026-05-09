import './bootstrap';

// Alpine.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// AOS - Animate On Scroll
import AOS from 'aos';
import 'aos/dist/aos.css';

document.addEventListener('DOMContentLoaded', () => {
    if (document.documentElement.dir === 'rtl') {
        document.querySelectorAll('[data-aos="fade-left"]').forEach((el) => {
            el.setAttribute('data-aos', 'fade-right');
        });
        document.querySelectorAll('[data-aos="fade-right"]').forEach((el) => {
            el.setAttribute('data-aos', 'fade-left');
        });
    }

    AOS.init({
        duration: 800,
        easing: 'ease-out-cubic',
        once: true,
        offset: 50,
    });

    const statCounters = document.querySelectorAll('[data-counter-target]');
    if (!statCounters.length) {
        return;
    }

    const language = document.documentElement.lang || 'en';
    const formatter = new Intl.NumberFormat(language);
    const reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

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

    const setFinalValues = () => {
        statCounters.forEach((counter) => {
            counter.textContent = formatter.format(parseCounterTarget(counter));
        });
    };

    const animateCounter = (element) => {
        if (element.dataset.counterAnimated === 'true') {
            return;
        }

        element.dataset.counterAnimated = 'true';
        const safeTarget = parseCounterTarget(element);
        const duration = reduceMotion
            ? 1100
            : Math.min(3600, 2200 + (safeTarget * 8));
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

    const animateAllCounters = () => {
        statCounters.forEach((counter) => animateCounter(counter));
    };

    statCounters.forEach((counter) => {
        counter.textContent = formatter.format(0);
    });

    if (!('IntersectionObserver' in window)) {
        animateAllCounters();
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

    // Fallback in case observer callback does not fire due browser quirks.
    window.setTimeout(() => {
        statCounters.forEach((counter) => {
            if (counter.dataset.counterAnimated !== 'true') {
                animateCounter(counter);
            }
        });
    }, 1800);
});
