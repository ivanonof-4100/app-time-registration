{assign var=yearNumber value=$yearNumber|default:0}
{assign var=weekNumber value=$weekNumber|default:1}
{assign var=weekDateStart value=$weekDateStart|default:''}
{assign var=weekDateEnd value=$weekDateEnd|default:''}
{assign var=arrAccumulatedHours_week value=$arrAccumulatedHours_week}
{assign var=arrAccumulatedHours_annualy value=$arrAccumulatedHours_annualy}
{assign var=arrLocalizedMonths value=$arrLocalizedMonths}
{assign var=decimalSeparator value=$decimalSeparator|default:','}
{assign var=thousandSeparator value=$thousandSeparator|default:'.'}
{assign var=focusDay value=$focusDay|default:$weekDateStart}
{assign var=arrConfigPaths value=$arrConfigPaths}
<div>
  <div class="d-flex justify-content-between">
    <div>
      <!-- Weekly Timesheets -->
      <h2 class="fs-2">Week: {$weekNumber|escape}, {$yearNumber|escape}</h2>
      <strong>{$weekDateStart|escape} - {$weekDateEnd|escape}</strong><br />
    </div>
    <div>
      <form name="form_weeks" method="get" accept-charset="utf8">
      <label for="select_week_option" class="form-label"><span>Choose a week:</span></label><br/>
{html_options name=week options=$arrOptions_weeks selected=$weekNumber|escape onchange="this.form.submit();" id="select_week_option" class="dropdown"}
      </form>
    </div>
  </div>

  <section class="section-accumulated-hours">
{include file="timesheet_accumulated_hours.tpl"}
  </section>

  <section class="section-registered-hours">
  <h3 class="fs-3 text-center">Registered hours</h3>
  <p class="fst-normal text-start">Please register your hours on each day of the week which you had worked.</p>

  <div class="accordion" id="timesheet_accordion">
    {foreach from=$arrWeekdays key=idx item=curWeekday}
      {assign var=accordionHeadingItem value='headingItem'|cat:$idx}
      {assign var=collapseItem value='collapseItem'|cat:$idx}
      {math equation="regularHours + overtimeHours + breakHours" format="%.2f" regularHours=$curWeekday.hours_regular overtimeHours=$curWeekday.hours_overtime breakHours=$curWeekday.hours_break assign=curTotalHoursDay}

{*      {if $idx == 1} *}
{if (isset($focusDay) && ($focusDay == $curWeekday.iso_work_date))}
        <div class="accordion-item">
          <h4 class="accordion-header" id="{$accordionHeadingItem}">
        <button class="accordion-button open" type="button" data-bs-toggle="collapse" data-bs-target="#{$collapseItem}" aria-expanded="true" aria-controls="{$collapseItem}">
{include file="{$arrConfigPaths.standard|cat:'widget_date.tpl'}" displayDate=$curWeekday.work_date weekday=$curWeekday.weekday_name weekdayShort=$curWeekday.weekday_name_short arrLocalizedMonths=$arrLocalizedMonths}
&nbsp;&nbsp;&nbsp;&nbsp;
          <strong>{$curWeekday.weekday_name|escape}<br />
            {$curWeekday.work_date|escape}</strong>
&nbsp;&nbsp;&nbsp;&nbsp;

          {if $curTotalHoursDay >0}
            <span class="align-middle badge bg-success rounded-pill" style="font-size:1em;line-height:1;">{$curTotalHoursDay|number_format:2:$decimalSeparator:$thousandSeparator}
              timer</span>
          {else}
            <span class="align-middle badge text-bg-secondary rounded-pill" style="font-size:1em;line-height:1;">{$curTotalHoursDay|number_format:2:$decimalSeparator:$thousandSeparator}
              timer</span>
          {/if}
        </button>
          </h4>

          <div id="{$collapseItem}" class="accordion-collapse collapse show" aria-labelledby="{$accordionHeadingItem}" data-bs-parent="#timesheet_accordion">
            <div class="accordion-body">
{include file="timesheet_register_daily.tpl"}
            </div>
          </div>
        {else}
          <div class="accordion-item">
            <h4 class="accordion-header" id="{$accordionHeadingItem}">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#{$collapseItem}" aria-expanded="false" aria-controls="{$collapseItem}">
{include file="{$arrConfigPaths.standard|cat:'widget_date.tpl'}" displayDate=$curWeekday.work_date weekday=$curWeekday.weekday_name weekdayShort=$curWeekday.weekday_name_short arrLocalizedMonths=$arrLocalizedMonths}
&nbsp;&nbsp;&nbsp;&nbsp;
                <strong>{$curWeekday.weekday_name|escape}<br />
                  {$curWeekday.work_date|escape}</strong>
&nbsp;&nbsp;&nbsp;&nbsp;
                {if $curTotalHoursDay >0}
                  <span class="align-middle badge bg-success rounded-pill" style="font-size:1em;line-height:1;">
                  {$curTotalHoursDay|number_format:2:$decimalSeparator:$thousandSeparator} timer</span>
                {else}
                  <span class="align-middle badge text-bg-secondary rounded-pill" style="font-size:1em;line-height:1;">
                  {$curTotalHoursDay|number_format:2:$decimalSeparator:$thousandSeparator} timer</span>
                {/if}
              </button>
            </h4>

            <div id="{$collapseItem}" class="accordion-collapse collapse" aria-labelledby="{$accordionHeadingItem}" data-bs-parent="#timesheet_accordion">
              <div class="accordion-body">
{include file="timesheet_register_daily.tpl"}
              </div>
            </div>
          {/if}
        </div>
      {/foreach}
    </div>
  </div>
</section>

{if isset($smarty.const.APP_DEBUG_MODE) && $smarty.const.APP_DEBUG_MODE}
<div class="clearfix float-start">
<p class="fw-semibold fst-italic p-2">
Template: <span class="text-nowrap text-lowercase">{$smarty.template}</span>
</p>
</div>
{/if}