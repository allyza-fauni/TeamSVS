<?php

//login.php

include('database_connection.php');

session_start();

if(isset($_SESSION["member_id"]))
{
  header('location:index.php');
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>SVS Member Log In</title>
  <link href="assets/img/favicon.png" rel="icon">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="assets/css/style.css" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="header container-fluid container-xl text-center" style="color: #fff; font-family: Nunito, sans serif; background-color: #383080;">
  <a class="logo align-items-center">
        <img src="assets/img/logo.png" alt="">
  <h1> SVS Member Entry Space</h1>
</a>
</div>


<div class="container">
  <div class="row">
    <div class="col-md-4">
    </div>
    <div class="col-md-4" style="margin-top:20px;">
      <div class="card">
        <div class="card-header"><h4><i><b>Member Login</b></i></h4></div>
        <div class="card-body">
          <form method="post" id="member_login_form">
            <div class="form-group">
              <label>Enter Email Address</label>
              <input type="text" name="member_emailid" id="member_emailid" class="form-control" />
              <span id="error_member_emailid" class="text-danger"></span>
            </div>
            <div class="form-group">
              <label>Enter Password</label>
              <input type="password" name="member_password" id="member_password" class="form-control" />
              <span id="error_member_password" class="text-danger"></span>
            </div>
            <div class="form-group">
              <input type="submit" name="member_login" id="member_login" class="btn btn-info" value="Login" style="background-color: #383080;" />
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-4">
    </div>
  </div>
</div>
</body>
</html>

<script>
$(document).ready(function(){
  $('#member_login_form').on('submit', function(event){
    event.preventDefault();
    $.ajax({
      url:"check_member_login.php",
      method:"POST",
      data:$(this).serialize(),
      dataType:"json",
      beforeSend:function(){
        $('#member_login').val('Logging in ...');
        $('#member_login').attr('disabled','disabled');
      },
      success:function(data)
      {
        if(data.success)
        {
          location.href="index.php";
        }
        if(data.error)
        {
          $('#member_login').val('Login');
          $('#member_login').attr('disabled', false);
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
        }
      }
    })
  });
});
</script>