# Theme System Documentation

## Overview
The application now supports **dual themes**: Dark (default) and Light. Users can switch between themes using a toggle button in the navigation bar, and their preference is persisted across sessions.

## Architecture

### Theme Files

#### 1. **Global Dark Theme** (`public/css/global-dark-theme.css`)
- Scoped under `[data-theme="dark"]`
- Navy/teal color scheme
- Light text on dark backgrounds
- Custom scrollbar styling

#### 2. **Global Light Theme** (`public/css/global-light-theme.css`)
- Scoped under `[data-theme="light"]`
- Clean, professional light theme
- Dark text on light backgrounds
- Subtle shadows and borders

#### 3. **Theme Switcher JavaScript** (`public/js/theme-switcher.js`)
- Handles theme toggling
- Persists preferences (localStorage for guests, database for authenticated users)
- Respects system preferences by default
- Provides public API: `window.ThemeSwitcher`

### Layout Files Updated
All layout files now include both theme CSS files and the theme switcher:
- `resources/views/master.blade.php` - Guest/public pages
- `resources/views/layouts/app.blade.php` - Hospital admin
- `resources/views/layouts/doctor.blade.php` - Doctor portal
- `resources/views/layouts/admin.blade.php` - Super admin
- `resources/views/layouts/guest.blade.php` - Guest pages

## How It Works

### Theme Switching Mechanism

1. **HTML Attribute**: The current theme is set via `data-theme` attribute on `<html>` and `<body>` elements
2. **CSS Scoping**: All theme styles are scoped under `[data-theme="dark"]` or `[data-theme="light"]`
3. **JavaScript Control**: `theme-switcher.js` manages the `data-theme` attribute

### Persistence Strategy

| User Type | Storage Method | API Endpoint |
|-----------|---------------|--------------|
| **Guest** | `localStorage` | N/A |
| **Authenticated** | `localStorage` + Database | `POST /api/user/settings/theme` |

### System Preference Detection
If a user hasn't explicitly chosen a theme, the system respects their OS preference via `prefers-color-scheme` media query.

## User Interface

### Theme Toggle Button
Located in the navigation bar (top right area):
- **Icon**: Sun (☀️) for dark theme, Moon (🌙) for light theme
- **Behavior**: Click to switch to the opposite theme
- **Accessibility**: Includes `aria-label` and keyboard support

### Visual Appearance

#### Dark Theme
- Background: Navy blue (#0a1428, #060d1f)
- Text: Light gray/white (#e8ede7)
- Accent: Teal (#00d4aa)
- Cards: Semi-transparent dark backgrounds
- Borders: Subtle teal

#### Light Theme
- Background: White (#ffffff)
- Text: Dark slate (#1e293b)
- Accent: Teal (#0d9488)
- Cards: White with subtle shadows
- Borders: Light gray (#e2e8f0)

## Database Schema

### Migration: `2026_04_08_000000_add_theme_to_settings_table.php`
```php
$table->string('theme', 10)->default('dark')->after('notification_volume');
```

### Settings Table
Stores user theme preference alongside other settings:
- `user_id` - Foreign key to users table
- `theme` - 'light' or 'dark' (default: 'dark')
- Other settings: criterion, specialty, notification_volume

## API Endpoints

### Get Theme Preference
```
GET /api/user/settings/theme
```
**Response:**
```json
{
  "success": true,
  "theme": "dark"
}
```

### Update Theme Preference
```
POST /api/user/settings/theme
```
**Request Body:**
```json
{
  "theme": "light"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Theme preference updated successfully",
  "theme": "light"
}
```

## JavaScript API

The theme switcher exposes a global API via `window.ThemeSwitcher`:

```javascript
// Get current theme
const currentTheme = ThemeSwitcher.getTheme(); // Returns 'light' or 'dark'

// Set theme
ThemeSwitcher.setTheme('light'); // or 'dark'

// Toggle theme
ThemeSwitcher.toggleTheme();

// Available themes
console.log(ThemeSwitcher.THEMES);
// { LIGHT: 'light', DARK: 'dark' }
```

### Custom Events
The theme switcher dispatches a `themeChanged` event when the theme changes:

```javascript
window.addEventListener('themeChanged', (e) => {
    console.log('Theme changed to:', e.detail.theme);
    // Perform custom actions on theme change
});
```

## Customization

### Adding a New Theme

1. **Create theme CSS file**: `public/css/global-{theme}-theme.css`
2. **Scope all styles** under `[data-theme="{theme}"]`
3. **Define CSS variables**:
   ```css
   [data-theme="newtheme"] {
       --global-navy: #color;
       --global-teal: #color;
       --global-offwhite: #color;
       --global-muted: rgba(...);
       --global-body-bg: #color;
       --global-text: #color;
   }
   ```
4. **Update theme switcher** to include the new theme in `THEMES` object
5. **Add UI controls** for the new theme option

### CSS Variables Reference

All themes should define these variables:

| Variable | Purpose | Dark Example | Light Example |
|----------|---------|--------------|---------------|
| `--global-navy` | Primary dark color | `#0a1428` | `#1e293b` |
| `--global-teal` | Accent color | `#00d4aa` | `#0d9488` |
| `--global-offwhite` | Primary text | `#e8ede7` | `#1e293b` |
| `--global-muted` | Secondary text | `rgba(232,237,231,0.5)` | `rgba(30,41,59,0.6)` |
| `--global-body-bg` | Background | `#0a1428` | `#ffffff` |
| `--global-text` | Text color | `#e8ede7` | `#1e293b` |

## Migration & Deployment

### Step 1: Run Migration
```bash
php artisan migrate
```

This adds the `theme` column to the `settings` table.

### Step 2: Clear Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Step 3: Test Theme Switching
1. Log in as a user
2. Click the theme toggle button (sun/moon icon)
3. Verify the theme changes smoothly
4. Refresh the page - preference should persist
5. Log out and log back in - preference should remain

### Step 4: Verify Guest Users
1. Visit public pages without logging in
2. Toggle theme - should save to localStorage
3. Refresh page - preference should persist

## Troubleshooting

### Theme Not Switching
**Check:**
1. Browser console for JavaScript errors
2. Network tab - verify `theme-switcher.js` loads
3. `data-theme` attribute on `<html>` element
4. Both theme CSS files are included in layout

### Flash of Incorrect Theme on Load
**Solution:**
The theme switcher applies the theme immediately in the IIFE. If you still see flashing:
1. Move `<script src="{{ asset('js/theme-switcher.js') }}"></script>` to `<head>`
2. Add inline script before other stylesheets:
   ```html
   <script>
   (function() {
       var theme = localStorage.getItem('app_theme_preference') || 'dark';
       document.documentElement.setAttribute('data-theme', theme);
   })();
   </script>
   ```

### Database Save Fails
**Check:**
1. CSRF token is present in meta tag
2. User is authenticated
3. API route is registered in `routes/api.php`
4. Laravel logs: `storage/logs/laravel.log`

### CSS Variables Not Working
**Verify:**
1. Browser supports CSS variables (modern browsers do)
2. `[data-theme="..."]` selector matches current theme
3. No syntax errors in CSS files
4. CSS files load in correct order (light before dark)

## Performance Considerations

### CSS Size
- Both theme files are loaded simultaneously (~20KB each)
- Browser only applies styles matching current `data-theme` attribute
- Consider minification and gzip for production

### JavaScript
- Theme switcher is lightweight (~6KB)
- Uses IIFE for immediate execution
- No external dependencies
- Debounced database saves (not immediate)

### Storage
- localStorage: ~4KB per domain (plenty for theme preference)
- Database: 1 column per user in settings table

## Accessibility

### Keyboard Navigation
Theme toggle button is keyboard accessible:
- Tab to focus
- Enter/Space to activate

### ARIA Attributes
- `aria-label`: Describes current action ("Switch to light theme")
- `role="button"`: Semantic HTML
- `aria-expanded`: Not applicable (toggle, not dropdown)

### Screen Readers
Announces theme change automatically via:
- Button label updates
- Live regions (if needed)
- Custom event notifications

## Future Enhancements

### Potential Features
1. **Auto theme** - Follow system preference with manual override
2. **Scheduled themes** - Auto-switch based on time of day
3. **More themes** - High contrast, sepia, custom colors
4. **Theme customization** - User-defined accent colors
5. **Theme preview** - See theme before applying
6. **Export/import preferences** - Sync across devices

### Implementation Notes
To add scheduled themes:
```javascript
// Check time and apply
const hour = new Date().getHours();
const autoTheme = hour >= 7 && hour < 19 ? 'light' : 'dark';
ThemeSwitcher.setTheme(autoTheme);
```

## Credits & References

### Color Palettes
- Dark theme: Inspired by GitHub Dark, VS Code Dark
- Light theme: Inspired by GitHub Light, Tailwind CSS defaults

### Technical Approach
- CSS Variables for theming (MDN Web Docs)
- `data-*` attributes for theme switching
- localStorage for client-side persistence
- Laravel API for server-side storage

### Browser Support
- CSS Variables: Chrome 49+, Firefox 31+, Safari 10+, Edge 15+
- localStorage: All modern browsers
- Custom Events: Chrome 15+, Firefox 11+, Safari 6+

## Support

For issues or questions about the theme system:
1. Check this documentation
2. Review browser console for errors
3. Verify database migration ran successfully
4. Contact the development team

---

**Last Updated**: April 8, 2026  
**Version**: 1.0.0  
**Maintainer**: Development Team
