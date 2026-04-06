<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="author" content="SemiColonWeb">
    <meta name="description" content="MedCura AI - Medical Clinic Management System">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ Auth::id() }}">
    <meta name="user-role" content="{{ Auth::user()->role ?? 'user' }}">

    <!-- Font Imports -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,300;0,400;0,700;1,400&family=Montserrat:wght@400;700&family=Crete+Round:ital@0;1&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('demos/medical/css/medical-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/swiper.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('demos/medical/medical.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/logo-fix.css') }}">
    <link rel="stylesheet" href="{{ asset('favicon.ico') }}">

    <style>
        body, * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
        }
        .fa, .fas, .far, .fab {
            font-family: "Font Awesome 6 Free" !important;
            font-weight: 900 !important;
        }
        .doctor-wrapper { display: flex; min-height: 100vh; }
        .doctor-sidebar {
            width: 280px;
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }
        .doctor-content {
            flex: 1;
            margin-left: 280px;
            background: #060d1f;
            min-height: 100vh;
        }
        body {
            background: #060d1f !important;
        }
        .sidebar-brand {
            padding: 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            text-align: center;
            background: rgba(0,0,0,0.2);
        }
        .sidebar-brand img { max-width: 90px; }
        .sidebar-nav { padding: 0.75rem 0; }
        .nav-item { margin: 0.2rem 0.6rem; }
        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.7rem 1rem;
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.25s ease;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .nav-link:hover {
            color: white;
            background: rgba(255,255,255,0.08);
            transform: translateX(4px);
        }
        .nav-link.active {
            color: white;
            background: linear-gradient(135deg, #00d4aa 0%, #00a88a 100%);
            box-shadow: 0 4px 15px rgba(0, 212, 170, 0.35);
        }
        .nav-link i { width: 22px; margin-right: 0.85rem; text-align: center; font-size: 1rem; }
        .nav-section {
            padding: 1rem 1.25rem 0.5rem;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.35);
        }
        .nav-badge {
            margin-left: auto;
            background: rgba(255,255,255,0.15);
            padding: 0.15rem 0.5rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        .nav-link.active .nav-badge {
            background: rgba(255,255,255,0.25);
        }

        /* Quick Action Button */
        .quick-action {
            margin: 0.75rem 0.6rem;
        }
        .quick-action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.85rem 1rem;
            background: linear-gradient(135deg, #00d4aa 0%, #00a88a 100%);
            color: #060d1f;
            border: none;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 4px 15px rgba(0, 212, 170, 0.3);
        }
        .quick-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 212, 170, 0.4);
            color: #060d1f;
        }
        .quick-action-btn i { margin-right: 0.5rem; }

        /* Divider */
        .nav-divider {
            height: 1px;
            background: rgba(255,255,255,0.08);
            margin: 0.75rem 0.6rem;
        }

        .user-info {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,0.08);
            background: rgba(0,0,0,0.15);
        }
        .doctor-page { padding: 1.5rem 2rem; }
        .doctor-container { max-width: 1400px; margin: 0 auto; }

        /* Workflow indicator */
        .workflow-hint {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.4);
            padding: 0.5rem 1rem;
            font-style: italic;
        }

        @media (max-width: 768px) {
            .doctor-sidebar { transform: translateX(-100%); }
            .doctor-sidebar.show { transform: translateX(0); }
            .doctor-content { margin-left: 0; }
            .sidebar-hamburger {
                position: absolute;
                top: 12px;
                right: 12px;
                background: rgba(255,255,255,0.1);
                border: none;
                color: white;
                width: 40px;
                height: 40px;
                border-radius: 8px;
                cursor: pointer;
                z-index: 1001;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }

        /* Sidebar overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        .sidebar-overlay.show {
            display: block;
        }

        /* Active state for AI pages */
        .nav-link.active-ai {
            color: white;
            background: linear-gradient(135deg, #00d4aa 0%, #00a88a 100%);
            box-shadow: 0 4px 15px rgba(0, 212, 170, 0.4);
        }

        /* Dark theme overrides for child pages */
        :root {
            --navy: #060d1f;
            --navy-card: #0f1c3a;
            --teal: #00d4aa;
            --offwhite: #e8edf5;
            --muted: rgba(232,237,231,0.55);
            --card-bg: rgba(10,22,40,0.9);
            --card-border: rgba(0,212,170,0.12);
            --teal-dim: rgba(0,212,170,0.1);
        }
        body { background: var(--navy) !important; }
        .dashboard-header {
            background: linear-gradient(135deg, #0a1628 0%, #0f1c3a 100%) !important;
            border: 1px solid rgba(0,212,170,0.15) !important;
            border-radius: 16px !important;
            padding: 2rem !important;
            margin-bottom: 2rem !important;
            position: relative;
            overflow: hidden;
        }
        .dashboard-header::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--teal), transparent);
        }
        .dashboard-header h1, .dashboard-header h2 {
            color: var(--offwhite) !important;
            font-weight: 700;
        }
        .dashboard-header p { color: var(--muted) !important; }
        .dashboard-header .btn {
            background: rgba(255,255,255,0.06) !important;
            border: 1px solid rgba(255,255,255,0.12) !important;
            color: var(--muted) !important;
        }
        .dashboard-header .btn:hover {
            background: rgba(255,255,255,0.1) !important;
            color: var(--offwhite) !important;
        }
        .table-card {
            background: var(--card-bg) !important;
            border: 1px solid var(--card-border) !important;
            border-radius: 16px !important;
            padding: 1.75rem !important;
            margin-bottom: 1.5rem !important;
            backdrop-filter: blur(12px);
        }
        .card {
            background: var(--card-bg) !important;
            border: 1px solid var(--card-border) !important;
            border-radius: 16px !important;
        }
        .card-header {
            background: rgba(0,212,170,0.05) !important;
            border-bottom: 1px solid var(--card-border) !important;
            color: var(--offwhite) !important;
        }
        .card-body { background: transparent !important; }
        .form-control, .form-select {
            background: rgba(10,20,40,0.8) !important;
            border: 1px solid var(--card-border) !important;
            color: var(--offwhite) !important;
            border-radius: 10px !important;
        }
        .form-control:focus, .form-select:focus {
            border-color: rgba(0,212,170,0.5) !important;
            box-shadow: 0 0 0 3px rgba(0,212,170,0.08) !important;
        }
        .form-control::placeholder { color: rgba(232,237,231,0.25) !important; }
        .form-select option { background: var(--navy-card); color: var(--offwhite); }
        .form-label { color: var(--offwhite) !important; }
        .form-text, .text-muted { color: var(--muted) !important; }
        .alert-success { background: rgba(0,212,170,0.08) !important; border: 1px solid rgba(0,212,170,0.2) !important; color: var(--teal) !important; border-radius: 12px; }
        .alert-danger { background: rgba(248,113,113,0.08) !important; border: 1px solid rgba(248,113,113,0.2) !important; color: #f87171 !important; border-radius: 12px; }
        .btn-primary { background: var(--teal) !important; border-color: var(--teal) !important; color: var(--navy) !important; font-weight: 600; }
        .btn-primary:hover { background: #00e8bb !important; transform: translateY(-1px); }
        .btn-secondary-custom, .btn-secondary { background: rgba(255,255,255,0.06) !important; border: 1px solid rgba(255,255,255,0.1) !important; color: var(--muted) !important; }
        .btn-secondary-custom:hover, .btn-secondary:hover { background: rgba(255,255,255,0.1) !important; color: var(--offwhite) !important; }
        .btn-outline-primary { border-color: rgba(0,212,170,0.3) !important; color: var(--teal) !important; }
        .btn-outline-primary:hover { background: rgba(0,212,170,0.08) !important; }
        .text-primary { color: var(--teal) !important; }
        .text-success { color: var(--teal) !important; }
        .text-dark { color: var(--offwhite) !important; }
        .text-secondary { color: var(--muted) !important; }
        .bg-light { background: rgba(255,255,255,0.04) !important; }
        .bg-white { background: var(--card-bg) !important; }
        .border { border-color: var(--card-border) !important; }
        .border-top { border-color: var(--card-border) !important; }
        .text-center { color: var(--muted); }
    </style>

    @yield('styles')

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- PWA Meta Tags -->
    <link rel="manifest" href="{{ asset('doctor-manifest.webmanifest') }}">
    <meta name="theme-color" content="#0EA5E9">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Doctor App">
    <link rel="apple-touch-icon" href="{{ asset('icons/doctor-icon-192.png') }}">
    <title>@yield('title', 'Doctor Dashboard | MedCura AI')</title>
</head>
<body>
    <!-- Sidebar overlay for mobile -->
    <div id="sidebar-overlay" class="sidebar-overlay" onclick="document.querySelector('.doctor-sidebar').classList.remove('show')"></div>

    <div class="doctor-wrapper">
        <nav class="doctor-sidebar" id="doctor-sidebar">
            <!-- Mobile hamburger toggle -->
            <button id="sidebar-hamburger-btn" class="sidebar-hamburger d-lg-none" onclick="document.getElementById('doctor-sidebar').classList.toggle('show');document.getElementById('sidebar-overlay').classList.toggle('show')">
                <i class="fas fa-bars"></i>
            </button>
            <div class="sidebar-brand">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('demos/medical/images/logo-medical.png') }}" alt="MedCura AI">
                </a>
                <small class="text-white-50 d-block mt-2" style="font-size: 0.75rem;">Doctor Panel</small>
            </div>

            <div class="sidebar-nav">
                <!-- Quick Action - Start Consultation -->
                <div class="quick-action">
                    <a href="{{ route('ai.ambient-listening.index') }}" class="quick-action-btn">
                        <i class="fas fa-microphone"></i>
                        Start Consultation
                    </a>
                </div>

                <div class="nav-section">Overview</div>
                <div class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                </div>

                <div class="nav-divider"></div>
                <div class="nav-section">Today's Work</div>
                <div class="nav-item">
                    <a href="{{ route('doctor.on-deck') }}" class="nav-link {{ request()->routeIs('doctor.on-deck') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list"></i>
                        <span>Today's Queue</span>
                        @php
                            $todayAppointments = \App\Models\Appointment::where('doctor_id', auth()->id())
                                ->whereDate('appointment_date', now()->toDateString())
                                ->whereIn('status', ['confirmed', 'pending'])
                                ->count();
                        @endphp
                        @if($todayAppointments > 0)
                            <span class="nav-badge">{{ $todayAppointments }}</span>
                        @endif
                    </a>
                </div>

                <div class="nav-divider"></div>
                <div class="nav-section">Patient Management</div>
                <div class="nav-item">
                    <a href="{{ route('doctor.appointments.index') }}" class="nav-link {{ request()->routeIs('doctor.appointments.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-check"></i>
                        <span>All Appointments</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('doctor.patients.index') }}" class="nav-link {{ request()->routeIs('doctor.patients.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>My Patients</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('doctor.cases.overview') }}" class="nav-link {{ request()->routeIs('doctor.cases.*') ? 'active' : '' }}">
                        <i class="fas fa-folder-open"></i>
                        <span>Medical Records</span>
                        @php
                            $casesCount = \App\Models\Diagnosis::where('doctor_id', auth()->id())->count();
                        @endphp
                        @if($casesCount > 0)
                            <span class="nav-badge">{{ $casesCount }}</span>
                        @endif
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('doctor.appointments.create') }}" class="nav-link">
                        <i class="fas fa-plus-circle"></i>
                        <span>New Appointment</span>
                    </a>
                </div>

                <div class="nav-divider"></div>
                <div class="nav-section">Tools</div>
                <div class="nav-item">
                    <a href="{{ route('ai.ambient-listening.index') }}" class="nav-link {{ request()->routeIs('ai.ambient-listening.index') ? 'active' : '' }}">
                        <i class="fas fa-ear-listen"></i>
                        <span>Ambient Listening</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('ai.ambient-listening.recorded-voices') }}" class="nav-link {{ request()->routeIs('ai.ambient-listening.recorded-voices') ? 'active' : '' }}">
                        <i class="fas fa-history"></i>
                        <span>Consultation History</span>
                    </a>
                </div>

                <div class="nav-divider"></div>
                <div class="nav-section">Practice</div>
                <div class="nav-item">
                    <a href="{{ route('reviews.index') }}" class="nav-link {{ request()->routeIs('reviews.index') ? 'active' : '' }}">
                        <i class="fas fa-star-half-alt"></i>
                        <span>Reviews</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('doctor.blog.index') }}" class="nav-link {{ request()->routeIs('doctor.blog.*') ? 'active' : '' }}">
                        <i class="fas fa-newspaper"></i>
                        <span>Blog</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('doctor.chat.index') }}" class="nav-link {{ request()->routeIs('doctor.chat.*') ? 'active' : '' }}">
                        <i class="fas fa-comments"></i>
                        <span>Messages</span>
                    </a>
                </div>
            </div>

            <div class="user-info">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar-circle me-2">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div>
                        <div class="text-white" style="font-size: 0.9rem; font-weight: 500;">{{ Auth::user()->name ?? 'Doctor' }}</div>
                        <small class="text-white-50">{{ Auth::user()->doctor->specialty ?? 'Physician' }}</small>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light w-100">
                        <i class="fas fa-sign-out-alt me-1"></i> Sign Out
                    </button>
                </form>
            </div>
        </nav>
        <main class="doctor-content">
            @yield('content')
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- PWA Install Banner -->
    <div id="pwa-install-banner" class="pwa-install-banner" style="display:none;">
        <div class="pwa-banner-content">
            <div class="pwa-banner-icon">
                <img src="{{ asset('icons/doctor-icon-192.png') }}" alt="Doctor App" width="32" height="32">
            </div>
            <div class="pwa-banner-text">
                <strong>Install Doctor App</strong>
                <span>For a faster, app-like experience</span>
            </div>
            <div class="pwa-banner-buttons">
                <button id="pwa-install-btn" class="btn btn-primary btn-sm">Install</button>
                <button id="pwa-dismiss-btn" class="btn btn-secondary btn-sm">Not now</button>
            </div>
        </div>
    </div>

    <style>
    .pwa-install-banner {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: #0c1929;
        color: white;
        padding: 12px 16px;
        z-index: 9999;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.3);
    }
    .pwa-banner-content {
        display: flex;
        align-items: center;
        gap: 12px;
        max-width: 600px;
        margin: 0 auto;
    }
    .pwa-banner-text {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .pwa-banner-text span {
        font-size: 12px;
        opacity: 0.8;
    }
    .pwa-banner-buttons {
        display: flex;
        gap: 8px;
    }
    .pwa-banner-buttons .btn {
        padding: 6px 16px;
        border-radius: 6px;
        font-size: 13px;
        cursor: pointer;
        border: none;
    }
    .pwa-banner-buttons .btn-primary {
        background: #0EA5E9;
        color: white;
    }
    .pwa-banner-buttons .btn-secondary {
        background: rgba(255,255,255,0.15);
        color: white;
    }
    @media (max-width: 480px) {
        .pwa-banner-content {
            flex-wrap: wrap;
        }
        .pwa-banner-text {
            flex: 1 1 calc(100% - 44px);
        }
        .pwa-banner-buttons {
            flex: 1;
            justify-content: flex-end;
        }
    }
    </style>

    <script>
    (function() {
        let deferredPrompt;
        const banner = document.getElementById('pwa-install-banner');
        const installBtn = document.getElementById('pwa-install-btn');
        const dismissBtn = document.getElementById('pwa-dismiss-btn');

        const isStandalone = window.matchMedia('(display-mode: standalone)').matches;
        let isDismissed = false;
        try {
            isDismissed = localStorage.getItem('doctorPwaDismissed') === 'true';
        } catch (e) {
            // localStorage unavailable (private browsing)
        }

        if (!isStandalone && !isDismissed && 'serviceWorker' in navigator) {
            navigator.serviceWorker.register('{{ asset("doctor-sw.js") }}')
                .then(() => console.log('Doctor SW registered'))
                .catch((err) => console.error('Doctor SW registration failed:', err));

            setTimeout(function() {
                try {
                    if (!isStandalone && !localStorage.getItem('doctorPwaDismissed')) {
                        banner.style.display = 'block';
                    }
                } catch (e) {
                    // localStorage unavailable
                }
            }, 30000);
        }

        if (isStandalone) {
            console.log('Doctor PWA running in standalone mode');
        }

        window.addEventListener('beforeinstallprompt', function(e) {
            e.preventDefault();
            deferredPrompt = e;
            if (!banner.style.display || banner.style.display === 'none') {
                banner.style.display = 'block';
            }
        });

        installBtn.addEventListener('click', async function() {
            if (!deferredPrompt) return;
            try {
                deferredPrompt.prompt();
                const result = await deferredPrompt.userChoice;
                deferredPrompt = null;
                banner.style.display = 'none';
                if (result.outcome === 'accepted') {
                    try {
                        localStorage.setItem('doctorPwaDismissed', 'true');
                    } catch (e) {
                        // localStorage unavailable
                    }
                }
            } catch (err) {
                console.error('PWA install prompt failed:', err);
                deferredPrompt = null;
                banner.style.display = 'none';
            }
        });

        dismissBtn.addEventListener('click', function() {
            banner.style.display = 'none';
            try {
                localStorage.setItem('doctorPwaDismissed', 'true');
            } catch (e) {
                // localStorage unavailable
            }
        });
    })();
    </script>
</body>
</html>
