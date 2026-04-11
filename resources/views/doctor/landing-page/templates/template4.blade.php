<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ session('direction', 'ltr') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@if(isset($doctor)) {{ $doctor->name }} - Premium Healthcare Services @else Premium Healthcare Services @endif">
    <title>@if(isset($doctor)) {{ $doctor->name }} | Expert Care @else Dr. Alexander Sterling | Expert Care @endif</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --amber-accent: #ffbf00;
            --amber-dark: #e6a900;
            --amber-light: #ffd54f;
            --dark-primary: #0a0a0a;
            --dark-secondary: #141414;
            --dark-tertiary: #1a1a1a;
            --dark-card: #1f1f1f;
            --text-primary: #ffffff;
            --text-secondary: #b3b3b3;
            --text-muted: #737373;
            --font-heading: 'Cormorant Garamond', serif;
            --font-body: 'DM Sans', sans-serif;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: var(--font-body);
            background-color: var(--dark-primary);
            color: var(--text-primary);
            line-height: 1.7;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-heading);
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        [dir="rtl"] { text-align: right; }

        /* NAVBAR */
        .navbar-dark-theme {
            background: rgba(10, 10, 10, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 191, 0, 0.1);
            padding: 1rem 0;
            transition: all 0.4s ease;
        }
        .navbar-dark-theme.scrolled { padding: 0.5rem 0; background: rgba(10, 10, 10, 0.98); }
        .navbar-brand { font-family: var(--font-heading); font-size: 1.8rem; font-weight: 700; color: var(--amber-accent) !important; text-decoration: none; }
        .navbar-brand span { color: var(--text-primary); }
        .nav-link { font-family: var(--font-body); font-weight: 500; color: var(--text-primary) !important; margin: 0 0.75rem; position: relative; transition: color 0.3s ease; }
        .nav-link::after { content: ''; position: absolute; bottom: -5px; left: 0; width: 0; height: 2px; background: var(--amber-accent); transition: width 0.3s ease; }
        .nav-link:hover::after, .nav-link.active::after { width: 100%; }
        .nav-link:hover { color: var(--amber-accent) !important; }
        .navbar-cta { background: var(--amber-accent); color: var(--dark-primary) !important; font-weight: 600; padding: 0.6rem 1.5rem !important; border-radius: 0; margin-left: 1rem; transition: all 0.3s ease; }
        .navbar-cta:hover { background: var(--amber-dark); transform: translateY(-2px); }
        .navbar-cta::after { display: none; }

        /* HERO */
        .hero-section {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--dark-primary) 0%, var(--dark-secondary) 50%, rgba(255, 191, 0, 0.05) 100%);
            position: relative;
            display: flex;
            align-items: center;
            overflow: hidden;
        }
        .hero-section::before { content: ''; position: absolute; top: -50%; right: -20%; width: 80%; height: 150%; background: radial-gradient(ellipse, rgba(255, 191, 0, 0.08) 0%, transparent 70%); pointer-events: none; }
        .hero-section::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 200px; background: linear-gradient(to top, var(--dark-primary), transparent); pointer-events: none; }
        .hero-content { position: relative; z-index: 2; }
        .hero-badge { display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(255, 191, 0, 0.1); border: 1px solid rgba(255, 191, 0, 0.3); padding: 0.5rem 1.25rem; font-size: 0.85rem; color: var(--amber-accent); margin-bottom: 1.5rem; letter-spacing: 0.1em; text-transform: uppercase; }
        .hero-title { font-size: clamp(3rem, 7vw, 5.5rem); font-weight: 700; line-height: 1.05; margin-bottom: 1.5rem; color: var(--text-primary); }
        .hero-title .accent { color: var(--amber-accent); display: block; }
        .hero-subtitle { font-size: 1.25rem; color: var(--text-secondary); max-width: 500px; margin-bottom: 2.5rem; line-height: 1.8; }
        .hero-buttons { display: flex; gap: 1rem; flex-wrap: wrap; }
        .btn-primary-gold { background: var(--amber-accent); color: var(--dark-primary); font-weight: 600; padding: 1rem 2.5rem; border: none; border-radius: 0; font-size: 1rem; text-transform: uppercase; letter-spacing: 0.05em; transition: all 0.4s ease; text-decoration: none; display: inline-flex; align-items: center; gap: 0.75rem; }
        .btn-primary-gold:hover { background: var(--amber-dark); color: var(--dark-primary); transform: translateY(-3px); box-shadow: 0 15px 40px rgba(255, 191, 0, 0.3); }
        .btn-outline-gold { background: transparent; color: var(--amber-accent); font-weight: 600; padding: 1rem 2.5rem; border: 2px solid var(--amber-accent); border-radius: 0; font-size: 1rem; text-transform: uppercase; letter-spacing: 0.05em; transition: all 0.4s ease; text-decoration: none; display: inline-flex; align-items: center; gap: 0.75rem; }
        .btn-outline-gold:hover { background: var(--amber-accent); color: var(--dark-primary); transform: translateY(-3px); }
        .hero-image { position: relative; z-index: 2; }
        .hero-image img { width: 100%; max-width: 450px; height: auto; object-fit: cover; filter: grayscale(20%); border: 3px solid var(--amber-accent); }
        .hero-image::before { content: ''; position: absolute; top: 20px; left: 20px; right: -20px; bottom: -20px; border: 2px solid rgba(255, 191, 0, 0.3); z-index: -1; }
        .hero-stats { display: flex; gap: 3rem; margin-top: 3rem; padding-top: 2rem; border-top: 1px solid rgba(255, 255, 255, 0.1); }
        .hero-stat { text-align: center; }
        .hero-stat-number { font-family: var(--font-heading); font-size: 3rem; font-weight: 700; color: var(--amber-accent); line-height: 1; }
        .hero-stat-label { font-size: 0.9rem; color: var(--text-secondary); margin-top: 0.5rem; }

        /* TRUST */
        .trust-section { background: var(--dark-secondary); padding: 3rem 0; border-top: 1px solid rgba(255, 191, 0, 0.1); border-bottom: 1px solid rgba(255, 191, 0, 0.1); }
        .trust-item { display: flex; align-items: center; gap: 1rem; padding: 1rem 2rem; background: var(--dark-tertiary); border: 1px solid rgba(255, 191, 0, 0.1); transition: all 0.3s ease; }
        .trust-item:hover { border-color: var(--amber-accent); transform: translateY(-3px); }
        .trust-icon { width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; background: rgba(255, 191, 0, 0.1); color: var(--amber-accent); font-size: 1.25rem; }
        .trust-text h5 { font-family: var(--font-body); font-size: 0.95rem; font-weight: 600; color: var(--text-primary); margin-bottom: 0.15rem; }
        .trust-text p { font-size: 0.8rem; color: var(--text-muted); margin: 0; }

        /* ABOUT */
        .about-section { padding: 8rem 0; background: var(--dark-primary); position: relative; }
        .section-label { display: inline-flex; align-items: center; gap: 0.5rem; color: var(--amber-accent); font-size: 0.85rem; font-weight: 600; letter-spacing: 0.15em; text-transform: uppercase; margin-bottom: 1rem; }
        .section-title { font-size: clamp(2.5rem, 5vw, 3.5rem); font-weight: 700; margin-bottom: 1.5rem; color: var(--text-primary); }
        .about-content { padding-right: 3rem; }
        [dir="rtl"] .about-content { padding-right: 0; padding-left: 3rem; }
        .about-text { color: var(--text-secondary); font-size: 1.1rem; margin-bottom: 2rem; line-height: 1.9; }
        .about-highlights { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; margin-bottom: 2.5rem; }
        .about-highlight { display: flex; align-items: flex-start; gap: 1rem; }
        .about-highlight-icon { width: 40px; height: 40px; min-width: 40px; display: flex; align-items: center; justify-content: center; background: rgba(255, 191, 0, 0.1); color: var(--amber-accent); font-size: 0.9rem; }
        .about-highlight h6 { font-family: var(--font-body); font-size: 1rem; font-weight: 600; margin-bottom: 0.25rem; }
        .about-highlight p { font-size: 0.85rem; color: var(--text-muted); margin: 0; }
        .timeline-wrapper { position: relative; }
        .timeline-item { display: flex; gap: 1.5rem; margin-bottom: 2rem; position: relative; }
        .timeline-item::before { content: ''; position: absolute; left: 20px; top: 50px; bottom: -30px; width: 2px; background: rgba(255, 191, 0, 0.2); }
        .timeline-item:last-child::before { display: none; }
        [dir="rtl"] .timeline-item::before { left: auto; right: 20px; }
        .timeline-marker { width: 42px; height: 42px; min-width: 42px; display: flex; align-items: center; justify-content: center; background: var(--amber-accent); color: var(--dark-primary); font-weight: 700; font-size: 0.85rem; }
        .timeline-content h5 { font-family: var(--font-body); font-size: 1.1rem; font-weight: 600; margin-bottom: 0.25rem; }
        .timeline-content p { color: var(--text-secondary); font-size: 0.9rem; margin: 0 0 0.5rem; }
        .timeline-content .year { color: var(--amber-accent); font-weight: 600; font-size: 0.9rem; }

        /* SERVICES */
        .services-section { padding: 8rem 0; background: var(--dark-secondary); position: relative; }
        .services-section::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent, var(--amber-accent), transparent); }
        .service-card { background: var(--dark-card); border: 1px solid rgba(255, 255, 255, 0.05); padding: 2.5rem; height: 100%; position: relative; overflow: hidden; transition: all 0.5s ease; }
        .service-card::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: var(--amber-accent); transform: scaleX(0); transform-origin: left; transition: transform 0.5s ease; }
        .service-card:hover::before { transform: scaleX(1); }
        .service-card:hover { border-color: rgba(255, 191, 0, 0.3); transform: translateY(-10px); box-shadow: 0 25px 60px rgba(0, 0, 0, 0.4); }
        .service-icon { width: 70px; height: 70px; display: flex; align-items: center; justify-content: center; background: rgba(255, 191, 0, 0.1); color: var(--amber-accent); font-size: 1.5rem; margin-bottom: 1.5rem; transition: all 0.4s ease; }
        .service-card:hover .service-icon { background: var(--amber-accent); color: var(--dark-primary); transform: scale(1.1); }
        .service-card h4 { font-size: 1.5rem; margin-bottom: 1rem; transition: color 0.3s ease; }
        .service-card:hover h4 { color: var(--amber-accent); }
        .service-card p { color: var(--text-secondary); font-size: 0.95rem; margin-bottom: 1.5rem; line-height: 1.7; }
        .service-link { color: var(--amber-accent); font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; transition: gap 0.3s ease; }
        .service-link:hover { color: var(--amber-light); gap: 1rem; }

        /* TESTIMONIALS */
        .testimonials-section { padding: 8rem 0; background: var(--dark-primary); position: relative; }
        .testimonial-card { background: var(--dark-secondary); border: 1px solid rgba(255, 255, 255, 0.05); padding: 3rem; height: 100%; position: relative; }
        .testimonial-card::before { content: '"'; position: absolute; top: 20px; left: 30px; font-family: var(--font-heading); font-size: 6rem; color: rgba(255, 191, 0, 0.15); line-height: 1; }
        .testimonial-stars { display: flex; gap: 0.25rem; margin-bottom: 1.5rem; }
        .testimonial-stars i { color: var(--amber-accent); font-size: 1rem; }
        .testimonial-text { font-size: 1.1rem; color: var(--text-secondary); line-height: 1.8; margin-bottom: 2rem; position: relative; z-index: 1; }
        .testimonial-author { display: flex; align-items: center; gap: 1rem; }
        .testimonial-avatar { width: 60px; height: 60px; border-radius: 50%; background: var(--amber-accent); display: flex; align-items: center; justify-content: center; font-family: var(--font-heading); font-size: 1.5rem; font-weight: 700; color: var(--dark-primary); }
        .testimonial-info h5 { font-family: var(--font-body); font-size: 1rem; font-weight: 600; margin-bottom: 0.15rem; }
        .testimonial-info p { font-size: 0.85rem; color: var(--amber-accent); margin: 0; }

        /* APPOINTMENT */
        .appointment-section { padding: 8rem 0; background: linear-gradient(135deg, var(--dark-secondary) 0%, var(--dark-tertiary) 100%); position: relative; }
        .appointment-section::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent, var(--amber-accent), transparent); }
        .appointment-card { background: var(--dark-card); border: 1px solid rgba(255, 191, 0, 0.2); padding: 3rem; position: relative; overflow: hidden; }
        .appointment-card::before { content: ''; position: absolute; top: 0; right: 0; width: 300px; height: 300px; background: radial-gradient(circle, rgba(255, 191, 0, 0.1) 0%, transparent 70%); pointer-events: none; }
        .form-control, .form-select { background: var(--dark-tertiary); border: 1px solid rgba(255, 255, 255, 0.1); color: var(--text-primary); padding: 1rem 1.25rem; border-radius: 0; font-size: 1rem; transition: all 0.3s ease; }
        .form-control:focus, .form-select:focus { background: var(--dark-tertiary); border-color: var(--amber-accent); box-shadow: 0 0 0 3px rgba(255, 191, 0, 0.1); color: var(--text-primary); outline: none; }
        .form-control::placeholder { color: var(--text-muted); }
        .form-label { color: var(--text-secondary); font-weight: 500; font-size: 0.9rem; margin-bottom: 0.5rem; }
        .form-floating > .form-control:focus, .form-floating > .form-control:not(:placeholder-shown) { padding-top: 1.625rem; padding-bottom: 0.625rem; }
        .btn-submit { background: var(--amber-accent); color: var(--dark-primary); font-weight: 700; padding: 1.1rem 3rem; border: none; border-radius: 0; font-size: 1rem; text-transform: uppercase; letter-spacing: 0.1em; width: 100%; transition: all 0.4s ease; }
        .btn-submit:hover { background: var(--amber-dark); transform: translateY(-3px); box-shadow: 0 15px 40px rgba(255, 191, 0, 0.3); }

        /* CONTACT */
        .contact-section { padding: 6rem 0; background: var(--dark-primary); }
        .contact-card { background: var(--dark-secondary); border: 1px solid rgba(255, 255, 255, 0.05); padding: 2.5rem; text-align: center; height: 100%; transition: all 0.4s ease; }
        .contact-card:hover { border-color: var(--amber-accent); transform: translateY(-5px); }
        .contact-icon { width: 70px; height: 70px; display: flex; align-items: center; justify-content: center; background: rgba(255, 191, 0, 0.1); color: var(--amber-accent); font-size: 1.5rem; margin: 0 auto 1.5rem; transition: all 0.4s ease; }
        .contact-card:hover .contact-icon { background: var(--amber-accent); color: var(--dark-primary); }
        .contact-card h5 { font-family: var(--font-body); font-size: 1.15rem; font-weight: 600; margin-bottom: 0.75rem; }
        .contact-card p { color: var(--text-secondary); font-size: 0.95rem; margin: 0; line-height: 1.6; }

        /* FOOTER */
        .footer { background: var(--dark-secondary); border-top: 1px solid rgba(255, 191, 0, 0.1); padding: 5rem 0 2rem; }
        .footer-brand { font-family: var(--font-heading); font-size: 2rem; font-weight: 700; color: var(--amber-accent); margin-bottom: 1rem; display: block; }
        .footer-brand span { color: var(--text-primary); }
        .footer-text { color: var(--text-secondary); font-size: 0.95rem; line-height: 1.8; margin-bottom: 1.5rem; max-width: 300px; }
        .footer-social { display: flex; gap: 0.75rem; }
        .footer-social a { width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; background: var(--dark-tertiary); color: var(--text-primary); border: 1px solid rgba(255, 255, 255, 0.1); transition: all 0.3s ease; }
        .footer-social a:hover { background: var(--amber-accent); color: var(--dark-primary); border-color: var(--amber-accent); transform: translateY(-3px); }
        .footer-title { font-family: var(--font-body); font-size: 1.1rem; font-weight: 600; color: var(--text-primary); margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .footer-links { list-style: none; padding: 0; margin: 0; }
        .footer-links li { margin-bottom: 0.75rem; }
        .footer-links a { color: var(--text-secondary); text-decoration: none; font-size: 0.95rem; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 0.5rem; }
        .footer-links a:hover { color: var(--amber-accent); padding-left: 5px; }
        [dir="rtl"] .footer-links a:hover { padding-left: 0; padding-right: 5px; }
        .footer-hours li { display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(255, 255, 255, 0.05); font-size: 0.9rem; }
        .footer-hours li span:first-child { color: var(--text-secondary); }
        .footer-hours li span:last-child { color: var(--text-primary); font-weight: 500; }
        .footer-bottom { border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 2rem; margin-top: 3rem; }
        .footer-copyright { color: var(--text-muted); font-size: 0.9rem; margin: 0; }
        .footer-copyright a { color: var(--amber-accent); text-decoration: none; }

        /* SCROLL TOP */
        .scroll-top { position: fixed; bottom: 30px; right: 30px; width: 50px; height: 50px; background: var(--amber-accent); color: var(--dark-primary); border: none; border-radius: 0; display: flex; align-items: center; justify-content: center; cursor: pointer; opacity: 0; visibility: hidden; transition: all 0.4s ease; z-index: 1000; }
        .scroll-top.visible { opacity: 1; visibility: visible; }
        .scroll-top:hover { background: var(--amber-dark); transform: translateY(-5px); }
        [dir="rtl"] .scroll-top { right: auto; left: 30px; }

        /* RESPONSIVE */
        @media (max-width: 991.98px) {
            .hero-stats { gap: 2rem; }
            .hero-stat-number { font-size: 2.5rem; }
            .about-content { padding-right: 0; margin-bottom: 3rem; }
            [dir="rtl"] .about-content { padding-left: 0; }
        }
        @media (max-width: 767.98px) {
            .hero-section { padding-top: 100px; }
            .hero-title { font-size: 2.5rem; }
            .hero-image { margin-top: 3rem; }
            .hero-image img { max-width: 300px; }
            .hero-stats { flex-wrap: wrap; gap: 1.5rem; }
            .about-highlights { grid-template-columns: 1fr; }
            .section-title { font-size: 2rem; }
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark-theme fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="#">
                @if(isset($doctor) && isset($doctor->name))
                    {{ explode(' ', $doctor->name)[0] }} <span>{{ explode(' ', $doctor->name)[1] ?? '' }}</span>
                @else
                    Dr. <span>Sterling</span>
                @endif
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto me-3">
                    <li class="nav-item"><a class="nav-link active" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                    @if(isset($showTestimonials) && $showTestimonials)
                    <li class="nav-item"><a class="nav-link" href="#testimonials">Testimonials</a></li>
                    @endif
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                </ul>
                <a href="#appointment" class="nav-link navbar-cta">Book Appointment</a>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero-section" id="home">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <div class="hero-badge"><i class="fas fa-award"></i><span>Board Certified Specialist</span></div>
                        <h1 class="hero-title">
                            @if(isset($doctor) && isset($doctor->name))
                                {{ $doctor->name }}
                            @else
                                Dr. Alexander<span class="accent">Sterling</span>
                            @endif
                        </h1>
                        <p class="hero-subtitle">
                            @if(isset($doctor) && isset($doctor->specialty))
                                Leading {{ $doctor->specialty }} expert delivering personalized care with cutting-edge treatments and compassionate approach.
                            @else
                                Leading Cardiologist delivering personalized cardiac care with cutting-edge treatments and compassionate approach for over 20 years.
                            @endif
                        </p>
                        <div class="hero-buttons">
                            <a href="#appointment" class="btn-primary-gold">Book Consultation <i class="fas fa-arrow-right"></i></a>
                            <a href="#services" class="btn-outline-gold">Our Services <i class="fas fa-chevron-down"></i></a>
                        </div>
                        @if(isset($showStats) && $showStats)
                        <div class="hero-stats">
                            <div class="hero-stat"><div class="hero-stat-number">20+</div><div class="hero-stat-label">Years Experience</div></div>
                            <div class="hero-stat"><div class="hero-stat-number">15K+</div><div class="hero-stat-label">Patients Treated</div></div>
                            <div class="hero-stat"><div class="hero-stat-number">98%</div><div class="hero-stat-label">Success Rate</div></div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image">
                        @if(isset($doctor) && isset($doctor->avatar))
                            <img src="{{ $doctor->avatar }}" alt="{{ $doctor->name }}">
                        @else
                            <img src="https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?w=450&h=550&fit=crop&crop=face" alt="Dr. Alexander Sterling">
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TRUST BADGES -->
    <section class="trust-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6 col-lg-3"><div class="trust-item"><div class="trust-icon"><i class="fas fa-shield-halved"></i></div><div class="trust-text"><h5>Board Certified</h5><p>American Board of Internal Medicine</p></div></div></div>
                <div class="col-md-6 col-lg-3"><div class="trust-item"><div class="trust-icon"><i class="fas fa-hospital"></i></div><div class="trust-text"><h5>Top Rated Hospital</h5><p>Mayo Clinic Affiliated</p></div></div></div>
                <div class="col-md-6 col-lg-3"><div class="trust-item"><div class="trust-icon"><i class="fas fa-users"></i></div><div class="trust-text"><h5>Expert Team</h5><p>Dedicated Specialists</p></div></div></div>
                <div class="col-md-6 col-lg-3"><div class="trust-item"><div class="trust-icon"><i class="fas fa-handshake"></i></div><div class="trust-text"><h5>Patient First</h5><p>Compassionate Care</p></div></div></div>
            </div>
        </div>
    </section>

    <!-- ABOUT -->
    <section class="about-section" id="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="about-content">
                        <div class="section-label"><i class="fas fa-user-md"></i><span>About the Doctor</span></div>
                        <h2 class="section-title">
                            @if(isset($doctor) && isset($doctor->tagline))
                                {{ $doctor->tagline }}
                            @else
                                A Legacy of Excellence in Healthcare
                            @endif
                        </h2>
                        <p class="about-text">
                            @if(isset($doctor) && isset($doctor->bio))
                                {{ $doctor->bio }}
                            @else
                                With over two decades of experience in internal medicine and cardiology, I have dedicated my career to providing exceptional patient care. My approach combines the latest medical advancements with a deep understanding of each patient's unique needs, ensuring personalized treatment plans that deliver lasting results.
                            @endif
                        </p>
                        <div class="about-highlights">
                            <div class="about-highlight"><div class="about-highlight-icon"><i class="fas fa-graduation-cap"></i></div><div><h6>Education</h6><p>Harvard Medical School</p></div></div>
                            <div class="about-highlight"><div class="about-highlight-icon"><i class="fas fa-certificate"></i></div><div><h6>Certifications</h6><p>ABIM, FACC, FSCAI</p></div></div>
                            <div class="about-highlight"><div class="about-highlight-icon"><i class="fas fa-trophy"></i></div><div><h6>Awards</h6><p>Patient's Choice Award 2024</p></div></div>
                            <div class="about-highlight"><div class="about-highlight-icon"><i class="fas fa-language"></i></div><div><h6>Languages</h6><p>English, Arabic, French</p></div></div>
                        </div>
                        @if(isset($doctor) && isset($doctor->cv_url))
                        <a href="{{ $doctor->cv_url }}" class="btn-primary-gold" target="_blank">Download CV <i class="fas fa-download"></i></a>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="timeline-wrapper">
                        <div class="section-label mb-4"><i class="fas fa-clock"></i><span>Career Journey</span></div>
                        <div class="timeline-item"><div class="timeline-marker">2024</div><div class="timeline-content"><h5>Chief of Cardiology</h5><p>Joined Metropolitan Heart Institute as Chief of Cardiology</p><span class="year">Present</span></div></div>
                        <div class="timeline-item"><div class="timeline-marker">2018</div><div class="timeline-content"><h5>Advanced Fellowship</h5><p>Completed interventional cardiology fellowship at Stanford</p><span class="year">2018</span></div></div>
                        <div class="timeline-item"><div class="timeline-marker">2012</div><div class="timeline-content"><h5>Board Certification</h5><p>American Board of Internal Medicine - Cardiovascular Disease</p><span class="year">2012</span></div></div>
                        <div class="timeline-item"><div class="timeline-marker">2008</div><div class="timeline-content"><h5>Residency Completion</h5><p>Completed internal medicine residency at Johns Hopkins Hospital</p><span class="year">2008</span></div></div>
                        <div class="timeline-item"><div class="timeline-marker">2004</div><div class="timeline-content"><h5>Medical Degree</h5><p>Graduated with honors from Harvard Medical School</p><span class="year">2004</span></div></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SERVICES -->
    <section class="services-section" id="services">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-label justify-content-center"><i class="fas fa-stethoscope"></i><span>Medical Services</span></div>
                <h2 class="section-title">Comprehensive Healthcare Solutions</h2>
                <p class="text-secondary mx-auto" style="max-width: 600px;">
                    @if(isset($doctor) && isset($doctor->services_description))
                        {{ $doctor->services_description }}
                    @else
                        Delivering exceptional medical care with advanced diagnostic and treatment options tailored to your unique needs.
                    @endif
                </p>
            </div>
            <div class="row g-4">
                @php
                    $services = isset($doctor) && isset($doctor->services) ? $doctor->services : [
                        ['icon' => 'fa-heart-pulse', 'title' => 'Cardiac Care', 'description' => 'Comprehensive heart health assessments, diagnostic testing, and personalized treatment plans for all cardiac conditions.'],
                        ['icon' => 'fa-lungs', 'title' => 'Pulmonary Medicine', 'description' => 'Expert diagnosis and management of respiratory conditions including asthma, COPD, and sleep disorders.'],
                        ['icon' => 'fa-brain', 'title' => 'Neurological Care', 'description' => 'Advanced care for headaches, epilepsy, Parkinson\'s disease, and other neurological conditions.'],
                        ['icon' => 'fa-kit-medical', 'title' => 'Preventive Medicine', 'description' => 'Comprehensive health screenings, wellness programs, and preventive care to maintain optimal health.'],
                        ['icon' => 'fa-heart-pulse', 'title' => 'Interventional Cardiology', 'description' => 'State-of-the-art catheter-based treatments for heart disease including angioplasty and stenting.'],
                        ['icon' => 'fa-user-group', 'title' => 'Patient Education', 'description' => 'Empowering patients with knowledge about their conditions and treatment options for better outcomes.']
                    ];
                @endphp
                @foreach($services as $service)
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="service-icon"><i class="fas {{ $service['icon'] ?? 'fa-stethoscope' }}"></i></div>
                        <h4>{{ $service['title'] }}</h4>
                        <p>{{ $service['description'] }}</p>
                        @if(isset($service['link']))
                        <a href="{{ $service['link'] }}" class="service-link">Learn More <i class="fas fa-arrow-right"></i></a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS -->
    @if(isset($showTestimonials) && $showTestimonials)
    <section class="testimonials-section" id="testimonials">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-label justify-content-center"><i class="fas fa-quote-left"></i><span>Testimonials</span></div>
                <h2 class="section-title">What Patients Say</h2>
            </div>
            <div class="row g-4">
                @php
                    $testimonials = isset($testimonials) ? $testimonials : [
                        ['text' => 'Dr. Sterling completely changed my life. After years of dealing with cardiac issues, I finally found a doctor who truly listens and provides exceptional care. His expertise and compassion are unmatched.', 'name' => 'Sarah Mitchell', 'title' => 'Cardiac Patient', 'initials' => 'SM'],
                        ['text' => 'The level of professionalism and expertise at this practice is outstanding. From the moment you walk in, you feel confident in the care you\'re receiving. Highly recommend to anyone seeking top-tier medical care.', 'name' => 'James Thompson', 'title' => 'Internal Medicine Patient', 'initials' => 'JT'],
                        ['text' => 'Dr. Sterling took the time to explain every aspect of my treatment plan. I\'ve never felt more informed or confident about my health decisions. A truly remarkable physician.', 'name' => 'Maria Rodriguez', 'title' => 'Preventive Care Patient', 'initials' => 'MR']
                    ];
                @endphp
                @foreach($testimonials as $testimonial)
                <div class="col-md-6 col-lg-4">
                    <div class="testimonial-card">
                        <div class="testimonial-stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                        <p class="testimonial-text">{{ $testimonial['text'] }}</p>
                        <div class="testimonial-author">
                            <div class="testimonial-avatar">{{ $testimonial['initials'] }}</div>
                            <div class="testimonial-info"><h5>{{ $testimonial['name'] }}</h5><p>{{ $testimonial['title'] }}</p></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- APPOINTMENT -->
    <section class="appointment-section" id="appointment">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-5 mb-lg-0">
                    <div class="section-label"><i class="fas fa-calendar-check"></i><span>Book Appointment</span></div>
                    <h2 class="section-title">Schedule Your Visit</h2>
                    <p class="text-secondary mb-4">Take the first step towards better health. Book your appointment today and experience healthcare excellence.</p>
                    @if(isset($doctor) && isset($doctor->availability_note))
                    <div class="alert" style="background: rgba(255, 191, 0, 0.1); border: 1px solid rgba(255, 191, 0, 0.3); color: var(--amber-accent);"><i class="fas fa-info-circle me-2"></i>{{ $doctor->availability_note }}</div>
                    @else
                    <div class="alert" style="background: rgba(255, 191, 0, 0.1); border: 1px solid rgba(255, 191, 0, 0.3); color: var(--amber-accent);"><i class="fas fa-info-circle me-2"></i>New patients are welcome. Same-day appointments may be available.</div>
                    @endif
                    <div class="mt-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="contact-icon" style="width: 50px; height: 50px; font-size: 1rem;"><i class="fas fa-phone"></i></div>
                            <div>
                                <p class="text-muted mb-0" style="font-size: 0.85rem;">Need urgent assistance?</p>
                                <h5 class="mb-0" style="font-size: 1.1rem;">@if(isset($doctor) && isset($doctor->phone)) {{ $doctor->phone }} @else +1 (555) 123-4567 @endif</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="appointment-card">
                        <form id="appointmentForm" method="POST" action="{{ isset($formAction) ? $formAction : '#' }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="patientName" name="name" placeholder="Your Name" required>
                                        <label for="patientName">Full Name</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" id="patientEmail" name="email" placeholder="Email Address" required>
                                        <label for="patientEmail">Email Address</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="tel" class="form-control" id="patientPhone" name="phone" placeholder="Phone Number" required>
                                        <label for="patientPhone">Phone Number</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="serviceType" name="service" required>
                                            <option value="" selected disabled>Select Service</option>
                                            @php
                                                $serviceOptions = isset($doctor) && isset($doctor->service_options) ? $doctor->service_options : [
                                                    'Cardiac Consultation',
                                                    'Pulmonary Assessment',
                                                    'Neurological Evaluation',
                                                    'Preventive Health Screening',
                                                    'Follow-up Visit',
                                                    'Second Opinion'
                                                ];
                                            @endphp
                                            @foreach($serviceOptions as $option)
                                            <option value="{{ $option }}">{{ $option }}</option>
                                            @endforeach
                                        </select>
                                        <label for="serviceType">Service Required</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" id="appointmentDate" name="date" min="{{ date('Y-m-d') }}" required>
                                        <label for="appointmentDate">Preferred Date</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="preferredTime" name="time">
                                            <option value="" selected disabled>Select Time</option>
                                            <option value="09:00">9:00 AM</option>
                                            <option value="10:00">10:00 AM</option>
                                            <option value="11:00">11:00 AM</option>
                                            <option value="14:00">2:00 PM</option>
                                            <option value="15:00">3:00 PM</option>
                                            <option value="16:00">4:00 PM</option>
                                        </select>
                                        <label for="preferredTime">Preferred Time</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" id="patientMessage" name="message" placeholder="Additional Notes" style="height: 100px;"></textarea>
                                        <label for="patientMessage">Additional Notes (Optional)</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn-submit" id="submitBtn"><i class="fas fa-calendar-plus me-2"></i>Confirm Appointment</button>
                                </div>
                            </div>
                        </form>
                        <div id="formSuccess" class="d-none text-center py-4">
                            <i class="fas fa-check-circle text-success fs-1 mb-3"></i>
                            <h4>Appointment Requested!</h4>
                            <p class="text-secondary">We will contact you shortly to confirm your appointment.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CONTACT -->
    <section class="contact-section" id="contact">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-label justify-content-center"><i class="fas fa-envelope"></i><span>Get in Touch</span></div>
                <h2 class="section-title">Contact Information</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="contact-card">
                        <div class="contact-icon"><i class="fas fa-location-dot"></i></div>
                        <h5>Office Address</h5>
                        <p>
                            @if(isset($doctor) && isset($doctor->address))
                                {{ $doctor->address }}
                            @else
                                123 Medical Center Drive<br>Suite 456, New York, NY 10001
                            @endif
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-card">
                        <div class="contact-icon"><i class="fas fa-phone-flip"></i></div>
                        <h5>Phone Number</h5>
                        <p>@if(isset($doctor) && isset($doctor->phone)) {{ $doctor->phone }} @else +1 (555) 123-4567 @endif</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-card">
                        <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                        <h5>Email Address</h5>
                        <p>@if(isset($doctor) && isset($doctor->email)) {{ $doctor->email }} @else contact@drsterling.com @endif</p>
                    </div>
                </div>
            </div>
            @if(isset($showMap) && $showMap)
            <div class="row mt-5">
                <div class="col-12">
                    <div class="ratio ratio-21x9" style="max-height: 400px;">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.9663095919355!2d-74.00425878428698!3d40.74076794379132!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c259bf5c1654f3%3A0xc80f9cfce5383d5d!2sGoogle!5e0!3m2!1sen!2sus!4v1614268783524!5m2!1sen!2sus" title="Google Maps location" style="border:0; filter: grayscale(80%) contrast(1.1);" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <a href="#" class="footer-brand">
                        @if(isset($doctor) && isset($doctor->name))
                            {{ explode(' ', $doctor->name)[0] }} <span>{{ explode(' ', $doctor->name)[1] ?? '' }}</span>
                        @else
                            Dr. <span>Sterling</span>
                        @endif
                    </a>
                    <p class="footer-text">Providing exceptional healthcare services with compassion and expertise. Your health is our priority.</p>
                    <div class="footer-social">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-6 col-lg-2">
                    <h5 class="footer-title">Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="#home">Home</a></li>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#appointment">Book Appointment</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2">
                    <h5 class="footer-title">Services</h5>
                    <ul class="footer-links">
                        <li><a href="#services">Cardiac Care</a></li>
                        <li><a href="#services">Pulmonary Medicine</a></li>
                        <li><a href="#services">Neurological Care</a></li>
                        <li><a href="#services">Preventive Care</a></li>
                        <li><a href="#services">Health Screening</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h5 class="footer-title">Office Hours</h5>
                    <ul class="footer-hours">
                        <li><span>Sunday - Thursday</span><span>9:00 AM - 6:00 PM</span></li>
                        <li><span>Friday</span><span>10:00 AM - 4:00 PM</span></li>
                        <li><span>Saturday</span><span>Closed</span></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="footer-copyright">&copy; {{ date('Y') }} @if(isset($doctor) && isset($doctor->name)){{ $doctor->name }}.@else Dr. Alexander Sterling.@endif All Rights Reserved. | Designed with <a href="#"><i class="fas fa-heart" style="color: var(--amber-accent);"></i></a> for exceptional care</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <ul class="footer-links d-inline-flex gap-3">
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">Terms of Service</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top -->
    <button class="scroll-top" id="scrollTop" aria-label="Scroll to top"><i class="fas fa-chevron-up"></i></button>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        (function() {
            var navbar = document.getElementById('mainNav');
            var scrollTopBtn = document.getElementById('scrollTop');
            var sections = document.querySelectorAll('section[id]');
            var navLinks = document.querySelectorAll('.nav-link');
            var ticking = false;

            // Consolidated scroll handler using requestAnimationFrame
            function onScroll() {
                if (!ticking) {
                    requestAnimationFrame(function() {
                        var scrollY = window.scrollY;
                        navbar.classList.toggle('scrolled', scrollY > 50);
                        scrollTopBtn.classList.toggle('visible', scrollY > 500);

                        // Section tracking
                        var current = '';
                        sections.forEach(function(section) {
                            if (scrollY >= section.offsetTop - 200) {
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

            document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    var target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        window.scrollTo({ top: target.offsetTop - navbar.offsetHeight, behavior: 'smooth' });
                    }
                });
            });

            scrollTopBtn.addEventListener('click', function() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

            var appointmentForm = document.getElementById('appointmentForm');
            var formSuccess = document.getElementById('formSuccess');
            var submitBtn = document.getElementById('submitBtn');

            if (appointmentForm) {
                appointmentForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    var name = document.getElementById('patientName').value;
                    var email = document.getElementById('patientEmail').value;
                    var phone = document.getElementById('patientPhone').value;
                    var service = document.getElementById('serviceType').value;
                    var date = document.getElementById('appointmentDate').value;

                    if (!name || !email || !phone || !service || !date) {
                        alert('Please fill in all required fields.');
                        return;
                    }

                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processing...';

                    setTimeout(function() {
                        appointmentForm.style.display = 'none';
                        formSuccess.classList.remove('d-none');
                    }, 1500);
                });
            }
        })();
    </script>
</body>
</html>
