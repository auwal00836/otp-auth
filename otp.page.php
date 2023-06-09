<?php 
session_start();
$connect = mysqli_connect("localhost", "root", "", "otp_system");

  $msg = '';
  $msg_type = '';
  if (isset($_POST['login'])) 
  {

    require_once "user.class.php";
    require_once "api.class.php";

    // validate the user's inputs
    $otp = $_POST['otp'];
  	$email = $_SESSION['email'];
    $user = new User();
    $api = new Api();

    $hasExpired = $api->hasExpired($otp, $api->getExpiryTime($email, $api->getOTP($email,$connect), $connect));
    $isValidOTP = $api->isValidOTP($email, $otp, $connect);

    if(!$hasExpired && $isValidOTP){
    	echo "True";
    }
    else{
    	echo "False";
    }
    // if () 
    // {

    //   $msg_type = 'danger';
    //   $msg = 'Failed to login.';
      
    // }
    // else
    // {

    //   $msg_type = 'success';
    //   $msg = 'Login successfully.';
    //   header("Location: dashboard.php" );

    // }
    
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Otp System</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="template/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="template/vendors/base/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="template/css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="img/logo.jpg" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
               <center><img src="img/logo.jpg" alt="logo" height="120" style=" width:300px; border-radius: 30px;"></center>
              </div>
    
              <h6 class="font-weight-light" style="text-align: center;">Enter OTP generated in your OTP Generator.</h6>
              <div class="alert alert-<?php echo $msg_type; ?>">
                <?php echo $msg; ?>
              </div>
              <form class="pt-3" method="POST" action="">
                <div class="form-group">
                  <input type="text" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Enter OTP" name="otp" required>
                </div>
                 <div class="mt-3">
                  <button type="submit" class="btn btn-block btn-primary btn-md font-weight-medium auth-form-btn" name="login">Authenticate</button>
                </div>
                <div class="text-center mt-4 font-weight-light">
                  Request OTP <a href="otp.generator.php" target="_blank" class="text-primary">HERE</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="../template/vendors/base/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="../template/js/off-canvas.js"></script>
  <script src="../template/js/hoverable-collapse.js"></script>
  <script src="../template/js/template.js"></script>
  <!-- endinject -->
</body>

</html>
