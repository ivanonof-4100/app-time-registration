<?php
/* Smarty version 4.2.1, created on 2022-10-14 14:34:46
  from '/data/WebProjects/Northern-partners/Projekt-timeseddler/app/view/templates/site_templates/templates/page_footer.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.2.1',
  'unifunc' => 'content_63495766727069_76479544',
  'has_nocache_code' => true,
  'file_dependency' => 
  array (
    'c5ab0b2a2bd3aa60ec34eb4d36f8aa1de9f564ae' => 
    array (
      0 => '/data/WebProjects/Northern-partners/Projekt-timeseddler/app/view/templates/site_templates/templates/page_footer.tpl',
      1 => 1664798811,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_63495766727069_76479544 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/data/WebProjects/Northern-partners/Projekt-timeseddler/app/my_codebase/common/library/smarty/smarty-4.2.1/libs/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
echo '/*%%SmartyNocache:175077430563495766724579_71621085%%*/<?php $_smarty_tpl->_checkPlugins(array(0=>array(\'file\'=>\'/data/WebProjects/Northern-partners/Projekt-timeseddler/app/my_codebase/common/library/smarty/smarty-4.2.1/libs/plugins/modifier.date_format.php\',\'function\'=>\'smarty_modifier_date_format\',),));
?>/*/%%SmartyNocache:175077430563495766724579_71621085%%*/';
$_smarty_tpl->compiled->nocache_hash = '175077430563495766724579_71621085';
echo '/*%%SmartyNocache:175077430563495766724579_71621085%%*/<?php $_smarty_tpl->_assignInScope(\'currentYear\', smarty_modifier_date_format(time(),"%Y") ,true);?>/*/%%SmartyNocache:175077430563495766724579_71621085%%*/';?>


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

<?php if ((defined('DEBUG') ? constant('DEBUG') : null)) {?>
<div>
<p>Template: <i><b><?php echo basename($_smarty_tpl->source->filepath);?>
</b></i></p>
</div>
<?php }?>

<!-- jQuery and JS bundle w/ Popper.js -->
<?php echo '<script'; ?>
 src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"><?php echo '</script'; ?>
>
</body>
</html><?php }
}
