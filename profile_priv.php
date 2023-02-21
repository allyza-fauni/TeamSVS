<?php

//profile.php

include('header_priv.php');

$memberpriv_name = '';
$memberpriv_emailid = '';
$memberpriv_password = '';
$memberpriv_doj = '';
$memberpriv_image = '';
$error_memberpriv_name = '';
$error_memberpriv_emailid = '';
$error_memberpriv_doj = '';
$error_memberpriv_image = '';
$error = 0;
$success = '';

if(isset($_POST["button_action"]))
{
	$memberpriv_image = $_POST["hidden_memberpriv_image"];
	if($_FILES["memberpriv_image"]["name"] != '')
	{
		$file_name = $_FILES["memberpriv_image"]["name"];
		$tmp_name = $_FILES["memberpriv_image"]["tmp_name"];
		$extension_array = explode(".", $file_name);
		$extension = strtolower($extension_array[1]);
		$allowed_extension = array('jpg','png');
		if(!in_array($extension, $allowed_extension))
		{
			$error_memberpriv_image = "Invalid Image Format";
			$error++;
		}
		else
		{
			$memberpriv_image = uniqid() . '.' . $extension;
			$upload_path = 'member_priv_image/' . $memberpriv_image;
			move_uploaded_file($tmp_name, $upload_path);
		}
	}

	if(empty($_POST["memberpriv_name"]))
	{
		$error_memberpriv_name = "Member Name is required";
		$error++;
	}
	else
	{
		$memberpriv_name = $_POST["memberpriv_name"];
	}

	if(empty($_POST["memberpriv_emailid"]))
	{
		$error_memberpriv_emailid = "Email Address is required";
		$error++;
	}
	else
	{
		if(!filter_var($_POST["memberpriv_emailid"], FILTER_VALIDATE_EMAIL))
		{
			$error_memberpriv_emailid = "Invalid email format";
			$error;
		}
		else
		{
			$memberpriv_emailid = $_POST["memberpriv_emailid"];
		}
	}
	if(!empty($_POST["memberpriv_password"]))
	{
		$memberpriv_password = $_POST["memberpriv_password"];
	}


	if(empty($_POST["memberpriv_doj"]))
	{
		$error_memberpriv_doj = "Date Joined Field is required";
		$error++;
	}
	else
	{
		$memberpriv_doj = $_POST["memberpriv_doj"];
	}

	if($error == 0)
	{
		if($memberpriv_password != '')
		{
			$data = array(
				':memberpriv_name'			=>	$memberpriv_name,
				':memberpriv_emailid'		=>	$memberpriv_emailid,
				':memberpriv_password'		=>	password_hash($memberpriv_password, PASSWORD_DEFAULT),
				':memberpriv_doj'			=>	$memberpriv_doj,
				':memberpriv_image'		=>	$memberpriv_image,
				':memberpriv_id'			=>	$_POST["memberpriv_id"]
			);
			$query = "
			UPDATE tbl_memberpriv 
		      SET memberpriv_name = :memberpriv_name, 
		      memberpriv_emailid = :memberpriv_emailid, 
		      memberpriv_password = :memberpriv_password, 
		      memberpriv_doj = :memberpriv_doj, 
		      memberpriv_image = :memberpriv_image 
		      WHERE memberpriv_id = :memberpriv_id
			";
		}
		else
		{
			$data = array(
				':memberpriv_name'			=>	$memberpriv_name,
				':memberpriv_emailid'		=>	$memberpriv_emailid,
				':memberpriv_doj'			=>	$memberpriv_doj,
				':memberpriv_image'		=>	$memberpriv_image,
				':memberpriv_id'			=>	$_POST["memberpriv_id"]
			);
			$query = "
			UPDATE tbl_memberpriv 
		      SET memberpriv_name = :memberpriv_name, 
		      memberpriv_emailid = :memberpriv_emailid, 
		      memberpriv_doj = :memberpriv_doj, 
		      memberpriv_image = :memberpriv_image 
		      WHERE memberpriv_id = :memberpriv_id
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
SELECT * FROM tbl_memberpriv 
WHERE memberpriv_id = '".$_SESSION["memberpriv_id"]."'
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
						<input type="text" name="memberpriv_name" id="memberpriv_name" class="form-control" readonly />
						<span class="text-danger"><?php echo $error_memberpriv_name; ?></span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<label class="col-md-4 text-right">Email Address</label>
					<div class="col-md-8">
						<input type="text" name="memberpriv_emailid" id="memberpriv_emailid" class="form-control" readonly />
						<span class="text-danger"><?php echo $error_memberpriv_emailid; ?></span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<label class="col-md-4 text-right">Password <span class="text-danger">*</span></label>
					<div class="col-md-8">
						<input type="password" name="memberpriv_password" id="memberpriv_password" class="form-control" placeholder="Leave blank to not change it" />
						<span class="text-danger"></span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<label class="col-md-4 text-right">Date Joined <span class="text-danger">*</span></label>
					<div class="col-md-8">
						<input type="text" name="memberpriv_doj" id="memberpriv_doj" class="form-control" />
						<span class="text-danger"><?php echo $error_memberpriv_doj; ?></span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<label class="col-md-4 text-right">Profile IMG <span class="text-danger">*</span></label>
					<div class="col-md-8">
						<input type="file" name="memberpriv_image" id="memberpriv_image" />
						<span class="text-muted">Only .jpg and .png allowed</span><br />
						<span id="error_memberpriv_image" class="text-danger"><?php echo $error_memberpriv_image; ?></span>
					</div>
				</div>
			</div>
		</div>
		<div class="card-footer" align="center">
			<input type="hidden" name="hidden_memberpriv_image" id="hidden_memberpriv_image" />
			<input type="hidden" name="memberpriv_id" id="memberpriv_id" />
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
$('#memberpriv_name').val("<?php echo $row["memberpriv_name"]; ?>");
$('#memberpriv_emailid').val("<?php echo $row["memberpriv_emailid"]; ?>");
$('#memberpriv_svs_id').val("<?php echo $row["memberpriv_svs_id"]; ?>");
$('#memberpriv_doj').val("<?php echo $row["memberpriv_doj"]; ?>");
$('#error_memberpriv_image').html("<img src='member_priv_image/<?php echo $row['memberpriv_image']; ?>' class='img-thumbnail' width='100' />");
$('#hidden_memberpriv_image').val('<?php echo $row["memberpriv_image"]; ?>');
$('#memberpriv_id').val("<?php echo $row["memberpriv_id"];?>");

<?php
}
?>
  	$('#memberpriv_doj').datepicker({
  		format: "yyyy-mm-dd",
    	autoclose: true
  	});

});
</script>