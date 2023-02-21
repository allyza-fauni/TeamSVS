<?php

//admin_attendance_priv.php

include('admin_header.php');

?>

<div class="container" style="margin-top:30px">
  <div class="card">
  	<div class="card-header">
      <div class="row">
        <div class="col-md-9"><h2><b><i>Makers Members Attendance List</i></b></h2></div>
        <div class="col-md-3" align="right">
          <button type="button" id="chart_button" class="btn btn-primary btn-sm">Chart</button>
          <button type="button" id="report_button" class="btn btn-danger btn-sm">Report</button>
        </div>
      </div>
    </div>
  	<div class="card-body">
  		<div class="table-responsive">
        <table class="table table-striped table-bordered" id="attendancepriv_table">
          <thead>
            <tr>
              <th>Member Name</th>
              <th>SVS Code</th>
              <th>Attendance Status</th>
              <th>Attendance Date</th>  
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
  		</div>
  	</div>
  </div>
</div>

</body>
</html>

<script type="text/javascript" src="js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="css/datepicker.css" />

<style>
    .datepicker
    {
      z-index: 1600 !important; /* has to be larger than 1050 */
    }
</style>

<div class="modal" id="reportModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Make Report</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="form-group">
          <select name="svs_id" id="svs_id" class="form-control">
            <option value="">Select SVS Code</option>
            <?php
            echo load_svs_list($connect);
            ?>
          </select>
          <span id="error_svs_id" class="text-danger"></span>
        </div>
        <div class="form-group">
          <div class="input-daterange">
            <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" readonly />
            <span id="error_from_date" class="text-danger"></span>
            <br />
            <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" readonly />
            <span id="error_to_date" class="text-danger"></span>
          </div>
        </div>
      </div>
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" name="create_report" id="create_report" class="btn btn-success btn-sm">Create PDF Report</button>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<div class="modal" id="chartModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Create Attandance Chart</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="form-group">
          <select name="chart_svs_id" id="chart_svs_id" class="form-control">
            <option value="">Select SVS Code</option>
            <?php
            echo load_svs_list($connect);
            ?>
          </select>
          <span id="error_chart_svs_id" class="text-danger"></span>
        </div>
        <div class="form-group">
          <div class="input-daterange">
            <input type="text" name="attendancepriv_date" id="attendancepriv_date" class="form-control" placeholder="Select Date" readonly />
            <span id="error_attendancepriv_date" class="text-danger"></span>
          </div>
        </div>
      </div>
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" name="create_chart" id="create_chart" class="btn btn-success btn-sm">Create Chart</button>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<script>
$(document).ready(function(){
	
  var dataTable = $('#attendancepriv_table').DataTable({
    "processing":true,
    "serverSide":true,
    "order":[],
    "ajax":{
      url:"admin_attendance_priv_action.php",
      type:"POST",
      data:{action:'fetch'}
    }
  });

  $('.input-daterange').datepicker({
    todayBtn: "linked",
    format: "yyyy-mm-dd",
    autoclose: true,
    container: '#formModal modal-body'
  });

  $(document).on('click', '#report_button', function(){
    $('#reportModal').modal('show');
  });

  $('#create_report').click(function(){
    var svs_id = $('#svs_id').val();
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    var error = 0;

    if(svs_id == '')
    {
      $('#error_svs_id').text('SVS Code is Required');
      error++;
    }
    else
    {
      $('#error_svs_id').text('');
    }

    if(from_date == '')
    {
      $('#error_from_date').text('From Date is Required');
      error++;
    }
    else
    {
      $('#error_from_date').text('');
    }

    if(to_date == '')
    {
      $('#error_to_date').text("To Date is Required");
      error++;
    }
    else
    {
      $('#error_to_date').text('');
    }

    if(error == 0)
    {
      $('#from_date').val('');
      $('#to_date').val('');
      $('#formModal').modal('hide');
      window.open("report_priv.php?action=attendancepriv_report_priv&svs_id="+svs_id+"&from_date="+from_date+"&to_date="+to_date);
    }

  });

  $('#chart_button').click(function(){
    $('#chart_svs_id').val('');
    $('#attendancepriv_date').val('');
    $('#chartModal').modal('show');
  });

  $('#create_chart').click(function(){
    var svs_id = $('#chart_svs_id').val();
    var attendancepriv_date = $('#attendancepriv_date').val();
    var error = 0;
    if(svs_id == '')
    {
      $('#error_chart_svs_id').text('SVS Code is Required');
      error++;
    }
    else
    {
      $('#error_chart_svs_id').text('');
    }
    if(attendancepriv_date == '')
    {
      $('#error_attendancepriv_date').text('Date is Required');
      $error++;
    }
    else
    {
      $('#error_attendancepriv_date').text('');
    }

    if(error == 0)
    {
      $('#attendancepriv_date').val('');
      $('#chart_svs_id').val('');
      $('#chartModal').modal('show');
      window.open("chart1priv.php?action=attendancepriv_report_priv&svs_id="+svs_id+"&date="+attendancepriv_date);
    }

  });

});
</script>