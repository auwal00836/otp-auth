<?php
$connect = mysqli_connect("localhost", "root", "", "otp_system");
//fetch.php
// require_once "config/dBase.php";

function getTimeDifference($time){
	$now = time('now');
	$difference = $now - $time;

	return $difference;
}

function isUserRegistered($email, $dbconn){
	$query = "SELECT * FROM users WHERE email='$email'";
  $result = $dbconn->query($query);
  $status = mysqli_num_rows($result) > 0 ? true : false;
  return $status;
}

function hasExpired($email,$dbconn){
	$query = "SELECT * FROM otp_request WHERE email='email'";
  $result = mysqli_query($dbconn, $query);
	return false;
}

function hasExistingRequest($email, $dbconn){
	$query = "SELECT * FROM otp_request WHERE email='$email'";
  $result = $dbconn->query($query);
  $status = mysqli_num_rows($result) > 0 ? true : false;
  return $status;
}

function requestOTP($email, $now, $dbconn){
	$res = [];
	$hasExistingRequest = hasExistingRequest($email, $dbconn);
	$isUserRegistered = isUserRegistered($email, $dbconn);
	
	if(!$isUserRegistered){
		$error = array(
			'success' => False,
			'message' => 'User Not Found.' 
		);
  	array_push($res, $error);
		return $res;
	}
	
	if($hasExistingRequest){
		$error = array(
			'success' => False,
			'message' => 'Pending Request' 
		);
  	array_push($res, $error);
		return $res;
	}
  $query = "INSERT INTO otp_request(email, generated_time) VALUES('$email','$now')";
  $result = mysqli_query($dbconn, $query);

  if($result){
  	$data = array(
  		'success' => True,
  		'generatedTime' => $now
  	);
  	array_push($res, $data);
  	return $res;
  }
}

$data = array();


$error = array("message" => "Method Not Allowed");

if(isset($_POST['email'])){
	$email = $_POST['email'];
  $data = array();
  $makeRequest = requestOTP($email, time(), $connect);
  echo json_encode($makeRequest);
  // while($row = mysqli_fetch_assoc($result)){
  //   array_push($data, $row);
  // }
  // echo json_encode($makeRequest);
}
else{
  echo json_encode($error);
}



/*$error = array("message" => "Method Not Allowed");

 
require_once "user.class.php";
$data = array();


$database = new dBase();
$email = $_POST['email'];


echo $email;*/

// echo json_encode($_POST['email']);

  /*while($row = mysqli_fetch_assoc($result)){
    array_push($data, $row);
  }
  echo json_encode($data);
}
else{
  echo json_encode($error);
}*/
?>
