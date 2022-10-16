<?php
/* Smarty version 3.1.34-dev-7, created on 2020-12-30 00:09:21
  from '/var/www/custom_codebase/templates/site_templates/templates/error_500.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5febb7218162d9_83934948',
  'has_nocache_code' => true,
  'file_dependency' => 
  array (
    '7e4c81e3fffdc04b2d76ba64b7967bbc0cf5d4f1' => 
    array (
      0 => '/var/www/custom_codebase/templates/site_templates/templates/error_500.tpl',
      1 => 1606391243,
      2 => 'file',
    ),
    '93de9d0e18adcc5764e3616c3bc2bc3567d9a1a8' => 
    array (
      0 => '/var/www/custom_codebase/templates/site_templates/templates/page_header.tpl',
      1 => 1606391947,
      2 => 'file',
    ),
    '84bb4492172cac59bd976f4767a5daecda078548' => 
    array (
      0 => '/var/www/custom_codebase/templates/site_templates/templates/page_footer.tpl',
      1 => 1606391447,
      2 => 'file',
    ),
  ),
  'cache_lifetime' => 3600,
),true)) {
function content_5febb7218162d9_83934948 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<head dir="ltr" lang="da">
 <meta charset="utf-8" />
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <title>Intern server-fejl - carmenbarsel.ivanonof.dk</title>
 <meta name="description" content="carmenbarsel.ivanonof.dk, 500 fejl-side">
 <meta name="author" content="Ivan Mark Andersen">

 <link rel="shortcut icon" href="/favicon.ico">
 <!-- Bootstrap Stylesheet -->
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
 <link type="text/css" rel="stylesheet" href="/styles/main_v17.min.css">

 <script type="text/javascript" src="/js/vendor/modernizr/modernizr.2.8.3.custom.min.js"></script>
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
                <li class="active"><a href="/pages/" target="_self"><span class="menu-startpage">Startsiden</span></a></li>
                <li ><a href="/pages/about_me.php" target="_self"><span class="menu-about-me">Om&nbsp;mig</span></a></li>
                <li ><a href="/pages/contact.php" target="_self"><span class="menu-contact">Kontakt&nbsp;mig</span></a></li>
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
        <h1>Intern server-fejl</h1>
      </div><!-- col -->

      <div class="col-md-5 breadcrumb-group">
        <ol role="navigation" class="breadcrumb text-right" itemscope itemtype="http://schema.org/BreadcrumbList">
          <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
            <a itemprop="item" href="/" target="_self"><span itemprop="name">Startsiden</span></a>
              <meta itemprop="position" content="1" />
          </li>
          <li class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
             <span itemprop="name">Intern server-fejl</span>
             <meta itemprop="position" content="2" />
          </li>
        </ol>
      </div><!-- col -->
    </div><!-- row -->
  </div><!-- container -->
</section>

<div>
<p>Template: <i><b>page_header.tpl</b></i></p>
</div>

<!--    <div class="main wrapper clearfix"> -->
<section class="background-pattern-series-four">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div lang="en" class="error-mesg text-center">
                    <span class="error-mesg-title" style="font-size:6.5em;"><i>500</i></span>
                    <h2><strong>An internal server-error occurred!</strong></h2>
                    <p>We are of cause sorry for the inconvenience that this error has caused you.<br/>
                        The internal-error has been logged and our <strong>web-master</strong> will fix the bug as soon as possible.</p>
                </div>
            </div><!-- col -->
        </div><!-- row -->

        <div class="row">
          <div class="col-md-12">
          <p class="text-center"><a class="btn btn-primary btn-lg btn-go-back" href="/">Back Home</a></p>
          </div><!-- col -->
        </div><!-- row -->

        <div class="row">
          <div class="col-md-12">
            <div class="bs-callout bs-callout-error overlay-color-white">
              <div lang="en" class="error-mesg">
              <h3 class="text-center"><i class="fa fa-info-circle" aria-hidden="true"></i></h3>
<p class="text-left"><span style="color:red;"><?php echo $_smarty_tpl->tpl_vars['errorMessage']->value;?>
</span></p>
              </div>
            </div>
          </div><!-- col -->
        </div><!-- row -->
             </div>
</section>
<p>Template: <i><b><?php echo basename($_smarty_tpl->source->filepath);?>
</b></i></p>
    </div><!-- container -->

<?php $_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/custom_codebase/common/library/smarty/smarty-3.1.35/libs/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
$_smarty_tpl->_assignInScope('currentYear', smarty_modifier_date_format(time(),"%Y") ,true);?>

</section>

<!-- FOOTER -->
<footer class="footer page-footer">
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div>
        <p class="text-center">
           <span style="color:#333333;" class="fa fa-info-circle" aria-hidden="true"></span>&nbsp; <a href="#about_cookies">Cookies</a> | <a href="#powered_by">Powered by</a> | <a href="/pages/contact.php">Kontakt mig</a></p>
        <p class="text-center" style="color:#585858;padding-top:15px;padding-bottom:5px;"><i class="fa fa-3x fa-html5" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<i class="fa fa-3x fa-css3" aria-hidden="true"></i></p>
      </div>
    </div><!-- col -->
  </div><!--- row -->
</div><!-- container -->
</footer>

<div>
<p>Template: <i><b>page_footer.tpl</b></i></p>
</div>

<!-- jQuery and JS bundle w/ Popper.js -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html><?php }
}
