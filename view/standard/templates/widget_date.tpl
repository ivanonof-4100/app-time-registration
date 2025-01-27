{assign var=displayDate value=$displayDate}
{assign var=weekday value=$weekday|default:""}
{assign var=weekdayShort value=$weekdayShort|default:""}
{assign var=arrLocalizedMonths value=$arrLocalizedMonths}
{assign var=monthIdent value=$monthIdent|default:$displayDate|date_format:"%m"}
{assign var=monthName value=$monthName|default:$arrLocalizedMonths[$monthIdent]}

<div class="outer-calender-container">
  <div style="z-index:10;">
    <svg xmlns="http://www.w3.org/2000/svg" width="110" height="110" viewBox="0 0 16 16" class="calender-frame">
      <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
    </svg>
  </div>
  <div class="inner-calender-container rounded-corners">
    <div class="text-center" style="background-color:#dee2e6;margin-left:0;margin-right:0;padding-top:3px;padding-bottom:3px;font-size:small;">
      <strong class="text-uppercase">{$monthName|escape}</strong>
    </div>
    <div class="d-flex align-items-center">
      <div class="flex-shrink-0">
        <div class="float-start" style="margin-left:5px;font-size:1.2em;">
          <span class="badge text-bg-warning"> {$displayDate|date_format:"W"|escape}</span>
        </div>
      </div>
      <div class="flex-grow-1 text-center">
        <strong class="cal-date-day-of-month">{$displayDate|date_format:"j"|escape}</strong><br/>
        <span>{$weekdayShort|escape}</span>
      </div>
    </div>
  </div>
</div>

{if isset($smarty.const.APP_DEBUG_MODE) && $smarty.const.APP_DEBUG_MODE}
<div class="clearfix float-start">
<p class="fw-semibold fst-italic p-2">
Template: <span class="text-nowrap text-lowercase">{$smarty.template}</span>
</p>
</div>
{/if}