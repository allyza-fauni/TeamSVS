<?php

//attendance.php

include('header_priv.php');

?>

<div class="container" style="margin-top:30px">
  <div class="card">
  	<div class="card-header">
      <div class="row">
        <div class="col-md-9"><h2><i><b>Attendance List</b></i><h2></div>
        <div class="col-md-3" align="right">
          <button type="button" id="add_button" class="btn btn-info btn-sm">Add your Attendance Here</button>
        </div>
      </div>
    </div>
  	<div class="card-body">
  		<div class="table-responsive">
        <span id="message_operation"></span>
        <table class="table table-striped table-bordered" id="attendancepriv_table">
          <thead>
            <tr>
              <th>Member Name</th>
              <th>SVS</th>
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

<?php

$query = "
SELECT * FROM tbl_svs WHERE svs_id = (SELECT memberpriv_svs_id FROM tbl_memberpriv 
    WHERE memberpriv_id = '".$_SESSION["memberpriv_id"]."')
";

$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();

?>

<div class="modal" id="formModal">
  <div class="modal-dialog">
    <form method="post" id="attendancepriv_form">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="modal_title"></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <?php
          foreach($result as $row)
          {
          ?>
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">SVS CODE<span class="text-danger">*</span></label>
              <div class="col-md-8">
                <?php
                echo '<label>'.$row["svs_name"].'</label>';
                ?>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Attendance Date <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="text" name="attendancepriv_date" id="attendancepriv_date" class="form-control" readonly />
                <span id="error_attendancepriv_date" class="text-danger"></span>
              </div>
            </div>
          </div>
          <div class="form-group" id="dupmemberpriv_details">
            <div class="table-responsive">
              <table class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>Member Name</th>
                    <th>Present</th>
                    <th>Absent</th>
                  </tr>
                </thead>
                <?php
                $sub_query = "
                  SELECT * FROM tbl_dupmemberpriv 
                  WHERE dupmemberpriv_svs_id = '".$row["svs_id"]."'
                ";
                $statement = $connect->prepare($sub_query);
                $statement->execute();
                $dupmemberpriv_result = $statement->fetchAll();
                foreach($dupmemberpriv_result as $dupmemberpriv)
                {
                ?>
                  <tr>
                    <td>
                      <?php echo $dupmemberpriv["dupmemberpriv_name"]; ?>
                      <input type="hidden" name="dupmemberpriv_id[]" value="<?php echo $dupmemberpriv["dupmemberpriv_id"]; ?>" />
                    </td>
                    <td>
                      <input type="radio" name="attendancepriv_status<?php echo $dupmemberpriv["dupmemberpriv_id"]; ?>" value="Present" />
                    </td>
                    <td>
                      <input type="radio" name="attendancepriv_status<?php echo $dupmemberpriv["dupmemberpriv_id"]; ?>" checked value="Absent" />
                    </td>
                  </tr>
                <?php
                }
                ?>
              </table>
            </div>
          </div>
          <?php
          }
          ?>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="action" id="action" value="Add" />
          <input type="submit" name="button_action" id="button_action" class="btn btn-success btn-sm" value="Add" />
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="modal" id="reportModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Make Report</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
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
      <div class="modal-footer">
        <button type="button" name="create_report" id="create_report" class="btn btn-success btn-sm">Create PDF Report</button>
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
      url:"attendance_priv_action.php",
      method:"POST",
      data:{action:"fetch"}
    }
  });

  $('#attendancepriv_date').datepicker({
    format:'yyyy-mm-dd',
    autoclose:true,
    container: '#formModal modal-body'
  });

  function clear_field()
  {
    $('#attendancepriv_form')[0].reset();
    $('#error_attendancepriv_date').text('');
  }

  $('#add_button').click(function(){
    $('#modal_title').text("Add Attendance");
    $('#formModal').modal('show');
    clear_field();
  });

  $('#attendancepriv_form').on('submit', function(event){
    event.preventDefault();
    $.ajax({
      url:"attendance_priv_action.php",
      method:"POST",
      data:$(this).serialize(),
      dataType:"json",
      beforeSend:function(){
        $('#button_action').val('Loading ...');
        $('#button_action').attr('disabled', 'disabled');
      },
      success:function(data)
      {
        $('#button_action').attr('disabled', false);
        $('#button_action').val($('#action').val());
        if(data.success)
        {
          $('#message_operation').html('<div class="alert alert-success">'+data.success+'</div>');
          clear_field();
          $('#formModal').modal('hide');
          dataTable.ajax.reload();
        }
        if(data.error)
        {
          if(data.error_attendancepriv_date != '')
          {
            $('#error_attendancepriv_date').text(data.error_attendancepriv_date);
          }
          else
          {
            $('#error_attendancepriv_date').text('');
          }
        }
      }
    })
  });

  $('.input-daterange').datepicker({
    todayBtn:"linked",
    format:"yyyy-mm-dd",
    autoclose:true,
    container: '#formModal modal-body'
  });

  $(document).on('click', '#report_button', function(){
    $('#reportModal').modal('show');
  });

  $('#create_report').click(function(){
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    var error = 0;
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
      window.open("report.php?action=attendancepriv_report&from_date="+from_date+"&to_date="+to_date);
    }

  });
});
</script>