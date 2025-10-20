<?php
/**
 * Plugin Name: WPWM Color Scheme Toggle
 * Plugin URI: https://wp-website-mastery.com/plugins/wpwm-color-scheme-toggle
 * Description: Make your WordPress site friendlier for visitors: add a Light/Dark/Auto theme toggle that respects the visitor’s Light-Dark Mode system preference or selection. Does not alter your theme’s colors. Your theme should already use CSS variables and the `light-dark()` function so colors change when the mode changes.
 * Version: 0.1.0
 * Requires at least: 6.7
 * Tested up to: 6.8.3
 * Requires PHP: 8.0
 * Author: WP Website Mastery
 * Author URI: https://wp-website-mastery.com/
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wpwm-color-scheme-toggle
 * Domain Path: /languages
 *
 * @package WPWM_Color_Scheme_Toggle
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load translations.
add_action(
	'init',
	function () {
		load_plugin_textdomain( 'wpwm-color-scheme-toggle', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
);

// Documentation example (non-executing): correct way to define light-dark with fallback, in your `style.css`.
// Note that WordPress gives all .has-* class colors !important, so !important is required to override.
$__wpwm_cst_doc = <<<'DOC'
Correct way to define light-dark, with fallback for browsers that don't support dark mode:

.bg-primary-lighter, .has-primary-lighter-background-color {
  /* Fallback for browsers without light-dark(): */
  background-color: var(--primary-lighter) !important;
  color: var(--text-on-light) !important;
  @supports (color: light-dark(black, white)) {
    background-color: light-dark(var(--primary-lighter), var(--primary-darker)) !important;
    color: light-dark(var(--text-on-light), var(--text-on-dark)) !important;
  }
}
DOC;


/**
 * Inline pre-paint bootstrap to apply the user's stored preference (or system) before first paint.
 */
function wpwmcst_output_initial_color_scheme() {
	$script = <<<'JS'
(function(){
  try{
    var LS_KEY='wpwm:color-scheme';
    var pref=localStorage.getItem(LS_KEY)||'auto';
    var mql=window.matchMedia&&window.matchMedia('(prefers-color-scheme: dark)');
    var darkPreferred=mql&&mql.matches;
    var mode=pref==='auto'?(darkPreferred?'dark':'light'):pref;
    var html=document.documentElement;
    html.setAttribute('data-color-scheme', mode);
    var clsLight='has-light-scheme', clsDark='has-dark-scheme';
    if(mode==='dark'){ html.classList.add(clsDark); html.classList.remove(clsLight);} else { html.classList.add(clsLight); html.classList.remove(clsDark);}
  }catch(e){}
})();
JS;
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo "\n<script>" . $script . "</script>\n";
}
add_action( 'wp_head', 'wpwmcst_output_initial_color_scheme', 0 );

/**
 * Enqueue minimal CSS hooks and the toggle JS.
 */
function wpwmcst_enqueue_assets() {
	$base_url  = plugin_dir_url( __FILE__ );
	$base_path = plugin_dir_path( __FILE__ );

	$css_rel = 'assets/css/color-scheme-hooks.css';
	$js_rel  = 'assets/js/color-scheme-toggle.js';

	$css = apply_filters( 'wpwm_color_scheme_toggle_css_src', $base_url . $css_rel );
	$js  = apply_filters( 'wpwm_color_scheme_toggle_js_src', $base_url . $js_rel );

	$css_ver = file_exists( $base_path . $css_rel ) ? (string) filemtime( $base_path . $css_rel ) : '0.1.0';
	$js_ver  = file_exists( $base_path . $js_rel ) ? (string) filemtime( $base_path . $js_rel ) : '0.1.0';

	wp_enqueue_style( 'wpwmcst-hooks', $css, array(), $css_ver );
	wp_enqueue_script( 'wpwmcst-toggle', $js, array(), $js_ver, true );

	$labels = apply_filters(
		'wpwm_color_scheme_toggle_labels',
		array(
			'auto'  => __( 'Auto', 'wpwm-color-scheme-toggle' ),
			'light' => __( 'Light', 'wpwm-color-scheme-toggle' ),
			'dark'  => __( 'Dark', 'wpwm-color-scheme-toggle' ),
		)
	);
	wp_localize_script( 'wpwmcst-toggle', 'WPWM_TOGGLE_LABELS', $labels );
}
add_action( 'wp_enqueue_scripts', 'wpwmcst_enqueue_assets' );

/**
 * Optional: add a body class placeholder for SSR parity.
 *
 * @param array $classes Existing classes.
 * @return array
 */
function wpwmcst_body_class( $classes ) {
	$classes[] = 'has-unknown-scheme';
	return $classes;
}
add_filter( 'body_class', 'wpwmcst_body_class' );

/**
 * Optional shortcode: [theme_toggle]
 * Renders a minimal toggle control if the theme doesn't add a .js-theme-toggle link in menus.
 *
 * @return string
 */
function wpwmcst_shortcode_toggle() {
	$label = __( 'Auto', 'wpwm-color-scheme-toggle' );
	return '<button type="button" class="js-theme-toggle" aria-pressed="false"><span data-label>' . esc_html( $label ) . '</span></button>';
}
add_shortcode( 'theme_toggle', 'wpwmcst_shortcode_toggle' );
