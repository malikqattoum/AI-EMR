# Doctor's Portal Redesign - Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development to implement this plan task-by-task.

**Goal:** Redesign the doctor's portal UI with MedSuite teal branding - new sidebar, dashboard, and consistent styling across all doctor pages.

**Architecture:** Replace existing doctor layout with updated sidebar using MedSuite teal colors. Update dashboard and key pages with new card styles, buttons, and form elements. Create shared CSS for consistency.

**Tech Stack:** Laravel Blade, Bootstrap 5, CSS custom properties, Font Awesome 6

---

## Task 1: Update doctor.blade.php Sidebar with Teal Theme

**Files:**
- Modify: `resources/views/layouts/doctor.blade.php`

**Steps:**

Step 1: Update sidebar background and nav styles to use new teal palette:
- Sidebar BG: `#0f172a` (keep dark slate)
- Active nav: Teal gradient `#0d9488` to `#14b8a6` with glow shadow
- Nav hover: `#1e293b` background + 3px teal left border
- Quick action button: Teal gradient background

Step 2: Update PWA banner colors to match teal theme

Step 3: Update logo/branding section with MedSuite colors

Step 4: Test sidebar renders correctly, mobile hamburger works

**Commit:** `feat: update doctor sidebar with MedSuite teal branding`

---

## Task 2: Create Shared Doctor Dashboard CSS

**Files:**
- Create: `public/css/doctor-portal.css`

**Steps:**

Step 1: Define CSS custom properties:
```css
:root {
    --teal-primary: #0d9488;
    --teal-dark: #0f766e;
    --teal-light: #14b8a6;
    --sidebar-bg: #0f172a;
    --content-bg: #f8fafc;
    --card-bg: #ffffff;
    --text-primary: #1e293b;
    --text-secondary: #64748b;
    --success: #10b981;
    --warning: #f59e0b;
    --error: #ef4444;
}
```

Step 2: Create stats-card styles with teal accent

Step 3: Create table-card styles with teal header border

Step 4: Create button styles (primary, secondary, success, danger, ghost)

Step 5: Create form-control styles with teal focus

Step 6: Create quick-action styles

**Commit:** `feat: add doctor-portal.css shared styles`

---

## Task 3: Redesign Doctor Dashboard Page

**Files:**
- Modify: `resources/views/doctor/dashboard.blade.php`

**Steps:**

Step 1: Update dashboard-header to use new teal/slate gradient

Step 2: Replace emoji icon with Font Awesome icon in teal circle

Step 3: Update stats-cards to use shared .stats-card class with teal accents

Step 4: Update table-card styles with teal header borders

Step 5: Update quick-action-enhanced styles

Step 6: Update appointment cards with teal styling

Step 7: Update badges and status indicators with teal colors

Step 8: Replace btn-primary-custom with standard teal classes

**Commit:** `feat: redesign doctor dashboard with MedSuite teal theme`

---

## Task 4: Update Appointments Page

**Files:**
- Modify: `resources/views/doctor/appointments/index.blade.php`

**Steps:**

Step 1: Add doctor-portal.css stylesheet

Step 2: Update dashboard-header with teal styling

Step 3: Update appointment cards with teal accents

Step 4: Update buttons to use teal palette

Step 5: Update filter dropdowns with teal focus states

**Commit:** `feat: update appointments page with teal theme`

---

## Task 5: Update Patients Page

**Files:**
- Modify: `resources/views/doctor/patients/index.blade.php`

**Steps:**

Step 1: Add doctor-portal.css stylesheet

Step 2: Update patient cards with teal styling

Step 3: Update search/filter forms with teal focus

Step 4: Update buttons and badges

**Commit:** `feat: update patients page with teal theme`

---

## Task 6: Update Patient Detail Page

**Files:**
- Modify: `resources/views/doctor/patients/show.blade.php`

**Steps:**

Step 1: Add doctor-portal.css stylesheet

Step 2: Update patient header with teal accent

Step 3: Update info cards and tabs

Step 4: Update action buttons

**Commit:** `feat: update patient detail page with teal theme`

---

## Task 7: Update Notes Pages

**Files:**
- Modify: `resources/views/doctor/notes/index.blade.php`
- Modify: `resources/views/doctor/notes/create.blade.php`
- Modify: `resources/views/doctor/notes/edit.blade.php`

**Steps:**

Step 1: Add doctor-portal.css to notes index

Step 2: Update note cards with teal styling

Step 3: Add teal styling to create/edit forms

**Commit:** `feat: update notes pages with teal theme`

---

## Task 8: Update Reviews Page

**Files:**
- Modify: `resources/views/doctor/reviews/index.blade.php`

**Steps:**

Step 1: Add doctor-portal.css stylesheet

Step 2: Update review cards with teal accents

Step 3: Update rating display with teal stars

**Commit:** `feat: update reviews page with teal theme`

---

## Task 9: Update Doctor Settings Pages

**Files:**
- Modify: `resources/views/doctor/settings/appointments.blade.php`
- Modify: `resources/views/doctor/profile/edit.blade.php`

**Steps:**

Step 1: Add doctor-portal.css stylesheet

Step 2: Update form elements with teal focus states

Step 3: Update buttons

**Commit:** `feat: update doctor settings pages with teal theme`

---

## Task 10: Update Chat/Messages Page

**Files:**
- Modify: `resources/views/doctor/chat/index.blade.php`

**Steps:**

Step 1: Add doctor-portal.css stylesheet

Step 2: Update chat interface with teal accents

Step 3: Update message bubbles and inputs

**Commit:** `feat: update chat page with teal theme`

---

## Task 11: Update AI Tools Pages

**Files:**
- Modify: `resources/views/voice-assistant/index.blade.php`
- Modify: `resources/views/voice-assistant/recorded-voices.blade.php`

**Steps:**

Step 1: Add doctor-portal.css stylesheet

Step 2: Update interface with teal accents

Step 3: Update buttons and controls

**Commit:** `feat: update AI tools pages with teal theme`

---

## Task 12: Final Review and Cleanup

**Steps:**

Step 1: Verify all pages use consistent teal palette

Step 2: Check responsive behavior on mobile

Step 3: Update any remaining hardcoded colors to CSS variables

Step 4: Final commit with all remaining changes
