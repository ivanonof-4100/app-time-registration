<?php
/* Smarty version 4.2.1, created on 2022-10-15 10:52:00
  from '/data/WebProjects/Northern-partners/Projekt-timeseddler/app/view/templates/site_templates/templates/std_page_html5.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.2.1',
  'unifunc' => 'content_634a74b05bd980_80896801',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1a8d30793c5a7601ccdb39f30bdea9bfb8d15cb1' => 
    array (
      0 => '/data/WebProjects/Northern-partners/Projekt-timeseddler/app/view/templates/site_templates/templates/std_page_html5.tpl',
      1 => 1665685437,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_634a74b05bd980_80896801 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '803925964634a74b05b9eb4_02786325';
$_smarty_tpl->_assignInScope('pageTitle', (($tmp = $_smarty_tpl->tpl_vars['pageTitle']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp));
$_smarty_tpl->_assignInScope('pageLangIdent', (($tmp = $_smarty_tpl->tpl_vars['pageLangIdent']->value ?? null)===null||$tmp==='' ? "en" ?? null : $tmp));
$_smarty_tpl->_assignInScope('mainContent', (($tmp = $_smarty_tpl->tpl_vars['mainContent']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp));
$_smarty_tpl->_assignInScope('sidebarContent', (($tmp = $_smarty_tpl->tpl_vars['sidebarContent']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp));?>

<!DOCTYPE html>
<html lang="<?php echo $_smarty_tpl->tpl_vars['pageLangIdent']->value;?>
">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['pageDomainTitle']->value, ENT_QUOTES, 'UTF-8', true);?>
</title>
<link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous"/>
</head>

<body>
<h1 class="text-center"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['pageTitle']->value, ENT_QUOTES, 'UTF-8', true);?>
</h1>

 <div class="container">
   <div class="row">
<?php if ($_smarty_tpl->tpl_vars['sidebarContent']->value != '') {?>
    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4" role="main">
    <?php echo $_smarty_tpl->tpl_vars['mainContent']->value;?>

    </div>

    <aside class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
    <?php echo $_smarty_tpl->tpl_vars['sidebarContent']->value;?>

    </aside>
<?php } else { ?>
    <div class="col-md-12 ms-sm-auto col-lg-12 px-md-4" role="main">
    <?php echo $_smarty_tpl->tpl_vars['mainContent']->value;?>

    </div>
<?php }?>
   </div>
 </div>

<?php echo '<script'; ?>
 src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"><?php echo '</script'; ?>
>
</body>
</html><?php }
}
