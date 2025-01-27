{if $smarty.server.SERVER_PORT == 443}
 {assign var=requestProtocol value=$requestProtocol|default:'https'}
{elseif $smarty.server.SERVER_PORT == 80}
 {assign var=requestProtocol value=$requestProtocol|default:'http'}
{/if}
{assign var=appURL value=$appURL|default:$requestProtocol|cat:'://'|cat:$smarty.server.HTTP_HOST|cat:$smarty.server.REQUEST_URI}
{assign var=pageTitle value=$pageTitle|default:""}
{assign var=pageLangIdent value=$pageLangIdent|default:"en"}
{assign var=pageLangDirection value=$pageLangDirection|default:"ltr"}
{assign var=pageMetaDescription value=$pageMetaDescription|default:""}
{assign var=pageMetaKeywords value=$pageMetaKeywords|default:""}
{assign var=mainNavigation value=$mainNavigation|default:""}
{assign var=mainContent value=$mainContent|default:""}
{assign var=sidebarContent value=$sidebarContent|default:""}

<!DOCTYPE html>
<html dir="{$pageLangDirection|escape}" lang="{$pageLangIdent|escape}">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<meta name="application-url" content={$appURL|escape} />
<meta name="description" content="{$pageMetaDescription|escape}" />
{if $pageMetaKeywords != ''}
<meta name="keywords" content="{$pageMetaKeywords|escape}" />
{/if}
<title>{$pageDomainTitle|escape}</title>
<!-- Open Graph Protocol -->
<meta property="og:title" content="{$pageTitle|escape}" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="{$smarty.const.SITE_DOMAIN_NAME|escape}" />
<meta property="og:url" content="{$appURL|escape}" />
<meta property="og:description" content="{$pageMetaDescription|escape}" />
<!--
<meta property="og:image" content="/public/images/logo-fullhd.jpg" />
-->
<link rel="shortcut icon" type="image/svg+xml" href="/images/calendar-clock.svg" />
<link rel="stylesheet" href="/css/main.css" charset="utf-8" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"/>
<!-- Inline style -->
{literal}
<style>
body {
position:relative;
overflow-x:hidden;
overflow-y:auto;
font-weight:500;
line-height:1.2em;
}
footer, section {
position:relative;
display:block;
float:left;
clear:both;
width:100%;
}
section {
padding-top:1em;
padding-bottom:1em;
padding: 1rem 0;
}
footer.page-footer {
color:#000;
font-weight:500;
text-align: center;
background:#ffffff;
background:-moz-linear-gradient(-45deg, #ffffff 0%, #ffffff 50%, #dedada 50%, #dedada 50%, #dedada 100%);
background:-webkit-gradient(linear, left top, right bottom, color-stop(0%,#ffffff), color-stop(50%,#ffffff), color-stop(50%,#F2F2F2), color-stop(50%,#F2F2F2), color-stop(100%,#F2F2F2));
background:-webkit-linear-gradient(-45deg, #ffffff 0%,#ffffff 50%,#dedada 50%,#dedada 50%,#dedada 100%);
background:-o-linear-gradient(-45deg, #ffffff 0%,#ffffff 50%,#dedada 50%,#dedada 50%,#dedada 100%);
background:-ms-linear-gradient(-45deg, #ffffff 0%,#ffffff 50%,#dedada 50%,#dedada 50%,#dedada 100%);
background:linear-gradient(135deg, #ffffff 0%,#ffffff 50%,#dedada 50%,#dedada 50%,#dedada 100%);
margin-top:1em;
margin-bottom:0;
font-size:1.2em;
min-height:120px;
}
footer.page-footer a {
color:#222;
text-decoration:underline;
}
footer.page-footer a:hover {
color:navy;
font-weight:500;
}
a > .icon {
margin:7px;
text-decoration:none;
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
}

h1, h2, h3, h5 {
font-weight:600;
}
h1 {
color:slategrey;
font-size:3em;
}
h4 {
font-weight:500;
padding-top:0.7em;
}

button.nav-link.active {
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
}

.icon {
width:4.5em;
height:4.5em;
vertical-align:baseline;
}
.icon-medium {
width:65px;
height:65px;
vertical-align:baseline;
}
.icon-small {
width:2.7em;
height:2.7em;
vertical-align:baseline;
}

a.goto-top-link {
fill:#222;
text-decoration:none;
display:block;
position:fixed;
z-index:100;
right:20px;
bottom:55px;
-webkit-text-stroke:1px black;
text-shadow:-1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000;
}
a.goto-top-link:hover {
color:rgba(248,248,255,0.97);
}
</style>
{/literal}
</head>

<body style="background:#ffc107;color:#222;">
<div class="container-fluid">
  <div class="row">
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-body-tertiary sidebar collapse">
      <div class="position-sticky pt-3 sidebar-sticky">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home align-text-bottom" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
              Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file align-text-bottom" aria-hidden="true"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
              Orders
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart align-text-bottom" aria-hidden="true"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
              Products
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users align-text-bottom" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
              Customers
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bar-chart-2 align-text-bottom" aria-hidden="true"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
              Reports
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layers align-text-bottom" aria-hidden="true"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
              Integrations
            </a>
          </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-body-secondary text-uppercase">
          <span>Saved reports</span>
          <a class="link-secondary" href="#" aria-label="Add a new report">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle align-text-bottom" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
          </a>
        </h6>
        <ul class="nav flex-column mb-2">
          <li class="nav-item">
            <a class="nav-link" href="#">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text align-text-bottom" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
              Current month
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text align-text-bottom" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
              Last quarter
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text align-text-bottom" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
              Social engagement
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text align-text-bottom" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
              Year-end sale
            </a>
          </li>
        </ul>
      </div>
    </nav>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
          </div>
          <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar align-text-bottom" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            This week
          </button>
        </div>
      </div>

      <canvas class="my-4 w-100" id="myChart" width="1539" height="650" style="display: block; box-sizing: border-box; height: 650px; width: 1539px;"></canvas>

      <h2>Section title</h2>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Header</th>
              <th scope="col">Header</th>
              <th scope="col">Header</th>
              <th scope="col">Header</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1,001</td>
              <td>random</td>
              <td>data</td>
              <td>placeholder</td>
              <td>text</td>
            </tr>
            <tr>
              <td>1,002</td>
              <td>placeholder</td>
              <td>irrelevant</td>
              <td>visual</td>
              <td>layout</td>
            </tr>
            <tr>
              <td>1,003</td>
              <td>data</td>
              <td>rich</td>
              <td>dashboard</td>
              <td>tabular</td>
            </tr>
            <tr>
              <td>1,003</td>
              <td>information</td>
              <td>placeholder</td>
              <td>illustrative</td>
              <td>data</td>
            </tr>
            <tr>
              <td>1,004</td>
              <td>text</td>
              <td>random</td>
              <td>layout</td>
              <td>dashboard</td>
            </tr>
            <tr>
              <td>1,005</td>
              <td>dashboard</td>
              <td>irrelevant</td>
              <td>text</td>
              <td>placeholder</td>
            </tr>
            <tr>
              <td>1,006</td>
              <td>dashboard</td>
              <td>illustrative</td>
              <td>rich</td>
              <td>data</td>
            </tr>
            <tr>
              <td>1,007</td>
              <td>placeholder</td>
              <td>tabular</td>
              <td>information</td>
              <td>irrelevant</td>
            </tr>
            <tr>
              <td>1,008</td>
              <td>random</td>
              <td>data</td>
              <td>placeholder</td>
              <td>text</td>
            </tr>
            <tr>
              <td>1,009</td>
              <td>placeholder</td>
              <td>irrelevant</td>
              <td>visual</td>
              <td>layout</td>
            </tr>
            <tr>
              <td>1,010</td>
              <td>data</td>
              <td>rich</td>
              <td>dashboard</td>
              <td>tabular</td>
            </tr>
            <tr>
              <td>1,011</td>
              <td>information</td>
              <td>placeholder</td>
              <td>illustrative</td>
              <td>data</td>
            </tr>
            <tr>
              <td>1,012</td>
              <td>text</td>
              <td>placeholder</td>
              <td>layout</td>
              <td>dashboard</td>
            </tr>
            <tr>
              <td>1,013</td>
              <td>dashboard</td>
              <td>irrelevant</td>
              <td>text</td>
              <td>visual</td>
            </tr>
            <tr>
              <td>1,014</td>
              <td>dashboard</td>
              <td>illustrative</td>
              <td>rich</td>
              <td>data</td>
            </tr>
            <tr>
              <td>1,015</td>
              <td>random</td>
              <td>tabular</td>
              <td>information</td>
              <td>text</td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>


 <header class="d-flex justify-content-center py-3">
 {$mainNavigation}
 </header>
 <div class="container-fluid" style="background-color:#dee2e6;padding-top:2em;">
   <h1 class="text-center">{$pageTitle|escape}</h1>
   <div class="row">
{if $sidebarContent != ''}
    <main class="col-md-9 ms-sm-auto col-lg-10" role="main">
{$mainContent}
    </main>
    <aside class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
    {$sidebarContent}
    </aside>
{else}
    <main class="col-md-12 ms-sm-auto col-lg-12" role="main">
{$mainContent}
    </main>
{/if}
   </div>
 </div>
{include file="page_footer.tpl"}

{if $smarty.const.APP_DEBUG_MODE}
<div>
<p>Template: <i><b>{$smarty.template}</b></i></p>
</div>
{/if}
<!-- JavaScript with Popper -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
</body>
</html>