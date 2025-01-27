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
<!-- Dynamic timesheet-stats -->
<div id="timesheet_stats"></div>


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
{literal}
<script type="text/javascript">
// API end-point for fetching timesheet-stats.
const apiUri ='/api/v1/timesheet-stats/';
const employeeUUID = '{/literal}{$smarty.session.employee_uuid}{literal}';
const year = {/literal}{$yearNumber}{literal};

function renderStatList(apiData) {
    // Process the retrieved data
    let divElement = document.getElementById("timesheet_stats");
    let currentQuarter =0;

    for (let currentQuarter =1; currentQuarter <= 4; currentQuarter++) {
      // Add header
      divElement.innerHTML += '<h5 id="quarter'+currentQuarter +'" style="font-weight:bold;">'+ currentQuarter +'. kvartal '+year +'</h5>';

      // Filter array
      let filteredData = apiData.filter((item) => {
        return (item.working_quarter == currentQuarter) && (item.total_hours_regular >0);
      });

      divElement.innerHTML += '<ul class="full-width list-style-none" style="background-color:transparent;margin-right:7px;">';
      filteredData.forEach((item) => {
        divElement.innerHTML += '<li><b>Week '+item.working_week +'</b>:&nbsp;<span>'+ item.total_hours_regular +' timer (70%)</span><div class="progress" role="progressbar" aria-label="'+ item.working_week +'" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="height:16pt;margin-bottom:10px;"><div class="progress-bar progress-bar-striped text-bg-light text-dark" style="width:70%;"> 70% </div></div></li>';
      });
    } // End for-loop

    divElement.innerHTML += '</ul>';
}

// Setup query-parameters
const queryParams = {
  employee_uuid: employeeUUID,
  year: year
};

// Convert query parameters to a string
const queryString = new URLSearchParams(queryParams).toString();

// Combine API end-point with query-parameters
const finalUri = `${apiUri}?${queryString}`;
// Make a async GET-request using the Fetch API
fetch(finalUri)
  .then(response => {
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    return response.json();
  })
  .then(apiData => {
    renderStatList(apiData);
    // console.log('Data:', apiData);
    // console.table(apiData);
  })
  .catch(error => {
    console.error('Error:', error);
  });
</script>
{/literal}