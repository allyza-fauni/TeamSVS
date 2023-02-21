<?php

//report.php

if(isset($_GET["action"]))
{
	include('database_connection.php');
	require_once 'pdf.php';
	session_start();
	$output = '';
	if($_GET["action"] == 'attendance_report')
	{
		if(isset($_GET["svs_id"], $_GET["from_date"], $_GET["to_date"]))
		{
			$pdf = new Pdf();
			$query = "
			SELECT tbl_attendance.attendance_date FROM tbl_attendance 
			INNER JOIN tbl_dupmember 
			ON tbl_dupmember.dupmember_id = tbl_attendance.dupmember_id 
			WHERE tbl_dupmember.dupmember_svs_id = '".$_GET["svs_id"]."' 
			AND (tbl_attendance.attendance_date BETWEEN '".$_GET["from_date"]."' AND '".$_GET["to_date"]."')
			GROUP BY tbl_attendance.attendance_date 
			ORDER BY tbl_attendance.attendance_date ASC
			";
			$statement = $connect->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll();
			$output .= '
				<style>
				@page { margin: 20px; }
				
				</style>
				<p>&nbsp;</p>
				<h3 align="center">Attendance Report</h3><br />';
			foreach($result as $row)
			{
				$output .= '
				<table width="100%" border="0" cellpadding="5" cellspacing="0">
			        <tr>
			        	<td><b>Date - '.$row["attendance_date"].'</b></td>
			        </tr>
			        <tr>
			        	<td>
			        		<table width="100%" border="1" cellpadding="5" cellspacing="0">
			        			<tr>
			        				<td><b>Member Name</b></td>
			        				<td><b>SVS Code</b></td>
			        				<td><b>Attendance Status</b></td>
			        			</tr>
				';
				$sub_query = "
				SELECT * FROM tbl_attendance 
			    INNER JOIN tbl_dupmember 
			    ON tbl_dupmember.dupmember_id = tbl_attendance.dupmember_id 
			    INNER JOIN tbl_svs 
			    ON tbl_svs.svs_id = tbl_dupmember.dupmember_svs_id 
			    WHERE tbl_dupmember.dupmember_svs_id = '".$_GET["svs_id"]."' 
				AND tbl_attendance.attendance_date = '".$row["attendance_date"]."'
				";

				$statement = $connect->prepare($sub_query);
				$statement->execute();
				$sub_result = $statement->fetchAll();
				foreach($sub_result as $sub_row)
				{
					$output .= '
					<tr>
						<td>'.$sub_row["dupmember_name"].'</td>
						<td>'.$sub_row["svs_name"].'</td>
						<td>'.$sub_row["attendance_status"].'</td>
					</tr>
					';
				}
				$output .= 
					'</table>
					</td>
					</tr>
				</table><br />';
			}
			$file_name = 'Attendance Report.pdf';
			$pdf->loadHtml($output);
			$pdf->render();
			$pdf->stream($file_name, array("Attachment" => false));
			exit(0);
		}
	}

	if($_GET["action"] == "dupmember_report")
	{
		if(isset($_GET["dupmember_id"], $_GET["from_date"], $_GET["to_date"]))
		{
			$pdf = new Pdf();
			$query = "
			SELECT * FROM tbl_dupmember 
			INNER JOIN tbl_svs 
			ON tbl_svs.svs_id = tbl_dupmember.dupmember_svs_id 
			WHERE tbl_dupmember.dupmember_id = '".$_GET["dupmember_id"]."' 
			";
			$statement = $connect->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll();
			foreach($result as $row)
			{
				$output .= '
				<style>
				@page { margin: 20px; }
				
				</style>
				<p>&nbsp;</p>
				<h3 align="center">Attendance Report</h3><br /><br />
				<table width="100%" border="0" cellpadding="5" cellspacing="0">
			        <tr>
			            <td width="25%"><b>Member Name</b></td>
			            <td width="75%">'.$row["dupmember_name"].'</td>
			        </tr>
			        <tr>
			            <td width="25%"><b>SVS Code</b></td>
			            <td width="75%">'.$row["svs_name"].'</td>
			        </tr>
			        <tr>
			        	<td colspan="2" height="5">
			        		<h3 align="center">Attendance Details</h3>
			        	</td>
			        </tr>
			        <tr>
			        	<td colspan="2">
			        		<table width="100%" border="1" cellpadding="5" cellspacing="0">
			        			<tr>
			        				<td><b>Attendance Date</b></td>
			        				<td><b>Attendance Status</b></td>
			        			</tr>
				';
				$sub_query = "
				SELECT * FROM tbl_attendance 
				WHERE dupmember_id = '".$_GET["dupmember_id"]."' 
				AND (attendance_date BETWEEN '".$_GET["from_date"]."' AND '".$_GET["to_date"]."') 
				ORDER BY attendance_date ASC
				";

				$statement = $connect->prepare($sub_query);
				$statement->execute();
				$sub_result = $statement->fetchAll();
				foreach($sub_result as $sub_row)
				{
					$output .= '
					<tr>
						<td>'.$sub_row["attendance_date"].'</td>
						<td>'.$sub_row["attendance_status"].'</td>
					</tr>
					';
				}
				$output .= '
						</table>
					</td>
					</tr>
				</table>
				';

				$file_name = "Attendance Report.pdf";
				$pdf->loadHtml($output);
				$pdf->render();
				$pdf->stream($file_name, array("Attachment" => false));
				exit(0);
			}
		}
	}
}

?>