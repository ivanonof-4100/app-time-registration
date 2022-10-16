<?php
/* Smarty version 3.1.34-dev-7, created on 2020-12-30 00:09:21
  from '/var/www/custom_codebase/templates/site_templates/templates/page_header.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5febb72180e428_42586046',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '93de9d0e18adcc5764e3616c3bc2bc3567d9a1a8' => 
    array (
      0 => '/var/www/custom_codebase/templates/site_templates/templates/page_header.tpl',
      1 => 1606391947,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5febb72180e428_42586046 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '16247658085febb721804719_92334077';
$_smarty_tpl->_assignInScope('scriptName', (($tmp = @$_smarty_tpl->tpl_vars['scriptName']->value)===null||$tmp==='' ? '' : $tmp));
$_smarty_tpl->_assignInScope('pageMetaDescription', (($tmp = @$_smarty_tpl->tpl_vars['pageMetaDescription']->value)===null||$tmp==='' ? '' : $tmp));?>

<!DOCTYPE html>
<head dir="ltr" lang="da">
 <meta charset="utf-8" />
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <title><?php echo $_smarty_tpl->tpl_vars['pageDomainTitle']->value;?>
</title>
 <meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['pageMetaDescription']->value;?>
">
 <meta name="author" content="Ivan Mark Andersen">

 <link rel="shortcut icon" href="/favicon.ico">
 <!-- Bootstrap Stylesheet -->
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
 <link type="text/css" rel="stylesheet" href="/styles/main_v17.min.css">

 <?php echo '<script'; ?>
 type="text/javascript" src="/js/vendor/modernizr/modernizr.2.8.3.custom.min.js"><?php echo '</script'; ?>
>
</head>

<body>
<section id="doc-start" class="background-pattern-two" style="border-bottom:9px solid #5cb85c;">
<div class="container">
  <div class="row">
    <div class="col-sm-12 col-md-12">
      <div class="text-left">
  <a href="/" target="_self" title="Til Startsiden"><span class="logo-section font-airstrike-academy" style="color:black;">Ivanonof.dk</span></a>
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
                <li <?php if ($_smarty_tpl->tpl_vars['scriptName']->value == '' || $_smarty_tpl->tpl_vars['scriptName']->value == 'index') {?>class="active"<?php }?>><a href="/pages/" target="_self"><span class="menu-startpage">Startsiden</span></a></li>
                <li <?php if ($_smarty_tpl->tpl_vars['scriptName']->value == 'about_me') {?>class="active"<?php }?>><a href="/pages/about_me.php" target="_self"><span class="menu-about-me">Om&nbsp;mig</span></a></li>
                <li <?php if ($_smarty_tpl->tpl_vars['scriptName']->value == 'contact') {?>class="active"<?php }?>><a href="/pages/contact.php" target="_self"><span class="menu-contact">Kontakt&nbsp;mig</span></a></li>
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
        <h1><?php echo $_smarty_tpl->tpl_vars['pageTitle']->value;?>
</h1>
      </div><!-- col -->

      <div class="col-md-5 breadcrumb-group">
        <ol role="navigation" class="breadcrumb text-right" itemscope itemtype="http://schema.org/BreadcrumbList">
          <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
            <a itemprop="item" href="/" target="_self"><span itemprop="name">Startsiden</span></a>
              <meta itemprop="position" content="1" />
          </li>
          <li class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
             <span itemprop="name"><?php echo $_smarty_tpl->tpl_vars['pageTitle']->value;?>
</span>
             <meta itemprop="position" content="2" />
          </li>
        </ol>
      </div><!-- col -->
    </div><!-- row -->
  </div><!-- container -->
</section>

<?php if (@constant('DEBUG')) {?>
<div>
<p>Template: <i><b><?php echo basename($_smarty_tpl->source->filepath);?>
</b></i></p>
</div>
<?php }
}
}
