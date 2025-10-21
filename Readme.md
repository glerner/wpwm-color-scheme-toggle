> Deprecated: This README is not maintained. Use `readme.txt` as the canonical documentation for this plugin. The WordPress.org Plugin Directory and the in-dashboard Plugins screen parse `readme.txt`.

# WPWM Color Scheme Toggle

Make your WordPress site friendlier for visitors: add a Light/Dark/Auto theme toggle that respects the visitor’s system preference and their manual choice.

- Adds a small inline “pre-paint” script so pages load in the chosen mode without flashing.
- Adds minimal CSS hooks so modern browsers switch `light-dark()` colors when the mode changes.
- Adds a tiny JS that upgrades any link or button with CSS class `js-theme-toggle` into a working toggle.

This plugin does not alter your theme’s colors. Your theme should already use CSS variables and (ideally) the `light-dark()` function so colors change when the mode changes.

---

## What it does
- **[Prevents flashes]** Sets `html[data-color-scheme]` before the first paint to match the user’s last choice (or their system preference).
- **[Provides hooks]** Enqueues minimal CSS so browsers know how to resolve `light-dark()` for Light vs Dark mode.
- **[Adds toggle behavior]** Enqueues a small JS that turns `.js-theme-toggle` into a three-state toggle (Auto → Dark → Light → Auto) and remembers the choice.
- **[Optional button]** Shortcode `[theme_toggle]` for placing a toggle button anywhere.

## What it does NOT do
- **[No color generation]** It does not generate new colors or restyle your theme automatically.
- **[No CSS rewrite]** It does not convert hard-coded CSS colors to variables/`light-dark()`; your theme (or child theme) should already use them.
- **[No dark class system]** It does not use theme-specific dark classes (e.g., `.cf-theme-dark`) — it uses a standards-based data attribute on `<html>`.

---

## Requirements
- WordPress 6.0+
- A theme that defines colors via CSS variables and, preferably, the `light-dark()` CSS function, e.g.:

```css
:root {
  --page-bg: light-dark(#ffffff, #0b0b0c);
  --page-fg: light-dark(#0c0c0d, #ffffff);
}
body { background: var(--page-bg); color: var(--page-fg); }
```

---

## Install
1. Upload the `wpwm-color-scheme-toggle/` folder to `wp-content/plugins/`.
2. Activate “WPWM Color Scheme Toggle” in Plugins.

The plugin automatically:
- Prints a tiny pre-paint script in `<head>` to set `html[data-color-scheme]`.
- Enqueues minimal CSS hooks (`:root { color-scheme: light dark; }` plus Light/Dark overrides).
- Enqueues a small JS that upgrades `.js-theme-toggle` elements into a working toggle.

---

## Use
You have two ways to add the toggle control:

### Option A: Add a menu link
- Add a custom link in Appearance → Menus/Navigation.
- Set its CSS class to `js-theme-toggle`.
- The plugin JS will change its label as the mode cycles.

Optional: Use a label span if you have an icon and want to control the text node.
```html
<a href="#" class="js-theme-toggle"><svg><!-- icon --></svg> <span data-label>Auto</span></a>
```

### Option B: Shortcode
- Insert `[theme_toggle]` wherever you want a button.

---

## How it works (under the hood)
- **Data attribute**: The plugin sets `html[data-color-scheme]` to `light` or `dark`. When set to `auto`, it follows the user’s OS preference.
- **CSS hooks**: The plugin enqueues:
  ```css
  :root { color-scheme: light dark; }
  html[data-color-scheme="light"] { color-scheme: light; }
  html[data-color-scheme="dark"]  { color-scheme: dark; }
  ```
  This tells modern browsers how to resolve `light-dark()`.
- **JS**: Listens for clicks on `.js-theme-toggle`, cycles the mode, and saves preference in `localStorage` (`wpwm:color-scheme`).
- **No theme rewrite**: Your theme still controls the actual look via variables and `light-dark()`.

---

## Translations (labels)
Change the labels for Auto/Light/Dark via a filter in your theme or a small helper plugin:
```php
add_filter('wpwm_color_scheme_toggle_labels', function ($labels) {
  return array(
    'auto'  => __('Automático', 'your-textdomain'),
    'light' => __('Claro', 'your-textdomain'),
    'dark'  => __('Oscuro', 'your-textdomain'),
  );
});
```

---

## Advanced filters
- **Change asset locations** if you move files:
```php
add_filter('wpwm_color_scheme_toggle_css_src', function($src){
  return get_stylesheet_directory_uri() . '/path/to/color-scheme-hooks.css';
});
add_filter('wpwm_color_scheme_toggle_js_src', function($src){
  return get_stylesheet_directory_uri() . '/path/to/color-scheme-toggle.js';
});
```

---

## FAQ
- **What is “SSR parity”?**
  - SSR = server-side render. Before JavaScript runs, the server outputs basic HTML/CSS. “SSR parity” means having a harmless placeholder class on the HTML/body so CSS has something predictable to match before JS sets the final mode.
  - This plugin adds a placeholder body class (`has-unknown-scheme`) by default. It’s optional and you can ignore/remove it if you don’t use it.

- **Why don’t my colors change when toggling?**
  - Your theme needs to use variables and/or `light-dark()` for color values. Fixed hex colors won’t change with the mode.
  - Some builder CSS uses very specific or `!important` rules. Update those to use variables or provide utilities with `!important` to override.

- **Can I force two-state (Light/Dark) instead of three-state (Auto/Light/Dark)?**
  - Yes. The toggle code can be adjusted to skip the `auto` state. Contact your developer to modify the script.

---

## Uninstall
- Deactivate the plugin. No settings are stored in the database (preference is stored in the browser’s `localStorage`).

---

## Changelog
- 0.1.0 – Initial release.
