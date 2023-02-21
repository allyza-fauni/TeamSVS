<?php

//dupmember.php

include('admin_header.php');

?>

<div class="container" style="margin-top:30px">
  <div class="card">
  	<div class="card-header">
      <div class="row">
        <div class="col-md-9"><h2><b><i>Duplicate Member Info</i></b></h2></div>
        <div class="col-md-3" align="right">
        	<button type="button" id="add_button" class="btn btn-info btn-sm">Add</button>
        </div>
      </div>
    </div>
  	<div class="card-body">
  		<div class="table-responsive">
        	<span id="message_operation"></span>
        	<table class="table table-striped table-bordered" id="dupmember_table">
  				<thead>
  					<tr>
  						<th>Member Name</th>
  						<th>Date Joined</th>
              <th>SVS Code</th>
  						<th>Edit</th>
  						<th>Delete</th>
  					</tr>
  				</thead>
  				<tbody>
          <p><b>You're in the right path!</b> <i>We need to duplicate the member info as we need it for attendance purposes. The site creator apologizes for the inconvenience :(</i></p>
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
    .datepicker {
      z-index: 1600 !important; /* has to be larger than 1050 */
    }
</style>

<div class="modal" id="formModal">
  <div class="modal-dialog">
  	<form method="post" id="dupmember_form">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="modal_title"></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Member Name <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="text" name="dupmember_name" id="dupmember_name" class="form-control" />
                <span id="error_dupmember_name" class="text-danger"></span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Date Joined <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="text" name="dupmember_dob" id="dupmember_dob" class="form-control" />
                <span id="error_dupmember_dob" class="text-danger"></span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">SVS Code <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <select name="dupmember_svs_id" id="dupmember_svs_id" class="form-control">
                  <option value="">Select SVS Code</option>
                  <?php
                  echo load_svs_list($connect);
                  ?>
              </select>
              <span id="error_dupmember_svs_id" class="text-danger"></span>
              </div>
            </div>
          </div>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
        	<input type="hidden" name="dupmember_id" id="dupmember_id" />
        	<input type="hidden" name="action" id="action" value="Add" />
        	<input type="submit" name="button_action" id="button_action" class="btn btn-success btn-sm" value="Add" />
          	<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
        </div>
      </div>
  </form>
  </div>
  </div>
<div class="modal" id="deleteModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Delete Confirmation</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <!-- Modal body -->
      <div class="modal-body">
        <h3 align="center">Are you sure you want to remove this?</h3>
      </div>
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" name="ok_button" id="ok_button" class="btn btn-primary btn-sm">OK</button>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function(){
	
	var dataTable = $('#dupmember_table').DataTable({
		"processing":true,
		"serverSide":true,
		"order":[],
		"ajax":{
			url:"dupmember_action.php",
			method:"POST",
			data:{action:'fetch'},
		}
	});

	$('#dupmember_dob').datepicker({
		format:"yyyy-mm-dd",
		autoclose: true,
        container: '#formModal modal-body'
	});

	function clear_field()
	{
		$('#dupmember_form')[0].reset();
		$('#error_dupmember_name').text('');
		$('#error_dupmember_dob').text('');
		$('#error_dupmember_svs_id').text('');
	}

	$('#add_button').click(function(){
		$('#modal_title').text('Add Member');
		$('#button_action').val('Add');
		$('#action').val('Add');
		$('#formModal').modal('show');
		clear_field();
	});

	$('#dupmember_form').on('submit', function(event){
		event.preventDefault();
		$.ajax({
			url:"dupmember_action.php",
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
					if(data.error_dupmember_name != '')
					{
						$('#error_dupmember_name').text(data.error_dupmember_name);
					}
					else
					{
						$('#error_dupmember_name').text('');
					}
					if(data.error_dupmember_dob != '')
					{
						$('#error_dupmember_dob').text(data.error_dupmember_dob);
					}
					else
					{
						$('#error_dupmember_dob').text('');
					}
					if(data.error_dupmember_svs_id != '')
					{
						$('#error_dupmember_svs_id').text(data.error_dupmember_svs_id);
					}
					else
					{
						$('#error_dupmember_svs_id').text('');
					}
				}
			}
		})
	});

  var dupmember_id = '';

  $(document).on('click', '.edit_dupmember', function(){
    dupmember_id = $(this).attr('id');
    clear_field();
    $.ajax({
      url:"dupmember_action.php",
      method:"POST",
      data:{action:'edit_fetch', dupmember_id:dupmember_id},
      dataType:"json",
      success:function(data)
      {
        $('#dupmember_name').val(data.dupmember_name);
        $('#dupmember_dob').val(data.dupmember_dob);
        $('#dupmember_svs_id').val(data.dupmember_svs_id);
        $('#dupmember_id').val(data.dupmember_id);
        $('#modal_title').text('Edit Student');
        $('#button_action').val('Edit');
        $('#action').val('Edit');
        $('#formModal').modal('show');
      }
    })
  });

  $(document).on('click', '.delete_dupmember', function(){
    dupmember_id = $(this).attr('id');
    $('#deleteModal').modal('show');
  });

  $('#ok_button').click(function(){
    $.ajax({
      url:"dupmember_action.php",
      method:"POST",
      data:{dupmember_id:dupmember_id, action:"delete"},
      success:function(data)
      {
        $('#message_operation').html('<div class="alert alert-success">'+data+'</div>');
        $('#deleteModal').modal('hide');
        dataTable.ajax.reload();
      }
    })
  });

});
</script>