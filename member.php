<?php

include('admin_header.php');

?>

<div class="container" style="margin-top:30px">
  <div class="card">
  	<div class="card-header">
      <div class="row">
        <div class="col-md-9"><h2><b><i>Add a Regular Member</i></b></h2></div>
        <div class="col-md-3" align="right">
          <button type="button" id="add_button" class="btn btn-info btn-sm">Add</button>
        </div>
      </div>
    </div>
  	<div class="card-body">
  		<div class="table-responsive">
        <span id="message_operation"></span>
  			<table class="table table-striped table-bordered" id="member_table">
  				<thead>
  					<tr>
  						<th>Image</th>
  						<th>Member Name</th>
  						<th>Email Address</th>
              <th>SVS Code</th>
  						<th>View</th>
  						<th>Edit</th>
  						<th>Delete</th>
  					</tr>
  				</thead>
  				<tbody>
          <p><i><b>Kindly duplicate the member that would be added here, in the <a href="dupmember.php">Duplicate Section</a>. Thank you!</b></i></p>
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
    <form method="post" id="member_form" enctype="multipart/form-data">
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
                <input type="text" name="member_name" id="member_name" class="form-control" />
                <span id="error_member_name" class="text-danger"></span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Email Address <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="text" name="member_emailid" id="member_emailid" class="form-control" />
                <span id="error_member_emailid" class="text-danger"></span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Password <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="password" name="member_password" id="member_password" class="form-control" />
                <span id="error_member_password" class="text-danger"></span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">SVS Code<span class="text-danger">*</span></label>
              <div class="col-md-8">
                <select name="member_svs_id" id="member_svs_id" class="form-control">
                  <option value="">Select SVS Code</option>
                  <?php
                  echo load_svs_list($connect);
                  ?>
                </select>
                <span id="error_member_svs_id" class="text-danger"></span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Date Joined <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="text" name="member_doj" id="member_doj" class="form-control" />
                <span id="error_member_doj" class="text-danger"></span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Image <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="file" name="member_image" id="member_image" />
                <span class="text-muted">Only .jpg and .png allowed</span><br />
                <span id="error_member_image" class="text-danger"></span>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="hidden_member_image" id="hidden_member_image" value="" />
          <input type="hidden" name="member_id" id="member_id" />
          <input type="hidden" name="action" id="action" value="Add" />
          <input type="submit" name="button_action" id="button_action" class="btn btn-success btn-sm" value="Add" />
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="modal" id="viewModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Member Details</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="member_details">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="deleteModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Delete Confirmation</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <h3 align="center">Are you sure you want to remove this?</h3>
      </div>
      <div class="modal-footer">
      	<button type="button" name="ok_button" id="ok_button" class="btn btn-primary btn-sm">OK</button>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function(){
	var dataTable = $('#member_table').DataTable({
		"processing":true,
		"serverSide":true,
		"order":[],
		"ajax":{
			url:"member_action.php",
			type:"POST",
			data:{action:'fetch'}
		},
		"columnDefs":[
			{
				"targets":[0, 4, 5, 6],
				"orderable":false,
			},
		],
	});

  $('#member_doj').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        container: '#formModal modal-body'
    });

  function clear_field()
  {
    $('#member_form')[0].reset();
    $('#error_member_name').text('');
    $('#error_member_emailid').text('');
    $('#error_member_password').text('');
    $('#error_member_doj').text('');
    $('#error_member_image').text('');
    $('#error_member_svs_id').text('');
  }

  $('#add_button').click(function(){
    $('#modal_title').text("Add Member");
    $('#button_action').val('Add');
    $('#action').val('Add');
    $('#formModal').modal('show');
    clear_field();
  });

  $('#member_form').on('submit', function(event){
    event.preventDefault();
    $.ajax({
      url:"member_action.php",
      method:"POST",
      data:new FormData(this),
      dataType:"json",
      contentType:false,
      processData:false,
      beforeSend:function()
      {        
        $('#button_action').val('Loading ...');
        $('#button_action').attr('disabled', 'disabled');
      },
      success:function(data){
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
          if(data.error_member_name != '')
          {
            $('#error_member_name').text(data.error_member_name);
          }
          else
          {
            $('#error_member_name').text('');
          }
          if(data.error_member_emailid != '')
          {
            $('#error_member_emailid').text(data.error_member_emailid);
          }
          else
          {
            $('#error_member_emailid').text('');
          }
          if(data.error_member_password != '')
          {
            $('#error_member_password').text(data.error_member_password);
          }
          else
          {
            $('#error_member_password').text('');
          }
          if(data.error_member_svs_id != '')
          {
            $('#error_member_svs_id').text(data.error_member_svs_id);
          }
          else
          {
            $('#error_member_svs_id').text('');
          }
          if(data.error_member_doj != '')
          {
            $('#error_member_doj').text(data.error_member_doj);
          }
          else
          {
            $('#error_member_doj').text('');
          }
          if(data.error_member_image != '')
          {
            $('#error_member_image').text(data.error_member_image);
          }
          else
          {
            $('#error_member_image').text('');
          }
        }
      }
    });
  });

  var member_id = '';

  $(document).on('click', '.view_member', function(){
    member_id = $(this).attr('id');
    $.ajax({
      url:"member_action.php",
      method:"POST",
      data:{action:'single_fetch', member_id:member_id},
      success:function(data)
      {
        $('#viewModal').modal('show');
        $('#member_details').html(data);
      }
    });
  });

  $(document).on('click', '.edit_member', function(){
  	member_id = $(this).attr('id');
  	clear_field();
  	$.ajax({
  		url:"member_action.php",
  		method:"POST",
  		data:{action:'edit_fetch', member_id:member_id},
  		dataType:"json",
  		success:function(data)
  		{
  			$('#member_name').val(data.member_name);
  			$('#member_svs_id').val(data.member_svs_id);
  			$('#member_doj').val(data.member_doj);
  			$('#error_member_image').html('<img src="member_image/'+data.member_image+'" class="img-thumbnail" width="50" />');
  			$('#hidden_member_image').val(data.member_image);
  			$('#member_id').val(data.member_id);
  			$('#modal_title').text('Edit Member');
  			$('#button_action').val('Edit');
  			$('#action').val('Edit');
  			$('#formModal').modal('show');
  		}
  	});
  });

  $(document).on('click', '.delete_member', function(){
  	member_id = $(this).attr('id');
  	$('#deleteModal').modal('show');
  });

  $('#ok_button').click(function(){
  	$.ajax({
  		url:"member_action.php",
  		method:"POST",
  		data:{member_id:member_id, action:'delete'},
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