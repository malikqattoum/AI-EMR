/**
 * Theme Switcher - Handles theme toggling with persistence
 * Supports: light, dark themes
 * Persistence: localStorage for guests, database for authenticated users
 */

(function() {
    'use strict';

    // Theme configuration
    const THEME_KEY = 'app_theme_preference';
    const THEME_STORAGE_KEY = 'theme';
    const THEMES = {
        LIGHT: 'light',
        DARK: 'dark'
    };
    const DEFAULT_THEME = THEMES.DARK; // Default to dark theme

    /**
     * Get the current theme from localStorage
     * @returns {string} The current theme
     */
    function getStoredTheme() {
        try {
            return localStorage.getItem(THEME_KEY) || DEFAULT_THEME;
        } catch (e) {
            console.warn('localStorage not available, using default theme');
            return DEFAULT_THEME;
        }
    }

    /**
     * Save theme to localStorage
     * @param {string} theme - The theme to save
     */
    function saveThemeToStorage(theme) {
        try {
            localStorage.setItem(THEME_KEY, theme);
        } catch (e) {
            console.warn('localStorage not available, theme will not persist');
        }
    }

    /**
     * Save theme to database for authenticated users
     * @param {string} theme - The theme to save
     */
    async function saveThemeToDatabase(theme) {
        try {
            // Check if user is authenticated (look for CSRF token or auth indicator)
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) return; // Not authenticated, skip DB save

            const response = await fetch('/api/user/settings/theme', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                },
                body: JSON.stringify({ theme: theme })
            });

            if (!response.ok) {
                console.warn('Failed to save theme to database');
            }
        } catch (error) {
            console.warn('Error saving theme to database:', error);
        }
    }

    /**
     * Apply theme to the document
     * @param {string} theme - The theme to apply
     */
    function applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        document.body.setAttribute('data-theme', theme);
        
        // Update any theme toggle UI elements
        updateToggleUI(theme);
        
        // Dispatch custom event for other scripts to listen to
        window.dispatchEvent(new CustomEvent('themeChanged', { 
            detail: { theme: theme } 
        }));
    }

    /**
     * Update theme toggle UI elements
     * @param {string} theme - The current theme
     */
    function updateToggleUI(theme) {
        // Update all theme toggle buttons
        const toggles = document.querySelectorAll('[data-theme-toggle]');
        toggles.forEach(toggle => {
            const icon = toggle.querySelector('[data-theme-icon]');
            if (icon) {
                if (theme === THEMES.DARK) {
                    icon.className = 'fas fa-sun';
                } else {
                    icon.className = 'fas fa-moon';
                }
            }
            
            // Update aria-label
            const nextTheme = theme === THEMES.DARK ? THEMES.LIGHT : THEMES.DARK;
            toggle.setAttribute('aria-label', `Switch to ${nextTheme} theme`);
        });

        // Update theme indicator if exists
        const indicator = document.querySelector('[data-theme-indicator]');
        if (indicator) {
            indicator.textContent = theme === THEMES.DARK ? 'Dark' : 'Light';
        }
    }

    /**
     * Get the effective theme (considering system preference)
     * @returns {string} The effective theme
     */
    function getEffectiveTheme() {
        const storedTheme = getStoredTheme();
        
        // If user has explicitly chosen a theme, use it
        if (storedTheme !== DEFAULT_THEME || hasUserPreference()) {
            return storedTheme;
        }
        
        // Otherwise, respect system preference
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) {
            return THEMES.LIGHT;
        }
        
        return DEFAULT_THEME;
    }

    /**
     * Check if user has explicitly set a preference
     * @returns {boolean} True if user has set a preference
     */
    function hasUserPreference() {
        try {
            return localStorage.getItem(THEME_KEY) !== null;
        } catch (e) {
            return false;
        }
    }

    /**
     * Toggle between light and dark themes
     */
    function toggleTheme() {
        const currentTheme = document.documentElement.getAttribute('data-theme') || getEffectiveTheme();
        const newTheme = currentTheme === THEMES.DARK ? THEMES.LIGHT : THEMES.DARK;
        
        setTheme(newTheme);
    }

    /**
     * Set the theme
     * @param {string} theme - The theme to set
     */
    function setTheme(theme) {
        if (!Object.values(THEMES).includes(theme)) {
            console.warn(`Invalid theme: ${theme}`);
            return;
        }
        
        // Apply theme immediately
        applyTheme(theme);
        
        // Save to localStorage
        saveThemeToStorage(theme);
        
        // Save to database if authenticated
        saveThemeToDatabase(theme);
    }

    /**
     * Initialize theme on page load
     */
    function initTheme() {
        const theme = getEffectiveTheme();
        applyTheme(theme);
    }

    /**
     * Initialize theme toggle event listeners
     */
    function initToggleListeners() {
        // Listen for toggle button clicks
        document.addEventListener('click', function(e) {
            const toggle = e.target.closest('[data-theme-toggle]');
            if (toggle) {
                e.preventDefault();
                toggleTheme();
            }
        });
    }

    /**
     * Listen for system theme changes
     */
    function initSystemThemeListener() {
        if (window.matchMedia) {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                // Only auto-switch if user hasn't set a preference
                if (!hasUserPreference()) {
                    const newTheme = e.matches ? THEMES.DARK : THEMES.LIGHT;
                    applyTheme(newTheme);
                }
            });
        }
    }

    /**
     * Public API
     */
    window.ThemeSwitcher = {
        getTheme: function() {
            return document.documentElement.getAttribute('data-theme') || getEffectiveTheme();
        },
        setTheme: setTheme,
        toggleTheme: toggleTheme,
        THEMES: THEMES
    };

    /**
     * Initialize on DOM ready
     */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initTheme();
            initToggleListeners();
            initSystemThemeListener();
        });
    } else {
        // DOM already loaded
        initTheme();
        initToggleListeners();
        initSystemThemeListener();
    }

    // Apply theme immediately to prevent flash
    initTheme();

})();
