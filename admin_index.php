<?php

//admin_index.php

include('admin_header.php');

?>
<head>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <link href="/assets/vendor/aos/aos.css" rel="stylesheet">
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
          <h1>Authorized personnel only here at Back Door! 
          </h1>
          <h2 data-aos="fade-up" data-aos-delay="400">You're doing such a marvelous job, our dearest admin!</h2>
          <h2 data-aos="fade-up" data-aos-delay="400">Message</h2>
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

  var index = 0, time = 2000;
  slides[index].classList.add('active');

  setInterval( () => {
    slides[index].classList.remove('active');
    
    index++;
    if (index === slides.length) index = 0;

    slides[index].classList.add('active');

  }, time);
}
</script> -->