{assign var=yearNumber value=$yearNumber|default:0}
{assign var=weekNumber value=$weekNumber|default:1}
{assign var=weekDateStart value=$weekDateStart|default:''}
{assign var=weekDateEnd value=$weekDateEnd|default:''}
{assign var=arrAccumulatedHours value=$arrAccumulatedHours}
{assign var=totalHours value=$totalHours|default:0}

<!-- Weekly Timesheets -->
<h2>Week: {$weekNumber}, {$yearNumber}</h2>
<p>Period:<br/>
<strong>{$weekDateStart} - {$weekDateEnd}</strong><br/>
{include file="timesheet_accumulated_hours.tpl"}
</p>

<h3 class="text-center">Registered hours</h3>
<p>Please register your hours on each day of the week which you had worked.</p>

<div class="accordion" id="timesheet_accordion">
{foreach from=$arrWeekdays key=idx item=curWeekday}
  {assign var=accordionHeadingItem value='headingItem'|cat:$idx}
  {assign var=collapseItem value='collapseItem'|cat:$idx}

{if $smarty.foreach.curWeekday.first}
  <div class="accordion-item">
  <h4 class="accordion-header" id="{$accordionHeadingItem}">
  <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#{$collapseItem}" aria-expanded="true" aria-controls="{$collapseItem}">
    <strong>{$curWeekday.weekday_name|escape}: {$curWeekday.work_date}</strong>
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
    <strong>{$curWeekday.weekday_name|escape}: {$curWeekday.work_date}</strong>
  </button>
</h4>
<div id="{$collapseItem}"  class="accordion-collapse collapse" aria-labelledby="{$accordionHeadingItem}" data-bs-parent="#timesheet_accordion">
  <div class="accordion-body">
{include file="timesheet_register_daily.tpl"}
  </div>
</div>
{/if}
  </div>
{/foreach}
</div>