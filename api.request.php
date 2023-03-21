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

function hasExistingRequest($email, $dbconn){
	$query = "SELECT * FROM otp_request WHERE email='$email'";
  $result = $dbconn->query($query);
  $status = mysqli_num_rows($result) > 0 ? true : false;
  return $status;
}

function hasExpired($otp, $expiryTime){
		$now = time();
		if($now > $expiryTime){
			return true;
		}
		return false;
}

function getOTP($email, $dbconn){
	$query = "SELECT otp FROM otp_request WHERE email='$email'";
  $result = $dbconn->query($query);
  $row = mysqli_fetch_assoc($result);
  return $row['otp'];	
}

function getExpiryTime($email,$otp, $dbconn){
	$query = "SELECT expiry_time FROM otp_request WHERE email='$email' AND otp='$otp'";
  $result = $dbconn->query($query);
  $row = mysqli_fetch_assoc($result);
  return $row['expiry_time'];	
}


function requestOTP($email, $now, $dbconn){
	$hasExistingRequest = hasExistingRequest($email, $dbconn);
	$isUserRegistered = isUserRegistered($email, $dbconn);
	$otp = rand(100000,999999);
	$expiryTime = $now + 60; //10 minutes
	
	if(!$isUserRegistered){
		$error = array(
			'success' => False,
			'message' => 'User Not Found.' 
		);
		return $error;
	}
	
	if($hasExistingRequest){
		$hasExpired = hasExpired(getOTP($email,$dbconn), getExpiryTime($email, getOTP($email,$dbconn), $dbconn));
		if(!$hasExpired){
			$error = array(
				'success' => False,
				'message' => 'You have a Pending OTP',
				'otp' =>  getOTP($email, $dbconn)
			);
			return $error;	
		}
		else{
			$query = "UPDATE otp_request SET otp='$otp', generated_time='$now', expiry_time='$expiryTime' WHERE email='$email'";
			$result = mysqli_query($dbconn, $query);

			if($result){
				$data = array(
					'success' => True,
					'generatedTime' => $now,
					'otp' => $otp
				);
				return $data;
			}
		}
	}
  $query = "INSERT INTO otp_request(email, otp, generated_time, expiry_time) VALUES('$email','$otp','$now','$expiryTime')";
  $result = mysqli_query($dbconn, $query);

  if($result){
  	$data = array(
  		'success' => True,
  		'generatedTime' => $now,
  		'otp' => $otp
  	);
  	return $data;
  }
}

$data = array();


$error = array("message" => "Method Not Allowed");

if(isset($_POST['email'])){
	$email = $_POST['email'];
  $data = array();
  $makeRequest = requestOTP($email, time(), $connect);
  // $hasExistingRequest = hasExistingRequest($email, $connect);
  // echo json_encode($hasExistingRequest);
  echo json_encode($makeRequest);
  // echo json_encode(getOTP($email, $connect));
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
