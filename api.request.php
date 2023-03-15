<?php
//fetch.php
require_once "config/dBase.php";

$error = array("message" => "Method Not Allowed");

 
  require_once "user.class.php";
  $data = array();


  $database = new dBase();
  $email = $_POST['email'];


  $user = new User();
  //$requested = $user->insertOtpRequest($email);
  $requested = $user->userExistsRequest($email);

  if ($email == $requested) 
  {
  //echo json_encode('success');
    //$time = new date();
    $requested = $user->insertOtpRequest($email);

  }
  else
  {
     echo json_encode('Failed');

  }

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
