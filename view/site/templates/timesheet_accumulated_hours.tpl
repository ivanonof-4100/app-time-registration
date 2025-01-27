{assign var=yearNumber value=$yearNumber|default:0}
{assign var=weekNumber value=$weekNumber|default:1}
{assign var=arrAccumulatedHours_week value=$arrAccumulatedHours_week}
{assign var=arrAccumulatedHours_annualy value=$arrAccumulatedHours_annualy}
{assign var=totalHours_week value=$totalHours_week|default:0}
{assign var=totalHours_annualy value=$totalHours_annualy|default:0}

<h3 class="text-center">Accumulated hours</h3>
<noscript>JavaScripting is not activated in your web-browser ...</noscript>

<ul class="nav nav-fill nav-tabs" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="tab-weekly" data-bs-toggle="tab" data-bs-target="#tabpane-overview-weekly" type="button" role="tab" aria-controls="tabpane-overview-weekly" aria-selected="true">Weekly Overview</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="tab-annualy" data-bs-toggle="tab" data-bs-target="#tabpane-overview-annualy" type="button" role="tab" aria-controls="tabpane-overview-annualy" aria-selected="false">Annual Overview</button>
  </li>
</ul>

<div class="tab-content custom-tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="tabpane-overview-weekly" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
  <h4 class="text-center text-uppercase">Week {$weekNumber}</h4>
  <table class="table table-warning table-hover">
  <thead>
  <tr>
    <th scope="col">Type</th>
    <th scope="col" class="text-end">Hours</th>
  </tr>
  </thead>
  
  <tbody class="table-group-divider">
    <tr>
      <td scope="row">Regular hours:</td>
      <td class="text-end">{$arrAccumulatedHours_week.total_hours_regular|number_format:2:$decimalSeparator:$thousandSeparator}</td>
    </tr>
    <tr>
      <td scope="row">Overtime hours:</td>
      <td class="text-end">{$arrAccumulatedHours_week.total_hours_overtime|number_format:2:$decimalSeparator:$thousandSeparator}</td>
    </tr>
    <tr>
      <td scope="row">Break hours:</td>
      <td class="text-end">{$arrAccumulatedHours_week.total_hours_break|number_format:2:$decimalSeparator:$thousandSeparator}</td>
    </tr>
    <tr class="table-group-divider">
      <td scope="row"><strong>Total hours</strong></td>
      <td class="text-end"><strong>{$totalHours_week|number_format:2:$decimalSeparator:$thousandSeparator}</strong></td>
  </tr>
  </tbody>
  </table>
  </div>

  <div class="tab-pane fade" id="tabpane-overview-annualy" role="tabpanel" aria-labelledby="profile-tab" tabindex="1">
<div class="d-flex justify-content-center">
  <div class="spinner-border" role="status" style="width:3rem;height:3rem;">
    <span class="visually-hidden">Loading...</span>
  </div><!-- spinner -->

</div>

  <h4 class="text-center text-uppercase">Annual overview {$yearNumber}</h4>
  <table class="table table-warning table-hover">
    <thead>
    <tr>
      <th scope="col">Type</th>
      <th scope="col" class="text-end">Hours</th>
    </tr>
    </thead>
    
    <tbody class="table-group-divider">
      <tr>
        <td scope="row">Regular hours:</td>
        <td class="text-end">{$arrAccumulatedHours_annualy.total_hours_regular|number_format:2:$decimalSeparator:$thousandSeparator}</td>
      </tr>
      <tr>
        <td scope="row">Overtime hours:</td>
        <td class="text-end">{$arrAccumulatedHours_annualy.total_hours_overtime|number_format:2:$decimalSeparator:$thousandSeparator}</td>
      </tr>
      <tr>
        <td scope="row">Break hours:</td>
        <td class="text-end">{$arrAccumulatedHours_annualy.total_hours_break|number_format:2:$decimalSeparator:$thousandSeparator}</td>
      </tr>
      <tr class="table-group-divider">
        <td scope="row"><strong>Total hours</strong></td>
        <td class="text-end"><strong>{$totalHours_annualy|number_format:2:$decimalSeparator:$thousandSeparator}</strong></td>
    </tr>
    </tbody>
  </table>

<section class="section-data-visualization">
<h4 class="text-center text-uppercase">Data visualization</h4>
<div class="row">
  <div class="col-3">
    <div id="list-quarters" class="list-group" style="margin-left:7px;" role="navigation">
      <a class="list-group-item list-group-item-action active" href="#quarter1">1. kvartal</a>
      <a class="list-group-item list-group-item-action" href="#quarter2">2. kvartal</a>
      <a class="list-group-item list-group-item-action" href="#quarter3">3. kvartal</a>
      <a class="list-group-item list-group-item-action" href="#quarter4">4. kvartal</a>
    </div>
  </div>
  <div class="col-9">
    <div data-bs-spy="scroll" data-bs-target="#list-quarters" data-bs-smooth-scroll="true">
    {*<div class="scrollspy-container" data-bs-spy="scroll" data-bs-target="#list-quarters" data-bs-smooth-scroll="true" data-bs-offset="30" tabindex="0"> *}
      <h5 id="quarter1">1. kvartal {$yearNumber}</h5>
      <p>
      <ul class="full-width list-style-none" style="background-color:transparent;margin-right:7px;">
<li><b>Week 1</b>: 11,10 timer (30%)
  <div class="progress" role="progressbar" aria-label="Week 1" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">
    <div class="progress-bar text-bg-primary text-light" style="width:30%;">30%</div>
  </div>
</li>
<li><b>Week 2</b>: 11,10 timer (40%)
  <div class="progress" role="progressbar" aria-label="Week 2" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
    <div class="progress-bar text-bg-primary text-light" style="width:40%;">40%</div>
  </div>
</li>
<li><b>Week 3</b>: 37,50 timer (100%)
  <div class="progress" role="progressbar" aria-label="Week 3" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
    <div class="progress-bar text-bg-primary text-light" style="width:100%;">100%</div>
  </div>
</li>
<li><b>Week 4</b>: 11,10 timer (40%)
  <div class="progress" role="progressbar" aria-label="Week 4" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100">
    <div class="progress-bar text-bg-primary text-light" style="width:45%;">45%</div>
  </div>
</li>
<li><b>Week 5</b>: 11,10 timer (40%)
  <div class="progress" role="progressbar" aria-label="Week 5" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100">
    <div class="progress-bar text-bg-primary text-light" style="width:45%;">45%</div>
  </div>
</li>
<li><b>Week 6</b>: 11,10 timer (30%)
  <div class="progress" role="progressbar" aria-label="Week 6" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">
    <div class="progress-bar text-bg-primary text-light" style="width:30%;">30%</div>
  </div>
</li>
<li><b>Week 7</b>: 11,10 timer (40%)
  <div class="progress" role="progressbar" aria-label="Week 7" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
    <div class="progress-bar text-bg-primary text-light" style="width:40%;">40%</div>
  </div>
</li>
<li><b>Week 8</b>: 11,10 timer (30%)
  <div class="progress" role="progressbar" aria-label="Week 8" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">
    <div class="progress-bar text-bg-primary text-light" style="width:30%;">30%</div>
  </div>
</li>
<li><b>Week 9</b>: 11,10 timer (45%)
  <div class="progress" role="progressbar" aria-label="Week 9" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100">
    <div class="progress-bar text-bg-primary text-light" style="width:45%;">45%</div>
  </div>
</li>
<li><b>Week 10</b>: 11,10 timer (45%)
  <div class="progress" role="progressbar" aria-label="Week 10" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100">
    <div class="progress-bar text-bg-primary text-light" style="width:45%;">45%</div>
  </div>
</li>
<li><b>Week 11</b>: 11,10 timer (30%)
<div class="progress" role="progressbar" aria-label="Week 11" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">
  <div class="progress-bar text-bg-primary text-ligth" style="width:30%;">30%</div>
</div>
</li>

<li><b>Week 12</b>: 18,50 timer (50%)
<div class="progress" role="progressbar" aria-label="Week 12" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
  <div class="progress-bar text-bg-primary text-light" style="width:50%;">50%</div>
</div>
</li>

<li><b>Week 13</b>: (90%)
<div class="progress" role="progressbar" aria-label="Week 13" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100">
  <div class="progress-bar text-bg-primary text-light" style="width:90%">90%</div>
</div>
</li>
</ul>
</p>

<h5 id="quarter2">2. kvartal {$yearNumber}</h5>
<p>
<ul class="full-width list-style-none" style="background-color:transparent;margin-right:7px;">
<li><b>Week 14</b>:
<div class="progress" role="progressbar" aria-label="Week 14" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">
  <div class="progress-bar text-bg-primary text-light" style="width:85%">85%</div>
</div>
</li>
      </ul>
</p>

      <h5 id="quarter3">3. kvartal {$yearNumber}</h5>
      <p><i>Ingen data endnu ...</i></p>

      <h5 id="quarter4">4. kvartal {$yearNumber}</h4>
      <p><i>Ingen data endnu ...</i></p>
    </div>
  </div>
</div>
<hr/>
</section>
  </div>
</div>

{if isset($smarty.const.APP_DEBUG_MODE) && $smarty.const.APP_DEBUG_MODE}
<div class="clearfix float-start">
<p class="fw-semibold fst-italic p-2">
Template: <span class="text-nowrap text-lowercase">{$smarty.template}</span>
</p>
</div>
{/if}