import { ref, onMounted } from 'vue';

export type Mode = 'light' | 'dark';
export type BaseTheme = 'kids' | 'youth' | 'adult';

const STORAGE_KEYS = {
    mode: 'escuela-mode',
    base: 'escuela-base-theme',
    fontSize: 'escuela-font-size',
    highContrast: 'escuela-high-contrast',
    autoDark: 'escuela-auto-dark'
};

const mode = ref<Mode>('light');
const baseTheme = ref<BaseTheme>('adult');
const fontSize = ref<number>(16); // px
const highContrast = ref<boolean>(false);

function applyMode(m: Mode) {
    const html = document.documentElement;
    console.log('[useTheme] applyMode:', m);
    html.classList.remove('light', 'dark');
    html.classList.add(m);
    // also set data attribute for selectors if needed
    html.setAttribute('data-theme-mode', m);
    console.log('[useTheme] HTML classes after applyMode:', html.className);
}

function applyBaseTheme(t: BaseTheme) {
    const html = document.documentElement;
    console.log('[useTheme] applyBaseTheme:', t);
    // remove previous theme-* classes
    html.classList.remove('theme-kids', 'theme-youth', 'theme-adult');
    html.classList.add(`theme-${t}`);
    console.log('[useTheme] HTML classes after applyBaseTheme:', html.className);
    
    // Forzar recalculo del tamaño de fuente base del tema
    // Cada tema tiene su --base-font-size en CSS, pero lo aplicamos explícitamente
    const baseSizes = { kids: 18, youth: 16, adult: 15 };
    const baseSize = baseSizes[t];
    
    // Si el usuario no ha ajustado manualmente, usar el base del tema
    // Si ya ajustó, mantener el offset relativo
    const savedFont = localStorage.getItem(STORAGE_KEYS.fontSize);
    console.log('[useTheme] savedFont from localStorage:', savedFont);
    if (!savedFont) {
        fontSize.value = baseSize;
        html.style.setProperty('--base-font-size', baseSize + 'px');
        html.style.fontSize = baseSize + 'px';
        console.log('[useTheme] Set font size to theme base:', baseSize + 'px');
    } else {
        console.log('[useTheme] Keeping saved font size:', savedFont);
    }
    // Si ya había un fontSize guardado, lo respetamos (no lo sobrescribimos)
}

function applyFontSize(px: number) {
    const html = document.documentElement;
    console.log('[useTheme] applyFontSize:', px + 'px');
    html.style.setProperty('--base-font-size', px + 'px');
    // also set computed font-size to be safe
    html.style.fontSize = px + 'px';
    console.log('[useTheme] Font applied. html.style.fontSize:', html.style.fontSize);
}

function applyHighContrast(enabled: boolean) {
    const html = document.documentElement;
    console.log('[useTheme] applyHighContrast:', enabled);
    if (enabled) html.classList.add('high-contrast'); else html.classList.remove('high-contrast');
    console.log('[useTheme] HTML classes after applyHighContrast:', html.className);
}

export function useTheme() {
    const setMode = (m: Mode) => {
        console.log('[useTheme] setMode called with:', m);
        mode.value = m;
        applyMode(m);
        localStorage.setItem(STORAGE_KEYS.mode, m);
    };

    const toggleMode = () => setMode(mode.value === 'light' ? 'dark' : 'light');

    const setBaseTheme = (t: BaseTheme) => {
        console.log('[useTheme] setBaseTheme called with:', t);
        baseTheme.value = t;
        applyBaseTheme(t);
        localStorage.setItem(STORAGE_KEYS.base, t);
        console.log('[useTheme] Theme saved to localStorage');
    };

    const setFontSize = (px: number) => {
        console.log('[useTheme] setFontSize called with:', px);
        fontSize.value = px;
        applyFontSize(px);
        localStorage.setItem(STORAGE_KEYS.fontSize, String(px));
    };

    const increaseFont = (step = 1) => setFontSize(Math.min(24, fontSize.value + step));
    const decreaseFont = (step = 1) => setFontSize(Math.max(12, fontSize.value - step));

    const setHighContrast = (v: boolean) => {
        console.log('[useTheme] setHighContrast called with:', v);
        highContrast.value = v;
        applyHighContrast(v);
        localStorage.setItem(STORAGE_KEYS.highContrast, v ? '1' : '0');
    };

    const initAppearance = (opts?: { autoDark?: boolean }) => {
        console.log('[useTheme] ========== initAppearance START ==========');
        console.log('[useTheme] Options:', opts);
        
        // base theme PRIMERO (porque define el font-size base)
        const savedBase = localStorage.getItem(STORAGE_KEYS.base) as BaseTheme | null;
        console.log('[useTheme] savedBase from localStorage:', savedBase);
        if (savedBase && ['kids', 'youth', 'adult'].includes(savedBase)) {
            baseTheme.value = savedBase;
        } else {
            baseTheme.value = 'adult'; // default
        }
        console.log('[useTheme] baseTheme.value set to:', baseTheme.value);
        applyBaseTheme(baseTheme.value);

        // font size (ahora que el tema está aplicado)
        const savedFont = localStorage.getItem(STORAGE_KEYS.fontSize);
        console.log('[useTheme] savedFont from localStorage:', savedFont);
        if (savedFont) {
            const n = parseInt(savedFont, 10);
            if (!isNaN(n)) {
                fontSize.value = n;
                applyFontSize(n);
            }
        }
        // Si no hay savedFont, applyBaseTheme ya configuró el tamaño base del tema

        // high contrast
        const savedContrast = localStorage.getItem(STORAGE_KEYS.highContrast);
        console.log('[useTheme] savedContrast from localStorage:', savedContrast);
        if (savedContrast !== null) {
            const v = savedContrast === '1';
            highContrast.value = v;
            applyHighContrast(v);
        }

        // mode: saved preference > system preference > auto time
        const savedMode = localStorage.getItem(STORAGE_KEYS.mode) as Mode | null;
        console.log('[useTheme] savedMode from localStorage:', savedMode);
        if (savedMode && (savedMode === 'light' || savedMode === 'dark')) {
            mode.value = savedMode;
            applyMode(savedMode);
            console.log('[useTheme] ========== initAppearance END (saved mode) ==========');
            return;
        }

        // if autoDark explicitly false, use system pref
        const autoDarkPref = localStorage.getItem(STORAGE_KEYS.autoDark);
        const autoDark = opts?.autoDark ?? (autoDarkPref !== '0');
        console.log('[useTheme] autoDark:', autoDark);

        if (autoDark) {
            // decide by client hour if no saved preference
            const hour = new Date().getHours();
            const isNight = hour < 7 || hour >= 19; // configurable window
            mode.value = isNight ? 'dark' : 'light';
            console.log('[useTheme] Auto mode by time. hour:', hour, 'isNight:', isNight, 'mode:', mode.value);
            applyMode(mode.value);
        } else {
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            mode.value = prefersDark ? 'dark' : 'light';
            console.log('[useTheme] System preference mode:', mode.value);
            applyMode(mode.value);
        }
        console.log('[useTheme] ========== initAppearance END ==========');
    };

    // Initialize immediately if running in browser
    onMounted(() => initAppearance());

    // Debug helper - exponer en window para debugging desde consola
    if (typeof window !== 'undefined') {
        (window as any).debugTheme = () => {
            const html = document.documentElement;
            console.log('========== THEME DEBUG INFO ==========');
            console.log('HTML classList:', html.className);
            console.log('HTML inline styles:', html.style.cssText);
            console.log('Current refs:', {
                mode: mode.value,
                baseTheme: baseTheme.value,
                fontSize: fontSize.value,
                highContrast: highContrast.value
            });
            console.log('LocalStorage:', {
                mode: localStorage.getItem(STORAGE_KEYS.mode),
                base: localStorage.getItem(STORAGE_KEYS.base),
                fontSize: localStorage.getItem(STORAGE_KEYS.fontSize),
                highContrast: localStorage.getItem(STORAGE_KEYS.highContrast)
            });
            console.log('Computed CSS variables:');
            const styles = getComputedStyle(html);
            console.log('  --brand-primary:', styles.getPropertyValue('--brand-primary'));
            console.log('  --bg-primary:', styles.getPropertyValue('--bg-primary'));
            console.log('  --surface:', styles.getPropertyValue('--surface'));
            console.log('  --border-primary:', styles.getPropertyValue('--border-primary'));
            console.log('  --base-font-size:', styles.getPropertyValue('--base-font-size'));
            console.log('  computed font-size:', styles.fontSize);
            console.log('======================================');
        };
        console.log('[useTheme] Debug helper available: window.debugTheme()');
    }

    return {
        mode,
        baseTheme,
        fontSize,
        highContrast,
        setMode,
        toggleMode,
        setBaseTheme,
        setFontSize,
        increaseFont,
        decreaseFont,
        setHighContrast,
        initAppearance
    };
}
