{assign var=arrAccumulatedHours value=$arrAccumulatedHours}
{assign var=totalHours value=$totalHours|default:0}

<h3 class="text-center">Accumulated hours</h3>
<table class="table table-hover">
<thead>
<tr>
  <th scope="col"></th>
  <th scope="col" class="text-end">Hours</th>
</tr>
</thead>

<tbody class="table-group-divider">
  <tr>
    <td scope="row">Regular hours:</td>
    <td class="text-end">{$arrAccumulatedHours.total_hours_regular|number_format:2:$commaSeparator:$thousandSeparator}</td>
  </tr>
  <tr>
    <td scope="row">Overtime hours:</td>
    <td class="text-end">{$arrAccumulatedHours.total_hours_overtime|number_format:2:$commaSeparator:$thousandSeparator}</td>
  </tr>
  <tr>
    <td scope="row">Break hours:</td>
    <td class="text-end">{$arrAccumulatedHours.total_hours_break|number_format:2:$commaSeparator:$thousandSeparator}</td>
  </tr>
  <tr class="table-group-divider">
    <td scope="row">Total hours:</td>
    <td class="text-end">{$totalHours|number_format:2:$commaSeparator:$thousandSeparator}</td>
</tr>
</tbody>
</table>