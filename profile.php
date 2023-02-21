<?php

//profile.php

include('header.php');

$member_name = '';
$member_emailid = '';
$member_password = '';
$member_doj = '';
$member_image = '';
$error_member_name = '';
$error_member_emailid = '';
$error_member_doj = '';
$error_member_image = '';
$error = 0;
$success = '';

if(isset($_POST["button_action"]))
{
	$member_image = $_POST["hidden_member_image"];
	if($_FILES["member_image"]["name"] != '')
	{
		$file_name = $_FILES["member_image"]["name"];
		$tmp_name = $_FILES["member_image"]["tmp_name"];
		$extension_array = explode(".", $file_name);
		$extension = strtolower($extension_array[1]);
		$allowed_extension = array('jpg','png');
		if(!in_array($extension, $allowed_extension))
		{
			$error_member_image = "Invalid Image Format";
			$error++;
		}
		else
		{
			$member_image = uniqid() . '.' . $extension;
			$upload_path = 'member_image/' . $member_image;
			move_uploaded_file($tmp_name, $upload_path);
		}
	}

	if(empty($_POST["member_name"]))
	{
		$error_member_name = "Member Name is required";
		$error++;
	}
	else
	{
		$member_name = $_POST["member_name"];
	}

	if(empty($_POST["member_emailid"]))
	{
		$error_member_emailid = "Email Address is required";
		$error++;
	}
	else
	{
		if(!filter_var($_POST["member_emailid"], FILTER_VALIDATE_EMAIL))
		{
			$error_member_emailid = "Invalid email format";
			$error;
		}
		else
		{
			$member_emailid = $_POST["member_emailid"];
		}
	}
	if(!empty($_POST["member_password"]))
	{
		$member_password = $_POST["member_password"];
	}


	if(empty($_POST["member_doj"]))
	{
		$error_member_doj = "Date Joined Field is required";
		$error++;
	}
	else
	{
		$member_doj = $_POST["member_doj"];
	}

	if($error == 0)
	{
		if($member_password != '')
		{
			$data = array(
				':member_name'			=>	$member_name,
				':member_emailid'		=>	$member_emailid,
				':member_password'		=>	password_hash($member_password, PASSWORD_DEFAULT),
				':member_doj'			=>	$member_doj,
				':member_image'		=>	$member_image,
				':member_id'			=>	$_POST["member_id"]
			);
			$query = "
			UPDATE tbl_member 
		      SET member_name = :member_name, 
		      member_emailid = :member_emailid, 
		      member_password = :member_password, 
		      member_doj = :member_doj, 
		      member_image = :member_image 
		      WHERE member_id = :member_id
			";
		}
		else
		{
			$data = array(
				':member_name'			=>	$member_name,
				':member_emailid'		=>	$member_emailid,
				':member_doj'			=>	$member_doj,
				':member_image'		=>	$member_image,
				':member_id'			=>	$_POST["member_id"]
			);
			$query = "
			UPDATE tbl_member 
		      SET member_name = :member_name, 
		      member_emailid = :member_emailid, 
		      member_doj = :member_doj, 
		      member_image = :member_image 
		      WHERE member_id = :member_id
			";
		}

		$statement = $connect->prepare($query);
		if($statement->execute($data))
		{
			$success = '<div class="alert alert-success">Profile Details Changed Successfully</div>';
		}
	}
}


$query = "
SELECT * FROM tbl_member 
WHERE member_id = '".$_SESSION["member_id"]."'
";

$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();

?>
<!DOCTYPE html>
<html>
<body>
<div class="container" style="margin-top:30px">
  <span><?php echo $success; ?></span>
  <div class="card">
    <form method="post" id="profile_form" enctype="multipart/form-data">
		<div class="card-header">
			<div class="row">
				<div class="col-md-9"><h2><b><i>Member Profile</i></b></h2></div>
				<div class="col-md-3" align="right">
				</div>
			</div>
		</div>
		<div class="card-body">
			<div class="form-group">
				<div class="row">
					<p><i><b> Warning: Member Name and Email Address cannot be changed, should you wish to change it, kindly contact the admins directly, thank you.</b></i></p><br><br>
					<label class="col-md-4 text-right">Member Name</label>
					<div class="col-md-8">
						<input type="text" name="member_name" id="member_name" class="form-control" readonly />
						<span class="text-danger"><?php echo $error_member_name; ?></span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<label class="col-md-4 text-right">Email Address</label>
					<div class="col-md-8">
						<input type="text" name="member_emailid" id="member_emailid" class="form-control" readonly />
						<span class="text-danger"><?php echo $error_member_emailid; ?></span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<label class="col-md-4 text-right">Password <span class="text-danger">*</span></label>
					<div class="col-md-8">
						<input type="password" name="member_password" id="member_password" class="form-control" placeholder="Leave blank to not change it" />
						<span class="text-danger"></span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<label class="col-md-4 text-right">Date Joined <span class="text-danger">*</span></label>
					<div class="col-md-8">
						<input type="text" name="member_doj" id="member_doj" class="form-control" />
						<span class="text-danger"><?php echo $error_member_doj; ?></span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<label class="col-md-4 text-right">Profile ID <span class="text-danger">*</span></label>
					<div class="col-md-8">
						<input type="file" name="member_image" id="member_image" />
						<span class="text-muted">Only .jpg and .png allowed</span><br />
						<span id="error_member_image" class="text-danger"><?php echo $error_member_image; ?></span>
					</div>
				</div>
			</div>
		</div>
		<div class="card-footer" align="center">
			<input type="hidden" name="hidden_member_image" id="hidden_member_image" />
			<input type="hidden" name="member_id" id="member_id" />
			<input type="submit" name="button_action" id="button_action" class="btn btn-success btn-sm" value="Save" />
		</div>     
    </form>
  </div>
</div>
<br />
<br />
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

<script>
$(document).ready(function(){
	
<?php
foreach($result as $row)
{
?>
$('#member_name').val("<?php echo $row["member_name"]; ?>");
$('#member_emailid').val("<?php echo $row["member_emailid"]; ?>");
$('#member_svs_id').val("<?php echo $row["member_svs_id"]; ?>");
$('#member_doj').val("<?php echo $row["member_doj"]; ?>");
$('#error_member_image').html("<img src='member_image/<?php echo $row['member_image']; ?>' class='img-thumbnail' width='100' />");
$('#hidden_member_image').val('<?php echo $row["member_image"]; ?>');
$('#member_id').val("<?php echo $row["member_id"];?>");

<?php
}
?>
  
  	$('#member_doj').datepicker({
  		format: "yyyy-mm-dd",
    	autoclose: true
  	});

});
</script>