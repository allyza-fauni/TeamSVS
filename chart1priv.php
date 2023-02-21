<?php

//chart1.php

include('admin_header.php');

$present_percentage = 0;
$absent_percentage = 0;
$total_present = 0;
$total_absent = 0;
$output = "";

$query = "
SELECT * FROM tbl_attendancepriv 
INNER JOIN tbl_dupmemberpriv  
ON tbl_dupmemberpriv.dupmemberpriv_id = tbl_attendancepriv.dupmemberpriv_id 
INNER JOIN tbl_svs 
ON tbl_svs.svs_id = tbl_dupmemberpriv.dupmemberpriv_svs_id 
WHERE tbl_dupmemberpriv.dupmemberpriv_svs_id = '".$_GET['svs_id']."' 
AND tbl_attendancepriv.attendancepriv_date = '".$_GET["date"]."'
";
//echo $query;
$statement = $connect->prepare($query);
$statement->execute();

$result = $statement->fetchAll();

$total_row = $statement->rowCount();

foreach($result as $row)
{
	$status = '';
	if($row["attendancepriv_status"] == "Present")
	{
		$total_present++;
		$status = '<span class="badge badge-success">Present</span>';
	}

	if($row["attendancepriv_status"] == "Absent")
	{
		$total_absent++;
		$status = '<span class="badge badge-danger">Absent</span>';
	}
	$output .= '
		<tr>
			<td>'.$row["dupmemberpriv_name"].'</td>
			<td>'.$status.'</td>
		</tr>
	';
}

if($total_row > 0)
{
	$present_percentage = ($total_present / $total_row) * 100;
	$absent_percentage = ($total_absent / $total_row) * 100;
}

?>

<div class="container" style="margin-top:30px">
  <div class="card">
  	<div class="card-header"><b>Attendance Chart</b></div>
  	<div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <tr>
            <th>SVS Code</th>
            <td><?php echo Get_svs_name($connect, $_GET["svs_id"]); ?></td>
          </tr>
          <tr>
            <th>Date</th>
            <td><?php echo $_GET["date"]; ?></td>
          </tr>
        </table>
      </div>
  		<div id="attendance_pie_chart" style="width: 100%; height: 400px;">
  		</div>
  		<div class="table-responsive">
        <table class="table table-striped table-bordered">
          <tr>
            <th>Member Name</th>
            <th>Attendance Status</th>
          </tr>
          <?php 
          echo $output;
          ?>
      </table></div>
  	</div>
  </div>
</div>

</body>
</html>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart()
  {
    var data = google.visualization.arrayToDataTable([
      ['Attendance Status', 'Percentage'],
      ['Present', <?php echo $present_percentage; ?>],
      ['Absent', <?php echo $absent_percentage; ?>]
    ]);
    var options = {
      title: 'Overall Attendance Analytics',
      hAxis: {
        title: 'Percentage',
        minValue: 0,
        maxValue: 100
      },
      vAxis: {
        title: 'Attendance Status'
      }
    };
    var chart = new google.visualization.PieChart(document.getElementById('attendance_pie_chart'));
    chart.draw(data, options);
  }
</script>