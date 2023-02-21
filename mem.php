<?php

//index.php

include('admin_header.php');

?>

<html>
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
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
         <p>REGULAR MEMBER</p>
        </header>
        <div class="row gy-4 justify-content-center">
            <p>&emsp; <b>REMINDER: Admins MUST ADD and then DUPLICATE NEWLY ADDED members. Admins can EDIT and DELETE member's information.</b></p>
        </div>
        <div class="row ">
          <div class="col-lg-6 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="service-box purple">
              <i class="ri-team-fill icon"></i>
              <h3>ADD a Regular Member</h3>
              <a href="member.php" class="read-more"><span>Click Here</span> <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
          <div class="col-lg-6 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="service-box purple">
              <i class="ri-team-fill icon"></i>
              <h3>DUPLICATE the Regular Member</h3>
              <a href="dupmember.php" class="read-more"><span>Click Here</span> <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
        </div>

        <header class="section-header">
         <p><br><br><br>MAKERS</p>
        </header>
        <div class="row gy-4 justify-content-center">
            <p>&emsp; <b>REMINDER: Admins MUST ADD and then DUPLICATE NEWLY ADDED members. Admins can EDIT and DELETE member's information.</b></p>
        </div>
        <div class="row ">
          <div class="col-lg-6 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="service-box purple">
              <i class="ri-team-fill icon"></i>
              <h3>ADD a Maker Member</h3>
              <a href="member_priv.php" class="read-more"><span>Click Here</span> <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
          <div class="col-lg-6 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="service-box purple">
              <i class="ri-team-fill icon"></i>
              <h3>DUPLICATE the Maker Member</h3>
              <a href="dupmember_priv.php" class="read-more"><span>Click Here</span> <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
        </div>

        <header class="section-header">
         <p><br><br><br>SVS CODE</p>
        </header>
        <div class="row ">
          <div class="col-lg-12 col-md-12" data-aos="fade-up" data-aos-delay="200">
            <div class="service-box purple">
              <i class="ri-team-fill icon"></i>
              <h3>ADD/DELETE SVS Code</h3>
              <a href="svs.php" class="read-more"><span>Click Here</span> <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
        </div>
    </div>
</section>
</html>