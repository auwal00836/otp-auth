<?php
//fetch.php
require_once "config/dBase.php";

$error = array("message" => "Method Not Allowed");

 if(isset($_POST['otp_generator'])){
  require_once "user.class.php";
  $data = array();


  $database = new dBase();
  //$email = $_POST['email'];
  $email ="afafa";

  $user = new User();
  $requested = $user->insertOtpRequest($email);

  if (!$requested) 
  {
  echo "fail";    
  }
  else
  {
    echo "sss";

  }

 }

  /*while($row = mysqli_fetch_assoc($result)){
    array_push($data, $row);
  }
  echo json_encode($data);
}
else{
  echo json_encode($error);
}*/
?>
