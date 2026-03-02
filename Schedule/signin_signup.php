<?php include('menu_Navbar_login.php');?>
<!doctype html>
<html lang="en">
<head>
  <title>Login 01</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

  <link rel="stylesheet" href="login_register/css/style.css">

</head>
<body>
  <section class="ftco-section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
          <div class="login-wrap p-4 p-md-5">
            <ul class="nav nav-tabs justify-content mb-4" id="myTab" role="tablist">
              <li class="nav-item" style="font-family: 'Kanit', sans-serif;">
                <a class="nav-link active" id="login-tab" data-toggle="tab" href="#login" role="tab" aria-controls="login" aria-selected="true">เข้าสู่ระบบ</a>
              </li>
              <li class="nav-item" style="font-family: 'Kanit', sans-serif;">
                <a class="nav-link" id="register-tab" data-toggle="tab" href="#register" role="tab" aria-controls="register" aria-selected="false">สมัครสมาชิก</a>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">

              <!-- Login Form -->
              <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
              <div class="icon d-flex align-items-center justify-content-center">
                <span class="fa fa-user-o"></span>
              </div>
                <h3 class="text-center mb-4" style="font-family: 'Kanit', sans-serif;">เข้าสู่ระบบ</h3>
                <form action="insertDatalogin.php"  method="POST" class="login-form">
                  <div class="form-group">
                    <input type="text" class="form-control rounded-left" name="username" placeholder="Username" required>
                  </div>
                  <div class="form-group d-flex">
                    <input type="password" class="form-control rounded-left" name="password" placeholder="Password" required>
                  </div>
                  <div class="form-group">
                    <button type="submit" class="form-control btn btn-primary rounded submit px-3" style="font-family: 'Kanit', sans-serif;">เข้าสู่ระบบ</button>
                  </div>
                  <!-- <div class="form-group d-md-flex">
                    <div class="w-50">
                      <label class="checkbox-wrap checkbox-primary">Remember Me
                        <input type="checkbox" checked>
                        <span class="checkmark"></span>
                      </label>
                    </div>
                    <div class="w-50 text-md-right">
                      <a href="#">Forgot Password</a>
                    </div>
                  </div> -->
                </form>
              </div>
              
              <!-- Registration Form -->
              <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
              <div class="icon d-flex align-items-center justify-content-center">
                <span class="fa fa-user-o"></span>
              </div>
                <h3 class="text-center mb-4 " style="font-family: 'Kanit', sans-serif;">สมัครสมาชิก</h3>
                <form action="insertDataregister.php" method="POST" class="login-form">
                  <div class="form-group">
                    <input type="text" class="form-control rounded-left" name="name" placeholder="Name" required>
                  </div>
                  <div class="form-group">
                    <input type="text" class="form-control rounded-left" name="username" placeholder="Username" required>
                  </div>
                  <div class="form-group d-flex">
                    <input type="password" class="form-control rounded-left" name="pwd" placeholder="Password" required>
                  </div>
                  <div class="form-group d-flex">
                    <input type="Email" class="form-control rounded-left" name="email" placeholder="Email" required>
                  </div>
                  <div class="form-group">
                    <button type="submit" class="form-control btn btn-primary rounded submit px-3" style="font-family: 'Kanit', sans-serif;">สมัครสมาชิก</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script src="login_register/js/jquery.min.js"></script>
  <script src="login_register/js/popper.js"></script>
  <script src="login_register/js/bootstrap.min.js"></script>
  <script src="login_register/js/main.js"></script>
  
</body>
</html>

