import './bootstrap';

// Alpine.js
import Alpine from '@alpinejs/csp';
window.Alpine = Alpine;
Alpine.start();

// AOS - Animate On Scroll
import AOS from 'aos';
import 'aos/dist/aos.css';

const THEME_LIGHT = 'light';
const THEME_DARK = 'dark';
const THEME_SYSTEM = 'system';

const getSystemTheme = () => {
    const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    return prefersDark ? THEME_DARK : THEME_LIGHT;
};

const normalizeThemePreference = (value) =>
    value === THEME_LIGHT || value === THEME_DARK || value === THEME_SYSTEM
        ? value
        : THEME_SYSTEM;

const resolveTheme = (themePreference) =>
    themePreference === THEME_SYSTEM ? getSystemTheme() : themePreference;

const getThemeLabels = () => {
    const root = document.documentElement;
    return {
        system: root.dataset.themeLabelSystem || 'System',
        light: root.dataset.themeLabelLight || 'Light',
        dark: root.dataset.themeLabelDark || 'Dark',
    };
};

const syncThemeDropdown = (themePreference) => {
    const themeLabels = getThemeLabels();
    const themeLabel = themeLabels[themePreference] || themeLabels.system;

    document.querySelectorAll('[data-theme-current]').forEach((labelElement) => {
        labelElement.textContent = themeLabel;
    });

    document.querySelectorAll('[data-theme-option]').forEach((optionElement) => {
        const isActive = optionElement.getAttribute('data-theme-option') === themePreference;
        optionElement.classList.toggle('is-active', isActive);
        optionElement.setAttribute('aria-checked', isActive ? 'true' : 'false');
    });
};

const applyTheme = (themePreference, persist = true) => {
    const normalizedTheme = normalizeThemePreference(themePreference);
    const resolvedTheme = resolveTheme(normalizedTheme);
    const html = document.documentElement;

    html.classList.remove('theme-light', 'theme-dark');
    html.classList.add(resolvedTheme === THEME_DARK ? 'theme-dark' : 'theme-light');
    html.setAttribute('data-theme-preference', normalizedTheme);
    syncThemeDropdown(normalizedTheme);

    if (persist) {
        localStorage.setItem('site-theme', normalizedTheme);
    }
};

const applyInitialTheme = () => {
    const storedTheme = localStorage.getItem('site-theme');
    const preference = normalizeThemePreference(storedTheme);
    applyTheme(preference, false);
};

const switchLocale = (locale) => {
    const currentPath = window.location.pathname.replace(/^\/(en|ar)(?=\/|$)/, '') || '/';
    window.location.href = `/${locale}${currentPath}${window.location.search}${window.location.hash}`;
};

window.setTheme = (themePreference) => applyTheme(themePreference, true);
window.switchLocale = switchLocale;
applyInitialTheme();

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

    document.querySelectorAll('[data-locale-switch]').forEach((button) => {
        button.addEventListener('click', () => {
            const locale = button.getAttribute('data-locale-switch');
            if (locale === 'en' || locale === 'ar') {
                switchLocale(locale);
            }
        });
    });

    document.querySelectorAll('[data-theme-option]').forEach((button) => {
        button.addEventListener('click', () => {
            const theme = button.getAttribute('data-theme-option');
            if (theme === THEME_SYSTEM || theme === THEME_LIGHT || theme === THEME_DARK) {
                applyTheme(theme, true);
            }
        });
    });

    const currentPreference = normalizeThemePreference(document.documentElement.getAttribute('data-theme-preference'));
    applyTheme(currentPreference, false);

    if (window.matchMedia) {
        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        const onSystemThemeChange = () => {
            const selectedTheme = normalizeThemePreference(localStorage.getItem('site-theme'));
            if (selectedTheme === THEME_SYSTEM) {
                applyTheme(THEME_SYSTEM, false);
            }
        };

        if (typeof mediaQuery.addEventListener === 'function') {
            mediaQuery.addEventListener('change', onSystemThemeChange);
        } else if (typeof mediaQuery.addListener === 'function') {
            mediaQuery.addListener(onSystemThemeChange);
        }
    }

    const statCounters = document.querySelectorAll('[data-counter-target]');
    if (statCounters.length) {
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
    }
});
