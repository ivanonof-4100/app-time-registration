{assign var=arrAccumulatedHours value=$arrAccumulatedHours}
{assign var=totalHours value=$totalHours|default:0}

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
    <td>{$arrAccumulatedHours.total_hours_regular}</td>
  </tr>
  <tr>
    <td scope="row">Overtime hours:</td>
    <td>{$arrAccumulatedHours.total_hours_overtime}</td>
  </tr>
  <tr>
    <td scope="row">Break hours:</td>
    <td>{$arrAccumulatedHours.total_hours_break}</td>
  </tr>
  <tr class="table-group-divider">
    <td scope="row">Total hours:</td>
    <td>{$totalHours}</td>
</tr>
</tbody>
</table>