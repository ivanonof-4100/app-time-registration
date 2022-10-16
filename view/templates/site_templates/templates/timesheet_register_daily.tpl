{assign var=yearNumber value=$yearNumber|default:0}
{assign var=weekNumber value=$weekNumber|default:1}
{assign var=curWeekday value=$curWeekday}
{assign var=employeeUuid value=$employeeUuid}

<!-- Timesheet Daily Form -->
<p>DAILY TIMESHEET:</p>
<form name="timesheet_daily_{$weekNumber}_{$curWeekday.iso_work_date}" method="post" accept-charset="utf8">
<input type="hidden" name="_token" value="{$smarty.const.APP_SECURITY_TOKEN}" />
<input type="hidden" name="timesheet_work_date" value="{$curWeekday.iso_work_date}" />
<input type="hidden" name="employee_uuid" value="{$employeeUuid}" />
<input type="hidden" name="timesheet_uuid" value="{$curWeekday.timesheet_uuid}" />

<div class="form-group mb-3">
  <label for="timesheet_hours_regular" class="form-label">Regular hours:</label>
  <input type="number" id="timesheet_hours_regular" name="timesheet_hours_regular" min="0" step="0.50" value="{$curWeekday.hours_regular}" tabindex="1" class="form-control form-control-lg" required="required" autofocus="1" />
</div>
<div class="form-group mb-3">
  <label for="timesheet_hours_overtime" class="form-label">Overtime hours:</label>
  <input type="number" id="timesheet_hours_overtime" name="timesheet_hours_overtime" min="0" step="0.50" value="{$curWeekday.hours_overtime}" tabindex="2" class="form-control form-control-lg" required="required" />
</div>
<div class="form-group mb-3">
  <label for="timesheet_hours_break" class="form-label">Break hours:</label>
  <input type="number" id="timesheet_hours_break" name="timesheet_hours_break" min="0" step="0.50" value="{$curWeekday.hours_break}" tabindex="3" class="form-control form-control-lg" required="required" />
</div>
<div class="form-group mb-3">
  <input type="submit" name="btn_submit" role="button" value="Gem" tabindex="4" class="btn btn-lg btn-block btn-primary" title="Gem"/>
</div>
</form>