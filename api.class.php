<?php

class Api{

	public function getTimeDifference($time){
		$now = time();
		$difference = $time - $now;
		return $difference;
	}

	public function isUserRegistered($email, $dbconn){
		$query = "SELECT * FROM users WHERE email='$email'";
	  $result = $dbconn->query($query);
	  $status = mysqli_num_rows($result) > 0 ? true : false;
	  return $status;
	}

	public function hasExistingRequest($email, $dbconn){
		$query = "SELECT * FROM otp_request WHERE email='$email'";
	  $result = $dbconn->query($query);
	  $status = mysqli_num_rows($result) > 0 ? true : false;
	  return $status;
	}

	public function hasExpired($otp, $expiryTime){
			$now = time();
			if($now > $expiryTime){
				return true;
			}
		return false;
	}

	public function getOTP($email, $dbconn){
		$query = "SELECT otp FROM otp_request WHERE email='$email'";
	  $result = $dbconn->query($query);
	  $row = mysqli_fetch_assoc($result);
	  return $row['otp'];	
	}

	public function getExpiryTime($email,$otp, $dbconn){
		$query = "SELECT expiry_time FROM otp_request WHERE email='$email' OR otp='$otp'";
	  $result = $dbconn->query($query);
	  $row = mysqli_fetch_assoc($result);
	  return $row['expiry_time'];	
	}


	public function requestOTP($email, $now, $dbconn){
		$hasExistingRequest = $this->hasExistingRequest($email, $dbconn);
		$isUserRegistered = $this->isUserRegistered($email, $dbconn);
		$otp = rand(100000,999999);
		$expiryTime = $now + 120; //10 minutes
	
		if(!$isUserRegistered){
			$error = array(
				'success' => False,
				'message' => 'User Not Found.' 
			);
			return $error;
		}
		
		if($hasExistingRequest){
			$hasExpired = $this->hasExpired($this->getOTP($email,$dbconn), $this->getExpiryTime($email, $this->getOTP($email,$dbconn), $dbconn));
			if(!$hasExpired){
				$error = array(
					'success' => False,
					'message' => 'You have a Pending OTP',
					'otp' =>  $this->getOTP($email, $dbconn),
					'time' => $this->getTimeDifference($this->getExpiryTime($email, $otp, $dbconn))
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
						'otp' => $otp,
						'time' => $this->getTimeDifference($this->getExpiryTime($email,$otp, $dbconn))
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
	  		'otp' => $otp,
				'time' => $this->getTimeDifference($this->getExpiryTime($email,$otp, $dbconn))
	  	);
	  	return $data;
	  }
	}

}
