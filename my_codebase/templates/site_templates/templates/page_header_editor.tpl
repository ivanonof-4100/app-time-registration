{assign var=scriptName value=$scriptName|default:""}
{assign var=pageMetaDescription value=$pageMetaDescription|default:""}

<!DOCTYPE html>
<!--[if lt IE 7]><html lang="da" dir="ltr" class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]>   <html lang="da" dir="ltr" class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]>   <html lang="da" dir="ltr" class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--><html lang="da" dir="ltr" class="no-js"><!--<![endif]-->
<head>
 <meta charset="utf-8" />
 <title>CKEditor Classic Editing Sample</title>
 <meta name="description" content="{$pageMetaDescription}">
 <meta name="author" content="Ivan Mark Andersen">
 <meta name="viewport" content="width=device-width, initial-scale=1">

 <link rel="shortcut icon" href="/favicon.ico">
 <link type="text/css" rel="stylesheet" href="/styles/main_v17.min.css">
 <script type="text/javascript" src="/js/vendor/modernizr/modernizr.2.8.3.custom.min.js"></script>
 <script type="text/javascript" src="/js/vendor/ckeditor/ckeditor.js"></script>
</head>

<body>
<section id="doc-start" class="background-pattern-two" style="border-bottom:9px solid #5cb85c;">
<div class="container">
  <div class="row">
    <div class="col-sm-12 col-md-12">
      <div class="text-left">
  <a href="/pages/" target="_self" title="Til Startsiden"><span class="logo-section font-airstrike-academy" style="color:black;">Ivanonof.dk</span></a>
      </div>
      <div class="text-left" style="margin:10px 0 10px 0">
        <span lang="en" class="overlay-color-blue text-uppercase wide-text">- OFFICIAL WEB-SITE OF Ivan Mark Andersen</span>
      </div>
    </div><!-- col -->
  </div><!-- row -->
  
  <section id="page-navbar">
  <div class="row">
     <div class="col-sm-12 col-md-12">
       <nav role="navigation" class="navbar navbar navbar-default navbar-static-top">
            <section id="page-navbar-nav" class="navbar-collapse collapse" style="float:left;">
              <ul class="nav navbar-nav">
                <li {if $scriptName == '' || $scriptName == 'index'}class="active"{/if}><a href="/pages/" target="_self"><span class="menu-startpage">Startsiden</span></a></li>
                <li {if $scriptName == 'about_me'}class="active"{/if}><a href="/pages/about_me.php" target="_self"><span class="menu-about-me">Om&nbsp;mig</span></a></li>
                <li {if $scriptName == 'contact'}class="active"{/if}><a href="/pages/contact.php" target="_self"><span class="menu-contact">Kontakt&nbsp;mig</span></a></li>
              </ul>
            </section>
        </nav>
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#page-navbar-nav" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
     </div><!-- col -->
<!--
     <div class="col-sm-2 col-lg-0">
       <div class="navbar-header">
         <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#page-navbar-nav" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
         </button>
       </div>
     </div>< ! -- col -- >
-->
  </div><!-- row -->
</section>
  
</div><!-- container -->
</section>

<section class="section-page-content">
<section class="section-breadcrumbs clearfix">
<!-- Title and bread-crumb-navigation -->
  <div class="container">
    <div class="row">
      <div class="col-md-7">
        <h1>{$pageTitle}</h1>
      </div><!-- col -->

      <div class="col-md-5 breadcrumb-group">
        <ol role="navigation" class="breadcrumb text-right" itemscope itemtype="http://schema.org/BreadcrumbList">
          <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
            <a itemprop="item" href="/pages/" target="_self"><span itemprop="name">Startsiden</span></a>
              <meta itemprop="position" content="1" />
          </li>
          <li class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
             <span itemprop="name">{$pageTitle}</span>
             <meta itemprop="position" content="2" />
          </li>
        </ol>
      </div><!-- col -->
    </div><!-- row -->
  </div><!-- container -->
</section>

{if $smarty.const.DEBUG}
<div>
<p>Template: <i><b>{$smarty.template}</b></i></p>
</div>
{/if}
