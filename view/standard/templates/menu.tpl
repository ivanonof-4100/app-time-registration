{assign var=arrMenuItems value=$arrMenuItems}
{assign var=arrLangs value=$arrLangs}
{assign var=curLangIdent value=$curURIPrefix|regex_replace:"/[\/]/":" "}
{assign var=arrConfigPaths value=$arrConfigPaths}

<nav class="navbar navbar-dark bg-dark fixed-top" role="navigation">
  <div class="container">
    <a class="navbar-brand" href="{$curURIPrefix|escape}">
    <strong class="fs-3 text-lowercase logo-text">{$smarty.const.SITE_DOMAIN_NAME|escape}</strong></a>

  <ul class="d-flex flex-wrap flex-row-reverse ms-md-auto nav navbar-nav justify-content-end">
    <li class="nav-item">
      <div class="d-block badge bg-secondary text-white">
        <img src="/bootstrap/bootstrap-icons/icons/globe.svg" alt="..." width="32" height="32"/>
        <span lang="en"></span><span class="text-uppercase">{$curLangIdent|escape}</span>
      </div>
    </li>
  </ul>

  <button id="menu_toggle_mainmenu" type="button" aria-label="Menu" class="navbar-toggler" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_navbar" aria-controls="offcanvas_navbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div id="offcanvas_navbar" class="offcanvas offcanvas-end" tabindex="-1" aria-labelledby="offcanvas_label">
    <header class="offcanvas-header">
  <div class="float-start" style="width:90%;display:inline-block;">
   <button type="button" class="align-middle btn-lg btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  &nbsp;&nbsp;
   <h5 id="offcanvas_label" role="heading" class="align-middle offcanvas-title" style="display:inline-block;white-space:nowrap;">HOVEDMENU</h5>
  </div>

  <div class="float-start">
  <div class="btn-group badge text-bg-light text-dark">
  <img loading="lazy" src="/bootstrap/bootstrap-icons/icons/globe.svg" alt="..." width="32" height="32"/>
  <button type="button" class="align-middle btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
  Sprog
  </button>
  <ul class="dropdown-menu dropdown-menu-end">
    <li><h6 class="dropdown-header" role="heading"><span>Language</span></h6></li>
  {foreach $arrLangs as $languageItem}
    <li>
    {if $curURIPrefix == $languageItem.lang_target}
     <a class="dropdown-item active" aria-current="true" href="{$languageItem.lang_target|escape}" target="_self" hreflang="{$languageItem.lang_ident|escape}">
      <span lang="{$languageItem.lang_ident|escape}" role="menuitem">{$languageItem.lang_native|escape}</span>
     </a>
    {else}
      <a class="dropdown-item" href="{$languageItem.lang_target|escape}" target="_self" hreflang="{$languageItem.lang_ident}">
       <span lang="{$languageItem.lang_ident|escape}" role="menuitem">{$languageItem.lang_native|escape}</span>
      </a>
    {/if}
    </li>
{/foreach}
  </ul>
</div>
  </div>
     </header>

     <div class="offcanvas-body" role="menubar">
        <ul class="navbar-nav justify-content-end flex-grow-1" role="menu">
{foreach $arrMenuItems as $menuItem}
{if $menuItem.menu_item_type == 'menu_item'}
  {if isset($curSubMenu) && ($menuItem.menu_id == $curSubMenu.sub_menu_id)}
    {if $menuItem@last}
      <li>
        <hr class="dropdown-divider"/>
      </li>
    {/if}
    <li><a class="dropdown-item" href="{$menuItem.menu_item_uri|escape}" target="_self" rel="canonical" role="menuitem">{$menuItem.menu_item_title|escape}</a></li>
    {if $menuItem@last}
    </ul>
    </li>
    {/if}
  {else}
  <li class="nav-item" role="menuitem">
  {if $smarty.server.REQUEST_URI == $menuItem.menu_item_uri}
    <a class="nav-link active" aria-current="page" href="{$menuItem.menu_item_uri|escape}" target="_self" rel="canonical">{$menuItem.menu_item_title|escape}</a>
  {else}
    <a class="nav-link" href="{$menuItem.menu_item_uri|escape}" target="_self" rel="canonical">{$menuItem.menu_item_title|escape}</a>
  {/if}
  </li>
  {/if}
{elseif $menuItem.menu_item_type == 'sub_menu'}
  {assign var=curSubMenu value=$menuItem}
  <li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
  {$menuItem.menu_item_title|escape}
  </a>
  <ul class="dropdown-menu dropdown-menu-dark" role="menu">
{/if}
{foreachelse}
<span lang="en">No menu-items were found ...</span>
{/foreach}
  </ul>

<!-- User settings -->
<div id="user_settings" class="btn-group btn-group-vertical btn-group-md" role="group" aria-label="User settings">
{include file="{$arrConfigPaths.standard|cat:'widget_theme_switcher.tpl'}"}

<!-- Toggle button for cookie-alert -->
<div id="btn_cookie_toggle" role="button" aria-label="Cookie settings" style="margin-top:1em;">
<div class="btn-group-vertical btn-group-md" role="group" aria-label="Cookie settings">
  <input type="radio" class="btn-check" name="cookie_show_dialog" id="vbtn-radio4" autocomplete="off" />
  <label class="btn btn-outline-info" for="vbtn-radio4" aria-label="Show/hide Cookie-alert dialog" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Show/hide Cookie-settings">
    <img src="/bootstrap/bootstrap-icons/icons/cookie.svg" loading="lazy" class="icon icon-size-small" alt="..."/>
  </label>
</div>
</div>
</div><!-- button-group -->

{if isset($smarty.const.APP_DEBUG_MODE) && $smarty.const.APP_DEBUG_MODE}
<div style="color:#fff;">
  <p class="fw-semibold fst-italic p-2">
Template: <span class="text-nowrap text-lowercase">{$smarty.template}</span>
  </p>
</div>
{/if}
      </div>
    </div>
  </div>
</nav>