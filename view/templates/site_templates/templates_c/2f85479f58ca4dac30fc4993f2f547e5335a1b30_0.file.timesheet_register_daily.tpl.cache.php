<?php
/* Smarty version 4.2.1, created on 2022-10-15 10:52:00
  from '/data/WebProjects/Northern-partners/Projekt-timeseddler/app/view/templates/site_templates/templates/timesheet_register_daily.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.2.1',
  'unifunc' => 'content_634a74b05b6471_97926959',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2f85479f58ca4dac30fc4993f2f547e5335a1b30' => 
    array (
      0 => '/data/WebProjects/Northern-partners/Projekt-timeseddler/app/view/templates/site_templates/templates/timesheet_register_daily.tpl',
      1 => 1665822403,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_634a74b05b6471_97926959 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '1356478592634a74b05b2c83_28441192';
$_smarty_tpl->_assignInScope('yearNumber', (($tmp = $_smarty_tpl->tpl_vars['yearNumber']->value ?? null)===null||$tmp==='' ? 0 ?? null : $tmp));
$_smarty_tpl->_assignInScope('weekNumber', (($tmp = $_smarty_tpl->tpl_vars['weekNumber']->value ?? null)===null||$tmp==='' ? 1 ?? null : $tmp));
$_smarty_tpl->_assignInScope('curWeekday', $_smarty_tpl->tpl_vars['curWeekday']->value);
$_smarty_tpl->_assignInScope('employeeUuid', $_smarty_tpl->tpl_vars['employeeUuid']->value);?>

<!-- Timesheet Daily Form -->
<p>DAILY TIMESHEET:</p>
<form name="timesheet_daily_<?php echo $_smarty_tpl->tpl_vars['weekNumber']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['curWeekday']->value['iso_work_date'];?>
" method="post" accept-charset="utf8">
<input type="hidden" name="_token" value="<?php echo (defined('APP_SECURITY_TOKEN') ? constant('APP_SECURITY_TOKEN') : null);?>
" />
<input type="hidden" name="timesheet_work_date" value="<?php echo $_smarty_tpl->tpl_vars['curWeekday']->value['iso_work_date'];?>
" />
<input type="hidden" name="employee_uuid" value="<?php echo $_smarty_tpl->tpl_vars['employeeUuid']->value;?>
" />
<input type="hidden" name="timesheet_uuid" value="<?php echo $_smarty_tpl->tpl_vars['curWeekday']->value['timesheet_uuid'];?>
" />

<div class="form-group mb-3">
  <label for="timesheet_hours_regular" class="form-label">Regular hours:</label>
  <input type="number" id="timesheet_hours_regular" name="timesheet_hours_regular" min="0" step="0.50" value="<?php echo $_smarty_tpl->tpl_vars['curWeekday']->value['hours_regular'];?>
" tabindex="1" class="form-control form-control-lg" required="required" autofocus="1" />
</div>
<div class="form-group mb-3">
  <label for="timesheet_hours_overtime" class="form-label">Overtime hours:</label>
  <input type="number" id="timesheet_hours_overtime" name="timesheet_hours_overtime" min="0" step="0.50" value="<?php echo $_smarty_tpl->tpl_vars['curWeekday']->value['hours_overtime'];?>
" tabindex="2" class="form-control form-control-lg" required="required" />
</div>
<div class="form-group mb-3">
  <label for="timesheet_hours_break" class="form-label">Break hours:</label>
  <input type="number" id="timesheet_hours_break" name="timesheet_hours_break" min="0" step="0.50" value="<?php echo $_smarty_tpl->tpl_vars['curWeekday']->value['hours_break'];?>
" tabindex="3" class="form-control form-control-lg" required="required" />
</div>
<div class="form-group mb-3">
  <input type="submit" name="btn_submit" role="button" value="Gem" tabindex="4" class="btn btn-lg btn-block btn-primary" title="Gem"/>
</div>
</form><?php }
}
