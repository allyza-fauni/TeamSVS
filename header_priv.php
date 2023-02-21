<?php

//header.php

include('database_connection.php');
session_start();

if(!isset($_SESSION["memberpriv_id"]))
{
  $memberpriv_id = $_SESSION['memberpriv_id'];
  header('location:login_priv.php');
}

?>
<html lang="en">
<head>
  <title>SVS Makers Member Suite</title>
  <link href="assets/img/favicon.png" rel="icon">
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <link href="assets/css/style.css" rel="stylesheet">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <script src="js/jquery.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.dataTables.min.js"></script>
  <script src="js/dataTables.bootstrap4.min.js"></script>
</head>
<body>
<div class="jumbotron-small text-center" style="color: #fff; font-family: Nunito, sans serif; background-color: #383080;">
  <a class="logo align-items-center">
        <img src="assets/img/logo.png" alt="">
  <h1>SVS Makers MEMBER LOUNGE</h1>
</a>
</div>
<nav class="navbar navbar-expand-sm navbar-dark">
  <a class="navbar-brand" href="index_priv.php"><img src="img/home.png"></a>
              <a href="profile_priv.php" target="" class="btn-get-started scrollto d-inline-flex align-items-center justify-content-center align-self-center">
                <span style="color: #383080.#383080;">Profile </span>
                <i class="bi bi-person-fill"></i>
              </a>
              <a href="index_priv.php#forms" target="" class="btn-get-started scrollto d-inline-flex align-items-center justify-content-center align-self-center">
                <span style="color: #383080.#383080;">SVS Forms </span>
                <i class="bi bi-folder-fill"></i>
              </a>
              <a href="logout.php" class="btn-get-started scrollto d-inline-flex align-items-center justify-content-center align-self-center">
                <span style="color: #383080.#383080;">Log Out </span>
                <i class="bi bi-unlock-fill"></i>
              </a>
</nav>
</body>
</html>