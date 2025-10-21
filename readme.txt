=== WPWM Color Scheme Toggle ===
Contributors: lernerconsult
Tags: dark mode, light mode, accessibility, appearance, theme
Requires at least: 6.7
Tested up to: 6.8.3
Requires PHP: 8.0
Stable tag: 0.1.0
License: GPLv3.0 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Donate link: https://wp-website-mastery.com/donate

Make your WordPress site friendlier for visitors: add a Light/Dark/Auto mode toggle that respects the visitor’s system preference or selection. Does not alter your theme’s colors.

Your theme should already use CSS variables and, ideally, the CSS light-dark() function so colors change when the Light/Dark mode changes.

== Description ==
- Provides minimal CSS hooks so modern browsers resolve the CSS light-dark() function correctly for Light vs Dark mode.
- Adds a tiny JS that turns .js-theme-toggle into an Auto → Dark → Light → Auto toggle and remembers the choice, in the user's browser storage. (If the user's browser doesn't support Auto, then Light → Dark → Light.)
- Prevents flashes by setting html[data-color-scheme] pre-paint to match the user’s last choice (or system preference).
- Optional shortcode [theme_toggle] to output a minimal toggle button.

== Installation ==
1. Upload the wpwm-color-scheme-toggle/ folder to /wp-content/plugins/.
2. Activate “WPWM Color Scheme Toggle” in Plugins.

= What it does =
- Provides minimal CSS hooks so modern browsers resolve the CSS light-dark() function correctly for Light vs Dark mode.
- Adds a tiny JS that turns .js-theme-toggle into an Auto → Dark → Light → Auto toggle and remembers the choice, in the user's browser storage.
- Optional shortcode [theme_toggle] to output a minimal toggle button.
- Prevents flashes by setting html[data-color-scheme] pre‑paint to match the user’s last choice (or system preference).
- Works with alternate Light/Dark implementations too (custom properties with attribute switch, prefers-color-scheme media queries, token remap, class-based utilities, or separate stylesheets).
- “Auto” follows the operating system or browser preference when the browser supports `prefers-color-scheme`. Where unsupported, Auto is equivalent to Light. If the app detects your browser doesn't have `prefers-color-scheme` support, it skips "Auto" (toggles Light → Dark → Light).

= What it does NOT do =
- Does not restyle your theme automatically or generate colors.
- Does not rewrite your CSS. Your theme should already use CSS variables and, ideally, CSS light-dark() so colors change when the mode changes.

= Usage =
- Option A (menu link): add a custom link in Appearance → Menus/Navigation, set its CSS class to `js-theme-toggle` (in the Custom Link, Block settings, Advanced section at the bottom, ADDITIONAL CSS CLASS(ES) enter `js-theme-toggle`). The JS will update its label as the mode cycles. Optional: Add a Tooltip to the custom link.

- Option B (shortcode): place `[theme_toggle]` where you want a minimal button. You can replace the button markup in your theme with your own element that keeps the same classes/data attributes.
- Recommendation: use concise labels “Auto/Light/Dark”. If desired, rely on the control’s tooltip/title to indicate the next action.

= How it works (under the hood) =
- Data attribute: sets `html[data-color-scheme]` to `light` or `dark`. When the preference is `auto`, it follows the visitor’s OS preference.
- CSS hooks:
  :root { color-scheme: light dark; }
  html[data-color-scheme="light"] { color-scheme: light; }
  html[data-color-scheme="dark"]  { color-scheme: dark; }
  This tells modern browsers how to resolve `light-dark()`.
- JS: listens for clicks on `.js-theme-toggle`, cycles the mode, and saves the preference in localStorage (`wpwm:color-scheme`).

= Alternate methods (besides CSS light-dark()) =
- Custom properties + attribute switch: define resolved tokens and override them under `html[data-color-scheme="dark"]`.
- prefers-color-scheme media queries: set dark tokens inside `@media (prefers-color-scheme: dark)` and allow manual override with the data attribute.
- Two tokens per color + remap: keep `--bg-light`/`--bg-dark`, map to `--bg` by mode so components consume a single token.
- Class-based approach: toggle a class (e.g., `dark`) on `html`/`body` and scope dark styles to that class.
- Separate stylesheets: load light/dark CSS conditionally (media queries) or swap via JS when the user toggles.

= Translations (labels) =
Change the labels for Auto/Light/Dark via a filter in your theme or a small helper plugin:
add_filter('wpwm_color_scheme_toggle_labels', function ($labels) {
  return array(
    'auto'  => __('Automático', 'your-textdomain'),
    'light' => __('Claro', 'your-textdomain'),
    'dark'  => __('Oscuro', 'your-textdomain'),
  );
});

= Advanced filters =
- Change asset locations if you move files:
add_filter('wpwm_color_scheme_toggle_css_src', function($src){
  return get_stylesheet_directory_uri() . '/path/to/color-scheme-hooks.css';
});
add_filter('wpwm_color_scheme_toggle_js_src', function($src){
  return get_stylesheet_directory_uri() . '/path/to/color-scheme-toggle.js';
});

= Uninstall =
- Deactivate the plugin. No settings are stored in the database (preference is stored in the visitor’s browser localStorage).

== Icons (optional) ==
This plugin ships simple SVG icons you can use with the toggle:
- assets/icons/light_mode.svg
- assets/icons/dark_mode.svg
- assets/icons/night_sight_auto.svg (for Auto)

Usage examples:
- Menu link method: add a custom link with class `js-theme-toggle` and include an inline SVG before a `<span data-label>…</span>`. Example (in HTML view; substitute your Media Library icon location):
``` <a href="#" class="js-theme-toggle" aria-pressed="false" title="Light/Dark Mode">
  <img src="/wp-content/uploads/2025/10/night_sight_auto.svg" alt="Auto" width="20" height="20" />
  <span data-label="">Auto</span></a> Mode```

- Shortcode method: `[theme_toggle]` outputs a button with a `<span data-label>…</span>`. You can replace the button in your theme template with your own markup that includes an icon and the same classes/data attributes.

Notes:
- Icons are optional. The toggle works with text‑only.
- Prefer inline SVG or locally served assets. Avoid external icon fonts for privacy/performance.

= Using icons from the Media Library (optional)
To upload SVGs safely, install the "Safe SVG" plugin: https://wordpress.org/plugins/safe-svg/

Steps:
1. Install and activate Safe SVG.
2. Upload the SVGs (or PNGs) to the Media Library.
3. In your menu/item HTML, use `<img>` tags that reference your uploaded icons.

Example (note: PNG/SVG via `<img>` will not inherit text color automatically like inline SVG):
`<a href="#" class="js-theme-toggle" aria-pressed="false" title="Theme">
  <img src="/wp-content/uploads/2025/10/night_sight_auto.svg" alt="Auto" width="20" height="20" />
  <span data-label>Auto</span>
</a>`

= Using the Icon Block plugin (paste raw SVG)
Install Icon Block: https://wordpress.org/plugins/icon-block/

In the Block Editor:
1. Insert the Icon block (type "/icon").
2. Choose a placeholder icon, then paste your SVG source into the block. Remove the XML header line if present (`<?xml version="1.0" encoding="UTF-8"?>`).
3. The pasted SVG becomes part of your icon library for reuse.

``` <a href="#" class="js-theme-toggle" aria-pressed="false" title="Theme">
  <!-- Auto icon -->
  <svg data-icon="auto" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" aria-hidden="true">
    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" fill="currentColor" opacity="0.85"></path>
    <path d="M8.5 16 L10 12 L11.5 16 M9 14.5 L11 14.5" stroke="currentColor" fill="none"></path>
  </svg>

  <!-- Dark icon -->
  <svg data-icon="dark" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" aria-hidden="true">
    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" fill="currentColor"></path>
  </svg>

  <!-- Light icon -->
  <svg data-icon="light" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" aria-hidden="true">
    <circle cx="12" cy="12" r="4" fill="currentColor"></circle>
    <line x1="12" y1="2" x2="12" y2="4" stroke="currentColor"></line>
    <line x1="12" y1="20" x2="12" y2="22" stroke="currentColor"></line>
    <line x1="4.93" y1="4.93" x2="6.34" y2="6.34" stroke="currentColor"></line>
    <line x1="17.66" y1="17.66" x2="19.07" y2="19.07" stroke="currentColor"></line>
    <line x1="2" y1="12" x2="4" y2="12" stroke="currentColor"></line>
    <line x1="20" y1="12" x2="22" y2="12" stroke="currentColor"></line>
    <line x1="4.93" y1="19.07" x2="6.34" y2="17.66" stroke="currentColor"></line>
    <line x1="17.66" y1="6.34" x2="19.07" y2="4.93" stroke="currentColor"></line>
  </svg>

  <span data-label>Auto</span>
</a>
```

== Frequently Asked Questions ==
= Why don’t my colors change when toggling? =
Your theme needs to use CSS variables and light-dark(). Fixed hex colors will not change with the mode.
My Color Palette Generator (https://wp-website-mastery.com/color-palette) generates Color Palettes as Theme Variations, with CSS light-dark().

= Brave/Chromium tip: toggle seems blocked or always dark =
Brave (Chromium-based) supports `prefers-color-scheme`, but the experimental flag “Auto Dark Mode for Web Contents” can force dark on all pages and override site CSS/toggles. (This uses calculated dark-mode colors, not CSS light-dark() or theme designer-selected colors.)
- Check brave://flags/#enable-force-dark and set it to "Disabled" if you want site CSS to control light/dark.

- To have Brave (and probably most Chromium-based browsers) follow your operating system's light-dark setting: go to Settings → Appearance panel → click the Theme area, and choose "Device". This allows the plugin’s “Auto” to follow the OS preference. Most operating systems support toggling whether you want light-mode or dark-mode; some have an app that changes mode at sunrise/sundown.
- Tested with Brave 1.83.118 (Chromium 141.0.7390.108).

= What is “server-side render parity (SSR parity)”? =
Server-side render parity means the initial HTML/CSS the server sends should already match the theme mode (Light/Dark) that JavaScript will finalize. This prevents a flash or visual jump during hydration. This plugin achieves that by:
- Printing a tiny inline script in `<head>` to set `html[data-color-scheme]` and `has-light-scheme`/`has-dark-scheme` before first paint.
- Optionally adding a neutral placeholder class (`has-unknown-scheme`) server-side, in case you want holding styles before the inline script runs.

= Can I change the button label? =
Yes. Use the filter “wpwm_color_scheme_toggle_labels” to change the text for Auto, Light, and Dark. You can do this in a small mu-plugin or in your theme.

= Can I style the button? =
Yes. Add styles like this to your (child) theme's style.css:

/* Light/Dark mode toggle, https://github.com/glerner/wpwm-color-scheme-toggle.git
 * Add to site main menu as a custom link with class js-theme-toggle,
 * and optionally class has-primary-darker-background-color (sets background to --primary-darker and color to --text-on-dark using light-dark() )
*/
li.js-theme-toggle {
    font-family: var(--wp--preset--font-family--wide-serif) !important;
    font-size: var(--wp--preset--font-size--small) !important;
    border: double var(--accent-darker) 0.4em;
    font-size: 1.2em;
    padding: 5px 0.5em !important;
}


= Does this store data in the database? =
No. The preference is stored in the visitor’s browser localStorage (key: wpwm:color-scheme).

= What about plugins like Greenshift which have a "dark mode switcher"?
These are meant for themes that *only* have light mode colors. They let you specify the dark-mode version of your background and text colors, and use an algorithm to swap your other colors.

This plugin is meant for themes that have true dark mode, with dark and light versions of all colors *selected by the theme designer*.

To add *true* light and dark mode to your current theme (if it is a modern WordPress theme that accepts Theme Variations), with adjusted color tints/shades (WCAG AAA+ color contrast), use my Color Palette Generator (https://wp-website-mastery.com/color-palette).

= Privacy
This plugin stores no personal data and does not connect to external services. Preferences are saved locally in the user’s browser.

= Donations (thank you for any amount)
https://wp-website-mastery.com/donate

= Contributing
I welcome bug reports and code contributions.
Source code is on GitHub, https://github.com/glerner/wpwm-color-scheme-toggle.git

Bug reports and feature requests: https://github.com/glerner/wpwm-color-scheme-toggle/issues

== Credits ==
Included optional SVG icons are original minimal designs for this plugin. If you prefer Material Symbols by Google, they are available under the Apache-2.0 license: https://fonts.google.com/icons

== Screenshots ==
1. Example toggle in a site menu.
2. Example CSS using variables and light-dark().

== Changelog ==
= 0.1.0 =
Initial release.

== Upgrade Notice ==
= 0.1.0 =
Initial release.
