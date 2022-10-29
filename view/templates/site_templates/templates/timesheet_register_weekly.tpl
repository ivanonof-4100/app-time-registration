{assign var=yearNumber value=$yearNumber|default:0}
{assign var=weekNumber value=$weekNumber|default:1}
{assign var=weekDateStart value=$weekDateStart|default:''}
{assign var=weekDateEnd value=$weekDateEnd|default:''}
{assign var=arrAccumulatedHours value=$arrAccumulatedHours}
{assign var=totalHours value=$totalHours|default:0}
{assign var=commaSeparator value=$commaSeparator|default:','}
{assign var=thousandSeparator value=$thousandSeparator|default:'.'}

<!-- Weekly Timesheets -->
<h2>Week: {$weekNumber}, {$yearNumber}</h2>
<div class="d-flex justify-content-between">
  <div>
    Period:<br/>
<strong>{$weekDateStart} - {$weekDateEnd}</strong><br/>
  </div>

  <div>
    <span>Select week:</span><br/>
    <form name="form_weeks" method="get" accept-charset="uft8">
     {html_options name=week options=$arrOptions_weeks selected=$weekNumber onchange="this.form.submit();" class="dropdown"}
    </form>
  </div>
</div>


<p>
{include file="timesheet_accumulated_hours.tpl"}
</p>

<h3 class="text-center">Registered hours</h3>
<p>Please register your hours on each day of the week which you had worked.</p>

<div class="accordion" id="timesheet_accordion">
{foreach from=$arrWeekdays key=idx item=curWeekday}
  {assign var=accordionHeadingItem value='headingItem'|cat:$idx}
  {assign var=collapseItem value='collapseItem'|cat:$idx}
  {math equation="regularHours + overtimeHours + breakHours" format="%.2f" regularHours=$curWeekday.hours_regular overtimeHours=$curWeekday.hours_overtime breakHours=$curWeekday.hours_break assign=curTotalHoursDay}
  
{if $idx == 1}
  <div class="accordion-item">
  <h4 class="accordion-header" id="{$accordionHeadingItem}">
  <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#{$collapseItem}" aria-expanded="true" aria-controls="{$collapseItem}">
  <strong>{$curWeekday.weekday_name|escape}<br/>
  {$curWeekday.work_date}</strong>&nbsp;&nbsp;&nbsp;&nbsp;
  {if $curTotalHoursDay >0}
    <span class="align-middle badge bg-success rounded-pill" style="font-size:1em; line-height:1.1;">{$curTotalHoursDay|number_format:2:$commaSeparator:$thousandSeparator} timer</span>
  {else}
    <span class="align-middle badge text-bg-secondary rounded-pill" style="font-size:1em; line-height:1.1;">{$curTotalHoursDay|number_format:2:$commaSeparator:$thousandSeparator} timer</span>
  {/if}
  </button>
</h4>
<div id="{$collapseItem}" class="accordion-collapse show" aria-labelledby="{$accordionHeadingItem}" data-bs-parent="#timesheet_accordion">
  <div class="accordion-body">
{include file="timesheet_register_daily.tpl"}
  </div>
</div>
{else}
<div class="accordion-item">
  <h4 class="accordion-header" id="{$accordionHeadingItem}">
    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#{$collapseItem}" aria-expanded="false" aria-controls="{$collapseItem}">
    <strong>{$curWeekday.weekday_name|escape}<br/>
    {$curWeekday.work_date}</strong>&nbsp;&nbsp;&nbsp;&nbsp;
    {if $curTotalHoursDay >0}
      <span class="align-middle badge bg-success rounded-pill" style="font-size:1em; line-height:1.1;">{$curTotalHoursDay|number_format:2:$commaSeparator:$thousandSeparator} timer</span>
    {else}
      <span class="align-middle badge text-bg-secondary rounded-pill" style="font-size:1em; line-height:1.1;">{$curTotalHoursDay|number_format:2:$commaSeparator:$thousandSeparator} timer</span>
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