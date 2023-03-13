
<?php 
	
	require_once "config/dBase.php";
	
	

	class User
	{


		private $fullname;
		private $email;
		private $phone;
		private $password;
		private $data;

		private $database;

		public function register($fullname, $email,$phone, $password)
		{

			$database = new dBase();


			$this->fullname = $fullname;
			$this->email = $email;
			$this->phone = $phone;
			$this->password = $password;

			// check whether the student's record exists in the db.
			if ($this->userExists($this->phone, $this->email)) 
			{
				return false;
			}
			else
			{
					
				$rowsAffected = $database->nonQuery("INSERT INTO `users`(`fullname`, `email`, `phone`, `password`) VALUES(?, ?, ?, ?) ", [$this->fullname, $this->email,$this->phone, $this->password]);

				return $rowsAffected > 0 ? true : false;

			}

		}

		public function data($email = null){
			$data = $this->database->query("SELECT * FROM `users` 
				WHERE `email` = ? LIMIT 1", [$email]);
			return count($data) > 0 ? $data[0] : false;
		}

		public function userExists($email, $phone=null)
		{

			$this->database = new dBase();
			
			$matchingRecordsCount = $this->database->nonQuery("SELECT * FROM `users` 
				WHERE `phone` = ? OR `email` = ? ", [$phone,$email]);
			return $matchingRecordsCount > 0 ? true : false;

		}
		
		public function login($email = null, $password = null){
			$user = $this->userExists($email=$email);
            $data = $this->data($email);

			if ($user && $password === $data['password']) {
                return true;
            }

			return false;
		}
	}//end class




 ?>