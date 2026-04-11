# MedSuite Redesign Design Document

## Overview

Complete redesign of logged-out public pages with a **Modern Minimalist** aesthetic for the MedSuite healthcare platform. Includes the landing page and all auth pages with split-screen layouts.

---

## 1. Design Language

### Aesthetic Direction
Modern Minimalist — Clean sections with depth through subtle shadows, generous whitespace, refined typography, and purposeful use of color. No visual clutter. Each element earns its place.

### Color Palette

| Role | Color | Hex | Usage |
|------|-------|-----|-------|
| Primary | Teal | `#0d9488` | Buttons, links, active states, brand moments |
| Primary Dark | Deep Teal | `#0f766e` | Hover states, emphasis |
| Primary Light | Light Teal | `#14b8a6` | Accents, highlights |
| Secondary | Forest Green | `#166534` | Success states, patient features |
| Neutral Dark | Slate | `#1e293b` | Headings, primary text |
| Neutral Medium | Gray | `#64748b` | Secondary text, labels |
| Neutral Light | Light Gray | `#f1f5f9` | Backgrounds, cards |
| Border | Border Gray | `#e2e8f0` | Dividers, input borders |
| Surface | White | `#ffffff` | Card surfaces, form backgrounds |
| Text Dark | Near Black | `#0f172a` | Body text |

### Typography

**Font Family:** Inter (Google Fonts)
- Fallback: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif

**Type Scale:**
| Element | Size | Weight | Line Height |
|---------|------|--------|-------------|
| H1 | 3rem (48px) | 700 | 1.1 |
| H2 | 2rem (32px) | 700 | 1.2 |
| H3 | 1.5rem (24px) | 600 | 1.3 |
| H4 | 1.25rem (20px) | 600 | 1.4 |
| Body Large | 1.125rem (18px) | 400 | 1.6 |
| Body | 1rem (16px) | 400 | 1.6 |
| Small | 0.875rem (14px) | 400 | 1.5 |
| Caption | 0.75rem (12px) | 500 | 1.4 |

### Spacing System
Base unit: 4px
| Name | Value | Usage |
|------|-------|-------|
| xs | 4px | Icon gaps, tight spacing |
| sm | 8px | Inline elements |
| md | 16px | Standard gaps |
| lg | 24px | Section internal spacing |
| xl | 32px | Card padding |
| 2xl | 48px | Section margins |
| 3xl | 64px | Large section gaps |
| 4xl | 96px | Hero spacing |

### Border Radius
| Element | Radius |
|---------|--------|
| Buttons | 8px |
| Inputs | 8px |
| Cards | 16px |
| Modals | 20px |
| Large Cards | 24px |

### Shadows
```css
--shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
--shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -2px rgba(0,0,0,0.1);
--shadow-md: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -4px rgba(0,0,0,0.1);
--shadow-lg: 0 25px 50px -12px rgba(0,0,0,0.15);
--shadow-xl: 0 35px 60px -15px rgba(0,0,0,0.2);
```

### Motion Philosophy
Minimal animations — subtle fade-ins (opacity 0→1, 300ms ease-out), smooth hover transitions (200ms), no parallax or distracting motion. Motion indicates interactivity, not decoration.

---

## 2. Layout Structure

### Landing Page — Single Page Sections

```
┌─────────────────────────────────────────────┐
│ NAVIGATION BAR (Fixed, transparent → solid) │
├─────────────────────────────────────────────┤
│ SECTION 1: HERO                            │
│   Headline + Subhead + 2 CTAs + Visual     │
│   Full viewport height                      │
├─────────────────────────────────────────────┤
│ SECTION 2: FEATURES (6-card grid)          │
│   Icon + Title + Description per card      │
├─────────────────────────────────────────────┤
│ SECTION 3: AI FEATURES SPOTLIGHT           │
│   Left: Text content | Right: Illustration  │
├─────────────────────────────────────────────┤
│ SECTION 4: HOW IT WORKS (3-step)           │
│   Numbered steps with connecting line      │
├─────────────────────────────────────────────┤
│ SECTION 5: STATS (horizontal metrics)      │
│   Large numbers with labels               │
├─────────────────────────────────────────────┤
│ SECTION 6: CTA (centered call-to-action)   │
│   Headline + supporting text + button     │
├─────────────────────────────────────────────┤
│ FOOTER                                     │
│   Links + Contact + Social                 │
└─────────────────────────────────────────────┘
```

### Auth Pages — Split Screen

```
┌─────────────────────────────────────────────┐
│ LEFT PANEL (40%)     │ RIGHT PANEL (60%)   │
│                      │                      │
│ Brand + Logo         │ Logo (mobile)        │
│ Headline             │ Form Header          │
│ Subtext              │                     │
│ Feature list         │ Form Fields          │
│                      │                     │
│                      │ [Submit Button]     │
│                      │                     │
│                      │ Links (forgot pw)   │
└─────────────────────────────────────────────┘
```

Mobile: Stacked vertically, form on top

---

## 3. Component Specifications

### 3.1 Navigation Bar

**States:**
- Transparent (at top of page)
- Solid white with shadow (scrolled)

**Content:**
- Left: MedSuite Logo + "MedSuite" wordmark
- Center: Home | About | Contact | For Patients
- Right: Login | Register (button)

**Styling:**
- Height: 72px
- Logo: 32px icon + 24px wordmark
- Links: 16px, medium weight, `#64748b`
- Link hover: `#0d9488`
- Register button: filled teal, rounded

**Behavior:**
- Sticky on scroll
- Mobile: hamburger menu → slide-out drawer

---

### 3.2 Hero Section

**Layout:**
- Left-aligned content (60% width max)
- Background: subtle gradient or large soft shape on right

**Content:**
```
Eyebrow: "AI-Powered Healthcare" (small caps, teal)
H1: "Modern EMR for Modern Practices"
Subhead: "Streamline your healthcare practice with AI-assisted diagnostics,
         voice transcription, and smart patient management."
CTAs: [Get Started Free] [Watch Demo]
```

**Visual:**
- Right side: Abstract geometric pattern or subtle illustration
- Background: Very light gradient (`#f0fdfa` to `#f8fafc`)

**Spacing:**
- Section padding: 120px top, 80px bottom
- H1 margin-bottom: 24px
- Subhead margin-bottom: 32px

---

### 3.3 Feature Cards

**Grid:** 3 columns desktop, 2 tablet, 1 mobile

**Card Content:**
- Icon (48px, teal background circle)
- Title (H4)
- Description (2-3 lines, muted color)

**Styling:**
- Background: white
- Border: 1px solid `#e2e8f0`
- Border-radius: 16px
- Padding: 32px
- Hover: subtle lift (`translateY(-4px)`) + shadow increase
- Icon container: 64px circle, teal gradient background, white icon

**Features to display:**
1. Patient Management
2. Smart Scheduling
3. Voice Transcription
4. Digital Prescriptions
5. Billing & Invoicing
6. Analytics Dashboard

---

### 3.4 AI Features Section

**Layout:** Two-column, content left, visual right

**Content:**
- Eyebrow: "Powered by AI"
- H2: "Your Intelligent Medical Assistant"
- Description paragraph (2-3 lines)
- Bullet list (4 items with checkmarks)

**Visual:**
- Right side: Abstract AI-themed illustration (gradient mesh or geometric shapes)
- Background: Light teal tint (`#f0fdfa`)

---

### 3.5 How It Works

**Layout:** 3-step horizontal flow with connecting line

**Step content:**
1. Create Account — Sign up in minutes
2. Configure Settings — Customize your practice
3. Start Managing — Go live immediately

**Styling:**
- Step number: 48px circle, teal background, white number
- Title: H4, below number
- Connecting line: 2px, dashed, gray
- Steps evenly spaced

---

### 3.6 Stats Section

**Layout:** 4-column horizontal metrics

**Metrics:**
- 10,000+ Doctors
- 500,000+ Appointments
- 100,000+ Patients
- 4.9/5 Rating

**Styling:**
- Number: H2, teal color
- Label: Body, muted
- Background: Dark slate (`#1e293b`)
- Text: White

---

### 3.7 CTA Section

**Layout:** Centered, max-width 600px

**Content:**
- H2: "Ready to Transform Your Practice?"
- Subtext: Short supporting sentence
- Button: [Start Free Trial] (large, teal)

**Background:** Subtle gradient or solid light gray

---

### 3.8 Footer

**Layout:** 4-column grid

**Columns:**
1. Brand: Logo + "MedSuite" + tagline + social icons
2. Product: Feature links
3. Company: About, Contact, Blog links
4. Legal: Privacy, Terms, HIPAA links

**Bottom bar:** Copyright + language selector

---

### 3.9 Auth Pages — Form Card

**Card Styling:**
- Max-width: 420px
- Background: white with subtle shadow
- Border-radius: 20px
- Padding: 40px
- Border: none (clean)

**Form Elements:**
- Labels: 14px, medium weight, slate color
- Inputs: Full width, 48px height, 8px radius, 2px border
- Input focus: teal border + subtle shadow
- Button: Full width, 48px height, teal gradient

---

## 4. File Structure

### New CSS Files
```
public/css/
├── auth.css          (existing - auth page styles)
└── landing.css       (NEW - landing page design system)
```

### Modified Files
```
resources/views/
├── main.blade.php              (landing page - complete refactor)
├── master.blade.php            (navigation + footer partials)
├── layouts/
│   └── navigation.blade.php   (navbar component)
└── auth/
    ├── login.blade.php         (redesign with landing.css)
    ├── register-choice.blade.php
    ├── register.blade.php
    ├── patient-register.blade.php
    ├── forgot-password.blade.php
    └── reset-password.blade.php
```

### Shared Components
```
resources/views/components/
├── medsuite-logo.blade.php     (NEW)
├── hero-section.blade.php      (NEW)
├── feature-card.blade.php      (NEW)
├── stats-counter.blade.php     (NEW)
└── cta-section.blade.php       (NEW)
```

---

## 5. Implementation Priorities

### Phase 1: Core Design System
1. Create `landing.css` with CSS variables
2. Create reusable Blade components
3. Update `master.blade.php` navbar

### Phase 2: Landing Page
1. Refactor `main.blade.php` section by section
2. Apply new typography, spacing, colors
3. Build responsive grid

### Phase 3: Auth Pages
1. Apply `landing.css` to all auth pages
2. Ensure split-screen layout consistency
3. Polish form styling

---

## 6. Responsive Breakpoints

| Name | Width | Target |
|------|-------|--------|
| Mobile | <576px | Phones |
| Tablet | 576-768px | Tablets |
| Desktop | 768-1024px | Laptops |
| Wide | 1024-1280px | Desktops |
| XL | >1280px | Large screens |

---

## 7. Accessibility Requirements

- Color contrast ratio: minimum 4.5:1 for text
- Focus states: visible outline on all interactive elements
- Form labels: always visible, never placeholder-only
- Button text: descriptive, not generic "Submit"
- Alt text: all images have descriptive alt attributes
- Semantic HTML: proper heading hierarchy (h1 → h2 → h3)
