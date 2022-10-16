{assign var=scriptName value=$scriptName|default:""}
{assign var=pageMetaDescription value=$pageMetaDescription|default:""}

<!DOCTYPE html>
<!--[if lt IE 7]>      <html lang="da" dir="ltr" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html lang="da" dir="ltr" class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html lang="da" dir="ltr" class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="da" dir="ltr" class="no-js"> <!--<![endif]-->
<head>
 <meta charset="utf-8" />
 <title>{$pageDomainTitle}</title>
 <meta name="description" content="{$pageMetaDescription}">
 <meta name="author" content="Ivan Mark Andersen">
 <meta name="viewport" content="width=device-width, initial-scale=1">

 <link rel="shortcut icon" href="/favicon.ico">
 <link type="text/css" rel="stylesheet" href="/styles/main_v15.min.css">

 <script type="text/javascript" src="/js/vendor/modernizr/modernizr.2.8.3.custom.min.js"></script>
 <script type="text/javascript" src="/js/anti-spam.js"></script>
</head>

<body>
   <div class="header-container">
       <header class="wrapper clearfix">
         <section id="logo-section">
          <a href="/pages/" title="Til Startsiden"><span class="font-airstrike-academy airstrike-academy-4x" style="color:#333;">Ivanonof.dk</span></a>
         </section>

         <section id="menu-section" style="width:100%;float:left;">
            <div role="navigation" class="my-nav-header">
              <ul class="nav nav-tabs nav-justified">
                <li role="presentation" {if $scriptName == '' || $scriptName == 'index'}class="active"{/if}><a href="/pages/" class="menu-startpage">Startsiden</a></li>
                <li role="presentation" {if $scriptName == 'about_me'}class="active"{/if}><a href="/pages/about_me.php" class="menu-about-me">Om mig</a></li>
                <li role="presentation" {if $scriptName == 'contact'}class="active"{/if}><a href="/pages/contact.php" class="menu-contact">Kontakt mig</a></li>
              </ul>
            </div>
         </section>
       </header>
   </div>

   <div class="main-container">
   <!--[if lt IE 7]>
    <p lang="en" class="browsehappy">You are using an <strong>outdated</strong> web-browser.<br/>
      Please <a href="http://browsehappy.com/">upgrade your web-browser</a> to improve your experience.</p>
   <![endif]-->