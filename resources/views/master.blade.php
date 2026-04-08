<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="MedSuite AI">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    @auth
    <meta name="user-id" content="{{ Auth::id() }}">
    <meta name="notification-sound-enabled" content="{{ env('NOTIFICATION_SOUND_ENABLED', 'true') }}">
    <meta name="notification-toast-enabled" content="{{ env('NOTIFICATION_TOAST_ENABLED', 'true') }}">
    <meta name="notification-badge-enabled" content="{{ env('NOTIFICATION_BADGE_ENABLED', 'true') }}">
    <script>
        window.userRole = '{{ Auth::user()->role ?? 'user' }}';
        window.userId = {{ Auth::id() ?? 'null' }};
    </script>
    @endauth

    <title>@yield('title', 'MedSuite AI')</title>

    <!-- ─── Fonts ─── -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">

    <!-- ─── External Libraries ─── -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
          crossorigin="anonymous" referrerpolicy="no-referrer">

    <!-- ─── Project Stylesheets ─── -->
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('demos/medical/css/medical-icons.css') }}">
    <!-- Global Themes - Light and Dark -->
    <link rel="stylesheet" href="{{ asset('css/global-light-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/global-dark-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/swiper.css') }}">
    <link rel="stylesheet" href="{{ asset('demos/medical/medical.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/logo-fix.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive-modals.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom-buttons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    @stack('styles')

    <!-- ─── Design System Tokens ─── -->
    <style>
    /* ════════════════════════════════════════════
       DESIGN TOKENS - Theme-aware
    ════════════════════════════════════════════ */
    
    /* Dark theme tokens */
    [data-theme="dark"] {
        --navy:          #060d1f;
        --navy-mid:      #0c1633;
        --navy-card:     #0f1c3a;
        --teal:          #00d4aa;
        --teal-dim:      rgba(0,212,170,0.10);
        --teal-glow:     rgba(0,212,170,0.25);
        --teal-border:   rgba(0,212,170,0.25);
        --amber:         #f59e0b;
        --red:           #f87171;
        --green:         #22c55e;
        --white:         #ffffff;
        --offwhite:      #e8edf5;
        --muted:         rgba(232,237,245,0.48);
        --border:        rgba(255,255,255,0.07);
        --border-hover:  rgba(255,255,255,0.14);
        --glass:         rgba(255,255,255,0.035);
        --shadow-nav:    0 1px 0 rgba(255,255,255,0.05), 0 4px 24px rgba(0,0,0,0.35);
    }
    
    /* Light theme tokens */
    [data-theme="light"] {
        --navy:          #1e293b;
        --navy-mid:      #f8fafc;
        --navy-card:     #ffffff;
        --teal:          #0d9488;
        --teal-dim:      rgba(13,148,136,0.10);
        --teal-glow:     rgba(13,148,136,0.25);
        --teal-border:   rgba(13,148,136,0.25);
        --amber:         #d97706;
        --red:           #dc2626;
        --green:         #16a34a;
        --white:         #ffffff;
        --offwhite:      #1e293b;
        --muted:         rgba(30,41,59,0.6);
        --border:        rgba(0,0,0,0.1);
        --border-hover:  rgba(0,0,0,0.2);
        --glass:         rgba(0,0,0,0.02);
        --shadow-nav:    0 1px 0 rgba(0,0,0,0.05), 0 4px 24px rgba(0,0,0,0.1);
    }
    
    /* Common tokens */
    :root {
        --font-display:  'Cormorant Garamond', Georgia, serif;
        --font-body:     'DM Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        --radius-sm:     8px;
        --radius-md:     12px;
        --radius-lg:     16px;
        --radius-xl:     20px;
        --radius-pill:   50px;
        --transition:    0.2s cubic-bezier(0.4,0,0.2,1);
    }

    /* ════════════════════════════════════════════
       GLOBAL RESET & BASE
    ════════════════════════════════════════════ */
    *, *::before, *::after { box-sizing: border-box; }

    html, body {
        margin: 0; padding: 0;
        background: var(--navy);
        color: var(--offwhite);
        font-family: var(--font-body);
        font-size: 15px;
        line-height: 1.6;
        -webkit-font-smoothing: antialiased;
        max-width: 100%;
        overflow-x: clip;
    }

    a { text-decoration: none; color: inherit; }

    /* FA icon font correction */
    .fa,.fas,.fa-solid,i.fa,i.fas,i.fa-solid{font-family:"Font Awesome 6 Free"!important;font-weight:900!important;}
    .far,.fa-regular,i.far,i.fa-regular{font-family:"Font Awesome 6 Free"!important;font-weight:400!important;}
    .fab,.fa-brands,i.fab,i.fa-brands{font-family:"Font Awesome 6 Brands"!important;font-weight:400!important;}

    /* ════════════════════════════════════════════
       SKIP LINK
    ════════════════════════════════════════════ */
    .ms-skip-link {
        position: fixed; top: -100px; left: 1rem; z-index: 99999;
        padding: 0.6rem 1.2rem;
        background: var(--teal); color: var(--navy);
        border-radius: var(--radius-pill); font-weight: 600;
        transition: top 0.2s;
    }
    .ms-skip-link:focus { top: 1rem; }

    /* ════════════════════════════════════════════
       TOP-BAR / NAV
    ════════════════════════════════════════════ */
    #ms-topbar {
        position: fixed; top: 0; left: 0; right: 0; z-index: 1050;
        height: 66px;
        background: rgba(6,13,31,0.85);
        backdrop-filter: blur(24px) saturate(180%);
        -webkit-backdrop-filter: blur(24px) saturate(180%);
        border-bottom: 1px solid var(--border);
        box-shadow: var(--shadow-nav);
        transition: background var(--transition), box-shadow var(--transition);
    }
    #ms-topbar.scrolled {
        background: rgba(6,13,31,0.96);
        box-shadow: 0 1px 0 rgba(255,255,255,0.04), 0 8px 32px rgba(0,0,0,0.5);
    }
    .ms-topbar-inner {
        max-width: 1400px; margin: 0 auto;
        height: 100%; display: flex; align-items: center;
        padding: 0 1.5rem; gap: 1rem;
    }

    /* Brand */
    .ms-brand {
        display: flex; align-items: center; gap: 0.55rem;
        font-family: var(--font-display); font-size: 1.3rem; font-weight: 600;
        color: var(--white); flex-shrink: 0; transition: opacity var(--transition);
    }
    .ms-brand:hover { opacity: 0.85; }
    .ms-brand-icon {
        width: 32px; height: 32px; border-radius: 8px;
        background: var(--teal);
        display: flex; align-items: center; justify-content: center;
        color: var(--navy); font-size: 0.875rem;
        box-shadow: 0 0 16px rgba(0,212,170,0.35);
    }
    .ms-brand-text em { color: var(--teal); font-style: normal; }

    /* Guest nav links */
    .ms-nav-links {
        display: flex; align-items: center; gap: 0.25rem;
        margin-left: 1.5rem;
    }
    .ms-nav-link {
        padding: 0.45rem 0.875rem; border-radius: var(--radius-sm);
        font-size: 0.875rem; font-weight: 400; color: var(--muted);
        transition: all var(--transition); position: relative;
    }
    .ms-nav-link:hover { color: var(--white); background: var(--glass); }
    .ms-nav-link.active { color: var(--teal); }
    .ms-nav-link::after {
        content: ''; position: absolute; bottom: 4px; left: 50%; right: 50%;
        height: 1.5px; background: var(--teal); border-radius: 1px;
        transition: left var(--transition), right var(--transition);
    }
    .ms-nav-link:hover::after { left: 0.875rem; right: 0.875rem; }

    /* Spacer */
    .ms-topbar-spacer { flex: 1; }

    /* System online pill */
    .ms-status-pill {
        display: flex; align-items: center; gap: 0.4rem;
        padding: 0.3rem 0.8rem;
        background: rgba(34,197,94,0.08);
        border: 1px solid rgba(34,197,94,0.2);
        border-radius: var(--radius-pill);
        font-size: 0.72rem; font-weight: 500; color: var(--green);
    }
    .ms-status-dot {
        width: 6px; height: 6px; border-radius: 50%;
        background: var(--green);
        box-shadow: 0 0 6px var(--green);
        animation: msPulse 2s ease-in-out infinite;
    }
    @keyframes msPulse { 0%,100%{opacity:1;}50%{opacity:0.4;} }

    /* Icon buttons */
    .ms-icon-btn {
        width: 40px; height: 40px;
        display: flex; align-items: center; justify-content: center;
        background: var(--glass); border: 1px solid var(--border);
        border-radius: 50%; color: var(--muted); font-size: 1rem;
        cursor: pointer; transition: all var(--transition);
        position: relative; flex-shrink: 0;
    }
    .ms-icon-btn:hover { border-color: var(--border-hover); color: var(--white); background: rgba(255,255,255,0.06); }

    /* Notification badge */
    .ms-notif-badge {
        position: absolute; top: -3px; right: -3px;
        min-width: 16px; height: 16px;
        background: #ef4444; color: #fff;
        font-size: 0.6rem; font-weight: 700;
        border-radius: 50px; padding: 0 4px;
        align-items: center; justify-content: center;
        border: 2px solid var(--navy);
        display: none;
    }

    /* User chip */
    .ms-user-chip {
        display: flex; align-items: center; gap: 0.5rem;
        padding: 0.35rem 0.875rem 0.35rem 0.5rem;
        background: var(--glass); border: 1px solid var(--border);
        border-radius: var(--radius-pill); cursor: pointer;
        transition: all var(--transition); color: var(--offwhite);
        font-family: var(--font-body);
    }
    .ms-user-chip:hover { border-color: var(--border-hover); background: rgba(255,255,255,0.06); }
    .ms-user-avatar {
        width: 28px; height: 28px;
        background: var(--teal-dim); border: 1px solid var(--teal-border);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: var(--teal); font-size: 0.875rem;
    }
    .ms-user-name { font-size: 0.8125rem; font-weight: 500; }

    /* Guest auth buttons */
    .ms-btn-ghost-sm {
        padding: 0.4rem 1rem;
        background: transparent; border: 1px solid var(--border);
        border-radius: var(--radius-pill); color: var(--offwhite);
        font-size: 0.84375rem; font-weight: 400;
        transition: all var(--transition); display: inline-flex; align-items: center; gap: 0.4rem;
    }
    .ms-btn-ghost-sm:hover { border-color: var(--border-hover); color: var(--white); background: var(--glass); }
    .ms-btn-teal-sm {
        padding: 0.4rem 1rem;
        background: var(--teal); border: 1px solid var(--teal);
        border-radius: var(--radius-pill); color: var(--navy);
        font-size: 0.84375rem; font-weight: 600;
        transition: all var(--transition); display: inline-flex; align-items: center; gap: 0.4rem;
    }
    .ms-btn-teal-sm:hover { box-shadow: 0 0 20px var(--teal-glow); transform: translateY(-1px); }

    /* Mobile hamburger */
    .ms-hamburger {
        width: 40px; height: 40px;
        display: none; align-items: center; justify-content: center;
        background: var(--glass); border: 1px solid var(--border);
        border-radius: 50%; cursor: pointer; transition: all var(--transition);
        flex-direction: column; gap: 5px; flex-shrink: 0;
    }
    .ms-hamburger:hover { border-color: var(--border-hover); }
    .ms-ham-line {
        width: 16px; height: 1.5px; background: var(--offwhite);
        border-radius: 2px; transition: all var(--transition);
    }
    .ms-hamburger.open .ms-ham-line:nth-child(1) { transform: translateY(6.5px) rotate(45deg); }
    .ms-hamburger.open .ms-ham-line:nth-child(2) { opacity: 0; transform: scaleX(0); }
    .ms-hamburger.open .ms-ham-line:nth-child(3) { transform: translateY(-6.5px) rotate(-45deg); }

    /* ── Dropdown menus ── */
    .ms-dropdown { position: relative; }
    .ms-dropdown-menu {
        position: absolute; top: calc(100% + 8px); right: 0;
        min-width: 220px;
        background: rgba(12,22,51,0.97);
        backdrop-filter: blur(20px);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        box-shadow: 0 20px 50px rgba(0,0,0,0.5), 0 0 0 1px rgba(0,212,170,0.05);
        padding: 0.5rem;
        opacity: 0; visibility: hidden; transform: translateY(-8px);
        transition: all var(--transition);
        z-index: 9999;
    }
    .ms-dropdown-menu.show { opacity: 1; visibility: visible; transform: translateY(0); }
    .ms-dropdown-item {
        display: flex; align-items: center; gap: 0.6rem;
        padding: 0.6rem 0.875rem; border-radius: var(--radius-sm);
        font-size: 0.84375rem; color: var(--offwhite);
        transition: all var(--transition); cursor: pointer;
        border: 1px solid transparent;
    }
    .ms-dropdown-item:hover { background: var(--teal-dim); color: var(--teal); border-color: rgba(0,212,170,0.15); transform: translateX(2px); }
    .ms-dropdown-item i { width: 1rem; text-align: center; font-size: 0.875rem; color: var(--teal); flex-shrink: 0; }
    .ms-dropdown-item.danger { color: var(--red); }
    .ms-dropdown-item.danger i { color: var(--red); }
    .ms-dropdown-item.danger:hover { background: rgba(248,113,113,0.08); border-color: rgba(248,113,113,0.15); }
    .ms-dropdown-divider { height: 1px; background: var(--border); margin: 0.375rem 0; }
    .ms-dropdown-header { padding: 0.5rem 0.875rem; font-size: 0.68rem; font-weight: 500; color: var(--muted); text-transform: uppercase; letter-spacing: 0.1em; }

    /* Notifications dropdown */
    .ms-notif-menu { min-width: 340px; max-height: 380px; overflow-y: auto; }
    .ms-notif-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.875rem 1rem; border-bottom: 1px solid var(--border); margin-bottom: 0.25rem;
    }
    .ms-notif-title { font-size: 0.875rem; font-weight: 500; color: var(--white); }
    .ms-notif-actions { display: flex; gap: 0.25rem; }
    .ms-notif-action-btn {
        width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;
        border-radius: var(--radius-sm); background: transparent; border: 1px solid transparent;
        color: var(--muted); font-size: 0.875rem; cursor: pointer; transition: all var(--transition);
    }
    .ms-notif-action-btn:hover { background: var(--glass); border-color: var(--border); color: var(--teal); }
    .ms-notif-empty {
        display: flex; flex-direction: column; align-items: center;
        padding: 2rem; color: var(--muted); text-align: center; gap: 0.5rem;
    }
    .ms-notif-empty i { font-size: 1.5rem; opacity: 0.4; }
    .ms-notif-empty span { font-size: 0.8125rem; }

    /* ── Mobile Nav Drawer ── */
    .ms-mobile-overlay {
        position: fixed; inset: 0; z-index: 1060;
        background: rgba(6,13,31,0.6); backdrop-filter: blur(4px);
        opacity: 0; visibility: hidden; transition: all var(--transition);
    }
    .ms-mobile-overlay.show { opacity: 1; visibility: visible; }
    .ms-mobile-drawer {
        position: fixed; top: 0; right: -320px; bottom: 0; width: 300px; z-index: 1061;
        background: var(--navy-mid); border-left: 1px solid var(--border);
        box-shadow: -20px 0 60px rgba(0,0,0,0.5);
        transition: right var(--transition);
        display: flex; flex-direction: column; overflow-y: auto;
    }
    .ms-mobile-drawer.show { right: 0; }
    .ms-drawer-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border); flex-shrink: 0;
    }
    .ms-drawer-close {
        width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;
        border-radius: 50%; background: var(--glass); border: 1px solid var(--border);
        color: var(--muted); cursor: pointer; transition: all var(--transition);
    }
    .ms-drawer-close:hover { color: var(--white); border-color: var(--border-hover); }
    .ms-drawer-nav { padding: 1rem; flex: 1; }
    .ms-drawer-link {
        display: flex; align-items: center; gap: 0.75rem;
        padding: 0.75rem 1rem; border-radius: var(--radius-md);
        font-size: 0.9rem; color: var(--muted); margin-bottom: 0.25rem;
        transition: all var(--transition); border: 1px solid transparent;
    }
    .ms-drawer-link i { color: var(--teal); font-size: 0.875rem; flex-shrink: 0; }
    .ms-drawer-link:hover { background: var(--teal-dim); color: var(--white); border-color: rgba(0,212,170,0.15); }
    .ms-drawer-footer { padding: 1rem 1.5rem; border-top: 1px solid var(--border); flex-shrink: 0; }
    .ms-drawer-btn {
        display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        width: 100%; padding: 0.75rem; border-radius: var(--radius-md);
        font-size: 0.875rem; font-weight: 600;
        background: var(--teal); color: var(--navy); border: none; cursor: pointer;
        transition: all var(--transition); margin-bottom: 0.5rem; font-family: var(--font-body);
    }
    .ms-drawer-btn:hover { box-shadow: 0 0 20px var(--teal-glow); }
    .ms-drawer-btn-ghost {
        background: var(--glass); color: var(--offwhite); border: 1px solid var(--border);
    }
    .ms-drawer-btn-ghost:hover { border-color: var(--border-hover); box-shadow: none; }

    /* ── Impersonation Banners ── */
    .ms-banner {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.5rem 1.5rem; font-size: 0.8125rem; border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    .ms-banner-chain   { background: rgba(99,102,241,0.15); color: #a5b4fc; }
    .ms-banner-hospital{ background: rgba(245,158,11,0.12); color: var(--amber); }
    .ms-banner-admin   { background: rgba(248,113,113,0.12); color: var(--red); }
    .ms-banner-left    { display: flex; align-items: center; gap: 0.6rem; }
    .ms-banner-actions { display: flex; gap: 0.5rem; }
    .ms-banner-btn {
        padding: 0.25rem 0.75rem; border-radius: var(--radius-pill);
        border: 1px solid currentColor; background: transparent; color: inherit;
        font-size: 0.75rem; font-weight: 500; cursor: pointer;
        transition: all var(--transition); white-space: nowrap; font-family: var(--font-body);
    }
    .ms-banner-btn:hover { background: rgba(255,255,255,0.1); }

    /* ── Flash Messages ── */
    .ms-flash-wrap { position: relative; z-index: 1045; }
    .ms-flash {
        display: flex; align-items: center; gap: 0.6rem;
        padding: 0.875rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.05);
        font-size: 0.875rem; animation: flashSlide 0.3s ease-out;
    }
    @keyframes flashSlide { from{opacity:0;transform:translateY(-8px);}to{opacity:1;transform:none;} }
    .ms-flash-success { background: rgba(34,197,94,0.1); color: #86efac; }
    .ms-flash-error   { background: rgba(248,113,113,0.1); color: #fca5a5; }
    .ms-flash-warning { background: rgba(245,158,11,0.1); color: #fcd34d; }
    .ms-flash-info    { background: rgba(0,212,170,0.08); color: var(--teal); }
    .ms-flash-close {
        margin-left: auto; background: none; border: none; color: inherit;
        opacity: 0.6; cursor: pointer; font-size: 1rem; padding: 0; line-height: 1;
        transition: opacity var(--transition);
    }
    .ms-flash-close:hover { opacity: 1; }

    /* ── Loading Toast ── */
    .ms-loading-toast {
        position: fixed; top: 80px; right: 1.5rem; z-index: 9998;
        display: flex; align-items: center; gap: 0.6rem; padding: 0.6rem 1rem;
        background: var(--navy-card); border: 1px solid var(--border);
        border-radius: var(--radius-pill); box-shadow: 0 8px 24px rgba(0,0,0,0.4);
        font-size: 0.8125rem; color: var(--teal);
        opacity: 0; transform: translateY(-8px); transition: all var(--transition); pointer-events: none;
    }
    .ms-loading-toast.show { opacity: 1; transform: translateY(0); }
    .ms-loading-spin {
        width: 14px; height: 14px;
        border: 1.5px solid rgba(0,212,170,0.3); border-top-color: var(--teal);
        border-radius: 50%; animation: msSpin 0.8s linear infinite;
    }
    @keyframes msSpin { to { transform: rotate(360deg); } }

    /* ── Skeleton ── */
    .ms-skeleton {
        background: linear-gradient(90deg,rgba(0,212,170,0.05) 25%,rgba(0,212,170,0.08) 50%,rgba(0,212,170,0.05) 75%);
        background-size: 200% 100%; animation: msSkeleton 1.6s ease-in-out infinite; border-radius: var(--radius-md);
        border: 1px solid rgba(0,212,170,0.06);
    }
    @keyframes msSkeleton { 0%{background-position:200% 0;}100%{background-position:-200% 0;} }

    /* ── Main content ── */
    #ms-main { padding-top: 66px; min-height: 100vh; }

    /* ── Footer ── */
    .ms-footer { background: var(--navy-mid); border-top: 1px solid var(--border); padding: 4rem 0 2rem; }
    .ms-footer-grid {
        max-width: 1200px; margin: 0 auto; padding: 0 2rem;
        display: grid; grid-template-columns: 1.5fr 1fr 1fr 1.5fr; gap: 3rem; margin-bottom: 3rem;
    }
    .ms-footer-brand p { font-size: 0.84375rem; color: var(--muted); margin-top: 0.75rem; line-height: 1.7; }
    .ms-footer-socials { display: flex; gap: 0.5rem; margin-top: 1.25rem; }
    .ms-footer-social {
        width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;
        border-radius: 50%; background: var(--glass); border: 1px solid var(--border);
        color: var(--muted); font-size: 0.875rem; transition: all var(--transition);
    }
    .ms-footer-social:hover { background: var(--teal-dim); border-color: var(--teal-border); color: var(--teal); transform: translateY(-2px); }
    .ms-footer-col h5 { font-size: 0.75rem; font-weight: 500; letter-spacing: 0.1em; text-transform: uppercase; color: var(--white); margin-bottom: 1rem; }
    .ms-footer-col a { display: block; font-size: 0.84375rem; color: var(--muted); margin-bottom: 0.5rem; transition: color var(--transition); }
    .ms-footer-col a:hover { color: var(--teal); }
    .ms-footer-contact-item { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem; }
    .ms-footer-contact-icon {
        width: 36px; height: 36px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        background: var(--teal-dim); border: 1px solid var(--teal-border);
        border-radius: 50%; color: var(--teal); font-size: 0.875rem;
    }
    .ms-footer-contact-label { font-size: 0.7rem; color: var(--muted); }
    .ms-footer-contact-val { font-size: 0.84375rem; color: var(--offwhite); }
    .ms-footer-bottom {
        max-width: 1200px; margin: 0 auto; padding: 1.5rem 2rem 0;
        border-top: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;
    }
    .ms-footer-bottom p { font-size: 0.8125rem; color: var(--muted); margin: 0; }
    .ms-footer-bottom-links { display: flex; gap: 1.5rem; }
    .ms-footer-bottom-links a { font-size: 0.8125rem; color: var(--muted); transition: color var(--transition); }
    .ms-footer-bottom-links a:hover { color: var(--teal); }

    /* ── Go-to-top ── */
    #ms-gototop {
        position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 900;
        width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;
        background: var(--teal); color: var(--navy); border: none;
        border-radius: 50%; cursor: pointer; font-size: 0.875rem;
        box-shadow: 0 4px 16px rgba(0,212,170,0.3);
        opacity: 0; transform: translateY(10px) scale(0.9); transition: all var(--transition); pointer-events: none;
    }
    #ms-gototop.visible { opacity: 1; transform: none; pointer-events: auto; }
    #ms-gototop:hover { box-shadow: 0 6px 24px rgba(0,212,170,0.5); transform: translateY(-2px); }

    /* ── Responsive ── */
    @media (max-width: 991px) {
        .ms-nav-links { display: none; }
        .ms-hamburger { display: flex; }
        .ms-status-pill { display: none; }
        .ms-user-name { display: none; }
    }
    @media (max-width: 600px) {
        .ms-footer-grid { grid-template-columns: 1fr 1fr; gap: 2rem; }
        .ms-footer-brand { grid-column: span 2; }
        .ms-footer-bottom { flex-direction: column; text-align: center; }
    }
    @media (max-width: 400px) {
        .ms-footer-grid { grid-template-columns: 1fr; }
        .ms-footer-brand { grid-column: span 1; }
    }
    </style>
</head>

<body>

<a href="#ms-main" class="ms-skip-link">Skip to content</a>

<!-- ═══ TOPBAR ═══ -->
<nav id="ms-topbar" aria-label="Main navigation">
    <div class="ms-topbar-inner">

        <a href="{{ url('/') }}" class="ms-brand">
            <span class="ms-brand-icon"><i class="bi bi-heart-pulse-fill" aria-hidden="true"></i></span>
            <span class="ms-brand-text">MedSuite<em>AI</em></span>
        </a>

        @guest
        <div class="ms-nav-links" role="navigation">
            <a href="{{ url('/') }}" class="ms-nav-link {{ request()->is('/') ? 'active' : '' }}">Home</a>
            <a href="{{ route('about') }}" class="ms-nav-link {{ request()->routeIs('about') ? 'active' : '' }}">About</a>
            <a href="{{ route('contact') }}" class="ms-nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
            <a href="{{ route('doctors.index') }}" class="ms-nav-link {{ request()->routeIs('doctors.*') ? 'active' : '' }}">Find Doctors</a>
        </div>
        @endguest

        <div class="ms-topbar-spacer"></div>

        @auth
        <div class="ms-status-pill">
            <span class="ms-status-dot" aria-hidden="true"></span>
            AI Online
        </div>

        <!-- Mobile sidebar -->
        <button id="ms-sidebar-toggle" class="ms-icon-btn d-lg-none" type="button" aria-label="Toggle sidebar">
            <i class="fa-solid fa-bars" aria-hidden="true"></i>
        </button>

        <!-- Notifications -->
        <div class="ms-dropdown">
            <button class="ms-icon-btn" id="ms-notif-toggle" type="button"
                    aria-label="Notifications" aria-expanded="false" aria-haspopup="true">
                <i class="bi bi-bell" aria-hidden="true"></i>
                <span class="ms-notif-badge" id="notification-count" aria-label="unread notifications">0</span>
            </button>
            <div class="ms-dropdown-menu ms-notif-menu" id="ms-notif-menu" role="menu">
                <div class="ms-notif-header">
                    <span class="ms-notif-title">Notifications</span>
                    <div class="ms-notif-actions">
                        <button class="ms-notif-action-btn mark-all-read-btn" title="Mark all read" aria-label="Mark all as read">
                            <i class="bi bi-check-all" aria-hidden="true"></i>
                        </button>
                        <button class="ms-notif-action-btn view-all-btn" title="View all" aria-label="View all">
                            <i class="bi bi-list-ul" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
                <div class="notification-list" id="notification-list" role="list" aria-live="polite">
                    <div class="ms-notif-empty">
                        <i class="bi bi-bell-slash" aria-hidden="true"></i>
                        <span>Loading notifications…</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Theme Toggle -->
        <button data-theme-toggle class="ms-icon-btn" type="button" aria-label="Switch to light theme" title="Toggle theme">
            <i data-theme-icon class="fas fa-sun" aria-hidden="true"></i>
        </button>

        <!-- User menu -->
        <div class="ms-dropdown">
            <button class="ms-user-chip" id="ms-user-toggle" type="button" aria-expanded="false" aria-haspopup="true">
                <span class="ms-user-avatar"><i class="bi bi-person" aria-hidden="true"></i></span>
                <span class="ms-user-name">{{ Auth::user()->name }}</span>
                <i class="bi bi-chevron-down" style="font-size:0.65rem;color:var(--muted)" aria-hidden="true"></i>
            </button>
            <div class="ms-dropdown-menu" id="ms-user-menu" role="menu">
                <div class="ms-dropdown-header">{{ Auth::user()->name }}</div>

                @if(Auth::guard('admin')->check())
                <a class="ms-dropdown-item" href="{{ route('admin.dashboard') }}" role="menuitem">
                    <i class="bi bi-shield-check"></i> Admin Dashboard
                </a>
                <a class="ms-dropdown-item" href="{{ route('admin.users.index') }}" role="menuitem">
                    <i class="bi bi-people"></i> Manage Users
                </a>
                <div class="ms-dropdown-divider"></div>
                @endif

                @if(Auth::user()->isDoctor())
                <a class="ms-dropdown-item" href="{{ route('settings') }}" role="menuitem"><i class="bi bi-gear"></i> Settings</a>
                <a class="ms-dropdown-item" href="{{ route('doctor.sms-settings') }}" role="menuitem"><i class="fas fa-sms"></i> SMS Settings</a>
                <a class="ms-dropdown-item" href="{{ route('doctor.profile.edit') }}" role="menuitem"><i class="fas fa-user-edit"></i> Edit Profile</a>
                @if(Auth::user()->isMainUser())
                <div class="ms-dropdown-divider"></div>
                <a class="ms-dropdown-item" href="{{ route('sub-users.index') }}" role="menuitem"><i class="fas fa-users"></i> Sub-Users</a>
                @endif
                @endif

                <div class="ms-dropdown-divider"></div>
                <a class="ms-dropdown-item" href="{{ route('notifications.settings') }}" role="menuitem">
                    <i class="bi bi-bell-slash"></i> Notification Settings
                </a>
                <div class="ms-dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="ms-dropdown-item danger" role="menuitem"
                            style="width:100%;text-align:left;background:none;border:none;cursor:pointer;font-family:var(--font-body);font-size:0.84375rem;">
                        <i class="bi bi-box-arrow-right"></i> Sign Out
                    </button>
                </form>
            </div>
        </div>

        @else
        <!-- Guest buttons -->
        <div style="display:flex;align-items:center;gap:0.5rem;flex-shrink:0;">
            <a href="{{ route('login') }}" class="ms-btn-ghost-sm">
                <i class="bi bi-box-arrow-in-right" aria-hidden="true"></i>
                <span class="d-none d-sm-inline">Sign In</span>
            </a>
            <a href="{{ route('register') }}" class="ms-btn-teal-sm">
                <i class="bi bi-person-plus" aria-hidden="true"></i>
                <span class="d-none d-sm-inline">Register</span>
            </a>
        </div>

        <button class="ms-hamburger" id="ms-hamburger" aria-label="Open menu" aria-expanded="false">
            <span class="ms-ham-line" aria-hidden="true"></span>
            <span class="ms-ham-line" aria-hidden="true"></span>
            <span class="ms-ham-line" aria-hidden="true"></span>
        </button>
        @endauth

    </div>
</nav>

<!-- ═══ MOBILE DRAWER (guest) ═══ -->
@guest
<div class="ms-mobile-overlay" id="ms-overlay" aria-hidden="true"></div>
<div class="ms-mobile-drawer" id="ms-drawer" role="dialog" aria-label="Navigation">
    <div class="ms-drawer-header">
        <div class="ms-brand">
            <span class="ms-brand-icon"><i class="bi bi-heart-pulse-fill" aria-hidden="true"></i></span>
            <span class="ms-brand-text">MedSuite<em>AI</em></span>
        </div>
        <button class="ms-drawer-close" id="ms-drawer-close" aria-label="Close"><i class="bi bi-x-lg"></i></button>
    </div>
    <nav class="ms-drawer-nav">
        <a href="{{ url('/') }}" class="ms-drawer-link"><i class="bi bi-house"></i> Home</a>
        <a href="{{ route('about') }}" class="ms-drawer-link"><i class="bi bi-info-circle"></i> About Us</a>
        <a href="{{ route('contact') }}" class="ms-drawer-link"><i class="bi bi-envelope"></i> Contact</a>
        <a href="{{ route('doctors.index') }}" class="ms-drawer-link"><i class="bi bi-person-badge"></i> Find Doctors</a>
    </nav>
    <div class="ms-drawer-footer">
        <a href="{{ route('register') }}"><button class="ms-drawer-btn"><i class="bi bi-person-plus"></i> Create Account</button></a>
        <a href="{{ route('login') }}"><button class="ms-drawer-btn ms-drawer-btn-ghost"><i class="bi bi-box-arrow-in-right"></i> Sign In</button></a>
    </div>
</div>
@endguest

<!-- ═══ SIDEBAR (auth) ═══ -->
@auth
<link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
@include('layouts.sidebar')
@endauth

<!-- ═══ IMPERSONATION BANNERS ═══ -->
@if(session('impersonating_admin_id') && session('impersonating_hospital_admin_id') && session('hospital_admin_impersonation_started_at') && auth()->check() && auth()->user()->isDoctor())
<div class="ms-banner ms-banner-chain" role="alert">
    <div class="ms-banner-left"><i class="fas fa-users"></i>
        <strong>Chain:</strong> {{ session('impersonating_admin_name') }} → {{ session('impersonating_hospital_admin_name') }} → Dr. {{ auth()->user()->name }}
    </div>
    <div class="ms-banner-actions">
        <form method="POST" action="{{ route('return-to-hospital-admin') }}" style="display:inline">@csrf
            <button type="submit" class="ms-banner-btn"><i class="fas fa-arrow-left"></i> Return to Hospital Admin</button>
        </form>
        <form method="POST" action="{{ route('return-to-admin') }}" style="display:inline">@csrf
            <button type="submit" class="ms-banner-btn"><i class="fas fa-arrow-up"></i> Return to Admin</button>
        </form>
    </div>
</div>
@elseif(session('impersonating_hospital_admin_id') && empty(session('impersonating_admin_id')) && auth()->check() && auth()->user()->isDoctor())
<div class="ms-banner ms-banner-hospital" role="alert">
    <div class="ms-banner-left"><i class="fas fa-user-shield"></i>
        <strong>Hospital Admin:</strong> {{ session('impersonating_hospital_admin_name') }} → Dr. {{ auth()->user()->name }}
    </div>
    <form method="POST" action="{{ route('return-to-hospital-admin') }}">@csrf
        <button type="submit" class="ms-banner-btn"><i class="fas fa-arrow-left"></i> Return</button>
    </form>
</div>
@elseif(session('impersonating_admin_id') && session('impersonating_user_id') && session('admin_impersonation_started_at') && empty(session('hospital_admin_impersonation_started_at')))
@php $impUser = auth()->user() ?? \App\Models\User::find(session('impersonating_user_id')); @endphp
<div class="ms-banner ms-banner-admin" role="alert">
    <div class="ms-banner-left"><i class="fas fa-user-shield"></i>
        <strong>Admin view:</strong> {{ session('impersonating_admin_name') }} → {{ $impUser?->name }}
    </div>
    <form method="POST" action="{{ route('return-to-admin') }}">@csrf
        <button type="submit" class="ms-banner-btn"><i class="fas fa-arrow-left"></i> Return</button>
    </form>
</div>
@endif

<!-- ═══ FLASH MESSAGES ═══ -->
<div class="ms-flash-wrap" aria-live="polite" aria-atomic="true">
    @if(session('success'))
    <div class="ms-flash ms-flash-success" role="alert">
        <i class="fas fa-check-circle"></i> <strong>Success —</strong> {{ session('success') }}
        <button class="ms-flash-close" onclick="this.parentElement.remove()" aria-label="Dismiss">×</button>
    </div>
    @endif
    @if(session('error'))
    <div class="ms-flash ms-flash-error" role="alert">
        <i class="fas fa-exclamation-circle"></i> <strong>Error —</strong> {{ session('error') }}
        <button class="ms-flash-close" onclick="this.parentElement.remove()" aria-label="Dismiss">×</button>
    </div>
    @endif
    @if(session('warning'))
    <div class="ms-flash ms-flash-warning" role="alert">
        <i class="fas fa-exclamation-triangle"></i> <strong>Warning —</strong> {{ session('warning') }}
        <button class="ms-flash-close" onclick="this.parentElement.remove()" aria-label="Dismiss">×</button>
    </div>
    @endif
    @if(session('info'))
    <div class="ms-flash ms-flash-info" role="alert">
        <i class="fas fa-info-circle"></i> <strong>Info —</strong> {{ session('info') }}
        <button class="ms-flash-close" onclick="this.parentElement.remove()" aria-label="Dismiss">×</button>
    </div>
    @endif
</div>

<!-- Loading toast -->
<div class="ms-loading-toast" id="ms-loading-toast" aria-live="polite">
    <div class="ms-loading-spin" aria-hidden="true"></div>
    <span>Loading…</span>
</div>

<!-- ═══ MAIN ═══ -->
<div id="ms-main">
    <main id="main-content" class="dashboard-container">
        <div class="app-main">
            @yield('content')
        </div>
    </main>
</div>

<!-- ═══ FOOTER (guest) ═══ -->
@guest
<footer class="ms-footer" role="contentinfo">
    <div class="ms-footer-grid">
        <div class="ms-footer-brand">
            <div class="ms-brand">
                <span class="ms-brand-icon"><i class="bi bi-heart-pulse-fill" aria-hidden="true"></i></span>
                <span class="ms-brand-text">MedSuite<em>AI</em></span>
            </div>
            <p>AI-powered clinical decision support for modern healthcare professionals. Secure, HIPAA-compliant, built for exceptional patient outcomes.</p>
            <div class="ms-footer-socials">
                <a href="#" class="ms-footer-social" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="ms-footer-social" aria-label="X / Twitter"><i class="bi bi-twitter-x"></i></a>
                <a href="#" class="ms-footer-social" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                <a href="#" class="ms-footer-social" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
            </div>
        </div>
        <div class="ms-footer-col">
            <h5>Platform</h5>
            <a href="{{ url('/') }}">Home</a>
            <a href="{{ route('about') }}">About Us</a>
            <a href="{{ route('contact') }}">Contact</a>
            <a href="{{ route('login') }}">Sign In</a>
        </div>
        <div class="ms-footer-col">
            <h5>Support</h5>
            <a href="{{ route('about') }}">Platform Overview</a>
            <a href="{{ route('contact') }}">Contact Support</a>
            <a href="#">Documentation</a>
            <a href="{{ route('admin.login') }}" style="font-size:0.75rem;opacity:0.45;">Admin Portal</a>
        </div>
        <div class="ms-footer-col">
            <h5>Security & Trust</h5>
            <div class="ms-footer-contact-item">
                <div class="ms-footer-contact-icon"><i class="bi bi-shield-check"></i></div>
                <div>
                    <div class="ms-footer-contact-label">Compliance</div>
                    <div class="ms-footer-contact-val">HIPAA Compliant & Encrypted</div>
                </div>
            </div>
            <div class="ms-footer-contact-item">
                <div class="ms-footer-contact-icon"><i class="bi bi-headset"></i></div>
                <div>
                    <div class="ms-footer-contact-label">Support</div>
                    <div class="ms-footer-contact-val">AI-Powered 24 / 7</div>
                </div>
            </div>
            <a href="{{ route('contact') }}" class="ms-btn-teal-sm" style="margin-top:0.5rem;width:fit-content;">
                <i class="bi bi-chat-dots"></i> Get Support
            </a>
        </div>
    </div>
    <div class="ms-footer-bottom">
        <p>&copy; {{ date('Y') }} MedSuite AI. All rights reserved.</p>
        <div class="ms-footer-bottom-links">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
            <a href="#">HIPAA Compliance</a>
        </div>
    </div>
</footer>
@endguest

<button id="ms-gototop" aria-label="Back to top" title="Back to top">
    <i class="bi bi-chevron-up" aria-hidden="true"></i>
</button>

@stack('modals')

<!-- ═══ SCRIPTS ═══ -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/morphext@2.4.7/dist/morphext.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12/lib/typed.min.js"></script>
<script src="{{ asset('js/functions.bundle.js?v=2') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>

@auth
<script src="{{ asset('js/sidebar.js') }}" defer></script>
@endauth

@viteReactRefresh
@vite(['resources/js/app.js', 'resources/css/app.css'])

@include('notifications._styles')
<script src="{{ asset('js/notification-manager.js?v=' . time()) }}"></script>
<script src="{{ asset('sounds/notification-sound.js?v=' . time()) }}"></script>
<script src="{{ asset('js/notification-accessibility-test.js?v=' . time()) }}"></script>

@if(config('app.debug'))
<meta name="app-debug" content="true">
<script src="{{ asset('js/notification-debug.js') }}"></script>
<script src="{{ asset('js/notification-diagnostics.js') }}"></script>
<script src="{{ asset('js/pusher-raw-debug.js') }}"></script>
<script src="{{ asset('js/laravel-notification-catcher.js') }}"></script>
<script src="{{ asset('js/appointment-notification-debug.js') }}"></script>
<script src="{{ asset('js/backend-diagnosis.js') }}"></script>
<script src="{{ asset('js/websocket-test.js') }}"></script>
<script src="{{ asset('js/pusher-connection-test.js') }}"></script>
@endif

<script>
(function () {
    'use strict';

    /* ── Navbar scroll ── */
    const topbar = document.getElementById('ms-topbar');
    window.addEventListener('scroll', () => topbar.classList.toggle('scrolled', window.scrollY > 20), { passive: true });

    /* ── Go-to-top ── */
    const gototop = document.getElementById('ms-gototop');
    if (gototop) {
        window.addEventListener('scroll', () => gototop.classList.toggle('visible', window.scrollY > 400), { passive: true });
        gototop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    }

    /* ── Custom dropdowns ── */
    function initDropdown(toggleId, menuId) {
        const toggle = document.getElementById(toggleId);
        const menu   = document.getElementById(menuId);
        if (!toggle || !menu) return;
        toggle.addEventListener('click', e => {
            e.stopPropagation();
            const open = menu.classList.contains('show');
            closeAllDropdowns();
            if (!open) { menu.classList.add('show'); toggle.setAttribute('aria-expanded', 'true'); }
        });
    }
    function closeAllDropdowns() {
        document.querySelectorAll('.ms-dropdown-menu').forEach(m => m.classList.remove('show'));
        document.querySelectorAll('[aria-expanded]').forEach(t => t.setAttribute('aria-expanded', 'false'));
    }
    document.addEventListener('click', closeAllDropdowns);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeAllDropdowns(); });

    initDropdown('ms-notif-toggle', 'ms-notif-menu');
    initDropdown('ms-user-toggle', 'ms-user-menu');

    /* Load notifications when panel opens */
    const notifToggle = document.getElementById('ms-notif-toggle');
    if (notifToggle) {
        notifToggle.addEventListener('click', () => {
            const m = document.getElementById('ms-notif-menu');
            if (m && m.classList.contains('show')) loadNotifications();
        });
    }

    /* ── Mobile drawer ── */
    const hamburger = document.getElementById('ms-hamburger');
    const overlay   = document.getElementById('ms-overlay');
    const drawer    = document.getElementById('ms-drawer');
    const drawerClose = document.getElementById('ms-drawer-close');

    const openDrawer  = () => { drawer?.classList.add('show'); overlay?.classList.add('show'); hamburger?.classList.add('open'); hamburger?.setAttribute('aria-expanded','true'); document.body.style.overflow='hidden'; };
    const closeDrawer = () => { drawer?.classList.remove('show'); overlay?.classList.remove('show'); hamburger?.classList.remove('open'); hamburger?.setAttribute('aria-expanded','false'); document.body.style.overflow=''; };

    hamburger?.addEventListener('click', openDrawer);
    drawerClose?.addEventListener('click', closeDrawer);
    overlay?.addEventListener('click', closeDrawer);

    /* ── Auth sidebar toggle ── */
    document.getElementById('ms-sidebar-toggle')?.addEventListener('click', () => {
        document.getElementById('sidebarCollapse')?.click() ||
        document.getElementById('sidebar')?.classList.toggle('active');
    });

    /* ── Loading toast ── */
    const toast = document.getElementById('ms-loading-toast');
    window.showLoadingToast = () => toast?.classList.add('show');
    window.hideLoadingToast = () => toast?.classList.remove('show');

    /* ── AJAX navigation ── */
    $(document).on('click', '.sidebar-nav a[data-ajax="true"]', function (e) {
        e.preventDefault();
        const $link = $(this);
        if ($link.hasClass('active')) return;
        $('.sidebar-nav .nav-link').removeClass('active');
        $link.addClass('active');
        const url = $link.attr('href');
        loadPageContent(url, $link.data('route'));
        history.pushState({ url }, '', url);
    });

    window.addEventListener('popstate', e => { if (e.state?.url) loadPageContent(e.state.url); });

    function loadPageContent(url) {
        showLoadingToast();
        const $main = $('#main-content');
        $main.html([120,80,80,200,60,60,60].map(h =>
            `<div class="ms-skeleton mb-3" style="height:${h}px;width:100%"></div>`
        ).join(''));
        $.ajax({
            url, method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success(response) {
                hideLoadingToast();
                const $tmp = $('<div>').html(response);
                const c = $tmp.find('#main-content').html();
                if (c) { $main.html(c); document.title = $tmp.find('title').text() || document.title; }
                $(document).trigger('pageContentLoaded');
                $('html,body').animate({ scrollTop: 0 }, 250);
            },
            error(xhr) {
                hideLoadingToast();
                if (xhr.status === 0 || xhr.status >= 500) setTimeout(() => { window.location.href = url; }, 1500);
            }
        });
    }

    /* ── Notifications ── */
    function loadNotifications() {
        const list = document.getElementById('notification-list');
        if (!list) return;
        list.innerHTML = `<div class="ms-notif-empty"><div class="ms-loading-spin"></div><span>Loading…</span></div>`;
        fetch('/api/notifications', { credentials: 'same-origin' })
            .then(r => r.json())
            .then(data => {
                if (data.notifications?.length) {
                    list.innerHTML = data.notifications.map(n => `
                        <div class="ms-dropdown-item notification-item ${n.read_at ? '' : 'unread'}"
                             data-id="${n.id}" data-link="${n.data?.link || ''}" tabindex="0" role="listitem">
                            <i class="bi ${n.data?.icon || 'bi-bell'}" style="color:var(--teal)"></i>
                            <div>
                                <div style="font-size:0.8125rem;font-weight:500;color:var(--white)">${n.data?.title || 'Notification'}</div>
                                <div style="font-size:0.75rem;color:var(--muted)">${n.data?.message || ''}</div>
                            </div>
                        </div>`).join('');
                    list.querySelectorAll('.notification-item.unread').forEach(item =>
                        item.addEventListener('click', () => {
                            markAsRead(item.dataset.id);
                            if (item.dataset.link) window.location.href = item.dataset.link;
                        })
                    );
                } else {
                    list.innerHTML = `<div class="ms-notif-empty"><i class="bi bi-bell-slash"></i><span>No notifications</span></div>`;
                }
            })
            .catch(() => {
                list.innerHTML = `<div class="ms-notif-empty"><i class="bi bi-exclamation-triangle"></i><span>Error loading</span></div>`;
            });
    }

    function markAsRead(id) {
        fetch(`/api/notifications/${id}/read`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content }
        }).then(() => updateNotificationBadge());
    }

    function updateNotificationBadge() {
        const badge = document.getElementById('notification-count');
        if (!badge) return;
        fetch('/api/notifications/unread-count', { credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.ok ? r.json() : null)
            .then(data => {
                if (!data) return;
                const c = data.count || 0;
                badge.textContent = c > 99 ? '99+' : c;
                badge.style.display = c > 0 ? 'flex' : 'none';
            })
            .catch(() => { badge.style.display = 'none'; });
    }

    window.updateNotificationBadge = updateNotificationBadge;

    /* ── Polling ── */
    let pollingTimer = null, pollingDelay = 30000;
    function startPolling() {
        clearTimeout(pollingTimer);
        pollingTimer = setTimeout(() => { updateNotificationBadge(); startPolling(); }, pollingDelay);
    }
    document.addEventListener('visibilitychange', () => {
        document.hidden ? clearTimeout(pollingTimer) : startPolling();
    });

    /* ── Init ── */
    document.addEventListener('DOMContentLoaded', () => {
        const isLoginRedirect = new URLSearchParams(location.search).get('login') === 'success';
        setTimeout(() => { updateNotificationBadge(); startPolling(); }, isLoginRedirect ? 5000 : 800);
    });
})();
</script>

<!-- Theme Switcher -->
<script src="{{ asset('js/theme-switcher.js') }}"></script>

@stack('scripts')
</body>
</html>