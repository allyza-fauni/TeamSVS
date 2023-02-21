<?php

//index_priv.php

include('header_priv.php');

?>
<head>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>

  <section id="hero" class="hero d-flex align-items-center">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 d-flex flex-column justify-content-center">
          <h1>Welcome to your special suite!</h1>
          <h2 data-aos="fade-up" data-aos-delay="400">We are hoping that you enjoy your time here!</h2>
          <h2 data-aos="fade-up" data-aos-delay="400">Message</h2>
          <div data-aos="fade-up" data-aos-delay="600">
            <div class="text-center text-lg-start">
              <a href="attendance_priv.php" target="_blank" class="btn-get-started scrollto d-inline-flex align-items-center justify-content-center align-self-center">
                <span>Check Attendance</span>
                <i class="bi bi-calendar-check"></i>
              </a>
              <a href="attendance_priv_progress.php" target="_blank" class="btn-get-started scrollto d-inline-flex align-items-center justify-content-center align-self-center">
                <span>Check Attendance Progress</span>
                <i class="bi bi-calendar-check"></i>
              </a>
            </div>
          </div>
        </div>
        <div class="col-lg-6 hero-img" data-aos="zoom-out" data-aos-delay="200">
          <div id="slideshow-example" data-component="slideshow">
            <div role="list">
              <div class="slide">
                <img src="img/to.png" alt="">
              </div>
              <div class="slide">
                <img src="img/to1.png" alt="">
              </div>
              <div class="slide">
                <img src="img/to2.png" alt="">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

     <section id="forms" class="tutorials">
      <div class="container" data-aos="fade-up">
        <header class="section-header">
         <p>SVS FORMS</p>
        </header>
        <div class="row ">
        <div class="col-lg-6 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="service-box purple">
              <i class="ri-file-lock-fill icon"></i>
              <h3>Rules and Regulations</h3>
              <a href="rulesreg.html" class="read-more"><span>Click Here</span> <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
          <div class="col-lg-6 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="service-box purple">
              <i class="ri-file-list-3-fill icon"></i>
              <h3>Masterlist</h3>
              <a href="#" class="read-more"><span>Click Here</span> <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
          <div class="col-lg-6 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="service-box purple">
              <i class="ri-file-edit-fill icon"></i>
              <h3>IA Application Form</h3>
              <a href="#" class="read-more"><span>Click Here</span> <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
          <div class="col-lg-6 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="service-box purple">
              <i class="ri-folder-user-fill icon"></i>
              <h3>Makers Form</h3>
              <a href="makers.php" class="read-more"><span>Click Here</span> <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
        </div>
      </div>
    </section>

<style type="text/css">
  [data-component="slideshow"] .slide {
  display: none;
  text-align: center;
}
[data-component="slideshow"] .slide.active {
  display: block;
}
</style>

<script type="text/javascript">
var slideshows = document.querySelectorAll('[data-component="slideshow"]');
slideshows.forEach(initSlideShow);
function initSlideShow(slideshow) {
  var slides = document.querySelectorAll(`#${slideshow.id} [role="list"] .slide`);
  var index = 0, time = 3000;
  slides[index].classList.add('active');
  setInterval( () => {
    slides[index].classList.remove('active');
    index++;
    if (index === slides.length) index = 0;
    slides[index].classList.add('active');
  }, time);
}
</script>