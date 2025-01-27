{assign var=yearNumber value=$yearNumber|default:0}
{assign var=weekNumber value=$weekNumber|default:1}
{assign var=curWeekday value=$curWeekday}
{assign var=genericWeekday value=$curWeekday.iso_work_date|date_format:'D'|lower|escape}
{assign var=employeeUuid value=$employeeUuid}
{assign var=resumeSessionId value=$resumeSessionId}
{assign var=securityToken value=$smarty.session.security_token|default:''}

<div class="d-flex justify-content-between">
  <div>
    <h4>DAILY TIMESHEET</h4>
  </div>
  <div>
  </div>
</div>

{if (isset($smarty.session.flash_message_timesheet))}
  {include file="alert_success.tpl" alertTitle="Dine data er nu gemt" alertText=$smarty.session.flash_message_timesheet}
{/if}
{*
{include file="flash_message.tpl"}
*}

<!-- Timesheet Daily Form -->
<form name="timesheet_daily_{$genericWeekday|escape}" method="post" accept-charset="utf8" class="py-2">
<input type="hidden" name="_resume_sid" value="{$resumeSessionId|escape}" />
<input type="hidden" name="_token" value="{$securityToken|base64_encode|escape}" />
<input type="hidden" name="timesheet_work_date" value="{$curWeekday.iso_work_date|escape}" />
<input type="hidden" name="employee_uuid" value="{$employeeUuid|escape}" />
<input type="hidden" name="timesheet_uuid" value="{$curWeekday.timesheet_uuid|escape}" />

<div class="form-group mb-2">
  <label for="{$genericWeekday}_timesheet_hours_regular" class="form-label">Regular hours:</label>
  <input id="{$genericWeekday}_timesheet_hours_regular" type="number" name="timesheet_hours_regular" min="0" max="7" step="0.50" value="{$curWeekday.hours_regular}" class="form-control form-control-lg text-end" required="required" autofocus="1" />
</div>
<div class="form-group mb-2">
  <label for="{$genericWeekday}_timesheet_hours_overtime" class="form-label">Overtime hours:</label>
  <input id="{$genericWeekday}_timesheet_hours_overtime" type="number" name="timesheet_hours_overtime" min="0" step="0.50" value="{$curWeekday.hours_overtime}" class="form-control form-control-lg text-end"/>
</div>
<div class="form-group mb-2">
  <label for="{$genericWeekday}_timesheet_hours_break" class="form-label">Break hours:</label>
  <input id="{$genericWeekday}_timesheet_hours_break" type="number" name="timesheet_hours_break" min="0" max="0.50" step="0.50" value="{$curWeekday.hours_break}" class="form-control form-control-lg text-end" required="required" />
</div>
<div class="d-grid form-group mt-3">
  <input type="submit" name="btn_submit" role="button" value="Gem" class="btn btn-lg btn-block btn-primary" title="Gem"/>
</div>
</form>

{if isset($smarty.const.APP_DEBUG_MODE) && $smarty.const.APP_DEBUG_MODE}
<p class="fw-semibold fst-italic p-2">
Template: <span class="text-nowrap text-lowercase">{$smarty.template}</span>
</p>
{/if}