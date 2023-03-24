<?php session_start(); ?>
<?php if(isset($_SESSION['email'])): ?>
  <?php
     $msg = '';
     $msg_type='';
     // unset($_SESSION['email']);
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
   <script src="js/jquery.min.js" type="text/javascript"></script>
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
      
                <h6 class="font-weight-light">Provide Your Email for verification.</h6>
                <div class="alert d-none" id="otp-container">
                	<center>
                		<h1 id="otp"></h1>
                	</center>
                </div>
                <br>
                <center>
                	<h1 id="countDown" style="font-size: 50px; transform:scaleY(3); transform:scaleX(2)"></h1>
                </center>
                <form class="pt-3" id="otp_generator">
                  <div class="form-group">
                    <input type="email" class="form-control form-control-lg" id="email" placeholder="Enter Your Email.." name="email" required>
                  </div>
                   <div class="mt-3">
                    <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" name="otp_generator" id="otp_generator">Generate Otp</button>
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
    
    <script type="text/javascript">
      var emailElm = document.getElementById("email");
      $(document).ready(function (){
        $("#otp_generator").on("submit", function(e) {
          e.preventDefault();
          emailElm.style.display = "none";
          let email = emailElm.value;
          // if(email === )
          $.ajax({
          	url: 'api.request.php',
          	method: 'POST',
          	data: {email: email},
          	dataType: 'json',
          	success: function(res){
          		console.log(res)
          		if(res.success === true){
          			document.getElementById('otp-container').classList.add('alert-success')
          			document.getElementById('otp-container').classList.remove('d-none')
          			document.getElementById('otp').innerHTML = res.otp

          			document.getElementById('otp_generator').style.display = "none";
          			document.getElementById('otp').style.fontSize = 40;
          			countdownTimer(res.time);
          		}
          		else if(res.success === false && res.otp)
          		{
          			document.getElementById('otp-container').classList.add('alert-warning')
          			document.getElementById('otp-container').classList.remove('d-none')
          			document.getElementById('otp').innerHTML = res.otp
          			document.getElementById('otp_generator').style.display = "none";
          			document.getElementById('otp').style.fontSize = 40;
          			countdownTimer(res.time);
          		}
          		else
          		{
          			document.getElementById('otp-container').classList.add('alert-danger')
          			document.getElementById('otp-container').classList.remove('d-none')
          			document.getElementById('otp').innerHTML = `${res.message}`	
          			document.getElementById('otp_generator').style.display = "none";
          		}
          	},
          	error: function(error){
          		console.log(error)
          	}
          });

        });
      })

      function countdownTimer(timeInSeconds) {
        let timer = setInterval(function() {
          // Calculate remaining time
          let minutes = Math.floor(timeInSeconds / 60);
          let seconds = timeInSeconds % 60;
          
          // Display countdown timer
          console.log(`${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`);
          document.getElementById('countDown').innerHTML = timeInSeconds

          // Decrement timeInSeconds
          timeInSeconds--;

          // Clear the timer when the countdown is finished
          if (timeInSeconds < 0) {
            clearInterval(timer);
          }
        }, 1000);
      }

    </script>
    
  </body>

  </html>
<?php else: ?>
  <? header("Location: login.php"); ?>
<?php endif; ?>