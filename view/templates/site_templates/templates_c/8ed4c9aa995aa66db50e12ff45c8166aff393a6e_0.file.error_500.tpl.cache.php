<?php
/* Smarty version 4.2.1, created on 2022-10-14 14:34:46
  from '/data/WebProjects/Northern-partners/Projekt-timeseddler/app/view/templates/site_templates/templates/error_500.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.2.1',
  'unifunc' => 'content_63495766718094_50087670',
  'has_nocache_code' => true,
  'file_dependency' => 
  array (
    '8ed4c9aa995aa66db50e12ff45c8166aff393a6e' => 
    array (
      0 => '/data/WebProjects/Northern-partners/Projekt-timeseddler/app/view/templates/site_templates/templates/error_500.tpl',
      1 => 1664798811,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:page_header.tpl' => 1,
    'file:page_footer.tpl' => 1,
  ),
),false)) {
function content_63495766718094_50087670 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '138817623463495766711257_88798796';
$_smarty_tpl->_subTemplateRender("file:page_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array('pageTitle'=>$_smarty_tpl->tpl_vars['pageTitle']->value), 0, false);
$_smarty_tpl->_assignInScope('errorTitle', (($tmp = $_smarty_tpl->tpl_vars['errorTitle']->value ?? null)===null||$tmp==='' ? "Script execution halted!" ?? null : $tmp));
$_smarty_tpl->_assignInScope('errorMessage', (($tmp = $_smarty_tpl->tpl_vars['errorMessage']->value ?? null)===null||$tmp==='' ? "No error-message." ?? null : $tmp));
$_smarty_tpl->_assignInScope('errorColor', (($tmp = $_smarty_tpl->tpl_vars['errorColor']->value ?? null)===null||$tmp==='' ? "red" ?? null : $tmp));?>

<!--    <div class="main wrapper clearfix"> -->
<section class="background-pattern-series-four">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div lang="en" class="error-mesg text-center">
                    <span class="error-mesg-title" style="font-size:6.5em;"><i>500</i></span>
                    <h2><strong><?php echo $_smarty_tpl->tpl_vars['errorTitle']->value;?>
</strong></h2>
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
<p class="text-left"><span style="color:<?php echo $_smarty_tpl->tpl_vars['errorColor']->value;?>
;"><?php echo '/*%%SmartyNocache:138817623463495766711257_88798796%%*/<?php echo $_smarty_tpl->tpl_vars[\'errorMessage\']->value;?>
/*/%%SmartyNocache:138817623463495766711257_88798796%%*/';?>
</span></p>
              </div>
            </div>
          </div><!-- col -->
        </div><!-- row -->
             </div>
</section>
<?php if ((defined('DEBUG') ? constant('DEBUG') : null)) {?>
<p>Template: <i><b><?php echo '/*%%SmartyNocache:138817623463495766711257_88798796%%*/<?php echo basename($_smarty_tpl->source->filepath);?>
/*/%%SmartyNocache:138817623463495766711257_88798796%%*/';?>
</b></i></p>
<?php }?>
    </div><!-- container -->

<?php $_smarty_tpl->_subTemplateRender("file:page_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
