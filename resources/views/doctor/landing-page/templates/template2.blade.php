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
            --bronze: #b8860b;
            --bronze-light: #d4a84b;
            --bronze-dark: #8b6508;
            --cream: #faf8f5;
            --warm-white: #fefefe;
            --charcoal: #2d2d2d;
            --stone: #6b6b6b;
            --soft-gray: #e8e6e3;
            --blush: #f5f0eb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            color: var(--charcoal);
            line-height: 1.8;
            overflow-x: hidden;
            background-color: var(--cream);
            font-weight: 400;
            letter-spacing: 0.01em;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 500;
            line-height: 1.3;
            letter-spacing: 0.02em;
        }

        /* Subtle Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .animate-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Elegant Navigation */
        .elegant-nav {
            background: transparent;
            position: fixed;
            width: 100%;
            z-index: 1000;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 1.5rem 0;
        }

        .elegant-nav.scrolled {
            background: rgba(250, 248, 245, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 1rem 0;
            box-shadow: 0 2px 40px rgba(0, 0, 0, 0.06);
        }

        .elegant-nav .navbar-brand {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--charcoal);
            letter-spacing: 0.03em;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .elegant-nav .navbar-brand:hover {
            color: var(--bronze);
        }

        .elegant-nav .nav-link {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--charcoal) !important;
            letter-spacing: 0.05em;
            padding: 0.5rem 1.25rem !important;
            position: relative;
            transition: color 0.3s ease;
        }

        .elegant-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 1.5px;
            background: var(--bronze);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(-50%);
        }

        .elegant-nav .nav-link:hover::after,
        .elegant-nav .nav-link.active::after {
            width: 60%;
        }

        .elegant-nav .nav-link:hover {
            color: var(--bronze) !important;
        }

        .elegant-nav .navbar-toggler {
            border: none;
            padding: 0.5rem;
        }

        .elegant-nav .navbar-toggler:focus {
            box-shadow: none;
        }

        .elegant-nav .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%232d2d2d' stroke-linecap='round' stroke-miterlimit='10' stroke-width='1.5' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Language Dropdown */
        .lang-dropdown .nav-link {
            font-size: 0.85rem;
            padding: 0.4rem 0.8rem !important;
        }

        .lang-dropdown .dropdown-menu {
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 0.5rem;
        }

        .lang-dropdown .dropdown-item {
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .lang-dropdown .dropdown-item:hover {
            background: var(--blush);
            color: var(--bronze);
        }

        /* Hero Section */
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, var(--cream) 0%, var(--blush) 50%, var(--soft-gray) 100%);
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 80%;
            height: 150%;
            background: radial-gradient(ellipse, rgba(184, 134, 11, 0.06) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-subtitle {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--bronze);
            margin-bottom: 1.5rem;
            animation: fadeInUp 1s ease forwards;
        }

        .hero-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(3rem, 6vw, 5rem);
            font-weight: 400;
            color: var(--charcoal);
            line-height: 1.15;
            margin-bottom: 1.5rem;
            animation: fadeInUp 1s ease 0.2s forwards;
            opacity: 0;
        }

        .hero-title em {
            font-style: italic;
            color: var(--bronze);
        }

        .hero-tagline {
            font-size: 1.15rem;
            color: var(--stone);
            max-width: 500px;
            margin-bottom: 2.5rem;
            animation: fadeInUp 1s ease 0.4s forwards;
            opacity: 0;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            animation: fadeInUp 1s ease 0.6s forwards;
            opacity: 0;
        }

        .btn-elegant {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
        }

        .btn-elegant-primary {
            background: var(--bronze);
            color: white;
            border: 2px solid var(--bronze);
        }

        .btn-elegant-primary:hover {
            background: var(--bronze-dark);
            border-color: var(--bronze-dark);
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(184, 134, 11, 0.25);
            color: white;
        }

        .btn-elegant-outline {
            background: transparent;
            color: var(--charcoal);
            border: 2px solid var(--charcoal);
        }

        .btn-elegant-outline:hover {
            background: var(--charcoal);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(45, 45, 45, 0.15);
        }

        .hero-image-wrapper {
            position: relative;
            animation: fadeIn 1.5s ease 0.5s forwards;
            opacity: 0;
        }

        .hero-image {
            width: 100%;
            max-width: 450px;
            height: auto;
            aspect-ratio: 3/4;
            object-fit: cover;
            border-radius: 200px 200px 20px 20px;
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.1);
        }

        .hero-image-decoration {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 30px;
            right: -30px;
            border: 2px solid var(--bronze);
            border-radius: 200px 200px 20px 20px;
            z-index: -1;
            opacity: 0.4;
        }

        /* Trust Indicators */
        .trust-section {
            padding: 5rem 0;
            background: white;
        }

        .trust-item {
            text-align: center;
            padding: 2rem;
            transition: transform 0.4s ease;
        }

        .trust-item:hover {
            transform: translateY(-5px);
        }

        .trust-number {
            font-family: 'Cormorant Garamond', serif;
            font-size: 3.5rem;
            font-weight: 600;
            color: var(--bronze);
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .trust-label {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--stone);
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        /* Section Styling */
        .section {
            padding: 7rem 0;
        }

        .section-light {
            background: var(--cream);
        }

        .section-dark {
            background: var(--charcoal);
            color: white;
        }

        .section-header {
            text-align: center;
            max-width: 700px;
            margin: 0 auto 4rem;
        }

        .section-subtitle {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--bronze);
            margin-bottom: 1rem;
        }

        .section-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2.5rem, 4vw, 3.5rem);
            font-weight: 500;
            color: var(--charcoal);
            margin-bottom: 1.25rem;
        }

        .section-dark .section-title {
            color: white;
        }

        .section-description {
            font-size: 1.1rem;
            color: var(--stone);
            line-height: 1.8;
        }

        .section-dark .section-description {
            color: rgba(255, 255, 255, 0.7);
        }

        /* About Section */
        .about-section {
            padding: 7rem 0;
            background: white;
        }

        .about-image-wrapper {
            position: relative;
            padding-left: 3rem;
        }

        .about-image {
            width: 100%;
            height: 550px;
            object-fit: cover;
            border-radius: 20px;
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.08);
        }

        .about-image-accent {
            position: absolute;
            width: 100%;
            height: 100%;
            top: -20px;
            left: 0;
            border: 2px solid var(--bronze);
            border-radius: 20px;
            opacity: 0.3;
        }

        .about-content {
            padding-left: 3rem;
        }

        .about-intro {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.4rem;
            font-style: italic;
            color: var(--bronze);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .about-text {
            font-size: 1rem;
            color: var(--stone);
            margin-bottom: 2rem;
            line-height: 1.9;
        }

        .credentials-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .credentials-list li {
            position: relative;
            padding-left: 2rem;
            margin-bottom: 1rem;
            font-size: 0.95rem;
            color: var(--charcoal);
        }

        .credentials-list li::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0.5rem;
            width: 8px;
            height: 8px;
            background: var(--bronze);
            border-radius: 50%;
        }

        /* Services Cards */
        .services-section {
            padding: 7rem 0;
            background: var(--cream);
        }

        .service-card {
            background: white;
            border-radius: 20px;
            padding: 3rem 2rem;
            text-align: center;
            height: 100%;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid transparent;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.08);
            border-color: rgba(184, 134, 11, 0.2);
        }

        .service-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--blush), var(--cream));
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            transition: all 0.4s ease;
        }

        .service-card:hover .service-icon {
            background: linear-gradient(135deg, var(--bronze), var(--bronze-light));
        }

        .service-icon i {
            font-size: 1.75rem;
            color: var(--bronze);
            transition: color 0.4s ease;
        }

        .service-card:hover .service-icon i {
            color: white;
        }

        .service-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--charcoal);
            margin-bottom: 1rem;
        }

        .service-description {
            font-size: 0.95rem;
            color: var(--stone);
            line-height: 1.7;
        }

        /* Testimonials Carousel */
        .testimonials-section {
            padding: 7rem 0;
            background: white;
            overflow: hidden;
        }

        .testimonial-carousel {
            position: relative;
            max-width: 900px;
            margin: 0 auto;
        }

        .testimonial-track {
            display: flex;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .testimonial-slide {
            min-width: 100%;
            padding: 0 2rem;
        }

        .testimonial-content {
            background: var(--cream);
            border-radius: 24px;
            padding: 4rem;
            text-align: center;
            position: relative;
        }

        .testimonial-content::before {
            content: '"';
            font-family: 'Cormorant Garamond', serif;
            font-size: 8rem;
            color: var(--bronze);
            opacity: 0.15;
            position: absolute;
            top: 0;
            left: 2rem;
            line-height: 1;
        }

        .testimonial-text {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.6rem;
            font-style: italic;
            color: var(--charcoal);
            line-height: 1.7;
            margin-bottom: 2rem;
            position: relative;
            z-index: 1;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }

        .testimonial-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--bronze);
        }

        .testimonial-avatar-placeholder {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--blush);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid var(--bronze);
        }

        .testimonial-name {
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            color: var(--charcoal);
            margin-bottom: 0.25rem;
        }

        .testimonial-info {
            font-size: 0.85rem;
            color: var(--stone);
        }

        .testimonial-rating {
            color: var(--bronze);
            margin-bottom: 1.5rem;
        }

        .carousel-controls {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
        }

        .carousel-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid var(--bronze);
            background: transparent;
            color: var(--bronze);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .carousel-btn:hover {
            background: var(--bronze);
            color: white;
        }

        .carousel-dots {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1.5rem;
        }

        .carousel-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--soft-gray);
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .carousel-dot.active {
            background: var(--bronze);
            width: 30px;
            border-radius: 5px;
        }

        /* Appointment Form Section */
        .appointment-section {
            padding: 7rem 0;
            background: linear-gradient(135deg, var(--charcoal) 0%, #3d3d3d 100%);
            position: relative;
            overflow: hidden;
        }

        .appointment-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(ellipse at 30% 50%, rgba(184, 134, 11, 0.1) 0%, transparent 60%);
            pointer-events: none;
        }

        .appointment-section .section-title {
            color: white;
        }

        .appointment-section .section-description {
            color: rgba(255, 255, 255, 0.7);
        }

        .appointment-form-wrapper {
            background: white;
            border-radius: 24px;
            padding: 4rem;
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
        }

        .form-label {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--charcoal);
            letter-spacing: 0.03em;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border: 2px solid var(--soft-gray);
            border-radius: 12px;
            padding: 0.875rem 1.25rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--bronze);
            box-shadow: 0 0 0 4px rgba(184, 134, 11, 0.1);
            outline: none;
        }

        .form-control::placeholder {
            color: #aaa;
        }

        .btn-submit {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 1.125rem 3rem;
            background: var(--bronze);
            color: white;
            border: none;
            border-radius: 50px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            width: 100%;
        }

        .btn-submit:hover {
            background: var(--bronze-dark);
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(184, 134, 11, 0.3);
            color: white;
        }

        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        /* Contact Info Section */
        .contact-section {
            padding: 7rem 0;
            background: var(--cream);
        }

        .contact-info-card {
            background: white;
            border-radius: 20px;
            padding: 3rem 2rem;
            text-align: center;
            height: 100%;
            transition: all 0.4s ease;
            border: 1px solid var(--soft-gray);
        }

        .contact-info-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.08);
            border-color: var(--bronze);
        }

        .contact-info-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--bronze), var(--bronze-light));
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .contact-info-icon i {
            font-size: 1.5rem;
            color: white;
        }

        .contact-info-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--charcoal);
            margin-bottom: 0.75rem;
        }

        .contact-info-text {
            font-size: 0.95rem;
            color: var(--stone);
            line-height: 1.7;
        }

        .contact-info-text a {
            color: var(--stone);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .contact-info-text a:hover {
            color: var(--bronze);
        }

        /* Footer */
        .elegant-footer {
            background: var(--charcoal);
            color: white;
            padding: 5rem 0 2rem;
        }

        .footer-brand {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            font-weight: 600;
            color: white;
            margin-bottom: 1rem;
        }

        .footer-tagline {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 0;
        }

        .footer-divider {
            border-color: rgba(255, 255, 255, 0.1);
            margin: 3rem 0 2rem;
        }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .footer-copyright {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.5);
            margin: 0;
        }

        .footer-links {
            display: flex;
            gap: 2rem;
        }

        .footer-links a {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--bronze);
        }

        /* Chat Widget */
        .chat-widget-trigger {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--bronze);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 40px rgba(184, 134, 11, 0.4);
            cursor: pointer;
            z-index: 999;
            transition: all 0.3s ease;
            border: none;
        }

        .chat-widget-trigger:hover {
            transform: scale(1.1);
            box-shadow: 0 15px 50px rgba(184, 134, 11, 0.5);
        }

        /* RTL Support */
        [dir="rtl"] .hero-image-decoration {
            right: auto;
            left: -30px;
        }

        [dir="rtl"] .about-image-wrapper {
            padding-left: 0;
            padding-right: 3rem;
        }

        [dir="rtl"] .about-image-accent {
            left: auto;
            right: -20px;
        }

        [dir="rtl"] .about-content {
            padding-left: 0;
            padding-right: 3rem;
        }

        [dir="rtl"] .credentials-list li {
            padding-left: 0;
            padding-right: 2rem;
        }

        [dir="rtl"] .credentials-list li::before {
            left: auto;
            right: 0;
        }

        [dir="rtl"] .testimonial-content::before {
            left: auto;
            right: 2rem;
        }

        [dir="rtl"] .carousel-btn i {
            transform: scaleX(-1);
        }

        [dir="rtl"] .chat-widget-trigger {
            right: auto;
            left: 30px;
        }

        /* Responsive Styles */
        @media (max-width: 992px) {
            .hero-section {
                padding-top: 100px;
            }

            .hero-image-wrapper {
                margin-top: 3rem;
            }

            .hero-image {
                max-width: 350px;
            }

            .about-content {
                padding-left: 0;
                margin-top: 3rem;
            }

            .about-image-wrapper {
                padding-left: 0;
            }

            .section {
                padding: 5rem 0;
            }

            .appointment-form-wrapper {
                padding: 2.5rem;
            }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-image {
                max-width: 280px;
            }

            .hero-image-decoration {
                display: none;
            }

            .section-title {
                font-size: 2rem;
            }

            .testimonial-content {
                padding: 2.5rem 1.5rem;
            }

            .testimonial-text {
                font-size: 1.25rem;
            }

            .service-card {
                padding: 2rem 1.5rem;
            }

            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }
        }

        @media (max-width: 576px) {
            .hero-buttons {
                flex-direction: column;
            }

            .btn-elegant {
                width: 100%;
                text-align: center;
            }

            .trust-number {
                font-size: 2.5rem;
            }

            .appointment-form-wrapper {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    @if($landingPage->section_visibility['navigation'] ?? true)
    <nav class="navbar navbar-expand-lg elegant-nav" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="#home">
                Dr. {{ $doctor->user->name }}
            </a>
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
                    @if($landingPage->section_visibility['appointments'] ?? true)
                    <li class="nav-item">
                        <a class="nav-link" href="#appointments">{{ $translatedContent['nav_appointments'] ?: (($language ?? 'en') === 'ar' ? 'حجز موعد' : 'Appointments') }}</a>
                    </li>
                    @endif
                    @if($landingPage->section_visibility['reviews'] ?? true)
                    <li class="nav-item">
                        <a class="nav-link" href="#testimonials">{{ $translatedContent['nav_reviews'] ?: (($language ?? 'en') === 'ar' ? 'آراء المرضى' : 'Reviews') }}</a>
                    </li>
                    @endif
                    @if($landingPage->section_visibility['contact'] ?? true)
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">{{ $translatedContent['nav_contact'] ?: (($language ?? 'en') === 'ar' ? 'اتصل بنا' : 'Contact') }}</a>
                    </li>
                    @endif
                    <li class="nav-item dropdown lang-dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-globe me-1"></i>
                            {{ ($language ?? 'en') === 'ar' ? 'العربية' : 'EN' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="?lang=en">English</a></li>
                            <li><a class="dropdown-item" href="?lang=ar">العربية</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    @endif

    @if($landingPage->section_visibility['hero'] ?? true)
    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <p class="hero-subtitle">
                            {{ $doctor->specialty->name ?? 'Medical Professional' }}
                        </p>
                        <h1 class="hero-title">
                            {{ $translatedContent['hero_title'] ?: (($language ?? 'en') === 'ar' ? 'رحلة صحية <em>أنت تستحقها</em>' : 'Healthcare That <em>You Deserve</em>') }}
                        </h1>
                        <p class="hero-tagline">
                            {{ $translatedContent['tagline'] ?: $landingPage->tagline ?: (($language ?? 'en') === 'ar' ? 'طب متقدم في بيئة رحيمة ومتعالية.' : 'Compassionate care meets advanced medicine.') }}
                        </p>
                        <div class="hero-buttons">
                            @if($landingPage->section_visibility['appointments'] ?? true)
                            <a href="#appointments" class="btn btn-elegant btn-elegant-primary">
                                {{ $translatedContent['hero_cta_primary'] ?: (($language ?? 'en') === 'ar' ? 'احجزي موعدك' : 'Book Appointment') }}
                            </a>
                            @endif
                            @if($landingPage->section_visibility['contact'] ?? true)
                            <a href="#contact" class="btn btn-elegant btn-elegant-outline">
                                {{ $translatedContent['hero_cta_secondary'] ?: (($language ?? 'en') === 'ar' ? 'تواصلي معنا' : 'Contact Us') }}
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image-wrapper">
                        <div class="hero-image-decoration"></div>
                        @if($doctor->profile_image)
                        <img src="{{ Storage::url($doctor->profile_image) }}" alt="Dr. {{ $doctor->user->name }}" class="hero-image">
                        @elseif($landingPage->hero_image)
                        <img src="{{ Storage::url($landingPage->hero_image) }}" alt="Dr. {{ $doctor->user->name }}" class="hero-image">
                        @else
                        <div class="hero-image d-flex align-items-center justify-content-center bg-light" style="border-radius: 200px 200px 20px 20px;">
                            <i class="fas fa-user-md fa-5x text-muted"></i>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if($landingPage->section_visibility['trust_indicators'] ?? true)
    <!-- Trust Indicators Section -->
    <section class="trust-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4 animate-on-scroll">
                    <div class="trust-item">
                        <div class="trust-number">{{ number_format($doctor->average_rating ?? 0, 1) }}</div>
                        <div class="trust-label">{{ ($language ?? 'en') === 'ar' ? 'تقييم المرضى' : 'Patient Rating' }}</div>
                    </div>
                </div>
                <div class="col-md-4 animate-on-scroll" style="transition-delay: 0.1s;">
                    <div class="trust-item">
                        <div class="trust-number">{{ $doctor->total_reviews ?? 0 }}+</div>
                        <div class="trust-label">{{ ($language ?? 'en') === 'ar' ? 'مراجعة المرضى' : 'Patient Reviews' }}</div>
                    </div>
                </div>
                <div class="col-md-4 animate-on-scroll" style="transition-delay: 0.2s;">
                    <div class="trust-item">
                        <div class="trust-number">{{ $doctor->experience_years ?? $doctor->years_experience ?? 15 }}+</div>
                        <div class="trust-label">{{ ($language ?? 'en') === 'ar' ? 'سنوات الخبرة' : 'Years Experience' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if($landingPage->section_visibility['about'] ?? true)
    <!-- About Section -->
    <section id="about" class="about-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="about-image-wrapper animate-on-scroll">
                        <div class="about-image-accent"></div>
                        @if($landingPage->about_image ?? $doctor->profile_image)
                        <img src="{{ Storage::url($landingPage->about_image ?? $doctor->profile_image) }}" alt="{{ $doctor->user->name }}" class="about-image">
                        @else
                        <div class="about-image d-flex align-items-center justify-content-center bg-light">
                            <i class="fas fa-user-md fa-6x text-muted"></i>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-content animate-on-scroll" style="transition-delay: 0.2s;">
                        <p class="section-subtitle">{{ ($language ?? 'en') === 'ar' ? 'تعرفي علي' : 'About Me' }}</p>
                        <h2 class="section-title" style="text-align: left; margin-bottom: 1.5rem;">
                            @if($translatedContent['about_title'])
                                {{ $translatedContent['about_title'] }}
                            @else
                                Dr. {{ $doctor->user->name }}
                            @endif
                        </h2>
                        <p class="about-intro">
                            {{ $translatedContent['about_intro'] ?: (($language ?? 'en') === 'ar' ? '"إلتزامي بتقديم رعاية صحية استثنائية لكل مريض."' : '"My commitment to exceptional healthcare for every patient."') }}
                        </p>
                        <p class="about-text">
                            {{ $translatedContent['about_text'] ?: $landingPage->about_text ?: $doctor->bio ?: (($language ?? 'en') === 'ar' ? 'طبيبة محترفة متخصصة في الطب الباطني والصحة العامة. أقدم الرعاية الصحية الشاملة مع التركيز على الوقاية والتشخيص المبكر والعلاج الفعال.' : 'Board-certified physician specializing in internal medicine and preventive care. I provide comprehensive healthcare services with a focus on prevention, early diagnosis, and effective treatment.') }}
                        </p>
                        <ul class="credentials-list">
                            @if($doctor->education)
                            <li>{{ $doctor->education }}</li>
                            @endif
                            @if($doctor->certifications)
                            <li>{{ $doctor->certifications }}</li>
                            @endif
                            @if($doctor->specialty->name)
                            <li>{{ ($language ?? 'en') === 'ar' ? 'أخصائي في ' : 'Specialist in ' }}{{ $doctor->specialty->name }}</li>
                            @endif
                            @if($doctor->hospital_affiliation)
                            <li>{{ $doctor->hospital_affiliation }}</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if($landingPage->section_visibility['services'] ?? true)
    <!-- Services Section -->
    <section id="services" class="services-section">
        <div class="container">
            <div class="section-header animate-on-scroll">
                <p class="section-subtitle">{{ ($language ?? 'en') === 'ar' ? 'خدماتنا' : 'Our Services' }}</p>
                <h2 class="section-title">{{ $translatedContent['services_title'] ?: (($language ?? 'en') === 'ar' ? 'الرعاية الصحية المتكاملة' : 'Comprehensive Healthcare Services') }}</h2>
                <p class="section-description">
                    {{ $translatedContent['services_description'] ?: (($language ?? 'en') === 'ar' ? 'نقدم مجموعة واسعة من الخدمات الطبية المصممة لتلبية جميع احتياجاتك الصحية.' : 'We offer a wide range of medical services designed to meet all your healthcare needs.') }}
                </p>
            </div>
            <div class="row g-4">
                @php
                    $services = $landingPage->services ?? [
                        ['icon' => 'fa-stethoscope', 'title' => (($language ?? 'en') === 'ar' ? 'الفحص العام' : 'General Checkup'), 'description' => (($language ?? 'en') === 'ar' ? 'فحوصات شاملة الروتينية لتقييم صحتك العامة.' : 'Comprehensive routine examinations to assess your overall health.')],
                        ['icon' => 'fa-heartbeat', 'title' => (($language ?? 'en') === 'ar' ? 'طب القلب' : 'Cardiac Care'), 'description' => (($language ?? 'en') === 'ar' ? 'رعاية قلب متخصصة تشمل تخطيط القلب والإيكو.' : 'Specialized heart care including ECG and echocardiography.')],
                        ['icon' => 'fa-lungs', 'title' => (($language ?? 'en') === 'ar' ? 'طب الجهاز التنفسي' : 'Respiratory Care'), 'description' => (($language ?? 'en') === 'ar' ? 'تشخيص وعلاج امراض الجهاز التنفسي.' : 'Diagnosis and treatment of respiratory conditions.')],
                        ['icon' => 'fa-notes-medical', 'title' => (($language ?? 'en') === 'ar' ? 'إدارة الأمراض المزمنة' : 'Chronic Disease Management'), 'description' => (($language ?? 'en') === 'ar' ? 'إدارة فعالة للأمراض المزمنة مثل السكري والضغط.' : 'Effective management of chronic conditions like diabetes and hypertension.')],
                        ['icon' => 'fa-video', 'title' => (($language ?? 'en') === 'ar' ? 'الاستشارات بالفيديو' : 'Video Consultations'), 'description' => (($language ?? 'en') === 'ar' ? 'استشارات طبية عن بُعد مريحة وآمنة.' : 'Convenient and secure remote medical consultations.')],
                        ['icon' => 'fa-clipboard-check', 'title' => (($language ?? 'en') === 'ar' ? 'طب الوقاية' : 'Preventive Medicine'), 'description' => (($language ?? 'en') === 'ar' ? 'برامج الفحص المبكر والتطعيمات.' : 'Early screening programs and vaccinations.')],
                    ];
                @endphp
                @foreach($services as $index => $service)
                <div class="col-lg-4 col-md-6 animate-on-scroll" style="transition-delay: {{ $index * 0.1 }}s;">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas {{ $service['icon'] ?? 'fa-heartbeat' }}"></i>
                        </div>
                        <h3 class="service-title">{{ $service['title'] }}</h3>
                        <p class="service-description">{{ $service['description'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if($landingPage->section_visibility['reviews'] ?? true)
    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials-section">
        <div class="container">
            <div class="section-header animate-on-scroll">
                <p class="section-subtitle">{{ ($language ?? 'en') === 'ar' ? 'شهادات المرضى' : 'Testimonials' }}</p>
                <h2 class="section-title">{{ $translatedContent['testimonials_title'] ?: (($language ?? 'en') === 'ar' ? 'ماذا يقول مرضانا' : 'What Our Patients Say') }}</h2>
            </div>

            @if($reviews->count() > 0)
            <div class="testimonial-carousel animate-on-scroll" style="transition-delay: 0.2s;">
                <div class="testimonial-track" id="testimonialTrack">
                    @foreach($reviews as $review)
                    <div class="testimonial-slide">
                        <div class="testimonial-content">
                            <div class="testimonial-rating">
                                @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $review->rating ? '' : '-half-alt' }}"></i>
                                @endfor
                            </div>
                            <p class="testimonial-text">{{ $review->comment }}</p>
                            <div class="testimonial-author">
                                @if($review->patient_avatar ?? false)
                                <img src="{{ Storage::url($review->patient_avatar) }}" alt="{{ $review->patient_display_name }}" class="testimonial-avatar">
                                @else
                                <div class="testimonial-avatar-placeholder">
                                    <i class="fas fa-user text-muted"></i>
                                </div>
                                @endif
                                <div style="text-align: left;">
                                    <p class="testimonial-name">{{ $review->patient_display_name }}</p>
                                    <p class="testimonial-info">{{ $review->formatted_date }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="carousel-controls">
                    <button class="carousel-btn" id="prevBtn"><i class="fas fa-chevron-left"></i></button>
                    <button class="carousel-btn" id="nextBtn"><i class="fas fa-chevron-right"></i></button>
                </div>

                <div class="carousel-dots" id="carouselDots">
                    @foreach($reviews as $index => $review)
                    <button class="carousel-dot{{ $index === 0 ? ' active' : '' }}" data-index="{{ $index }}"></button>
                    @endforeach
                </div>
            </div>
            @else
            <div class="text-center py-5 animate-on-scroll">
                <i class="fas fa-star fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">{{ ($language ?? 'en') === 'ar' ? 'لا توجد مراجعات بعد' : 'No reviews yet' }}</h5>
            </div>
            @endif
        </div>
    </section>
    @endif

    @if($landingPage->section_visibility['appointments'] ?? true)
    <!-- Appointment Form Section -->
    <section id="appointments" class="appointment-section">
        <div class="container">
            <div class="section-header animate-on-scroll">
                <p class="section-subtitle">{{ ($language ?? 'en') === 'ar' ? 'احجزي موعدك' : 'Book Your Appointment' }}</p>
                <h2 class="section-title">{{ $translatedContent['appointment_title'] ?: (($language ?? 'en') === 'ar' ? 'ابدئي رحلتك الصحية' : 'Begin Your Health Journey') }}</h2>
                <p class="section-description">
                    {{ $translatedContent['appointment_subtitle'] ?: (($language ?? 'en') === 'ar' ? 'املئي النموذج أدناه لحجز موعدك بسهولة.' : 'Fill out the form below to schedule your appointment with ease.') }}
                </p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="appointment-form-wrapper animate-on-scroll" style="transition-delay: 0.2s;">
                        @if(!empty($availableSlots))
                        <form id="appointmentForm" action="{{ route('appointments.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
                            <input type="hidden" name="booking_type" value="guest">

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label for="guest_name" class="form-label">{{ $translatedContent['form_name_label'] ?: (($language ?? 'en') === 'ar' ? 'الاسم الكامل *' : 'Full Name *') }}</label>
                                    <input type="text" class="form-control" id="guest_name" name="guest_name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="guest_email" class="form-label">{{ $translatedContent['form_email_label'] ?: (($language ?? 'en') === 'ar' ? 'البريد الإلكتروني *' : 'Email Address *') }}</label>
                                    <input type="email" class="form-control" id="guest_email" name="guest_email" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="guest_phone" class="form-label">{{ $translatedContent['form_phone_label'] ?: (($language ?? 'en') === 'ar' ? 'رقم الهاتف *' : 'Phone Number *') }}</label>
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
                                        <option value="male">{{ ($language ?? 'en') === 'ar' ? 'ذكر' : 'Male' }}</option>
                                        <option value="female">{{ ($language ?? 'en') === 'ar' ? 'أنثى' : 'Female' }}</option>
                                        <option value="other">{{ ($language ?? 'en') === 'ar' ? 'آخر' : 'Other' }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="appointment_type" class="form-label">Appointment Type *</label>
                                    <select class="form-select" id="appointment_type" name="appointment_type" required>
                                        <option value="">Select type</option>
                                        @php
                                            $appointmentTypeLabels = [
                                                'in_person' => (($language ?? 'en') === 'ar' ? 'زيارة عيادة' : 'In-Person Visit'),
                                                'video_call' => (($language ?? 'en') === 'ar' ? 'مكالمة فيديو' : 'Video Call'),
                                                'phone_call' => (($language ?? 'en') === 'ar' ? 'مكالمة هاتفية' : 'Phone Call')
                                            ];
                                        @endphp
                                        @foreach($doctor->getEnabledAppointmentTypes() as $type)
                                            <option value="{{ $type }}">{{ $appointmentTypeLabels[$type] ?? $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="appointment_date_select" class="form-label">{{ $translatedContent['form_date_label'] ?: (($language ?? 'en') === 'ar' ? 'التاريخ المفضل *' : 'Preferred Date *') }}</label>
                                    <input type="hidden" id="selected_appointment_datetime" name="appointment_date">
                                    <select class="form-select" id="appointment_date_select" required>
                                        <option value="">{{ ($language ?? 'en') === 'ar' ? 'اختر تاريخاً' : 'Select a date' }}</option>
                                        @foreach($availableSlots as $date => $slots)
                                        <option value="{{ $date }}">{{ \Carbon\Carbon::parse($date)->format('l, M j, Y') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="appointment_time" class="form-label">{{ $translatedContent['form_time_label'] ?: (($language ?? 'en') === 'ar' ? 'الوقت المفضل *' : 'Preferred Time *') }}</label>
                                    <select class="form-select" id="appointment_time" required disabled>
                                        <option value="">{{ ($language ?? 'en') === 'ar' ? 'اختر تاريخاً أولاً' : 'Select a date first' }}</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="reason" class="form-label">Reason for Visit *</label>
                                    <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="{{ ($language ?? 'en') === 'ar' ? 'صف سبب الزيارة...' : 'Please describe your reason for the visit...' }}" required></textarea>
                                </div>
                                <div class="col-12">
                                    <label for="patient_notes" class="form-label">{{ $translatedContent['form_message_label'] ?: (($language ?? 'en') === 'ar' ? 'ملاحظات إضافية (اختيارية)' : 'Additional Notes (Optional)') }}</label>
                                    <textarea class="form-control" id="patient_notes" name="patient_notes" rows="2" placeholder="{{ ($language ?? 'en') === 'ar' ? 'أي معلومات إضافية...' : 'Any additional information...' }}"></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn-submit" id="submitBtn">
                                        <i class="fas fa-calendar-check me-2"></i>{{ $translatedContent['form_submit_button'] ?: (($language ?? 'en') === 'ar' ? 'احجزي موعدك' : 'Book Appointment') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                        @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">{{ ($language ?? 'en') === 'ar' ? 'لا توجد مواعيد متاحة حالياً' : 'No available slots at the moment' }}</h5>
                            <p class="text-muted">{{ ($language ?? 'en') === 'ar' ? 'يرجى التواصل معنا مباشرة لحجز موعد.' : 'Please contact us directly to book an appointment.' }}</p>
                            @if($doctor->phone)
                            <a href="tel:{{ $doctor->phone }}" class="btn btn-elegant btn-elegant-primary mt-3">
                                <i class="fas fa-phone me-2"></i>{{ ($language ?? 'en') === 'ar' ? 'اتصلي بنا' : 'Call Us' }}
                            </a>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if($landingPage->section_visibility['contact'] ?? true)
    <!-- Contact Info Section -->
    <section id="contact" class="contact-section">
        <div class="container">
            <div class="section-header animate-on-scroll">
                <p class="section-subtitle">{{ ($language ?? 'en') === 'ar' ? 'تواصلي معنا' : 'Get In Touch' }}</p>
                <h2 class="section-title">{{ $translatedContent['contact_title'] ?: (($language ?? 'en') === 'ar' ? 'معلومات الاتصال' : 'Contact Information') }}</h2>
            </div>
            <div class="row g-4">
                @if($doctor->phone)
                <div class="col-lg-4 col-md-6 animate-on-scroll">
                    <div class="contact-info-card">
                        <div class="contact-info-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <h4 class="contact-info-title">{{ ($language ?? 'en') === 'ar' ? 'الهاتف' : 'Phone' }}</h4>
                        <p class="contact-info-text">
                            <a href="tel:{{ $doctor->phone }}">{{ $doctor->phone }}</a>
                        </p>
                    </div>
                </div>
                @endif
                @if($doctor->user->email)
                <div class="col-lg-4 col-md-6 animate-on-scroll" style="transition-delay: 0.1s;">
                    <div class="contact-info-card">
                        <div class="contact-info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h4 class="contact-info-title">{{ ($language ?? 'en') === 'ar' ? 'البريد الإلكتروني' : 'Email' }}</h4>
                        <p class="contact-info-text">
                            <a href="mailto:{{ $doctor->user->email }}">{{ $doctor->user->email }}</a>
                        </p>
                    </div>
                </div>
                @endif
                @if($doctor->full_address)
                <div class="col-lg-4 col-md-6 animate-on-scroll" style="transition-delay: 0.2s;">
                    <div class="contact-info-card">
                        <div class="contact-info-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h4 class="contact-info-title">{{ ($language ?? 'en') === 'ar' ? 'العنوان' : 'Address' }}</h4>
                        <p class="contact-info-text">{{ $doctor->full_address }}</p>
                    </div>
                </div>
                @endif
                @if($doctor->working_hours)
                <div class="col-lg-4 col-md-6 animate-on-scroll" style="transition-delay: 0.3s;">
                    <div class="contact-info-card">
                        <div class="contact-info-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h4 class="contact-info-title">{{ ($language ?? 'en') === 'ar' ? 'ساعات العمل' : 'Working Hours' }}</h4>
                        <p class="contact-info-text">{{ $doctor->working_hours }}</p>
                    </div>
                </div>
                @endif
                @if($doctor->whatsapp)
                <div class="col-lg-4 col-md-6 animate-on-scroll" style="transition-delay: 0.4s;">
                    <div class="contact-info-card">
                        <div class="contact-info-icon">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <h4 class="contact-info-title">WhatsApp</h4>
                        <p class="contact-info-text">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $doctor->whatsapp) }}" target="_blank">{{ $doctor->whatsapp }}</a>
                        </p>
                    </div>
                </div>
                @endif
                @if($landingPage->social_links ?? false)
                <div class="col-lg-4 col-md-6 animate-on-scroll" style="transition-delay: 0.5s;">
                    <div class="contact-info-card">
                        <div class="contact-info-icon">
                            <i class="fas fa-share-alt"></i>
                        </div>
                        <h4 class="contact-info-title">{{ ($language ?? 'en') === 'ar' ? 'تابعينا' : 'Follow Us' }}</h4>
                        <p class="contact-info-text">
                            @if($landingPage->social_links['facebook'] ?? false)
                            <a href="{{ $landingPage->social_links['facebook'] }}" target="_blank" class="me-2"><i class="fab fa-facebook"></i></a>
                            @endif
                            @if($landingPage->social_links['twitter'] ?? false)
                            <a href="{{ $landingPage->social_links['twitter'] }}" target="_blank" class="me-2"><i class="fab fa-twitter"></i></a>
                            @endif
                            @if($landingPage->social_links['instagram'] ?? false)
                            <a href="{{ $landingPage->social_links['instagram'] }}" target="_blank" class="me-2"><i class="fab fa-instagram"></i></a>
                            @endif
                            @if($landingPage->social_links['linkedin'] ?? false)
                            <a href="{{ $landingPage->social_links['linkedin'] }}" target="_blank"><i class="fab fa-linkedin"></i></a>
                            @endif
                        </p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    <!-- Footer -->
    <footer class="elegant-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h3 class="footer-brand">Dr. {{ $doctor->user->name }}</h3>
                    <p class="footer-tagline">{{ $doctor->specialty->name ?? 'Medical Professional' }}</p>
                </div>
                <div class="col-lg-6 text-lg-end">
                    @if($doctor->phone)
                    <p class="footer-tagline mb-2">
                        <i class="fas fa-phone me-2"></i>{{ $doctor->phone }}
                    </p>
                    @endif
                    @if($doctor->user->email)
                    <p class="footer-tagline">
                        <i class="fas fa-envelope me-2"></i>{{ $doctor->user->email }}
                    </p>
                    @endif
                </div>
            </div>
            <hr class="footer-divider">
            <div class="footer-bottom">
                <p class="footer-copyright">&copy; {{ date('Y') }} Dr. {{ $doctor->user->name }}. {{ ($language ?? 'en') === 'ar' ? 'جميع الحقوق محفوظة' : 'All rights reserved.' }}</p>
                <div class="footer-links">
                    <a href="#home">{{ ($language ?? 'en') === 'ar' ? 'الرئيسية' : 'Home' }}</a>
                    <a href="#about">{{ ($language ?? 'en') === 'ar' ? 'نبذة عني' : 'About' }}</a>
                    <a href="#services">{{ ($language ?? 'en') === 'ar' ? 'الخدمات' : 'Services' }}</a>
                    <a href="#contact">{{ ($language ?? 'en') === 'ar' ? 'اتصل بنا' : 'Contact' }}</a>
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
        (function() {
            var nav = document.getElementById('mainNav');
            var sections = document.querySelectorAll('section[id]');
            var navLinks = document.querySelectorAll('.navbar-nav .nav-link');
            var ticking = false;

            // Consolidated scroll handler using requestAnimationFrame
            function onScroll() {
                if (!ticking) {
                    requestAnimationFrame(function() {
                        var scrollY = window.scrollY;

                        // Navbar scroll effect
                        if (nav) {
                            nav.classList.toggle('scrolled', scrollY > 50);
                        }

                        // Active nav link on scroll
                        var current = '';
                        var navHeight = nav ? nav.offsetHeight : 0;
                        sections.forEach(function(section) {
                            var sectionTop = section.offsetTop - navHeight - 100;
                            if (scrollY >= sectionTop) {
                                current = section.getAttribute('id');
                            }
                        });
                        navLinks.forEach(function(link) {
                            link.classList.remove('active');
                            if (link.getAttribute('href') === '#' + current) {
                                link.classList.add('active');
                            }
                        });

                        ticking = false;
                    });
                    ticking = true;
                }
            }

            window.addEventListener('scroll', onScroll);

            // Animate on scroll using IntersectionObserver (already efficient)
            var animateElements = document.querySelectorAll('.animate-on-scroll');
            if (animateElements.length > 0) {
                var observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('visible');
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

                animateElements.forEach(function(el) {
                    observer.observe(el);
                });
            }

            // Testimonial carousel
            var track = document.getElementById('testimonialTrack');
            var prevBtn = document.getElementById('prevBtn');
            var nextBtn = document.getElementById('nextBtn');
            var dots = document.querySelectorAll('.carousel-dot');

            if (track && prevBtn && nextBtn) {
                var currentIndex = 0;
                var totalSlides = track.children.length;

                function updateCarousel() {
                    track.style.transform = 'translateX(' + (-currentIndex * 100) + '%)';
                    dots.forEach(function(dot, index) {
                        if (dot) {
                            dot.classList.toggle('active', index === currentIndex);
                        }
                    });
                }

                prevBtn.addEventListener('click', function() {
                    currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
                    updateCarousel();
                });

                nextBtn.addEventListener('click', function() {
                    currentIndex = (currentIndex + 1) % totalSlides;
                    updateCarousel();
                });

                dots.forEach(function(dot) {
                    if (dot) {
                        dot.addEventListener('click', function() {
                            currentIndex = parseInt(this.getAttribute('data-index'), 10);
                            updateCarousel();
                        });
                    }
                });

                // Auto-advance carousel
                setInterval(function() {
                    if (totalSlides > 1) {
                        currentIndex = (currentIndex + 1) % totalSlides;
                        updateCarousel();
                    }
                }, 6000);
            }

            // Appointment form - date/time handling
            var availableSlots = {!! json_encode($availableSlots ?? []) !!};
            var dateSelect = document.getElementById('appointment_date_select');
            var timeSelect = document.getElementById('appointment_time');
            var hiddenDateTime = document.getElementById('selected_appointment_datetime');

            var selectDateText = '{{ ($language ?? "en") === "ar" ? "اختر تاريخاً أولاً" : "Select a date first" }}';
            var noSlotsText = '{{ ($language ?? "en") === "ar" ? "لا توجد مواعيد" : "No slots available" }}';
            var loadingText = '{{ ($language ?? "en") === "ar" ? "جارٍ التحميل..." : "Loading..." }}';
            var selectTimeText = '{{ ($language ?? "en") === "ar" ? "اختر وقتاً" : "Select a time" }}';

            if (dateSelect && timeSelect) {
                dateSelect.addEventListener('change', function() {
                    var selectedDate = this.value;

                    timeSelect.innerHTML = '';
                    var loadingOption = document.createElement('option');
                    loadingOption.textContent = loadingText;
                    timeSelect.appendChild(loadingOption);
                    timeSelect.disabled = true;

                    if (selectedDate && availableSlots[selectedDate]) {
                        timeSelect.innerHTML = '';
                        var defaultOption = document.createElement('option');
                        defaultOption.value = '';
                        defaultOption.textContent = selectTimeText;
                        timeSelect.appendChild(defaultOption);

                        availableSlots[selectedDate].forEach(function(slot) {
                            var option = document.createElement('option');
                            option.value = slot.datetime;
                            option.textContent = slot.start_time + ' - ' + slot.end_time;
                            timeSelect.appendChild(option);
                        });
                        timeSelect.disabled = false;
                    } else {
                        timeSelect.innerHTML = '';
                        var noOption = document.createElement('option');
                        noOption.value = '';
                        noOption.textContent = noSlotsText;
                        timeSelect.appendChild(noOption);
                    }
                });

                if (timeSelect) {
                    timeSelect.addEventListener('change', function() {
                        if (hiddenDateTime) {
                            hiddenDateTime.value = this.value;
                        }
                    });
                }
            }

            // Form submission
            var appointmentForm = document.getElementById('appointmentForm');
            if (appointmentForm) {
                appointmentForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    var submitBtn = document.getElementById('submitBtn');
                    var originalText = submitBtn.innerHTML;

                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ ($language ?? "en") === "ar" ? "جارٍ الحجز..." : "Booking..." }}';

                    fetch(this.action, {
                        method: 'POST',
                        body: new FormData(this),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(function(response) {
                        if (response.ok) {
                            alert('{{ ($language ?? "en") === "ar" ? "تم حجز موعدك بنجاح!" : "Appointment booked successfully!" }}');
                            appointmentForm.reset();
                            if (timeSelect) {
                                timeSelect.innerHTML = '';
                                var resetOption = document.createElement('option');
                                resetOption.value = '';
                                resetOption.textContent = selectDateText;
                                timeSelect.appendChild(resetOption);
                                timeSelect.disabled = true;
                            }
                        } else {
                            throw new Error('Booking failed');
                        }
                    })
                    .catch(function(error) {
                        alert('{{ ($language ?? "en") === "ar" ? "حدث خطأ. يرجى المحاولة مرة أخرى." : "An error occurred. Please try again." }}');
                    })
                    .finally(function() {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    });
                });
            }

            // Smooth scrolling for navigation links
            document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
                anchor.addEventListener('click', function(e) {
                    var targetId = this.getAttribute('href');
                    if (targetId === '#') return;

                    var target = document.querySelector(targetId);
                    if (target) {
                        e.preventDefault();
                        var navHeight = nav ? nav.offsetHeight : 0;
                        var targetPosition = target.offsetTop - navHeight - 20;

                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });

                        // Close mobile menu if open
                        var navCollapse = document.getElementById('navbarNav');
                        if (navCollapse && navCollapse.classList.contains('show')) {
                            var bsCollapse = bootstrap.Collapse.getInstance(navCollapse);
                            if (bsCollapse) {
                                bsCollapse.hide();
                            }
                        }
                    }
                });
            });
        })();
    </script>

    @stack('scripts')
</body>
</html>
