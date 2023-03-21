<?php
$connect = mysqli_connect("localhost", "root", "", "otp_system");
include "api.class.php";

$data = array();

$api = new Api();

//var_dump($api);
$error = array("message" => "Method Not Allowed");

if(isset($_POST['email'])){
	$email = $_POST['email'];
  $data = array();
  $makeRequest = $api->requestOTP($email, time(), $connect);
  
  echo json_encode($makeRequest);
  
}
else{
  echo json_encode($error);
}

?>
