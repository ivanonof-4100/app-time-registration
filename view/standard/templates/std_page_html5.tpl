{if $smarty.server.SERVER_PORT == 443}
{assign var=requestProtocol value=$requestProtocol|default:'https'|cat:'://'}
{elseif $smarty.server.SERVER_PORT == 80}
{assign var=requestProtocol value=$requestProtocol|default:'http'|cat:'://'}
{/if}
{assign var=pageLangIdent value=$pageLangIdent|default:$smarty.const.APP_LANGUAGE_IDENT}
{assign var=appURLCanonical value=$appURLCanonical|default:$requestProtocol|cat:$smarty.server.HTTP_HOST|cat:'/'|cat:$pageLangIdent|cat:'/'}
{assign var=appURL value=$appURL|default:$requestProtocol|cat:$smarty.server.HTTP_HOST|cat:$smarty.server.REQUEST_URI}
{assign var=pageTitle value=$pageTitle|default:""}
{assign var=pageLangDirection value=$pageLangDirection|default:"ltr"}
{assign var=pageMetaDescription value=$pageMetaDescription|default:""}
{assign var=pageMetaKeywords value=$pageMetaKeywords|default:""}
{assign var=mainNavigation value=$mainNavigation|default:""}
{assign var=mainContent value=$mainContent|default:""}
{assign var=sidebarContent value=$sidebarContent|default:""}
{assign var=arrConfigPaths value=$arrConfigPaths}
{assign var=smartyVersion value=$smartyVersion|default:"Undefined"}
<!DOCTYPE html>
<html dir="{$pageLangDirection|escape}" lang="{$pageLangIdent|escape}">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<meta name="application-url" content="{$appURL|escape}"/>
<meta name="description" content="{$pageMetaDescription|escape}"/>
{if $pageMetaKeywords != ''}
<meta name="keywords" content="{$pageMetaKeywords|escape}"/>
{/if}
<title>{$pageDomainTitle|escape}</title>
<link rel="canonical" href="{$appURLCanonical|escape}"/>
<!-- Open Graph Protocol -->
<meta property="og:title" content="{$pageTitle|escape}"/>
<meta property="og:type" content="website"/>
<meta property="og:site_name" content="{$smarty.const.SITE_DOMAIN_NAME|escape}"/>
<meta property="og:url" content="{$appURL|escape}" />
<meta property="og:description" content="{$pageMetaDescription|escape}"/>
<meta property="og:image" content="/images/calendar-clock.svg"/>
<meta name="color-scheme" content="light dark"/>
<link rel="shortcut icon" type="image/svg+xml" href="/images/calendar-clock.svg"/>
{literal}
<!-- Inline font-style -->
<style>
@font-face {
font-family:'limelightregular';
src:url('/css/fonts/limelight/limelight-regular.woff2') format('woff2'), url('/css/fonts/limelight/limelight-regular.woff') format('woff');
font-display:swap;
font-weight:normal;
font-style:normal;
}
@font-face {
font-family:'dejavu_sansbold_oblique';
src:url('/css/fonts/dejavu-sans/dejavusans-boldoblique-webfont.woff2') format('woff2'),url('/css/fonts/dejavu-sans/dejavusans-boldoblique-webfont.woff') format('woff');
font-display:swap;
font-weight:normal;
font-style:normal;
}
</style>
{/literal}
<link rel="stylesheet" type="text/css" href="/bootstrap/bootstrap-5.3.3-dist/css/bootstrap.min.css" />
<link rel="stylesheet" media="print" type="text/css" href="/css/print.min.css" />

{literal}
<!-- Inline style -->
<style>
/* This theme can be viewed in both light and dark */
:root {
color-scheme:light dark;
}

/*
:root {
color-scheme:light dark;
--font-color:#c1bfbd;
--link-color:#0a86da;
--link-white-color:#c1bfbd;

--light-bg:ghostwhite;
--light-color: darkslategray;
--light-code: tomato;

--dark-bg: slategray;
--dark-color: ghostwhite;
--dark-code: gold;
}

* {
  background-color: light-dark(var(--light-bg), var(--dark-bg));
  color: light-dark(var(--light-color), var(--dark-color));
}
*/
/*
body[data-bs-theme="dark"] {
--font-color:#c1bfbd;
--link-color:#0a86da;
--link-white-color:#c1bfbd;
background-color:#444;
}
*/
html {
scroll-behavior:smooth;
}
body {
position:relative;
display:flex;
flex-direction:column;
/*background:#eee;*/
overflow-x:hidden;
overflow-y:auto;
font-weight:500;
font-size:14pt;
line-height:1.2;
}
main {
display:block;
min-height:540px;
}

h1 {
font-family:'dejavu_sansbold_oblique';
font-size:2.4em;
font-style:normal;
font-weight:normal;
line-height:1.2;
/*color:#08357b;*/
margin-top:15px;
margin-bottom:15px;
}
/*
body[data-bs-theme="light"] h1 {
color:#08357b;
}
body[data-bs-theme="dark"] h1 {
color:#444;
}
*/
h2,h3,h5 {
font-weight:600 !important;
font-style:normal;
}
h4 {
font-weight:500;
}

.visible {
visibility:visible !important;
}
.invisible {
visibility:hidden !important;
}

.logo-text {
font-family:'limelightregular';
font-size:2.2em;
line-height:1.2;
}

/* Menu links */
li.nav-item a.nav-link:hover, li.nav-item a.nav-link:visited:hover {
color:#50f148;
font-weight:600;
}
li.nav-item a.nav-link:visited {
color:ghostwhite;
font-weight:normal;
}

/* Light theme */
/*
body[data-bs-theme="light"] {
background-color:#ffc107;
color:#222;
}
*/
/*
body[data-bs-theme="light"] div.offcanvas {
background-color:slategray;
color:#222;
}
*/
body[data-bs-theme="light"] div.page-area {
background-color:#dee2e6;
color:#222;
padding-top:2em;
padding-bottom:9px;
}
body[data-bs-theme="light"] div.progress {
background-color:slategrey;
}

/* Light theme */
@media (prefers-color-scheme: light) {
  body {
  color-scheme:light;
  background-color:#ffc107;
  color:#222;
  }
  h1 {
  color:#08357b;
  }
  div.offcanvas {
    background-color:chocolate;
    color:#222;
  }
  /* Radio-buttons */
  body[data-bs-theme="light"] div#user_settings input[type="radio"]:not(:checked)+label:hover {
background-color:lightgoldenrodyellow;
color:#222;
}
/* Radio-buttons */
body[data-bs-theme="light"] div#user_settings input[type="radio"]:checked+label {
background-color:ghostwhite;
color:#222;
}
body[data-bs-theme="light"] div#user_settings input[type="radio"]:not(:checked)+label {
color:#222;
background-color:#999999;
background-color:ghostwhite;
background-color:lightskyblue;
}

  body ul.nav-tabs > li.nav-item > button.nav-link.active {
    background-color:#ffc107;
    color:#222;
  }
  body div.page-area {
    background-color:#dee2e6;
    color:#222;
    padding-top:2em;
    padding-bottom:9px;
  }
  body div.custom-tab-content {
    background-color:#fff3cd;
    color:#222;
  }
  svg path {
    fill:#222;
  }
  body svg.calender-frame {
    fill:#222;
    stroke:#222;
  }
  body div.outer-calender-container {
    position:relative;
    box-sizing:border-box;
    width:110px;
    height:110px;
  }
  body div.inner-calender-container {
    position:absolute;
    background-color:#997404;
    color:rgb(255,218,106);
    width:96px;
    height:76px;
    z-index:2;
    top:27px;
    margin-left:7px;
    margin-right:7px;
    background-color:#fff;
    color:#222;
  }
  body footer.page-footer {
    background:#ffffff;
    background:-moz-linear-gradient(-45deg,#ffffff 0%,#ffffff 50%,#dedada 50%,#dedada 50%,#dedada 100%);
    background:-webkit-linear-gradient(-45deg,#ffffff 0%,#ffffff 50%,#dedada 50%,#dedada 50%,#dedada 100%);
    background:-o-linear-gradient(-45deg,#ffffff 0%,#ffffff 50%,#dedada 50%,#dedada 50%,#dedada 100%);
    background:-ms-linear-gradient(-45deg,#ffffff 0%,#ffffff 50%,#dedada 50%,#dedada 50%,#dedada 100%);
    background:linear-gradient(135deg,#ffffff 0%,#ffffff 50%,#dedada 50%,#dedada 50%,#dedada 100%);
  }

  div.cookie-alert a {
    color:#08357b;
  }
}

/* Dark theme */
body[data-bs-theme="dark"] div.offcanvas {
background-color:#222;
color:ghostwhite;
}
body[data-bs-theme="dark"] div.page-area {
background-color:#d7d2cb;
color:#222;
padding-top:2em;
padding-bottom:9px;
}

/* Radio-buttons */
body[data-bs-theme="dark"] div#user_settings input[type="radio"]:not(:checked)+label:hover {
background-color:lightgoldenrodyellow;
color:#222;
}
/* Radio-buttons */
body[data-bs-theme="dark"] div#user_settings input[type="radio"]:checked+label {
background-color:ghostwhite;
color:#222;
}
body[data-bs-theme="dark"] div#user_settings input[type="radio"]:not(:checked)+label {
color:#222;
background-color:lightseagreen;
}
/*
body[data-bs-theme="dark"] input[type="radio"]:not(:checked)+label:hover {
background-color:coral;
color:#222;
}

body[data-bs-theme="dark"] input[type="radio"]:checked+label {
color:#222;
background-color:ghostwhite;
}
body[data-bs-theme="dark"] input[type="radio"]:not(:checked)+label {
background-color:ghostwhite;
background-color:#999999;
background-color:lightseagreen;
background-color:#004a77;
color:ghostwhite;
}
*/

body[data-bs-theme="dark"] footer.page-footer {
background:#fff3cd;
background-color:#fff3cd;
background:-moz-linear-gradient(-45deg,#d7d2cb 0%,#fff3cd 50%,#dedada 50%,#dedada 50%,#dedada 100%);
background:-webkit-linear-gradient(-45deg,#d7d2cb 0%,#fff3cd 50%,#dedada 50%,#dedada 50%,#dedada 100%);
background:-o-linear-gradient(-45deg,#d7d2cb 0%,#fff3cd 50%,#dedada 50%,#dedada 50%,#dedada 100%);
background:-ms-linear-gradient(-45deg,#d7d2cb 0%,#fff3cd 50%,#dedada 50%,#dedada 50%,#dedada 100%);
background:linear-gradient(135deg,#d7d2cb 0%,#fff3cd 50%,#dedada 50%,#dedada 50%,#dedada 100%);
}

@media (prefers-color-scheme:dark) {
  body {
    color-scheme:dark;
    background-color:#444;
    color:#222;
  }

  body[data-bs-theme="dark"] {
    color-scheme:dark;
    background-color:#444;
    color:#222;
  }

  body[data-bs-theme="dark"] h1 {
    color-scheme:dark;
  color:#222;
  }
  div.offcanvas {
    background-color:#222;
    color:ghostwhite;
  }
  body ul.nav-tabs > li.nav-item > button.nav-link.active {
    background-color:#333;
    color:#dedada;
  }
/*
  body[data-bs-theme="dark"] div.page-area {
background-color:#d7d2cb;
color:#222;
padding-top:2em;
padding-bottom:9px;
}
*/
  div.page-area {
    background-color:#d7d2cb;
    color:#222;
    padding-top:2em;
    padding-bottom:9px;
  }
  body div.custom-tab-content {
    background-color:#fff3cd;
    color:#222;
  }
  div.accordion-body {
    color:#dedada;
  }
  svg path {
    fill:#08357b;
  }
  body svg.calender-frame {
    fill:#08357b;
    stroke:#08357b;
  }
  body div.outer-calender-container {
    position:relative;
    box-sizing:border-box;
    width:110px;
    height:110px;
  }
  body div.inner-calender-container {
    position:absolute;
    background-color:#997404;
    color:rgb(255,218,106);
    width:96px;
    height:76px;
    z-index:2;
    top:27px;
    margin-left:7px;
    margin-right:7px;
    background-color:#fff;
    color:#222;
  }
  body footer.page-footer {
    background:#fff3cd;
    background-color:#fff3cd;
    background:-moz-linear-gradient(-45deg,#d7d2cb 0%,#fff3cd 50%,#dedada 50%,#dedada 50%,#dedada 100%);
    background:-webkit-linear-gradient(-45deg,#d7d2cb 0%,#fff3cd 50%,#dedada 50%,#dedada 50%,#dedada 100%);
    background:-o-linear-gradient(-45deg,#d7d2cb 0%,#fff3cd 50%,#dedada 50%,#dedada 50%,#dedada 100%);
    background:-ms-linear-gradient(-45deg,#d7d2cb 0%,#fff3cd 50%,#dedada 50%,#dedada 50%,#dedada 100%);
    background:linear-gradient(135deg,#d7d2cb 0%,#fff3cd 50%,#dedada 50%,#dedada 50%,#dedada 100%);
  }
  div.cookie-alert a {
    color:#ffda6a;
  }
}

/* Themes Definition */
body[data-bs-theme="light"] {
/* Forces light color-scheme */
color-scheme:light;
}
body[data-bs-theme="dark"] {
/* Forces dark color-scheme */
color-scheme:dark;
}

/* Light Theme */
@media (prefers-color-scheme:light) {
  body[data-bs-theme="light"] {
  color-scheme:light;
  background-color:#ffc107;
  color:#222;
  }
  body h1 {
  color:#08357b;
  }
}
/* Dark Theme */
@media (prefers-color-scheme:dark) {
  body[data-bs-theme="dark"] {
    color-scheme:dark;
    background-color:#444;
    color:#222;
  }
  body h1 {
    color:#222;
  }
}

footer,section {
position:relative;
display:block;
float:left;
clear:both;
width:100%;
}
section {
padding-top:1em;
}

footer.page-footer {
min-height:fit-content;
color:#000;
font-weight:500;
text-align:center;
margin-top:0;
margin-bottom:0;
font-size:1.2em;
}
footer.page-footer a {
color:#222;
text-decoration:underline;
}
footer.page-footer a:hover {
color:navy;
font-weight:500;
}
div.social-links a {
margin:.5em;
text-decoration:none;
fill:currentColor;
}

section.section-footer-links {
padding:15px;
background:-moz-linear-gradient(top,#fff 0,#f6f6f6 47%,#ededed 100%);
background:-webkit-linear-gradient(top,#fff 0,#f6f6f6 47%,#ededed 100%);
background:linear-gradient(to bottom,#fff 0,#f6f6f6 47%,#ededed 100%);
padding-top:15px!important;
padding-bottom:15px!important;
text-align:center;
border-top:1px solid #999999;
border-bottom: 2px solid #999999;
color:#222;
line-height:normal;
font-weight:500;
}
section.section-footer-content {
color:#222;
border-bottom:2px solid#999999;
}

.navbar-nav > .nav-item {
margin-left:15px;
margin-right:15px;
}

#user_settings {
position:fixed;
top:36%;
right:15px;
}

.tab-pane {
padding-top:0.7em;
}
ul.nav-tabs > li.nav-item > .nav-link {
background-color:#dedada;
color:#222;
}
ul.nav-tabs > li.nav-item > .nav-link.active {
font-weight:600;
background-color:#ffc107;
}

.inner {
position:absolute;
}
.outer {
position:relative;
}
.cal-date-day-of-month {
color: #222;
font-size:170%;
font-weight:600;
}

.scrollspy-container {
position:relative !important;
height:400px;
overflow-y:scroll;
margin-left:15px;
margin-right:15px;
}
section.section-data-visualization {
background-color:transparent;
color:#000;
}

.alert {
margin-top:15px;
font-weight:500;
white-space:collapse;
word-wrap:normal;
}
.cookie-alert {
position:fixed;
display:block;
z-index:100;
left:20px;
bottom:15px;
border-left:2px solid slategray;
border-top:1px solid slategray;
}
/* Small devices */
@media only screen and (min-width:567px) {
  .cookie-alert {
  width:78%;
  }
}
@media only screen and (min-width:768px) {
  .cookie-alert {
  width:72%;
  }
}
/* Large (lg) screen */
@media only screen and (min-width:992px) { 
  .cookie-alert {
  width:64%;
  }
}
/* Extra Large screen */
@media only screen and (min-width:1200px) {
  .cookie-alert {
  width:52%;
  }
}
/* XXL screen */
@media only screen and (min-width:1400px) {
  .cookie-alert {
  width:40%;
  }
}

.drop-shadow {
box-shadow:10px 10px 5px 0px rgba(0,0,0,0.75);
-webkit-box-shadow:10px 10px 5px 0px rgba(0,0,0,0.75);
}

img.icon, svg.icon {
color:currentColor;
fill:currentColor;
vertical-align:baseline;
}

.icon-size-large {
width:5em;
height:5em;
}
.icon-size-medium {
width:65px;
height:65px;
}
.icon-size-small {
width:3.2em;
height:3.2em;
}

#btn_scroll_up.goto-top-link {
background-color:transparent;
fill:#222;
text-decoration:none;
display:block;
position:fixed;
z-index:100;
right:20px;
bottom:50px;
stroke:#222;
}
#btn_scroll_up:hover svg {
background-color:rgba(255,255,255,0.64);
fill:#222;
stroke:#222;
padding-left:1px;
padding-right:1px;
padding-top:1px;
padding-bottom:1px;
border-radius:50%;
}
.rounded-corners {
border-radius:5px;
}

/* Automatic add the red asterisk, if there is a label and the input-field is required. */
div.form-group:has(input.form-control:required) label.form-label::after,
div.form-floating:has(input:required) label.form-label::after {
content:" *";
color:red;
font-weight:600;
}

input.form-control:focus {
background-color:rgba(222, 222, 49, 0.16) !important;
}
input:in-range {
background-color:rgb(0 255 0 / 16%);
}
input:out-of-range,
input:invalid {
background-color:rgb(255 0 0 / 16%);
border:2px solid red;
}
input:focus {
background-color:rgb(255 0 255 / 16%);
border:2px solid yellow;
}
</style>
{/literal}
</head>

<body id="page-top" data-bs-theme="light">
 <header class="d-flex flex-wrap justify-content-center py-3" style="width:fit-content;font-size:1.25em;">
 {$mainNavigation}
 </header>
 <div class="container container-xxl page-area">
   <div class="row">
{if $sidebarContent != ''}
    <main class="float-start ms-sm-auto col-sm-12 col-md-9 col-lg-10" role="main">
<h1 class="text-center">{$pageTitle|escape}</h1>
{$mainContent}
    </main>
    <aside class="col-sm-12 col-md-3 col-lg-2 d-md-block bg-light sidebar">
{$sidebarContent}
    </aside>
{else}
    <main class="float-start ms-sm-auto col-sm-12 col-md-12 col-lg-12" role="main">
<h1 class="text-center">{$pageTitle|escape}</h1>
{$mainContent}
    </main>
{/if}
   </div>
 </div>

{include file="{$arrConfigPaths.standard|cat:'widget_cookies.tpl'}"}
{include file="{$arrConfigPaths.standard|cat:'page_footer.tpl'}"}

{if isset($smarty.const.APP_DEBUG_MODE) && $smarty.const.APP_DEBUG_MODE}
<section>
<div class="container container-xxl">
    <div class="row">
      <div class="col">
        <div class="float-start">
          <!-- Smarty SVG Logo -->
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" role="image" class="icon" aria-hidden="true">
            <path d="M12 6a6 6 0 0 1 6 6c0 2.22-1.21 4.16-3 5.2V19a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1v-1.8c-1.79-1.04-3-2.98-3-5.2a6 6 0 0 1 6-6m2 15v1a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-1h4m6-10h3v2h-3v-2M1 11h3v2H1v-2M13 1v3h-2V1h2M4.92 3.5l2.13 2.14-1.42 1.41L3.5 4.93 4.92 3.5m12.03 2.13 2.12-2.13 1.43 1.43-2.13 2.12-1.42-1.42Z"></path>
          </svg>
        </div>
        <div>
          <p class="fw-semibold fst-italic p-2">
          <span class="fs-5">Powered by PHP <span class="badge text-bg-secondary rounded-pill">version {$smarty.const.PHP_VERSION}</span> & Smarty Template-engine <span class="badge text-bg-secondary rounded-pill">version {$smartyVersion}</span> hosted on {$smarty.const.PHP_OS_FAMILY}.</span><br/>
          Template: <span class="text-nowrap text-lowercase">{$smarty.template}</span></p>
        </div>
      </div>
    </div>
</div>
</section>
{/if}
<!-- Load JavaScripts using RequireJS.
data-main tells to load /js/main.min.js after RequireJS has loaded -->
<script async defer data-main="/js/main.min" src="/js/requirejs/v2.3.7/require.min.js"></script>
</body>
</html>