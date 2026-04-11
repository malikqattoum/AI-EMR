<!DOCTYPE html>
<html lang="{{ $language ?? 'en' }}" dir="{{ ($language ?? 'en') === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $translatedContent['page_title'] ?: $landingPage->getSeoTitle() }}</title>
    <meta name="description" content="{{ $translatedContent['page_description'] ?: $landingPage->getSeoDescription() }}">
    <meta name="robots" content="index, follow">

    <!-- Open Graph -->
    <meta property="og:title" content="{{ $translatedContent['page_title'] ?: $landingPage->getSeoTitle() }}">
    <meta property="og:description" content="{{ $translatedContent['page_description'] ?: $landingPage->getSeoDescription() }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $landingPage->url }}">
    @if($landingPage->hero_image)
    <meta property="og:image" content="{{ Storage::url($landingPage->hero_image) }}">
    @endif

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts: Cormorant Garamond + DM Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400;1,500&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #1a1a2e;
            --accent-color: #c9a227;
            --accent-light: #e8d48a;
            --text-dark: #1a1a2e;
            --text-muted: #6b7280;
            --white: #ffffff;
            --off-white: #faf9f7;
            --glass-bg: rgba(26, 26, 46, 0.85);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            color: var(--text-dark);
            line-height: 1.7;
            overflow-x: hidden;
            background-color: var(--off-white);
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 500;
        }

        /* Grain Texture Overlay */
        .grain-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 9999;
            opacity: 0.04;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
        }

        /* Frosted Glass Navigation */
        .navbar-glass {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
            padding: 1rem 0;
            transition: all 0.4s ease;
        }

        .navbar-glass.scrolled {
            padding: 0.75rem 0;
            background: rgba(26, 26, 46, 0.95);
        }

        .navbar-brand {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--white) !important;
            letter-spacing: 0.02em;
        }

        .navbar-brand .accent {
            color: var(--accent-color);
        }

        .nav-link {
            font-family: 'DM Sans', sans-serif;
            font-weight: 500;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.85) !important;
            letter-spacing: 0.03em;
            padding: 0.5rem 1rem !important;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--accent-color) !important;
        }

        .navbar-toggler {
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.5rem;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255,255,255,0.9)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        .verified-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: rgba(201, 162, 39, 0.15);
            border: 1px solid var(--accent-color);
            color: var(--accent-color);
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.05em;
        }

        /* Cinematic Hero Section */
        .hero-section {
            min-height: 100vh;
            position: relative;
            display: flex;
            align-items: center;
            overflow: hidden;
            background: var(--primary-color);
        }

        .hero-gradient-mesh {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(ellipse at 20% 80%, rgba(201, 162, 39, 0.15) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(201, 162, 39, 0.1) 0%, transparent 40%),
                radial-gradient(ellipse at 50% 50%, rgba(26, 26, 46, 1) 0%, rgba(10, 10, 20, 1) 100%);
            z-index: 1;
        }

        .hero-particles {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 2;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: var(--accent-color);
            border-radius: 50%;
            opacity: 0.3;
            animation: float-particle 15s infinite ease-in-out;
        }

        @keyframes float-particle {
            0%, 100% { transform: translateY(0) translateX(0); opacity: 0.3; }
            50% { transform: translateY(-100px) translateX(50px); opacity: 0.6; }
        }

        .hero-content {
            position: relative;
            z-index: 3;
            color: var(--white);
        }

        .hero-subtitle {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.85rem;
            font-weight: 500;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--accent-color);
            margin-bottom: 1.5rem;
        }

        .hero-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(3rem, 8vw, 5.5rem);
            font-weight: 400;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            letter-spacing: -0.02em;
        }

        .hero-title em {
            font-style: italic;
            color: var(--accent-color);
        }

        .hero-tagline {
            font-size: 1.25rem;
            font-weight: 300;
            opacity: 0.9;
            max-width: 500px;
            margin-bottom: 2.5rem;
            line-height: 1.8;
        }

        .hero-image-wrapper {
            position: relative;
        }

        .hero-image-frame {
            position: relative;
            width: 340px;
            height: 420px;
            margin: 0 auto;
        }

        .hero-image-frame::before {
            content: '';
            position: absolute;
            top: -20px;
            left: -20px;
            right: 20px;
            bottom: 20px;
            border: 2px solid var(--accent-color);
            z-index: -1;
        }

        .hero-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: grayscale(20%);
        }

        .hero-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(201, 162, 39, 0.2), rgba(26, 26, 46, 0.8));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent-color);
            font-size: 4rem;
        }

        .btn-gold {
            background: var(--accent-color);
            color: var(--primary-color);
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            font-size: 0.9rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 1rem 2.5rem;
            border: none;
            border-radius: 0;
            transition: all 0.4s ease;
        }

        .btn-gold:hover {
            background: var(--accent-light);
            color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(201, 162, 39, 0.3);
        }

        .btn-outline-gold {
            background: transparent;
            color: var(--white);
            font-family: 'DM Sans', sans-serif;
            font-weight: 500;
            font-size: 0.9rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 1rem 2.5rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.4s ease;
        }

        .btn-outline-gold:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--accent-color);
            color: var(--accent-color);
        }

        /* Scroll Reveal Animations */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }
        .reveal-delay-3 { transition-delay: 0.3s; }
        .reveal-delay-4 { transition-delay: 0.4s; }

        /* Trust Indicators Section */
        .trust-section {
            background: var(--white);
            padding: 4rem 0;
            border-bottom: 1px solid rgba(26, 26, 46, 0.05);
        }

        .trust-item {
            text-align: center;
            padding: 1.5rem;
        }

        .trust-number {
            font-family: 'Cormorant Garamond', serif;
            font-size: 3.5rem;
            font-weight: 600;
            color: var(--accent-color);
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .trust-label {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-muted);
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        /* About Section with Timeline */
        .about-section {
            background: var(--off-white);
            padding: 8rem 0;
        }

        .section-header {
            text-align: center;
            margin-bottom: 5rem;
        }

        .section-subtitle {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--accent-color);
            margin-bottom: 1rem;
        }

        .section-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2.5rem, 5vw, 3.5rem);
            font-weight: 500;
            color: var(--primary-color);
            line-height: 1.2;
        }

        .about-content {
            position: relative;
        }

        .about-text {
            font-size: 1.1rem;
            color: var(--text-muted);
            margin-bottom: 2.5rem;
            line-height: 2;
        }

        .timeline {
            position: relative;
            padding-left: 3rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, var(--accent-color), rgba(201, 162, 39, 0.2));
        }

        .timeline-item {
            position: relative;
            margin-bottom: 2.5rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -3rem;
            top: 0.25rem;
            width: 12px;
            height: 12px;
            background: var(--accent-color);
            border-radius: 50%;
            transform: translateX(-5px);
        }

        .timeline-year {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--accent-color);
            margin-bottom: 0.25rem;
        }

        .timeline-title {
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }

        .timeline-desc {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        /* Services Cards Section */
        .services-section {
            background: var(--white);
            padding: 8rem 0;
        }

        .service-card {
            background: var(--off-white);
            border: 1px solid transparent;
            padding: 3rem 2rem;
            text-align: center;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-color), var(--accent-light));
            transform: scaleX(0);
            transition: transform 0.5s ease;
        }

        .service-card:hover {
            background: var(--white);
            border-color: rgba(201, 162, 39, 0.2);
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(26, 26, 46, 0.1);
        }

        .service-card:hover::before {
            transform: scaleX(1);
        }

        .service-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, var(--primary-color), rgba(26, 26, 46, 0.8));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent-color);
            font-size: 1.75rem;
            transition: all 0.5s ease;
        }

        .service-card:hover .service-icon {
            background: var(--accent-color);
            color: var(--primary-color);
            transform: scale(1.1);
        }

        .service-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .service-desc {
            font-size: 0.95rem;
            color: var(--text-muted);
            line-height: 1.8;
        }

        /* Testimonials Section */
        .testimonials-section {
            background: var(--primary-color);
            padding: 8rem 0;
            position: relative;
            overflow: hidden;
        }

        .testimonials-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(ellipse at 30% 50%, rgba(201, 162, 39, 0.08) 0%, transparent 50%),
                radial-gradient(ellipse at 70% 50%, rgba(201, 162, 39, 0.05) 0%, transparent 40%);
        }

        .testimonials-section .section-title {
            color: var(--white);
        }

        .testimonial-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 3rem;
            position: relative;
            height: 100%;
            transition: all 0.4s ease;
        }

        .testimonial-card:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(201, 162, 39, 0.3);
        }

        .testimonial-quote {
            font-family: 'Cormorant Garamond', serif;
            font-size: 4rem;
            color: var(--accent-color);
            opacity: 0.3;
            position: absolute;
            top: 1rem;
            left: 2rem;
            line-height: 1;
        }

        .testimonial-text {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.85);
            line-height: 1.9;
            margin-bottom: 2rem;
            font-style: italic;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .testimonial-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--accent-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .testimonial-name {
            font-weight: 600;
            color: var(--white);
            margin-bottom: 0.15rem;
        }

        .testimonial-date {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.5);
        }

        .testimonial-rating {
            color: var(--accent-color);
            margin-bottom: 1rem;
        }

        /* Appointment Form Section */
        .appointment-section {
            background: var(--off-white);
            padding: 8rem 0;
        }

        .appointment-card {
            background: var(--white);
            box-shadow: 0 40px 80px rgba(26, 26, 46, 0.08);
            border: 1px solid rgba(26, 26, 46, 0.04);
        }

        .appointment-card .card-body {
            padding: 4rem;
        }

        .form-label {
            font-family: 'DM Sans', sans-serif;
            font-weight: 500;
            font-size: 0.85rem;
            color: var(--primary-color);
            letter-spacing: 0.03em;
        }

        .form-control, .form-select {
            border: 1px solid rgba(26, 26, 46, 0.1);
            border-radius: 0;
            padding: 1rem 1.25rem;
            font-family: 'DM Sans', sans-serif;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(201, 162, 39, 0.1);
        }

        /* Premium Footer */
        .footer {
            background: var(--primary-color);
            color: var(--white);
            padding: 5rem 0 2rem;
        }

        .footer-brand {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            font-weight: 600;
            color: var(--white);
            margin-bottom: 1rem;
        }

        .footer-brand .accent {
            color: var(--accent-color);
        }

        .footer-tagline {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 1.5rem;
        }

        .footer-social {
            display: flex;
            gap: 1rem;
        }

        .footer-social a {
            width: 44px;
            height: 44px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            transition: all 0.3s ease;
        }

        .footer-social a:hover {
            background: var(--accent-color);
            border-color: var(--accent-color);
            color: var(--primary-color);
        }

        .footer-heading {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--accent-color);
            margin-bottom: 1.5rem;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 0.95rem;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--accent-color);
        }

        .footer-contact-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1rem;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.95rem;
        }

        .footer-contact-item i {
            color: var(--accent-color);
            margin-top: 0.25rem;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            margin-top: 4rem;
            padding-top: 2rem;
        }

        .footer-bottom p {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.85rem;
            margin: 0;
        }

        /* Language Dropdown */
        .dropdown-menu {
            background: var(--primary-color);
            border: 1px solid var(--glass-border);
            border-radius: 0;
            padding: 0.5rem;
        }

        .dropdown-item {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background: transparent;
            color: var(--accent-color);
        }

        /* Responsive Adjustments */
        @media (max-width: 991px) {
            .hero-title {
                font-size: 3rem;
            }

            .hero-image-frame {
                width: 280px;
                height: 350px;
                margin-top: 3rem;
            }

            .navbar-glass {
                background: rgba(26, 26, 46, 0.98);
            }
        }

        @media (max-width: 768px) {
            .hero-section {
                padding-top: 100px;
            }

            .hero-title {
                font-size: 2.5rem;
            }

            .hero-image-frame {
                width: 240px;
                height: 300px;
            }

            .section-padding {
                padding: 5rem 0;
            }

            .appointment-card .card-body {
                padding: 2rem;
            }

            .trust-number {
                font-size: 2.5rem;
            }
        }

        /* RTL Support */
        [dir="rtl"] .timeline {
            padding-left: 0;
            padding-right: 3rem;
        }

        [dir="rtl"] .timeline::before {
            left: auto;
            right: 0;
        }

        [dir="rtl"] .timeline-item::before {
            left: auto;
            right: -3rem;
        }

        [dir="rtl"] .hero-image-frame::before {
            top: -20px;
            left: 20px;
            right: -20px;
        }

        [dir="rtl"] .testimonial-quote {
            left: auto;
            right: 2rem;
        }
    </style>
</head>
<body>
    <!-- Grain Texture Overlay -->
    <div class="grain-overlay"></div>

    @php
        $hasPageSections = !empty($landingPage->page_sections) && is_array($landingPage->page_sections);
        $pageSections = $hasPageSections ? collect($landingPage->page_sections)->sortBy('order') : collect();
    @endphp

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-glass fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#home">
                Dr. {{ $doctor->user->name }}<span class="accent">.</span>
            </a>
            @if($doctor->is_verified)
                <div class="d-none d-lg-block me-3">
                    <span class="verified-badge">
                        <i class="fas fa-shield-check"></i>
                        Verified
                    </span>
                </div>
            @endif
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">{{ $translatedContent['nav_home'] ?: (($language ?? 'en') === 'ar' ? 'الرئيسية' : 'Home') }}</a>
                    </li>
                    @if($landingPage->section_visibility['about'] ?? true)
                    <li class="nav-item">
                        <a class="nav-link" href="#about">{{ $translatedContent['nav_about'] ?: (($language ?? 'en') === 'ar' ? 'نبذة عني' : 'About') }}</a>
                    </li>
                    @endif
                    @if($landingPage->section_visibility['services'] ?? true)
                    <li class="nav-item">
                        <a class="nav-link" href="#services">{{ $translatedContent['nav_services'] ?: (($language ?? 'en') === 'ar' ? 'الخدمات' : 'Services') }}</a>
                    </li>
                    @endif
                    @if($landingPage->section_visibility['reviews'] ?? true)
                    <li class="nav-item">
                        <a class="nav-link" href="#testimonials">{{ $translatedContent['nav_testimonials'] ?: (($language ?? 'en') === 'ar' ? 'آراء المرضى' : 'Testimonials') }}</a>
                    </li>
                    @endif
                    @if($landingPage->section_visibility['appointments'] ?? true)
                    <li class="nav-item">
                        <a class="nav-link" href="#appointments">{{ $translatedContent['nav_appointments'] ?: (($language ?? 'en') === 'ar' ? 'حجز موعد' : 'Appointments') }}</a>
                    </li>
                    @endif

                    <!-- Language Switcher -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-globe me-1"></i>
                            {{ ($language ?? 'en') === 'ar' ? 'العربية' : 'English' }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?lang=en">English</a></li>
                            <li><a class="dropdown-item" href="?lang=ar">العربية</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    @if($landingPage->section_visibility['hero'] ?? true)
    <section id="home" class="hero-section">
        <div class="hero-gradient-mesh"></div>
        <div class="hero-particles">
            <div class="particle" style="left: 10%; top: 20%; animation-delay: 0s;"></div>
            <div class="particle" style="left: 25%; top: 60%; animation-delay: 2s;"></div>
            <div class="particle" style="left: 50%; top: 30%; animation-delay: 4s;"></div>
            <div class="particle" style="left: 75%; top: 70%; animation-delay: 1s;"></div>
            <div class="particle" style="left: 90%; top: 40%; animation-delay: 3s;"></div>
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <p class="hero-subtitle">{{ $doctor->specialty->name ?? 'Medical Professional' }}</p>
                        <h1 class="hero-title">
                            Excellence in<br><em>Healthcare</em>
                        </h1>
                        @if($translatedContent['tagline'] ?: $landingPage->tagline)
                        <p class="hero-tagline">{{ $translatedContent['tagline'] ?: $landingPage->tagline }}</p>
                        @endif
                        <div class="d-flex flex-wrap gap-3">
                            @if($landingPage->section_visibility['appointments'] ?? true)
                            <a href="#appointments" class="btn btn-gold">
                                Book Appointment
                            </a>
                            @endif
                            @if($landingPage->section_visibility['contact'] ?? true)
                            <a href="#contact" class="btn btn-outline-gold">
                                Contact Me
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image-wrapper">
                        <div class="hero-image-frame">
                            @if($doctor->profile_image)
                            <img src="{{ Storage::url($doctor->profile_image) }}" alt="Dr. {{ $doctor->user->name }}" class="hero-image">
                            @else
                            <div class="hero-placeholder">
                                <i class="fas fa-user-md"></i>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Trust Indicators -->
    @if($landingPage->section_visibility['trust_indicators'] ?? true)
    <section class="trust-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-6 col-md-3">
                    <div class="trust-item reveal">
                        <div class="trust-number">{{ $doctor->total_reviews ?? 0 }}+</div>
                        <div class="trust-label">Patients Served</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="trust-item reveal reveal-delay-1">
                        <div class="trust-number">{{ number_format($doctor->average_rating ?? 0, 1) }}</div>
                        <div class="trust-label">Rating</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="trust-item reveal reveal-delay-2">
                        <div class="trust-number">{{ $doctor->years_of_experience ?? '10' }}+</div>
                        <div class="trust-label">Years Experience</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="trust-item reveal reveal-delay-3">
                        <div class="trust-number">{{ $reviews->count() > 0 ? $reviews->count() : '50' }}+</div>
                        <div class="trust-label">Reviews</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- About Section with Timeline -->
    @if($landingPage->section_visibility['about'] ?? true)
    <section id="about" class="about-section">
        <div class="container">
            <div class="section-header reveal">
                <p class="section-subtitle">About</p>
                <h2 class="section-title">Meet Your Physician</h2>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-6 reveal">
                    @if($translatedContent['about_text'] ?: $landingPage->about_text)
                    <p class="about-text">{{ $translatedContent['about_text'] ?: $landingPage->about_text }}</p>
                    @else
                    <p class="about-text">{{ $doctor->bio ?? (($language ?? 'en') === 'ar' ? 'طبيب محترف ذو خبرة مكرس لتقديم رعاية صحية عالية الجودة.' : 'Experienced medical professional dedicated to providing quality healthcare with a patient-centered approach.') }}</p>
                    @endif

                    <div class="d-flex flex-wrap gap-4 mb-4">
                        @if($doctor->specialty->name)
                        <div>
                            <small class="text-muted text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.15em;">Specialty</small>
                            <p class="mb-0 fw-semibold">{{ $doctor->specialty->name }}</p>
                        </div>
                        @endif
                        @if($doctor->languages)
                        <div>
                            <small class="text-muted text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.15em;">Languages</small>
                            <p class="mb-0 fw-semibold">{{ implode(', ', $doctor->languages) }}</p>
                        </div>
                        @endif
                        @if($doctor->appointment_duration)
                        <div>
                            <small class="text-muted text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.15em;">Consultation</small>
                            <p class="mb-0 fw-semibold">{{ $doctor->appointment_duration }} Minutes</p>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 reveal reveal-delay-2">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-year">2010</div>
                            <div class="timeline-title">Medical Degree</div>
                            <div class="timeline-desc">Graduated with honors from Medical School</div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-year">2015</div>
                            <div class="timeline-title">Board Certification</div>
                            <div class="timeline-desc">Specialized training completed</div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-year">2018</div>
                            <div class="timeline-title">Private Practice</div>
                            <div class="timeline-desc">Established current practice</div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-year">{{ date('Y') }}</div>
                            <div class="timeline-title">Today</div>
                            <div class="timeline-desc">Serving patients with dedication</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Services Section -->
    @if($landingPage->section_visibility['services'] ?? true)
    <section id="services" class="services-section">
        <div class="container">
            <div class="section-header reveal">
                <p class="section-subtitle">Services</p>
                <h2 class="section-title">What I Offer</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4 reveal">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                        <h3 class="service-title">General Consultation</h3>
                        <p class="service-desc">Comprehensive health assessments and personalized care plans tailored to your unique needs.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal reveal-delay-1">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <h3 class="service-title">Cardiac Screening</h3>
                        <p class="service-desc">Advanced cardiovascular evaluations using state-of-the-art diagnostic equipment.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal reveal-delay-2">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-video"></i>
                        </div>
                        <h3 class="service-title">Video Consultation</h3>
                        <p class="service-desc">Convenient virtual appointments for follow-ups and initial assessments from anywhere.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal reveal-delay-1">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-notes-medical"></i>
                        </div>
                        <h3 class="service-title">Health Screening</h3>
                        <p class="service-desc">Preventive care packages designed to detect potential issues early.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal reveal-delay-2">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <h3 class="service-title">Chronic Care</h3>
                        <p class="service-desc">Ongoing management and support for chronic conditions with regular monitoring.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal reveal-delay-3">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-lab"></i>
                        </div>
                        <h3 class="service-title">Lab Analysis</h3>
                        <p class="service-desc">Quick turnaround on diagnostic tests with detailed result explanations.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Testimonials Section -->
    @if($landingPage->section_visibility['reviews'] ?? true)
    <section id="testimonials" class="testimonials-section">
        <div class="container position-relative">
            <div class="section-header reveal">
                <p class="section-subtitle">Testimonials</p>
                <h2 class="section-title">Patient Stories</h2>
            </div>
            <div class="row g-4">
                @if($reviews->count() > 0)
                    @foreach($reviews->take(3) as $review)
                    <div class="col-lg-4 reveal">
                        <div class="testimonial-card">
                            <span class="testimonial-quote">"</span>
                            <div class="testimonial-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $review->rating ? '' : ' text-muted' }}"></i>
                                @endfor
                            </div>
                            <p class="testimonial-text">{{ $review->comment }}</p>
                            <div class="testimonial-author">
                                <div class="testimonial-avatar">
                                    {{ substr($review->patient_display_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="testimonial-name">{{ $review->patient_display_name }}</div>
                                    <div class="testimonial-date">{{ $review->formatted_date }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="col-lg-4 reveal">
                        <div class="testimonial-card">
                            <span class="testimonial-quote">"</span>
                            <div class="testimonial-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star"></i>
                                @endfor
                            </div>
                            <p class="testimonial-text">Outstanding physician with exceptional bedside manner. Highly recommend for any medical needs.</p>
                            <div class="testimonial-author">
                                <div class="testimonial-avatar">S</div>
                                <div>
                                    <div class="testimonial-name">Sarah Mitchell</div>
                                    <div class="testimonial-date">March 2026</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 reveal reveal-delay-1">
                        <div class="testimonial-card">
                            <span class="testimonial-quote">"</span>
                            <div class="testimonial-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star"></i>
                                @endfor
                            </div>
                            <p class="testimonial-text">Professional, thorough, and genuinely caring. The level of attention received was remarkable.</p>
                            <div class="testimonial-author">
                                <div class="testimonial-avatar">J</div>
                                <div>
                                    <div class="testimonial-name">James Wilson</div>
                                    <div class="testimonial-date">February 2026</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 reveal reveal-delay-2">
                        <div class="testimonial-card">
                            <span class="testimonial-quote">"</span>
                            <div class="testimonial-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star"></i>
                                @endfor
                            </div>
                            <p class="testimonial-text">The consultation was informative and the treatment plan was clearly explained. Excellent care.</p>
                            <div class="testimonial-author">
                                <div class="testimonial-avatar">M</div>
                                <div>
                                    <div class="testimonial-name">Maria Garcia</div>
                                    <div class="testimonial-date">January 2026</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    <!-- Appointment Form Section -->
    @if($landingPage->section_visibility['appointments'] ?? true)
    <section id="appointments" class="appointment-section">
        <div class="container">
            <div class="section-header reveal">
                <p class="section-subtitle">Appointments</p>
                <h2 class="section-title">Book Your Visit</h2>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="appointment-card reveal">
                        <div class="card-body">
                            <form id="appointmentForm" action="{{ route('appointments.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
                                <input type="hidden" name="booking_type" value="guest">

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label for="guest_name" class="form-label">Full Name *</label>
                                        <input type="text" class="form-control" id="guest_name" name="guest_name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="guest_email" class="form-label">Email Address *</label>
                                        <input type="email" class="form-control" id="guest_email" name="guest_email" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="guest_phone" class="form-label">Phone Number *</label>
                                        <input type="tel" class="form-control" id="guest_phone" name="guest_phone" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="guest_date_of_birth" class="form-label">Date of Birth *</label>
                                        <input type="date" class="form-control" id="guest_date_of_birth" name="guest_date_of_birth" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="guest_gender" class="form-label">Gender *</label>
                                        <select class="form-select" id="guest_gender" name="guest_gender" required>
                                            <option value="">Select gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="appointment_type" class="form-label">Appointment Type *</label>
                                        <select class="form-select" id="appointment_type" name="appointment_type" required>
                                            <option value="">Select type</option>
                                            @php
                                                $appointmentTypeLabels = [
                                                    'in_person' => 'In-Person Consultation',
                                                    'video_call' => 'Video Call',
                                                    'phone_call' => 'Phone Call'
                                                ];
                                            @endphp
                                            @foreach($doctor->getEnabledAppointmentTypes() as $type)
                                                <option value="{{ $type }}">{{ $appointmentTypeLabels[$type] ?? ucfirst($type) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="appointment_date" class="form-label">Preferred Date *</label>
                                        <input type="hidden" id="selected_appointment_datetime" name="appointment_date">
                                        <select class="form-select" id="appointment_date_select" required>
                                            <option value="">Select a date</option>
                                            @if(!empty($availableSlots))
                                                @foreach($availableSlots as $date => $slots)
                                                <option value="{{ $date }}">{{ \Carbon\Carbon::parse($date)->format('l, M j, Y') }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="appointment_time" class="form-label">Preferred Time *</label>
                                        <select class="form-select" id="appointment_time" required disabled>
                                            <option value="">Select a date first</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label for="reason" class="form-label">Reason for Visit *</label>
                                        <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Please describe your symptoms or reason for the appointment..." required></textarea>
                                    </div>
                                    @if(empty($availableSlots))
                                    <div class="col-12">
                                        <div class="alert alert-info mb-0">
                                            <i class="fas fa-info-circle me-2"></i>
                                            No available slots at the moment. Please contact the doctor directly to schedule an appointment.
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-gold w-100" @if(empty($availableSlots)) disabled @endif>
                                            <i class="fas fa-calendar-check me-2"></i>Book Appointment
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Contact Section -->
    @if($landingPage->section_visibility['contact'] ?? true)
    <section id="contact" class="py-5" style="background: var(--white);">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center reveal">
                    <p class="section-subtitle">Contact</p>
                    <h2 class="section-title mb-4">Get In Touch</h2>
                    <p class="text-muted mb-5">Have questions? Reach out directly for inquiries or to schedule a consultation.</p>
                    <div class="d-flex flex-wrap justify-content-center gap-4">
                        @if($doctor->phone)
                        <a href="tel:{{ $doctor->phone }}" class="btn btn-outline-dark px-4 py-3" style="border-radius: 0;">
                            <i class="fas fa-phone me-2" style="color: var(--accent-color);"></i>
                            {{ $doctor->phone }}
                        </a>
                        @endif
                        @if($doctor->user->email)
                        <a href="mailto:{{ $doctor->user->email }}" class="btn btn-outline-dark px-4 py-3" style="border-radius: 0;">
                            <i class="fas fa-envelope me-2" style="color: var(--accent-color);"></i>
                            {{ $doctor->user->email }}
                        </a>
                        @endif
                    </div>
                    @if($doctor->full_address)
                    <p class="text-muted mt-4">
                        <i class="fas fa-map-marker-alt me-2" style="color: var(--accent-color);"></i>
                        {{ $doctor->full_address }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <div class="footer-brand">
                        Dr. {{ $doctor->user->name }}<span class="accent">.</span>
                    </div>
                    <p class="footer-tagline">{{ $doctor->specialty->name ?? 'Medical Professional' }}</p>
                    <div class="footer-social">
                        @if($doctor->social_links['facebook'] ?? null)
                        <a href="{{ $doctor->social_links['facebook'] }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if($doctor->social_links['twitter'] ?? null)
                        <a href="{{ $doctor->social_links['twitter'] }}" target="_blank"><i class="fab fa-twitter"></i></a>
                        @endif
                        @if($doctor->social_links['linkedin'] ?? null)
                        <a href="{{ $doctor->social_links['linkedin'] }}" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                        @endif
                        @if($doctor->social_links['instagram'] ?? null)
                        <a href="{{ $doctor->social_links['instagram'] }}" target="_blank"><i class="fab fa-instagram"></i></a>
                        @endif
                    </div>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h5 class="footer-heading">Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="#home">Home</a></li>
                        @if($landingPage->section_visibility['about'] ?? true)
                        <li><a href="#about">About</a></li>
                        @endif
                        @if($landingPage->section_visibility['services'] ?? true)
                        <li><a href="#services">Services</a></li>
                        @endif
                        @if($landingPage->section_visibility['appointments'] ?? true)
                        <li><a href="#appointments">Book Now</a></li>
                        @endif
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h5 class="footer-heading">Services</h5>
                    <ul class="footer-links">
                        <li><a href="#services">Consultation</a></li>
                        <li><a href="#services">Video Call</a></li>
                        <li><a href="#services">Health Screening</a></li>
                        <li><a href="#services">Chronic Care</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4">
                    <h5 class="footer-heading">Contact Info</h5>
                    @if($doctor->phone)
                    <div class="footer-contact-item">
                        <i class="fas fa-phone"></i>
                        <span>{{ $doctor->phone }}</span>
                    </div>
                    @endif
                    @if($doctor->user->email)
                    <div class="footer-contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>{{ $doctor->user->email }}</span>
                    </div>
                    @endif
                    @if($doctor->full_address)
                    <div class="footer-contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $doctor->full_address }}</span>
                    </div>
                    @endif
                </div>
            </div>
            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p>&copy; {{ date('Y') }} Dr. {{ $doctor->user->name }}. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p>Powered by MedCuraAI</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Chat Widget -->
    @if($landingPage->section_visibility['chat_widget'] ?? true)
    @include('components.chat-widget', [
        'doctorUsername' => $landingPage->username,
        'doctorName' => $doctor->user->name
    ])
    @endif

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Navbar scroll effect with requestAnimationFrame optimization
        const navbar = document.querySelector('.navbar-glass');
        let ticking = false;
        function onScroll() {
            if (!ticking) {
                requestAnimationFrame(function() {
                    navbar.classList[window.scrollY > 50 ? 'add' : 'remove']('scrolled');
                    ticking = false;
                });
                ticking = true;
            }
        }
        window.addEventListener('scroll', onScroll);

        // Scroll reveal animation using IntersectionObserver (more efficient than scroll events)
        const revealElements = document.querySelectorAll('.reveal');
        if (revealElements.length > 0) {
            const revealObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                        revealObserver.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
            revealElements.forEach(el => revealObserver.observe(el));
        }

        // Available slots data
        const availableSlots = @json($availableSlots ?? []);

        // Handle date selection
        const appointmentDateSelect = document.getElementById('appointment_date_select');
        const appointmentTime = document.getElementById('appointment_time');
        const selectedDateTime = document.getElementById('selected_appointment_datetime');

        if (appointmentDateSelect) {
            appointmentDateSelect.addEventListener('change', function() {
                const selectedDate = this.value;
                appointmentTime.innerHTML = '<option value="">Select a time</option>';
                selectedDateTime.value = '';

                if (selectedDate && availableSlots[selectedDate]) {
                    appointmentTime.disabled = false;
                    availableSlots[selectedDate].forEach(function(slot) {
                        const option = document.createElement('option');
                        option.value = slot.datetime;
                        option.textContent = `${slot.start_time} - ${slot.end_time}`;
                        appointmentTime.appendChild(option);
                    });
                } else {
                    appointmentTime.disabled = true;
                    appointmentTime.innerHTML = '<option value="">No slots available</option>';
                }
            });
        }

        // Handle time selection
        if (appointmentTime) {
            appointmentTime.addEventListener('change', function() {
                selectedDateTime.value = this.value;
            });
        }

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const offset = 80;
                    const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - offset;
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Form submission
        const appointmentForm = document.getElementById('appointmentForm');
        if (appointmentForm) {
            appointmentForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;

                // Check if appointment date/time is selected
                if (!selectedDateTime.value && appointmentDateSelect.value) {
                    alert('Please select a time for your appointment.');
                    return;
                }

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Booking...';

                const formData = new FormData(this);

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message || 'Appointment booked successfully! You will receive a confirmation email shortly.');
                        appointmentForm.reset();
                        appointmentTime.innerHTML = '<option value="">Select a date first</option>';
                        appointmentTime.disabled = true;
                        selectedDateTime.value = '';

                        if (data.redirect_url) {
                            setTimeout(() => {
                                window.location.href = data.redirect_url;
                            }, 2000);
                        }
                    } else {
                        alert(data.message || 'Failed to book appointment. Please try again.');
                    }
                })
                .catch(error => {
                    alert('An error occurred while booking your appointment. Please try again.');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
            });
        }
    </script>

    @stack('scripts')
</body>
</html>