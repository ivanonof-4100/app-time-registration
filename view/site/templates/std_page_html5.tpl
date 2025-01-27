{assign var=pageTitle value=$pageTitle|default:""}
{assign var=pageLangIdent value=$pageLangIdent|default:"en"}
{assign var=pageLangDirection value=$pageLangDirection|default:"ltr"}
{assign var=pageMetaDescription value=$pageMetaDescription|default:""}
{assign var=pageMetaKeywords value=$pageMetaKeywords|default:""}
{assign var=mainContent value=$mainContent|default:""}
{assign var=sidebarContent value=$sidebarContent|default:""}
{assign var=appURL value=$appURL|default:"http://ivanonof.dk/da/admin/timesheets/"}

<!DOCTYPE html>
<html dir="{$pageLangDirection}" lang="{$pageLangIdent}">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<meta name="application-url" content={$appURL|escape} />
<meta name="description" content="{$pageMetaDescription|escape}" />
{if $pageMetaKeywords != ''}
<meta name="keywords" content="{$pageMetaKeywords|escape}" />
{/if}
<title>{$pageDomainTitle|escape}</title>
<link rel="shortcut icon" type="image/png" href="/images/icon-timesheet-128x128_v2.png" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"/>
</head>

<body class="text-bg-light">
 <div class="container text-bg-light">
   <h1 class="text-center">{$pageTitle|escape}</h1>
   <div class="row">
{if $sidebarContent != ''}
    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4" role="main">
    {$mainContent}
    </div>

    <aside class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
    {$sidebarContent}
    </aside>
{else}
    <div class="col-md-12 ms-sm-auto col-lg-12 px-md-4" role="main">
    {$mainContent}
    </div>
{/if}
   </div>
 </div>
<!-- JavaScript with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
</body>
</html>