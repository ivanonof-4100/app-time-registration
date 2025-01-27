<!-- Theme settings -->
<div role="button">
<div id="theme_switcher" class="btn-group-vertical btn-group-md" role="group" aria-label="Theme settings">
  <input type="radio" role="button" class="btn-check" name="theme" id="radio_theme_light" autocomplete="off" data-bs-theme-value="light"/>
  <label class="btn btn-md" for="radio_theme_light" aria-label="Light theme"  data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Select Light Theme">
    <img src="/bootstrap/bootstrap-icons/icons/sun.svg" loading="lazy" class="icon icon-size-small" alt="Light theme"/>
  </label>
  <input type="radio" class="btn-check" name="theme" id="radio_theme_dark" autocomplete="off" data-bs-theme-value="dark"/>
  <label class="btn btn-md" for="radio_theme_dark" aria-label="Dark theme" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Select Dark Theme">
    <img src="/bootstrap/bootstrap-icons/icons/moon-stars.svg" loading="lazy" class="icon icon-size-small" alt="Dark theme"/>
  </label>
  <input type="radio" class="btn-check" name="theme" id="radio_theme_auto" autocomplete="off" data-bs-theme-value="auto" checked aria-checked="true"/>
  <label class="btn btn-md" for="radio_theme_auto" aria-label="Browser settings" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Select a Theme that automaticly follows your selected theme-preference set in your web-browser.">
    <img src="/images/icons/browsers/browser-settings.svg" loading="lazy" class="icon icon-size-small" alt="Browser settings"/>
  </label>
</div>

{if isset($smarty.const.APP_DEBUG_MODE) && $smarty.const.APP_DEBUG_MODE}
<div style="color:#fff;">
<p class="fw-semibold fst-italic p-2">
Template: <span class="text-nowrap text-lowercase">{$smarty.template}</span>
</p>
</div>
{/if}
</div>