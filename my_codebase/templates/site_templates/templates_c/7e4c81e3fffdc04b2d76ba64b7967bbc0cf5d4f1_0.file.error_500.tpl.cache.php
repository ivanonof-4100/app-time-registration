<?php
/* Smarty version 3.1.34-dev-7, created on 2020-12-30 00:09:21
  from '/var/www/custom_codebase/templates/site_templates/templates/error_500.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5febb721826874_74093558',
  'has_nocache_code' => true,
  'file_dependency' => 
  array (
    '7e4c81e3fffdc04b2d76ba64b7967bbc0cf5d4f1' => 
    array (
      0 => '/var/www/custom_codebase/templates/site_templates/templates/error_500.tpl',
      1 => 1606391243,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:page_header.tpl' => 1,
    'file:page_footer.tpl' => 1,
  ),
),false)) {
function content_5febb721826874_74093558 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '11592605735febb72181b9d4_65779176';
$_smarty_tpl->_subTemplateRender("file:page_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array('pageTitle'=>$_smarty_tpl->tpl_vars['pageTitle']->value), 0, false);
$_smarty_tpl->_assignInScope('errorTitle', (($tmp = @$_smarty_tpl->tpl_vars['errorTitle']->value)===null||$tmp==='' ? "Script execution halted!" : $tmp));
$_smarty_tpl->_assignInScope('errorMessage', (($tmp = @$_smarty_tpl->tpl_vars['errorMessage']->value)===null||$tmp==='' ? "No error-message." : $tmp));
$_smarty_tpl->_assignInScope('errorColor', (($tmp = @$_smarty_tpl->tpl_vars['errorColor']->value)===null||$tmp==='' ? "red" : $tmp));?>

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
<p class="text-start"><span style="color:<?php echo $_smarty_tpl->tpl_vars['errorColor']->value;?>
;"><?php echo '/*%%SmartyNocache:11592605735febb72181b9d4_65779176%%*/<?php echo $_smarty_tpl->tpl_vars[\'errorMessage\']->value;?>
/*/%%SmartyNocache:11592605735febb72181b9d4_65779176%%*/';?>
</span></p>
              </div>
            </div>
          </div><!-- col -->
        </div><!-- row -->
             </div>
</section>
<?php if (@constant('DEBUG')) {?>
<p>Template: <i><b><?php echo '/*%%SmartyNocache:11592605735febb72181b9d4_65779176%%*/<?php echo basename($_smarty_tpl->source->filepath);?>
/*/%%SmartyNocache:11592605735febb72181b9d4_65779176%%*/';?>
</b></i></p>
<?php }?>
    </div><!-- container -->

<?php $_smarty_tpl->_subTemplateRender("file:page_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
