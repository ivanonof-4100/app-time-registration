<?php
/* Smarty version 4.2.1, created on 2022-10-15 10:52:00
  from '/data/WebProjects/Northern-partners/Projekt-timeseddler/app/view/templates/site_templates/templates/std_page_html5.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.2.1',
  'unifunc' => 'content_634a74b05bfad0_72454898',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1a8d30793c5a7601ccdb39f30bdea9bfb8d15cb1' => 
    array (
      0 => '/data/WebProjects/Northern-partners/Projekt-timeseddler/app/view/templates/site_templates/templates/std_page_html5.tpl',
      1 => 1665685437,
      2 => 'file',
    ),
  ),
  'cache_lifetime' => 3600,
),true)) {
function content_634a74b05bfad0_72454898 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="da">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>Weekly Timesheets | Northern Partners</title>
<link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous"/>
</head>

<body>
<h1 class="text-center">Weekly Timesheets</h1>

 <div class="container">
   <div class="row">
    <div class="col-md-12 ms-sm-auto col-lg-12 px-md-4" role="main">
    <!-- Weekly Timesheets -->
<h2>Week: 41, 2022</h2>
<p>Period:<br/>
<strong>10-10-2022 - 16-10-2022</strong><br/>

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
    <td>26.5</td>
  </tr>
  <tr>
    <td scope="row">Overtime hours:</td>
    <td>13.5</td>
  </tr>
  <tr>
    <td scope="row">Break hours:</td>
    <td>3.5</td>
  </tr>
  <tr class="table-group-divider">
    <td scope="row">Total hours:</td>
    <td>43.5</td>
</tr>
</tbody>
</table></p>

<h3 class="text-center">Registered hours</h3>
<p>Please register your hours on each day of the week which you had worked.</p>

<div class="accordion" id="timesheet_accordion">
    
  <div class="accordion-item">
  <h4 class="accordion-header" id="headingItem1">
  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseItem1" aria-expanded="false" aria-controls="collapseItem1">
    <strong>mandag: 10-10-2022</strong>
  </button>
</h4>
<div id="collapseItem1"  class="accordion-collapse collapse" aria-labelledby="headingItem1" data-bs-parent="#timesheet_accordion">
  <div class="accordion-body">

<!-- Timesheet Daily Form -->
<p>DAILY TIMESHEET:</p>
<form name="timesheet_daily_41_2022-10-10" method="post" accept-charset="utf8">
<input type="hidden" name="_token" value="n1mFGz2XGh9VE1so7DmEEYubQ065OsRxYsEVonJp" />
<input type="hidden" name="timesheet_work_date" value="2022-10-10" />
<input type="hidden" name="employee_uuid" value="597e8483-467d-11ed-b005-1c1bb5a9bf9b" />
<input type="hidden" name="timesheet_uuid" value="593f531f-49a5-11ed-9bbf-1c1bb5a9bf9b" />

<div class="form-group mb-3">
  <label for="timesheet_hours_regular" class="form-label">Regular hours:</label>
  <input type="number" id="timesheet_hours_regular" name="timesheet_hours_regular" min="0" step="0.50" value="4.5" tabindex="1" class="form-control form-control-lg" required="required" autofocus="1" />
</div>
<div class="form-group mb-3">
  <label for="timesheet_hours_overtime" class="form-label">Overtime hours:</label>
  <input type="number" id="timesheet_hours_overtime" name="timesheet_hours_overtime" min="0" step="0.50" value="5" tabindex="2" class="form-control form-control-lg" required="required" />
</div>
<div class="form-group mb-3">
  <label for="timesheet_hours_break" class="form-label">Break hours:</label>
  <input type="number" id="timesheet_hours_break" name="timesheet_hours_break" min="0" step="0.50" value="0.5" tabindex="3" class="form-control form-control-lg" required="required" />
</div>
<div class="form-group mb-3">
  <input type="submit" name="btn_submit" role="button" value="Gem" tabindex="4" class="btn btn-lg btn-block btn-primary" title="Gem"/>
</div>
</form>  </div>
</div>
  </div>
    
  <div class="accordion-item">
  <h4 class="accordion-header" id="headingItem2">
  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseItem2" aria-expanded="false" aria-controls="collapseItem2">
    <strong>tirsdag: 11-10-2022</strong>
  </button>
</h4>
<div id="collapseItem2"  class="accordion-collapse collapse" aria-labelledby="headingItem2" data-bs-parent="#timesheet_accordion">
  <div class="accordion-body">

<!-- Timesheet Daily Form -->
<p>DAILY TIMESHEET:</p>
<form name="timesheet_daily_41_2022-10-11" method="post" accept-charset="utf8">
<input type="hidden" name="_token" value="n1mFGz2XGh9VE1so7DmEEYubQ065OsRxYsEVonJp" />
<input type="hidden" name="timesheet_work_date" value="2022-10-11" />
<input type="hidden" name="employee_uuid" value="597e8483-467d-11ed-b005-1c1bb5a9bf9b" />
<input type="hidden" name="timesheet_uuid" value="703e2dea-4ae4-11ed-80db-1c1bb5a9bf9b" />

<div class="form-group mb-3">
  <label for="timesheet_hours_regular" class="form-label">Regular hours:</label>
  <input type="number" id="timesheet_hours_regular" name="timesheet_hours_regular" min="0" step="0.50" value="3.5" tabindex="1" class="form-control form-control-lg" required="required" autofocus="1" />
</div>
<div class="form-group mb-3">
  <label for="timesheet_hours_overtime" class="form-label">Overtime hours:</label>
  <input type="number" id="timesheet_hours_overtime" name="timesheet_hours_overtime" min="0" step="0.50" value="1" tabindex="2" class="form-control form-control-lg" required="required" />
</div>
<div class="form-group mb-3">
  <label for="timesheet_hours_break" class="form-label">Break hours:</label>
  <input type="number" id="timesheet_hours_break" name="timesheet_hours_break" min="0" step="0.50" value="0.5" tabindex="3" class="form-control form-control-lg" required="required" />
</div>
<div class="form-group mb-3">
  <input type="submit" name="btn_submit" role="button" value="Gem" tabindex="4" class="btn btn-lg btn-block btn-primary" title="Gem"/>
</div>
</form>  </div>
</div>
  </div>
    
  <div class="accordion-item">
  <h4 class="accordion-header" id="headingItem3">
  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseItem3" aria-expanded="false" aria-controls="collapseItem3">
    <strong>onsdag: 12-10-2022</strong>
  </button>
</h4>
<div id="collapseItem3"  class="accordion-collapse collapse" aria-labelledby="headingItem3" data-bs-parent="#timesheet_accordion">
  <div class="accordion-body">

<!-- Timesheet Daily Form -->
<p>DAILY TIMESHEET:</p>
<form name="timesheet_daily_41_2022-10-12" method="post" accept-charset="utf8">
<input type="hidden" name="_token" value="n1mFGz2XGh9VE1so7DmEEYubQ065OsRxYsEVonJp" />
<input type="hidden" name="timesheet_work_date" value="2022-10-12" />
<input type="hidden" name="employee_uuid" value="597e8483-467d-11ed-b005-1c1bb5a9bf9b" />
<input type="hidden" name="timesheet_uuid" value="60805fb3-4ae5-11ed-80db-1c1bb5a9bf9b" />

<div class="form-group mb-3">
  <label for="timesheet_hours_regular" class="form-label">Regular hours:</label>
  <input type="number" id="timesheet_hours_regular" name="timesheet_hours_regular" min="0" step="0.50" value="3.5" tabindex="1" class="form-control form-control-lg" required="required" autofocus="1" />
</div>
<div class="form-group mb-3">
  <label for="timesheet_hours_overtime" class="form-label">Overtime hours:</label>
  <input type="number" id="timesheet_hours_overtime" name="timesheet_hours_overtime" min="0" step="0.50" value="5.5" tabindex="2" class="form-control form-control-lg" required="required" />
</div>
<div class="form-group mb-3">
  <label for="timesheet_hours_break" class="form-label">Break hours:</label>
  <input type="number" id="timesheet_hours_break" name="timesheet_hours_break" min="0" step="0.50" value="0.5" tabindex="3" class="form-control form-control-lg" required="required" />
</div>
<div class="form-group mb-3">
  <input type="submit" name="btn_submit" role="button" value="Gem" tabindex="4" class="btn btn-lg btn-block btn-primary" title="Gem"/>
</div>
</form>  </div>
</div>
  </div>
    
  <div class="accordion-item">
  <h4 class="accordion-header" id="headingItem4">
  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseItem4" aria-expanded="false" aria-controls="collapseItem4">
    <strong>torsdag: 13-10-2022</strong>
  </button>
</h4>
<div id="collapseItem4"  class="accordion-collapse collapse" aria-labelledby="headingItem4" data-bs-parent="#timesheet_accordion">
  <div class="accordion-body">

<!-- Timesheet Daily Form -->
<p>DAILY TIMESHEET:</p>
<form name="timesheet_daily_41_2022-10-13" method="post" accept-charset="utf8">
<input type="hidden" name="_token" value="n1mFGz2XGh9VE1so7DmEEYubQ065OsRxYsEVonJp" />
<input type="hidden" name="timesheet_work_date" value="2022-10-13" />
<input type="hidden" name="employee_uuid" value="597e8483-467d-11ed-b005-1c1bb5a9bf9b" />
<input type="hidden" name="timesheet_uuid" value="3855b040-4aec-11ed-80db-1c1bb5a9bf9b" />

<div class="form-group mb-3">
  <label for="timesheet_hours_regular" class="form-label">Regular hours:</label>
  <input type="number" id="timesheet_hours_regular" name="timesheet_hours_regular" min="0" step="0.50" value="5.5" tabindex="1" class="form-control form-control-lg" required="required" autofocus="1" />
</div>
<div class="form-group mb-3">
  <label for="timesheet_hours_overtime" class="form-label">Overtime hours:</label>
  <input type="number" id="timesheet_hours_overtime" name="timesheet_hours_overtime" min="0" step="0.50" value="0" tabindex="2" class="form-control form-control-lg" required="required" />
</div>
<div class="form-group mb-3">
  <label for="timesheet_hours_break" class="form-label">Break hours:</label>
  <input type="number" id="timesheet_hours_break" name="timesheet_hours_break" min="0" step="0.50" value="0.5" tabindex="3" class="form-control form-control-lg" required="required" />
</div>
<div class="form-group mb-3">
  <input type="submit" name="btn_submit" role="button" value="Gem" tabindex="4" class="btn btn-lg btn-block btn-primary" title="Gem"/>
</div>
</form>  </div>
</div>
  </div>
    
  <div class="accordion-item">
  <h4 class="accordion-header" id="headingItem5">
  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseItem5" aria-expanded="false" aria-controls="collapseItem5">
    <strong>fredag: 14-10-2022</strong>
  </button>
</h4>
<div id="collapseItem5"  class="accordion-collapse collapse" aria-labelledby="headingItem5" data-bs-parent="#timesheet_accordion">
  <div class="accordion-body">

<!-- Timesheet Daily Form -->
<p>DAILY TIMESHEET:</p>
<form name="timesheet_daily_41_2022-10-14" method="post" accept-charset="utf8">
<input type="hidden" name="_token" value="n1mFGz2XGh9VE1so7DmEEYubQ065OsRxYsEVonJp" />
<input type="hidden" name="timesheet_work_date" value="2022-10-14" />
<input type="hidden" name="employee_uuid" value="597e8483-467d-11ed-b005-1c1bb5a9bf9b" />
<input type="hidden" name="timesheet_uuid" value="6dd2fcf3-4ae9-11ed-80db-1c1bb5a9bf9b" />

<div class="form-group mb-3">
  <label for="timesheet_hours_regular" class="form-label">Regular hours:</label>
  <input type="number" id="timesheet_hours_regular" name="timesheet_hours_regular" min="0" step="0.50" value="2.5" tabindex="1" class="form-control form-control-lg" required="required" autofocus="1" />
</div>
<div class="form-group mb-3">
  <label for="timesheet_hours_overtime" class="form-label">Overtime hours:</label>
  <input type="number" id="timesheet_hours_overtime" name="timesheet_hours_overtime" min="0" step="0.50" value="1" tabindex="2" class="form-control form-control-lg" required="required" />
</div>
<div class="form-group mb-3">
  <label for="timesheet_hours_break" class="form-label">Break hours:</label>
  <input type="number" id="timesheet_hours_break" name="timesheet_hours_break" min="0" step="0.50" value="0.5" tabindex="3" class="form-control form-control-lg" required="required" />
</div>
<div class="form-group mb-3">
  <input type="submit" name="btn_submit" role="button" value="Gem" tabindex="4" class="btn btn-lg btn-block btn-primary" title="Gem"/>
</div>
</form>  </div>
</div>
  </div>
    
  <div class="accordion-item">
  <h4 class="accordion-header" id="headingItem6">
  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseItem6" aria-expanded="false" aria-controls="collapseItem6">
    <strong>lørdag: 15-10-2022</strong>
  </button>
</h4>
<div id="collapseItem6"  class="accordion-collapse collapse" aria-labelledby="headingItem6" data-bs-parent="#timesheet_accordion">
  <div class="accordion-body">

<!-- Timesheet Daily Form -->
<p>DAILY TIMESHEET:</p>
<form name="timesheet_daily_41_2022-10-15" method="post" accept-charset="utf8">
<input type="hidden" name="_token" value="n1mFGz2XGh9VE1so7DmEEYubQ065OsRxYsEVonJp" />
<input type="hidden" name="timesheet_work_date" value="2022-10-15" />
<input type="hidden" name="employee_uuid" value="597e8483-467d-11ed-b005-1c1bb5a9bf9b" />
<input type="hidden" name="timesheet_uuid" value="7e334e99-4ae5-11ed-80db-1c1bb5a9bf9b" />

<div class="form-group mb-3">
  <label for="timesheet_hours_regular" class="form-label">Regular hours:</label>
  <input type="number" id="timesheet_hours_regular" name="timesheet_hours_regular" min="0" step="0.50" value="2.5" tabindex="1" class="form-control form-control-lg" required="required" autofocus="1" />
</div>
<div class="form-group mb-3">
  <label for="timesheet_hours_overtime" class="form-label">Overtime hours:</label>
  <input type="number" id="timesheet_hours_overtime" name="timesheet_hours_overtime" min="0" step="0.50" value="0" tabindex="2" class="form-control form-control-lg" required="required" />
</div>
<div class="form-group mb-3">
  <label for="timesheet_hours_break" class="form-label">Break hours:</label>
  <input type="number" id="timesheet_hours_break" name="timesheet_hours_break" min="0" step="0.50" value="0.5" tabindex="3" class="form-control form-control-lg" required="required" />
</div>
<div class="form-group mb-3">
  <input type="submit" name="btn_submit" role="button" value="Gem" tabindex="4" class="btn btn-lg btn-block btn-primary" title="Gem"/>
</div>
</form>  </div>
</div>
  </div>
    
  <div class="accordion-item">
  <h4 class="accordion-header" id="headingItem7">
  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseItem7" aria-expanded="false" aria-controls="collapseItem7">
    <strong>søndag: 16-10-2022</strong>
  </button>
</h4>
<div id="collapseItem7"  class="accordion-collapse collapse" aria-labelledby="headingItem7" data-bs-parent="#timesheet_accordion">
  <div class="accordion-body">

<!-- Timesheet Daily Form -->
<p>DAILY TIMESHEET:</p>
<form name="timesheet_daily_41_2022-10-16" method="post" accept-charset="utf8">
<input type="hidden" name="_token" value="n1mFGz2XGh9VE1so7DmEEYubQ065OsRxYsEVonJp" />
<input type="hidden" name="timesheet_work_date" value="2022-10-16" />
<input type="hidden" name="employee_uuid" value="597e8483-467d-11ed-b005-1c1bb5a9bf9b" />
<input type="hidden" name="timesheet_uuid" value="a0d5950e-49ac-11ed-9bbf-1c1bb5a9bf9b" />

<div class="form-group mb-3">
  <label for="timesheet_hours_regular" class="form-label">Regular hours:</label>
  <input type="number" id="timesheet_hours_regular" name="timesheet_hours_regular" min="0" step="0.50" value="4.5" tabindex="1" class="form-control form-control-lg" required="required" autofocus="1" />
</div>
<div class="form-group mb-3">
  <label for="timesheet_hours_overtime" class="form-label">Overtime hours:</label>
  <input type="number" id="timesheet_hours_overtime" name="timesheet_hours_overtime" min="0" step="0.50" value="1" tabindex="2" class="form-control form-control-lg" required="required" />
</div>
<div class="form-group mb-3">
  <label for="timesheet_hours_break" class="form-label">Break hours:</label>
  <input type="number" id="timesheet_hours_break" name="timesheet_hours_break" min="0" step="0.50" value="0.5" tabindex="3" class="form-control form-control-lg" required="required" />
</div>
<div class="form-group mb-3">
  <input type="submit" name="btn_submit" role="button" value="Gem" tabindex="4" class="btn btn-lg btn-block btn-primary" title="Gem"/>
</div>
</form>  </div>
</div>
  </div>
</div>
    </div>
   </div>
 </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>
</html><?php }
}
