<?php

//login.php

include('database_connection.php');

session_start();

if(isset($_SESSION["memberpriv_id"]))
{
  header('location:index_priv.php');
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>SVS Makers Member Log In</title>
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
  <h1> SVS Makers Entry Space</h1>
</a>
</div>


<div class="container">
  <div class="row">
    <div class="col-md-4">
    </div>
    <div class="col-md-4" style="margin-top:20px;">
      <div class="card">
        <div class="card-header"><h4><i><b>Makers Member Login</b></i></h4></div>
        <div class="card-body">
          <form method="post" id="memberpriv_login_form">
            <div class="form-group">
              <label>Enter Email Address</label>
              <input type="text" name="memberpriv_emailid" id="memberpriv_emailid" class="form-control" />
              <span id="error_memberpriv_emailid" class="text-danger"></span>
            </div>
            <div class="form-group">
              <label>Enter Password</label>
              <input type="password" name="memberpriv_password" id="memberpriv_password" class="form-control" />
              <span id="error_memberpriv_password" class="text-danger"></span>
            </div>
            <div class="form-group">
              <input type="submit" name="memberpriv_login" id="memberpriv_login" class="btn btn-info" value="Login" style="background-color: #383080;" />
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
  $('#memberpriv_login_form').on('submit', function(event){
    event.preventDefault();
    $.ajax({
      url:"check_priv_member_login.php",
      method:"POST",
      data:$(this).serialize(),
      dataType:"json",
      beforeSend:function(){
        $('#memberpriv_login').val('Logging in ...');
        $('#memberpriv_login').attr('disabled','disabled');
      },
      success:function(data)
      {
        if(data.success)
        {
          location.href="index_priv.php";
        }
        if(data.error)
        {
          $('#memberpriv_login').val('Login');
          $('#memberpriv_login').attr('disabled', false);
          if(data.error_memberpriv_emailid != '')
          {
            $('#error_memberpriv_emailid').text(data.error_memberpriv_emailid);
          }
          else
          {
            $('#error_memberpriv_emailid').text('');
          }
          if(data.error_memberpriv_password != '')
          {
            $('#error_memberpriv_password').text(data.error_memberpriv_password);
          }
          else
          {
            $('#error_memberpriv_password').text('');
          }
        }
      }
    })
  });
});
</script>