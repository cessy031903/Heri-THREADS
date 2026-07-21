// Livewire v4 bundles Alpine — do NOT import Alpine separately or it will double-register
document.addEventListener('alpine:init', () => {
    const getStoredLang = () => {
        try {
            return localStorage.getItem('ifugao_lang') || 'en';
        } catch {
            return 'en';
        }
    };

    const setStoredLang = (val) => {
        try {
            localStorage.setItem('ifugao_lang', val);
        } catch {
            // localStorage unavailable (private browsing or quota exceeded)
        }
    };

    Alpine.store('app', {
        lang: getStoredLang(),
        setLang(val) {
            this.lang = val;
            setStoredLang(val);
        },
    });
});
