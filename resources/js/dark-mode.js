/**
 * Dark Mode Module
 * Handles dark mode toggle, persistence, and initialization
 * Works with floating button UI
 */

export const DarkMode = {
    // Configuration
    STORAGE_KEY: 'gizmo-store-dark-mode',
    DARK_CLASS: 'dark-mode',
    TOGGLE_ID: 'darkModeToggle',
    FLOATING_BTN_ID: 'darkModeFloatingBtn',

    /**
     * Initialize dark mode on page load
     */
    init() {
        this.applyStoredPreference();
        this.setupToggleListener();
        this.setupSystemPreferenceListener();
    },

    /**
     * Apply stored preference or system preference
     */
    applyStoredPreference() {
        const stored = localStorage.getItem(this.STORAGE_KEY);
        
        if (stored !== null) {
            // Use stored preference
            this.setDarkMode(stored === 'true');
        } else {
            // Check system preference
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            this.setDarkMode(prefersDark);
            localStorage.setItem(this.STORAGE_KEY, String(prefersDark));
        }
    },

    /**
     * Setup toggle button listener
     */
    setupToggleListener() {
        // Try floating button first
        let toggle = document.getElementById(this.FLOATING_BTN_ID);
        
        // Fallback to navbar button
        if (!toggle) {
            toggle = document.getElementById(this.TOGGLE_ID);
        }
        
        if (toggle) {
            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggle();
            });
        }
    },

    /**
     * Listen to system preference changes
     */
    setupSystemPreferenceListener() {
        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        mediaQuery.addEventListener('change', (e) => {
            // Only apply system preference if user hasn't set preference
            if (localStorage.getItem(this.STORAGE_KEY) === null) {
                this.setDarkMode(e.matches);
            }
        });
    },

    /**
     * Toggle dark mode
     */
    toggle() {
        const isDarkMode = document.documentElement.classList.contains(this.DARK_CLASS);
        this.setDarkMode(!isDarkMode);
    },

    /**
     * Set dark mode on/off
     */
    setDarkMode(enabled) {
        if (enabled) {
            document.documentElement.classList.add(this.DARK_CLASS);
        } else {
            document.documentElement.classList.remove(this.DARK_CLASS);
        }
        localStorage.setItem(this.STORAGE_KEY, String(enabled));
        this.updateToggleIcon();
    },

    /**
     * Update toggle button icon
     */
    updateToggleIcon() {
        // Update floating button
        let toggle = document.getElementById(this.FLOATING_BTN_ID);
        
        // Fallback to navbar button
        if (!toggle) {
            toggle = document.getElementById(this.TOGGLE_ID);
        }
        
        if (!toggle) return;

        const isDarkMode = document.documentElement.classList.contains(this.DARK_CLASS);
        const icon = toggle.querySelector('i');
        
        if (icon) {
            if (isDarkMode) {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
                toggle.setAttribute('title', 'Light Mode');
                toggle.setAttribute('aria-label', 'Switch to light mode');
            } else {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
                toggle.setAttribute('title', 'Dark Mode');
                toggle.setAttribute('aria-label', 'Switch to dark mode');
            }
        }
    },

    /**
     * Check if dark mode is currently enabled
     */
    isDarkMode() {
        return document.documentElement.classList.contains(this.DARK_CLASS);
    }
};

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => DarkMode.init());
} else {
    DarkMode.init();
}
