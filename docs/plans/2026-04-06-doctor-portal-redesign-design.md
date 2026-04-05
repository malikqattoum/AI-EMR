# Doctor's Portal Redesign - MedSuite Teal Theme

> **Design Date:** 2026-04-06

## Overview

Complete redesign of the doctor's portal UI with a professional medical aesthetic using MedSuite's teal color palette.

## Design System

### Color Palette

| Role | Hex | Usage |
|------|-----|-------|
| Primary Teal | `#0d9488` | Active nav, primary buttons, accents |
| Primary Dark | `#0f766e` | Button hover states |
| Primary Light | `#14b8a6` | Highlights, secondary accents |
| Sidebar BG | `#0f172a` | Dark slate sidebar background |
| Sidebar Hover | `#1e293b` | Nav item hover background |
| Content BG | `#f8fafc` | Main content area |
| Card Surface | `#ffffff` | Dashboard cards |
| Text Primary | `#1e293b` | Headings, important text |
| Text Secondary | `#64748b` | Body text, labels |
| Success | `#10b981` | Completed, confirmed |
| Warning | `#f59e0b` | Pending, attention |
| Error | `#ef4444` | Errors, cancellations |

### Typography
- **Font**: Inter (Google Fonts)
- **Headings**: 600-700 weight
- **Body**: 400-500 weight

### Sidebar Layout
- Width: 280px (fixed)
- Background: `#0f172a` (dark slate)
- Brand area: MedSuite logo + "Doctor Panel" badge
- Active nav: Teal gradient background with glow
- Hover: Lighten background + teal left border
- Quick action: Teal gradient "Start Consultation" button

### Navigation Sections
1. Overview - Dashboard
2. Today's Work - Queue, Appointments
3. Patient Management - Patients, Records, Notes
4. AI Tools - Ambient Listening, History
5. Practice - Reviews, Blog, Messages
6. Settings - Profile, Availability

### Dashboard Components

**Stats Card:**
- White bg, 12px radius, subtle shadow
- Left accent border (4px) in teal
- Icon circle: 48px, teal gradient
- Number: 2rem bold slate
- Label: 0.875rem muted

**Table Card:**
- White bg, rounded corners
- Header with teal left border
- Zebra striping for rows

**Quick Action Items:**
- Horizontal layout
- Icon (teal circle) + Content + Arrow
- Hover lift + teal border

**Buttons:**
| Type | Style |
|------|-------|
| Primary | Teal gradient, white text |
| Secondary | White bg, teal border |
| Success | Green gradient |
| Danger | Red gradient |
| Ghost | Transparent, teal text |

**Form Elements:**
- Border: 2px solid gray-200
- Focus: Teal border + glow
- Labels: Semibold slate

## Files to Modify

1. `resources/views/layouts/doctor.blade.php` - Sidebar, layout shell
2. `resources/views/doctor/dashboard.blade.php` - Dashboard redesign
3. `resources/views/doctor/appointments/index.blade.php` - Appointments list
4. `resources/views/doctor/patients/index.blade.php` - Patients list
5. `resources/views/doctor/patients/show.blade.php` - Patient detail
6. `resources/views/doctor/notes/index.blade.php` - Notes list
7. `public/css/doctor-dashboard.css` - Shared dashboard styles

## Components

### Sidebar Component (doctor.blade.php)
- Dark slate background
- MedSuite logo at top
- Quick action button (teal)
- Navigation with icons
- User info at bottom

### Dashboard Stats Cards
- Teal accent border
- Gradient icon circle
- Large number display
- Subtle shadow

### Table Cards
- White surface
- Teal header accent
- Rounded corners
- Hover states

### Action Buttons
- Primary: Teal gradient
- Secondary: Outlined teal
- States: hover lift, active press

## Pages to Redesign

1. Doctor Dashboard (`/dashboard`)
2. Appointments (`/doctor/appointments`)
3. Patients (`/doctor/patients`)
4. Patient Detail (`/doctor/patients/{id}`)
5. Notes (`/doctor/notes`)
6. Reviews (`/doctor/reviews`)
7. Settings pages
8. Chat/Messages
9. AI Tools pages
