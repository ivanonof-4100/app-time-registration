<?php
/* Smarty version 4.2.1, created on 2022-10-15 10:52:00
  from '/data/WebProjects/Northern-partners/Projekt-timeseddler/app/view/templates/site_templates/templates/timesheet_register_weekly.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.2.1',
  'unifunc' => 'content_634a74b05ac615_39445927',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '738d0433c0383022357c717de934748abe05481b' => 
    array (
      0 => '/data/WebProjects/Northern-partners/Projekt-timeseddler/app/view/templates/site_templates/templates/timesheet_register_weekly.tpl',
      1 => 1665665672,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:timesheet_accumulated_hours.tpl' => 1,
    'file:timesheet_register_daily.tpl' => 2,
  ),
),false)) {
function content_634a74b05ac615_39445927 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '1129305872634a74b05a2136_89805899';
$_smarty_tpl->_assignInScope('yearNumber', (($tmp = $_smarty_tpl->tpl_vars['yearNumber']->value ?? null)===null||$tmp==='' ? 0 ?? null : $tmp));
$_smarty_tpl->_assignInScope('weekNumber', (($tmp = $_smarty_tpl->tpl_vars['weekNumber']->value ?? null)===null||$tmp==='' ? 1 ?? null : $tmp));
$_smarty_tpl->_assignInScope('weekDateStart', (($tmp = $_smarty_tpl->tpl_vars['weekDateStart']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp));
$_smarty_tpl->_assignInScope('weekDateEnd', (($tmp = $_smarty_tpl->tpl_vars['weekDateEnd']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp));
$_smarty_tpl->_assignInScope('arrAccumulatedHours', $_smarty_tpl->tpl_vars['arrAccumulatedHours']->value);
$_smarty_tpl->_assignInScope('totalHours', (($tmp = $_smarty_tpl->tpl_vars['totalHours']->value ?? null)===null||$tmp==='' ? 0 ?? null : $tmp));?>

<!-- Weekly Timesheets -->
<h2>Week: <?php echo $_smarty_tpl->tpl_vars['weekNumber']->value;?>
, <?php echo $_smarty_tpl->tpl_vars['yearNumber']->value;?>
</h2>
<p>Period:<br/>
<strong><?php echo $_smarty_tpl->tpl_vars['weekDateStart']->value;?>
 - <?php echo $_smarty_tpl->tpl_vars['weekDateEnd']->value;?>
</strong><br/>
<?php $_smarty_tpl->_subTemplateRender("file:timesheet_accumulated_hours.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
</p>

<h3 class="text-center">Registered hours</h3>
<p>Please register your hours on each day of the week which you had worked.</p>

<div class="accordion" id="timesheet_accordion">
<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arrWeekdays']->value, 'curWeekday', false, 'idx');
$_smarty_tpl->tpl_vars['curWeekday']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['idx']->value => $_smarty_tpl->tpl_vars['curWeekday']->value) {
$_smarty_tpl->tpl_vars['curWeekday']->do_else = false;
?>
  <?php $_smarty_tpl->_assignInScope('accordionHeadingItem', ('headingItem').($_smarty_tpl->tpl_vars['idx']->value));?>
  <?php $_smarty_tpl->_assignInScope('collapseItem', ('collapseItem').($_smarty_tpl->tpl_vars['idx']->value));?>

<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_curWeekday']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_curWeekday']->value['first'] : null)) {?>
  <div class="accordion-item">
  <h4 class="accordion-header" id="<?php echo $_smarty_tpl->tpl_vars['accordionHeadingItem']->value;?>
">
  <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo $_smarty_tpl->tpl_vars['collapseItem']->value;?>
" aria-expanded="true" aria-controls="<?php echo $_smarty_tpl->tpl_vars['collapseItem']->value;?>
">
    <strong><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['curWeekday']->value['weekday_name'], ENT_QUOTES, 'UTF-8', true);?>
: <?php echo $_smarty_tpl->tpl_vars['curWeekday']->value['work_date'];?>
</strong>
  </button>
</h4>
<div id="<?php echo $_smarty_tpl->tpl_vars['collapseItem']->value;?>
" class="accordion-collapse show" aria-labelledby="<?php echo $_smarty_tpl->tpl_vars['accordionHeadingItem']->value;?>
" data-bs-parent="#timesheet_accordion">
  <div class="accordion-body">
<?php $_smarty_tpl->_subTemplateRender("file:timesheet_register_daily.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>
  </div>
</div>

<?php } else { ?>
  <div class="accordion-item">
  <h4 class="accordion-header" id="<?php echo $_smarty_tpl->tpl_vars['accordionHeadingItem']->value;?>
">
  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo $_smarty_tpl->tpl_vars['collapseItem']->value;?>
" aria-expanded="false" aria-controls="<?php echo $_smarty_tpl->tpl_vars['collapseItem']->value;?>
">
    <strong><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['curWeekday']->value['weekday_name'], ENT_QUOTES, 'UTF-8', true);?>
: <?php echo $_smarty_tpl->tpl_vars['curWeekday']->value['work_date'];?>
</strong>
  </button>
</h4>
<div id="<?php echo $_smarty_tpl->tpl_vars['collapseItem']->value;?>
"  class="accordion-collapse collapse" aria-labelledby="<?php echo $_smarty_tpl->tpl_vars['accordionHeadingItem']->value;?>
" data-bs-parent="#timesheet_accordion">
  <div class="accordion-body">
<?php $_smarty_tpl->_subTemplateRender("file:timesheet_register_daily.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>
  </div>
</div>
<?php }?>
  </div>
<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
</div><?php }
}
