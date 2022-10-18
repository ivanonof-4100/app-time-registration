{assign var=pageTitle value=$pageTitle|default:""}
{assign var=pageLangIdent value=$pageLangIdent|default:"en"}
{assign var=mainContent value=$mainContent|default:""}
{assign var=sidebarContent value=$sidebarContent|default:""}

<!DOCTYPE html>
<html dir="ltr" lang="{$pageLangIdent}">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>{$pageDomainTitle|escape}</title>
<link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous"/>
</head>

<body>
<h1 class="text-center">{$pageTitle|escape}</h1>

 <div class="container">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>
</html>