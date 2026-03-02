<?php include('menu_Navbar_user.php');?>
<!DOCTYPE html>
<html lang="en">

<body>
  <!-- ***** Preloader Start ***** -->
  <div id="js-preloader" class="js-preloader">
    <div class="preloader-inner">
      <span class="dot"></span>
      <div class="dots">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </div>
  <div class="main-banner wow fadeIn my-1" id="top" data-wow-duration="1s" data-wow-delay="0.5s">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="row">
            <div class="col-lg-6 align-self-center">
              <div class="left-content show-up header-text wow fadeInLeft" data-wow-duration="1s" data-wow-delay="1s">
                <div class="row">
                  <div class="col-lg-12">
                    <h6>Your Schedule</h6>
                    <h2 style="font-family: 'Kanit', sans-serif;">ยินดีต้อนรับ : <br><?php echo $row['name'] ?></h2>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="right-image wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.5s">
                <img src="assets/images/schedule_index.png" alt="">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </body>
</html>