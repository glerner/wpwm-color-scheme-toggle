/*
 * color-scheme-toggle.js (plugin copy)
 *
 * Makes a button/link with class .js-theme-toggle toggle between
 * 'auto' → 'dark' → 'light' → 'auto' and updates:
 * - document.documentElement.dataset.colorScheme
 * - html classes: has-light-scheme / has-dark-scheme
 * - persist to localStorage('wpwm:color-scheme')
 * - accessible label/aria-pressed and text content
 *
 * Expects CSS to include:
 * :root { color-scheme: light dark; }
 * html[data-color-scheme="light"] { color-scheme: light; }
 * html[data-color-scheme="dark"]  { color-scheme: dark; }
 */
(function(){
  var LS_KEY = 'wpwm:color-scheme';
  var root = document.documentElement;
  var mql = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)');
  // Detect if the browser actually supports prefers-color-scheme
  var supportsPrefers = !!(mql && typeof mql.media === 'string' && mql.media !== 'not all');
  var labels = (typeof window.WPWM_TOGGLE_LABELS === 'object' && window.WPWM_TOGGLE_LABELS) || { auto: 'Auto', light: 'Light', dark: 'Dark' };

  function systemPrefersDark(){ return !!(supportsPrefers && mql && mql.matches); }

  function appliedMode(pref){
    if (pref === 'auto') return systemPrefersDark() ? 'dark' : 'light';
    return pref;
  }

  function apply(mode){
    // Update html dataset and classes
    root.setAttribute('data-color-scheme', mode);
    var isDark = mode === 'dark';
    root.classList.toggle('has-dark-scheme', isDark);
    root.classList.toggle('has-light-scheme', !isDark);
    // Optionally expose on window for debugging
    window.__colorScheme = mode;
  }

  function setPreference(pref){
    // Normalize: if Auto is requested but prefers-color-scheme isn't supported, treat as light
    if (!supportsPrefers && pref === 'auto') pref = 'light';
    try { localStorage.setItem(LS_KEY, pref); } catch(e) {}
    var mode = appliedMode(pref);
    apply(mode);
    updateToggles(pref, mode);
  }

  function getPreference(){
    try {
      var v = localStorage.getItem(LS_KEY);
      // If unsupported and stored value is auto, surface light so UI never shows Auto in unsupported environments
      if (!supportsPrefers && v === 'auto') return 'light';
      return v || 'auto';
    } catch(e){ return 'auto'; }
  }

  function cyclePref(pref){
    if (!supportsPrefers) {
      // Two-state cycle when prefers-color-scheme isn't supported
      return pref === 'dark' ? 'light' : 'dark';
    }
    return pref === 'auto' ? 'dark' : pref === 'dark' ? 'light' : 'auto';
  }

  function updateToggleEl(el, pref, mode){
    var text = pref === 'auto' ? labels.auto : (mode === 'dark' ? labels.dark : labels.light);
    var pressed = mode === 'dark';

    el.setAttribute('data-pref', pref);
    el.setAttribute('data-mode', mode);
    el.setAttribute('aria-pressed', String(pressed));
    el.setAttribute('aria-label', 'Theme: ' + text);
    if (!el.getAttribute('title')) el.setAttribute('title', 'Theme: ' + text);

    // If element has a child span[data-label], keep that in sync, else update textContent
    var labelSpan = el.querySelector('[data-label]');
    if (labelSpan) {
      labelSpan.textContent = text;
    } else if (el.firstElementChild && el.firstElementChild.tagName === 'SVG') {
      // Leave SVG as-is; don't overwrite
    } else {
      el.textContent = text;
    }
  }

  function updateToggles(pref, mode){
    document.querySelectorAll('.js-theme-toggle, [data-theme-toggle]').forEach(function(el){
      updateToggleEl(el, pref, mode);
    });
  }

  function onClickToggle(ev){
    ev.preventDefault();
    var pref = getPreference();
    var next = cyclePref(pref);
    setPreference(next);
  }

  function init(){
    var pref = getPreference();
    var mode = appliedMode(pref);
    apply(mode);
    updateToggles(pref, mode);

    document.addEventListener('click', function(e){
      var t = e.target;
      if (!t) return;
      var el = t.closest && t.closest('.js-theme-toggle, [data-theme-toggle]');
      if (el) onClickToggle(e);
    }, true);

    if (supportsPrefers && mql && typeof mql.addEventListener === 'function'){
      mql.addEventListener('change', function(){
        var pref = getPreference();
        if (pref === 'auto') setPreference('auto');
      });
    } else if (supportsPrefers && mql && typeof mql.addListener === 'function') {
      // Safari < 14
      mql.addListener(function(){
        var pref = getPreference();
        if (pref === 'auto') setPreference('auto');
      });
    }
  }

  if (document.readyState === 'complete' || document.readyState === 'interactive') {
    init();
  } else {
    document.addEventListener('DOMContentLoaded', init);
  }
})();
