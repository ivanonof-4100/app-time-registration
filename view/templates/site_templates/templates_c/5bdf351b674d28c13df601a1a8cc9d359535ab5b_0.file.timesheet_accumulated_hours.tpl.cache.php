<?php
/* Smarty version 4.2.1, created on 2022-10-15 10:52:00
  from '/data/WebProjects/Northern-partners/Projekt-timeseddler/app/view/templates/site_templates/templates/timesheet_accumulated_hours.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.2.1',
  'unifunc' => 'content_634a74b05b14d1_16994674',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5bdf351b674d28c13df601a1a8cc9d359535ab5b' => 
    array (
      0 => '/data/WebProjects/Northern-partners/Projekt-timeseddler/app/view/templates/site_templates/templates/timesheet_accumulated_hours.tpl',
      1 => 1665665715,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_634a74b05b14d1_16994674 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '457661133634a74b05af256_13424768';
$_smarty_tpl->_assignInScope('arrAccumulatedHours', $_smarty_tpl->tpl_vars['arrAccumulatedHours']->value);
$_smarty_tpl->_assignInScope('totalHours', (($tmp = $_smarty_tpl->tpl_vars['totalHours']->value ?? null)===null||$tmp==='' ? 0 ?? null : $tmp));?>

<h3 class="text-center">Accumulated hours</h3>
<table class="table table-hover">
<thead>
<tr>
  <th scope="col"></th>
  <th scope="col">Hours</th>
</tr>
</thead>

<tbody class="table-group-divider">
  <tr>
    <td scope="row">Regular hours:</td>
    <td><?php echo $_smarty_tpl->tpl_vars['arrAccumulatedHours']->value['total_hours_regular'];?>
</td>
  </tr>
  <tr>
    <td scope="row">Overtime hours:</td>
    <td><?php echo $_smarty_tpl->tpl_vars['arrAccumulatedHours']->value['total_hours_overtime'];?>
</td>
  </tr>
  <tr>
    <td scope="row">Break hours:</td>
    <td><?php echo $_smarty_tpl->tpl_vars['arrAccumulatedHours']->value['total_hours_break'];?>
</td>
  </tr>
  <tr class="table-group-divider">
    <td scope="row">Total hours:</td>
    <td><?php echo $_smarty_tpl->tpl_vars['totalHours']->value;?>
</td>
</tr>
</tbody>
</table><?php }
}
