/**
 * MedSuite Doctor Portal - Enhanced JavaScript
 * Features: Toast notifications, keyboard shortcuts, global search, sidebar toggle
 */

(function() {
    'use strict';

    // ============================================
    // TOAST NOTIFICATION SYSTEM
    // ============================================
    const ToastSystem = {
        container: null,
        toasts: [],
        maxToasts: 5,
        defaultDuration: 5000,

        init() {
            this.container = document.createElement('div');
            this.container.className = 'toast-container';
            document.body.appendChild(this.container);
        },

        show(message, options = {}) {
            const {
                title = '',
                type = 'info', // success, warning, error, info
                duration = this.defaultDuration,
                icon = null
            } = options;

            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.style.setProperty('--toast-duration', `${duration}ms`);

            const icons = {
                success: 'fa-check-circle',
                warning: 'fa-exclamation-triangle',
                error: 'fa-times-circle',
                info: 'fa-info-circle'
            };

            toast.innerHTML = `
                <div class="toast-icon">
                    <i class="fas ${icon || icons[type]}"></i>
                </div>
                <div class="toast-content">
                    ${title ? `<div class="toast-title">${this.escapeHtml(title)}</div>` : ''}
                    <div class="toast-message">${this.escapeHtml(message)}</div>
                </div>
                <button class="toast-close" aria-label="Close notification">
                    <i class="fas fa-times"></i>
                </button>
                <div class="toast-progress"></div>
            `;

            this.container.appendChild(toast);

            const closeBtn = toast.querySelector('.toast-close');
            closeBtn.addEventListener('click', () => this.dismiss(toast));

            if (duration > 0) {
                setTimeout(() => this.dismiss(toast), duration);
            }

            this.toasts.push(toast);

            if (this.toasts.length > this.maxToasts) {
                const oldest = this.toasts.shift();
                this.dismiss(oldest);
            }

            return toast;
        },

        dismiss(toast) {
            if (!toast || !toast.parentNode) return;

            toast.classList.add('toast-exit');
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
                const index = this.toasts.indexOf(toast);
                if (index > -1) {
                    this.toasts.splice(index, 1);
                }
            }, 250);
        },

        success(message, options = {}) {
            return this.show(message, { ...options, type: 'success' });
        },

        warning(message, options = {}) {
            return this.show(message, { ...options, type: 'warning' });
        },

        error(message, options = {}) {
            return this.show(message, { ...options, type: 'error' });
        },

        info(message, options = {}) {
            return this.show(message, { ...options, type: 'info' });
        },

        escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    };

    // ============================================
    // KEYBOARD SHORTCUTS SYSTEM
    // ============================================
    const KeyboardShortcuts = {
        shortcuts: [],
        enabled: true,

        register(shortcut) {
            this.shortcuts.push(shortcut);
        },

        handleKeyDown(e) {
            if (!this.enabled) return;

            const key = e.key.toLowerCase();
            const ctrl = e.ctrlKey || e.metaKey;
            const shift = e.shiftKey;

            for (const shortcut of this.shortcuts) {
                const { keys, ctrl: needCtrl = false, shift: needShift = false, handler, preventDefault = true } = shortcut;

                const keyMatch = keys.includes(key);
                const ctrlMatch = needCtrl === ctrl;
                const shiftMatch = needShift === shift;

                if (keyMatch && ctrlMatch && shiftMatch) {
                    if (preventDefault) {
                        e.preventDefault();
                    }
                    handler(e);
                    break;
                }
            }
        },

        init() {
            document.addEventListener('keydown', (e) => this.handleKeyDown(e));
        }
    };

    // ============================================
    // GLOBAL SEARCH MODAL
    // ============================================
    const GlobalSearch = {
        overlay: null,
        modal: null,
        input: null,
        resultsContainer: null,
        isOpen: false,
        searchTimeout: null,
        activeIndex: -1,
        results: [],

        init() {
            this.createSearchModal();
            this.bindEvents();
        },

        createSearchModal() {
            this.overlay = document.createElement('div');
            this.overlay.className = 'search-modal-overlay';
            this.overlay.innerHTML = `
                <div class="search-modal">
                    <div class="search-modal-header">
                        <i class="fas fa-search"></i>
                        <input type="text" class="search-modal-input" placeholder="Search patients, appointments, notes..." />
                        <button class="search-modal-close" aria-label="Close search">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="search-modal-body">
                        <div class="search-modal-results"></div>
                    </div>
                    <div class="search-modal-footer">
                        <span><kbd>↑</kbd><kbd>↓</kbd> Navigate</span>
                        <span><kbd>↵</kbd> Select</span>
                        <span><kbd>Esc</kbd> Close</span>
                    </div>
                </div>
            `;

            document.body.appendChild(this.overlay);

            this.modal = this.overlay.querySelector('.search-modal');
            this.input = this.overlay.querySelector('.search-modal-input');
            this.resultsContainer = this.overlay.querySelector('.search-modal-results');

            this.input.addEventListener('input', (e) => this.handleSearch(e.target.value));
            this.input.addEventListener('keydown', (e) => this.handleInputKeyDown(e));

            // Use event delegation on results container instead of individual listeners
            this.resultsContainer.addEventListener('click', (e) => {
                const resultItem = e.target.closest('.search-result-item');
                if (resultItem) {
                    const patientId = resultItem.getAttribute('data-id');
                    this.selectResult(patientId);
                }
            });

            const closeBtn = this.overlay.querySelector('.search-modal-close');
            closeBtn.addEventListener('click', () => this.close());

            this.overlay.addEventListener('click', (e) => {
                if (e.target === this.overlay) {
                    this.close();
                }
            });
        },

        bindEvents() {
            document.addEventListener('keydown', (e) => {
                if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                    e.preventDefault();
                    this.toggle();
                }
                if (e.key === 'Escape' && this.isOpen) {
                    this.close();
                }
            });

            const searchTrigger = document.querySelector('.global-search-trigger');
            if (searchTrigger) {
                searchTrigger.addEventListener('click', () => this.open());
            }
        },

        open() {
            this.isOpen = true;
            this.overlay.classList.add('active');
            this.input.value = '';
            this.input.focus();
            this.resultsContainer.innerHTML = '';
            this.activeIndex = -1;
        },

        close() {
            this.isOpen = false;
            this.overlay.classList.remove('active');
            this.input.blur();
        },

        toggle() {
            if (this.isOpen) {
                this.close();
            } else {
                this.open();
            }
        },

        handleSearch(query) {
            clearTimeout(this.searchTimeout);

            if (query.length < 2) {
                this.resultsContainer.innerHTML = '';
                this.results = [];
                return;
            }

            this.searchTimeout = setTimeout(() => {
                this.performSearch(query);
            }, 300);
        },

        async performSearch(query) {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                const token = csrfToken ? csrfToken.getAttribute('content') : '';

                const response = await fetch(`/doctor/patients/search?query=${encodeURIComponent(query)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': token
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    // Controller returns array directly, not wrapped in 'patients' key
                    const patients = Array.isArray(data) ? data : (data.patients || []);
                    this.displayResults(patients, query);
                } else {
                    // Handle non-OK responses (403, 500, etc.)
                    console.error('Search failed with status:', response.status);
                    this.resultsContainer.innerHTML = `
                        <div style="padding: 1rem; text-align: center; color: var(--danger);">
                            <i class="fas fa-exclamation-triangle" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                            <p>Search is currently unavailable. Please try again later.</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Search failed:', error);
                this.resultsContainer.innerHTML = '<div class="text-muted" style="padding: 1rem; text-align: center;">Search failed. Please try again.</div>';
            }
        },

        displayResults(patients, query) {
            this.results = patients.slice(0, 10);
            this.activeIndex = -1;

            if (this.results.length === 0) {
                this.resultsContainer.innerHTML = `
                    <div style="padding: 2rem; text-align: center; color: var(--text-muted);">
                        <i class="fas fa-search" style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.3;"></i>
                        <p>No results found for "${this.escapeHtml(query)}"</p>
                    </div>
                `;
                return;
            }

            this.resultsContainer.innerHTML = this.results.map((patient, index) => `
                <div class="search-result-item" data-index="${index}" data-id="${patient.id}">
                    <div class="search-result-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="search-result-info">
                        <div class="search-result-title">${this.escapeHtml(patient.name)}</div>
                        <div class="search-result-subtitle">${this.escapeHtml(patient.phone || 'No phone')} ${patient.email ? '• ' + this.escapeHtml(patient.email) : ''}</div>
                    </div>
                </div>
            `).join('');

            // Event delegation handles clicks - no need for individual listeners
        },

        handleInputKeyDown(e) {
            const items = this.resultsContainer.querySelectorAll('.search-result-item');

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                this.activeIndex = Math.min(this.activeIndex + 1, items.length - 1);
                this.updateActiveItem(items);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                this.activeIndex = Math.max(this.activeIndex - 1, 0);
                this.updateActiveItem(items);
            } else if (e.key === 'Enter' && this.activeIndex >= 0) {
                e.preventDefault();
                const item = items[this.activeIndex];
                const patientId = item.getAttribute('data-id');
                this.selectResult(patientId);
            }
        },

        updateActiveItem(items) {
            items.forEach((item, index) => {
                if (index === this.activeIndex) {
                    item.classList.add('active');
                    item.scrollIntoView({ block: 'nearest' });
                } else {
                    item.classList.remove('active');
                }
            });
        },

        selectResult(patientId) {
            if (!patientId) {
                console.error('Invalid patient ID:', patientId);
                return;
            }
            this.close();
            window.location.href = `/doctor/patients/${patientId}`;
        },

        escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    };

    // ============================================
    // SIDEBAR TOGGLE
    // ============================================
    const SidebarToggle = {
        sidebar: null,
        isCollapsed: false,

        init() {
            this.sidebar = document.getElementById('doctor-sidebar');
            if (!this.sidebar) return;

            const toggleBtn = document.querySelector('.sidebar-toggle');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', () => this.toggle());
            }

            const savedState = localStorage.getItem('sidebarCollapsed');
            if (savedState === 'true') {
                this.collapse();
            }

            this.handleMobile();
        },

        toggle() {
            if (window.innerWidth > 1024) {
                this.isCollapsed = !this.isCollapsed;
                if (this.isCollapsed) {
                    this.collapse();
                } else {
                    this.expand();
                }
            } else {
                this.toggleMobile();
            }
        },

        collapse() {
            if (!this.sidebar) return;
            this.sidebar.classList.add('collapsed');
            this.isCollapsed = true;
            try {
                localStorage.setItem('sidebarCollapsed', 'true');
            } catch (e) {
                // localStorage unavailable
            }
        },

        expand() {
            if (!this.sidebar) return;
            this.sidebar.classList.remove('collapsed');
            this.isCollapsed = false;
            try {
                localStorage.setItem('sidebarCollapsed', 'false');
            } catch (e) {
                // localStorage unavailable
            }
        },

        handleMobile() {
            const overlay = document.getElementById('sidebar-overlay');
            if (!overlay) return;

            if (window.innerWidth <= 1024) {
                this.sidebar.classList.remove('collapsed');
            }
        },

        toggleMobile() {
            if (!this.sidebar) return;
            this.sidebar.classList.toggle('show');
            const overlay = document.getElementById('sidebar-overlay');
            if (overlay) {
                overlay.classList.toggle('show');
            }
        }
    };

    // ============================================
    // USER MENU DROPDOWN
    // ============================================
    const UserMenu = {
        menu: null,
        isOpen: false,

        init() {
            this.menu = document.querySelector('.user-menu');
            if (!this.menu) return;

            const trigger = this.menu.querySelector('.user-menu-trigger');
            if (trigger) {
                trigger.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.toggle();
                });
            }

            document.addEventListener('click', (e) => {
                if (this.isOpen && !this.menu.contains(e.target)) {
                    this.close();
                }
            });
        },

        toggle() {
            if (this.isOpen) {
                this.close();
            } else {
                this.open();
            }
        },

        open() {
            this.menu.classList.add('open');
            this.isOpen = true;
        },

        close() {
            this.menu.classList.remove('open');
            this.isOpen = false;
        }
    };

    // ============================================
    // KEYBOARD SHORTCUTS HELPER MODAL
    // ============================================
    const ShortcutsModal = {
        overlay: null,

        init() {
            this.createModal();
            this.bindEvents();
        },

        createModal() {
            this.overlay = document.createElement('div');
            this.overlay.className = 'shortcuts-modal-overlay';
            this.overlay.innerHTML = `
                <div class="shortcuts-modal">
                    <div class="shortcuts-modal-header">
                        <h3><i class="fas fa-keyboard"></i> Keyboard Shortcuts</h3>
                        <button class="shortcuts-modal-close" aria-label="Close shortcuts">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="shortcuts-modal-body">
                        <div class="shortcut-group">
                            <div class="shortcut-group-title">Navigation</div>
                            <div class="shortcut-item">
                                <span class="shortcut-description">Global Search</span>
                                <div class="shortcut-keys">
                                    <kbd class="shortcut-key">Ctrl</kbd>
                                    <span class="shortcut-key-plus">+</span>
                                    <kbd class="shortcut-key">K</kbd>
                                </div>
                            </div>
                            <div class="shortcut-item">
                                <span class="shortcut-description">Show Shortcuts</span>
                                <div class="shortcut-keys">
                                    <kbd class="shortcut-key">Ctrl</kbd>
                                    <span class="shortcut-key-plus">+</span>
                                    <kbd class="shortcut-key">/</kbd>
                                </div>
                            </div>
                        </div>
                        <div class="shortcut-group">
                            <div class="shortcut-group-title">Quick Actions</div>
                            <div class="shortcut-item">
                                <span class="shortcut-description">New Appointment</span>
                                <div class="shortcut-keys">
                                    <kbd class="shortcut-key">Ctrl</kbd>
                                    <span class="shortcut-key-plus">+</span>
                                    <kbd class="shortcut-key">N</kbd>
                                </div>
                            </div>
                            <div class="shortcut-item">
                                <span class="shortcut-description">Open Messages</span>
                                <div class="shortcut-keys">
                                    <kbd class="shortcut-key">Ctrl</kbd>
                                    <span class="shortcut-key-plus">+</span>
                                    <kbd class="shortcut-key">M</kbd>
                                </div>
                            </div>
                            <div class="shortcut-item">
                                <span class="shortcut-description">Start Consultation</span>
                                <div class="shortcut-keys">
                                    <kbd class="shortcut-key">Ctrl</kbd>
                                    <span class="shortcut-key-plus">+</span>
                                    <kbd class="shortcut-key">Shift</kbd>
                                    <span class="shortcut-key-plus">+</span>
                                    <kbd class="shortcut-key">C</kbd>
                                </div>
                            </div>
                        </div>
                        <div class="shortcut-group">
                            <div class="shortcut-group-title">General</div>
                            <div class="shortcut-item">
                                <span class="shortcut-description">Close Modal</span>
                                <div class="shortcut-keys">
                                    <kbd class="shortcut-key">Esc</kbd>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(this.overlay);

            const closeBtn = this.overlay.querySelector('.shortcuts-modal-close');
            closeBtn.addEventListener('click', () => this.close());

            this.overlay.addEventListener('click', (e) => {
                if (e.target === this.overlay) {
                    this.close();
                }
            });
        },

        bindEvents() {
            document.addEventListener('keydown', (e) => {
                if ((e.ctrlKey || e.metaKey) && e.key === '/') {
                    e.preventDefault();
                    this.toggle();
                }
            });
        },

        toggle() {
            if (this.overlay.classList.contains('active')) {
                this.close();
            } else {
                this.open();
            }
        },

        open() {
            this.overlay.classList.add('active');
        },

        close() {
            this.overlay.classList.remove('active');
        }
    };

    // ============================================
    // NOTIFICATION BELL SYSTEM
    // ============================================
    const NotificationBell = {
        bell: null,
        dropdown: null,
        isOpen: false,

        init() {
            this.bell = document.querySelector('.notification-bell');
            if (!this.bell) return;

            this.bell.addEventListener('click', (e) => {
                e.stopPropagation();
                this.toggleDropdown();
            });

            document.addEventListener('click', (e) => {
                if (this.isOpen && !this.bell.contains(e.target)) {
                    this.closeDropdown();
                }
            });
        },

        toggleDropdown() {
            if (this.isOpen) {
                this.closeDropdown();
            } else {
                this.openDropdown();
            }
        },

        openDropdown() {
            this.isOpen = true;
        },

        closeDropdown() {
            this.isOpen = false;
        },

        updateCount(count) {
            const badge = this.bell.querySelector('.badge-count');
            if (badge) {
                if (count > 0) {
                    badge.textContent = count > 99 ? '99+' : count;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
            }
        }
    };

    // ============================================
    // INITIALIZE ALL SYSTEMS
    // ============================================
    function initializeDoctorPortal() {
        ToastSystem.init();
        KeyboardShortcuts.init();
        GlobalSearch.init();
        SidebarToggle.init();
        UserMenu.init();
        ShortcutsModal.init();
        NotificationBell.init();

        // Register default keyboard shortcuts
        KeyboardShortcuts.register({
            keys: ['n'],
            ctrl: true,
            handler: () => {
                window.location.href = '/doctor/appointments/create';
            }
        });

        KeyboardShortcuts.register({
            keys: ['m'],
            ctrl: true,
            handler: () => {
                window.location.href = '/doctor/messages';
            }
        });

        KeyboardShortcuts.register({
            keys: ['c'],
            ctrl: true,
            shift: true,
            handler: () => {
                window.location.href = '/ai/ambient-listening';
            }
        });

        // Expose toast system globally for use in other scripts
        window.showToast = (message, options = {}) => ToastSystem.show(message, options);
        window.toast = ToastSystem;

        // Show welcome toast on first visit (once per session)
        try {
            if (!sessionStorage.getItem('doctorWelcomeShown')) {
                const hour = new Date().getHours();
                let greeting = 'Good evening';
                if (hour < 12) greeting = 'Good morning';
                else if (hour < 18) greeting = 'Good afternoon';

                ToastSystem.info(`Welcome back, Dr. ${document.querySelector('.user-name')?.textContent || 'Doctor'}!`, {
                    title: greeting,
                    duration: 4000
                });
                sessionStorage.setItem('doctorWelcomeShown', 'true');
            }
        } catch (e) {
            // sessionStorage unavailable
        }

        console.log('✓ Doctor Portal Enhanced UI Loaded');
    }

    // Run on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeDoctorPortal);
    } else {
        initializeDoctorPortal();
    }

})();
