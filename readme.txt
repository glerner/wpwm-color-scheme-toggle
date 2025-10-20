=== WPWM Color Scheme Toggle ===
Contributors: lernerconsult
Tags: dark mode, light mode, accessibility, appearance, theme
Requires at least: 6.7
Tested up to: 6.8.3
Requires PHP: 8.0
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Donate link: https://wp-website-mastery.com/donate

Make your WordPress site friendlier for visitors: add a Light/Dark/Auto theme toggle that respects the visitor’s system preference or selection. Does not alter your theme’s colors.

== Description ==
- Prevents flashes by setting html[data-color-scheme] pre-paint to match the user’s last choice (or system preference).
- Provides minimal CSS hooks so modern browsers resolve the CSS light-dark() function correctly for Light vs Dark mode.
- Adds a tiny JS that turns .js-theme-toggle into an Auto → Dark → Light → Auto toggle and remembers the choice, in the user's browser storage.
- Optional shortcode [theme_toggle] to output a minimal toggle button.

This plugin does not restyle your theme. Your theme should already use CSS variables and, ideally, the CSS light-dark() function so colors change when the mode changes.

== Installation ==
1. Upload the wpwm-color-scheme-toggle/ folder to /wp-content/plugins/.
2. Activate “WPWM Color Scheme Toggle” in Plugins.

== Icons (optional) ==
This plugin ships simple SVG icons you can use with the toggle:
- assets/icons/light_mode.svg
- assets/icons/dark_mode.svg
- assets/icons/night_sight_auto.svg (for Auto)

Usage examples:
- Menu link method: add a custom link with class `js-theme-toggle` and include an inline SVG before a `<span data-label>…</span>`.
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

= Can I change the button label? =
Yes. Use the filter “wpwm_color_scheme_toggle_labels” to change the text for Auto, Light, and Dark. You can do this in a small mu-plugin or in your theme.

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
Source code is on GitHub, https://github.com/wp-website-mastery/wpwm-color-scheme-toggle

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
