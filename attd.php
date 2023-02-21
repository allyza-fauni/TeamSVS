<?php

//index.php

include('admin_header.php');

?>

<html>
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/favicon.png" rel="apple-touch-icon">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <link href="/assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<section id="forms" class="tutorials">
    <div class="container" data-aos="fade-up">
        <header class="section-header">
         <p>SVS ATTENDANCE</p>
         <p>View and Get PDF Report of Members</p>
        </header>
        <div class="row ">
          <div class="col-lg-6 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="service-box purple">
              <i class="ri-team-fill icon"></i>
              <h3>Regular Members</h3>
              <a href="admin_attendance.php" class="read-more"><span>Click Here</span> <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
          <div class="col-lg-6 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="service-box purple">
              <i class="ri-team-fill icon"></i>
              <h3>Makers Members</h3>
              <a href="admin_attendance_priv.php" class="read-more"><span>Click Here</span> <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
        </div>
    </div>
</section>
</html>