<?php
/**
 * Created by PhpStorm.
 * User: McMillan
 * Date: 05-Oct-16
 * Time: 3:07 PM
 *
 *
 *
 *
 * 	$pRegister->register(email,fname,sname,password="")
 *
 */


define('LOGIN_BAD_PASSWORD',			-2);
define('LOGIN_BAD',						-1);
define('LOGIN_NOT_VERIFIED',			0);
define('LOGIN_OK',						1);

define('PASSWORD_PERSON_NOT_FOUND',		-3);
define('PASSWORD_EMPTY',				-2);
define('PASSWORD_MISMATCH',				-1);
define('PASSWORD_OK',					1);

define('REGISTER_ID_EMAIL_INVALID',					-3);
define('REGISTER_NOT_ALLOWED',						-2);
define('REGISTER_OK',								0);
define('REGISTER_ACCOUNT_EXISTS',					1);
define('REGISTER_EXISTS_IN_PROCLAIM_DATABSE',		2);
define('REGISTER_USER_NOT_IN_PROCLAIM_DATABSE',		3);


define('REGISTRATION_ACTIVATION_ALREADY',			1);
define('REGISTRATION_ACTIVATION_INVALID',			2);
define('REGISTRATION_ACTIVATION_OK',				3);

define('LOST_LINK_OK',								0);
define('LOST_LINK_EXPIRED',							1);


class Registration_Class
{

	protected	$session_id			=	"";
	public		$logged_in_url		=	"";
	public		$logged_out_url		=	"";
	public		$login_url			=	"";
	public		$registered_url		=	"";


	protected	$from_email	=	"noreply@fairplane.co.uk";
	protected	$login_link	=	"https://www.fairplane.co.uk/login";

	protected $formName;
	protected $formEmail;
	protected $formPassword;
	protected $formFairplaneID;
	protected $data;


	public function __construct($params=array())
	{
		$this->data				=	"";
		$this->session_id		=	(isset($params['session_id']))		?	$params['session_id']		:	"";
		$this->table			=	(isset($params['table']))			?	$params['table']			:	"";
		$this->logged_out_url	=	(isset($params['logged_out_url']))	?	$params['logged_out_url']	:	"";
		$this->logged_in_url	=	(isset($params['logged_in_url']))	?	$params['logged_in_url']	:	"";
		$this->login_url		=	(isset($params['login_url']))		?	$params['login_url']		:	"";
		$this->registered_url	=	(isset($params['registered_url']))	?	$params['registered_url']	:	"";

		if(session_id() == '') {
			session_start();
		}
	}

	# grab the data
	public function getData()
	{
		return	$this->data;
	}



	# creates a new activation code and sends activation email
	public function create_activation_code($email)
	{
		global $gMysql;

		if (($data		=	$gMysql->queryRow("select * from fp_client where email='$email' ",__FILE__,__LINE__)) != NULL)
		{
			$forename	=	$data['forename'];
			$surname	=	$data['surname'];

			# activation code
			$activation_code	=	getToken(10);

			# update the activation code
			$gMysql->update("update fp_client set activation_code='$activation_code', activation_code_created=NOW() where email='$email' ",__FILE__,__LINE__);

			# we send details about validating email
			$to_email_array	=	array($email);
			$this->send_activation($forename,$activation_code,$to_email_array);

			return true;
		}
	}

	# register for fairplane - we need to make sure this FairPlaneID exists in DBASE
	public function register_fairplane_customer($email,$password)
	{
		global $gMysql;

		# grab the data from the storage (case_key should change soon to something else)
		$data		=	$gMysql->queryRow("select * from fp_flight_master_db_flight_info where email='$email'",__FILE__,__LINE__);
		$title		=	$data['title'];
		$forename	=	$data['forename'];
		$surname	=	$data['surname'];
		$phone		=	$data['phone'];

		# insert the record, but we need to validate the email, so
		$this->insert_customer_record($email,$title,$forename,$surname,$phone,$password);

		$this->create_activation_code($email);

		return	REGISTER_OK;
	}





	# validation of customer - checks that the customer details exist in the proclaim data or if they are already registered
	public function validate_customer_details($email)
	{
		global	$gMysql;

		# does this user exist in the login section
		if	(($data	=	$gMysql->queryRow("select * from fp_client where email='$email'",__FILE__,__LINE__)) != NULL)
		{
			return REGISTER_ACCOUNT_EXISTS;
		}

		# does this claim exist in the dbase (the case_key can change eventually to a proclaim_id)
		else if	(($data	=	$gMysql->queryRow("select * from fp_flight_master_db_flight_info where email='$email'",__FILE__,__LINE__)) != NULL)
		{
			return REGISTER_EXISTS_IN_PROCLAIM_DATABSE;

		}

		# does not exist in the databases, perhaps try later in 24hrs when the proclaim dbase has uploaded
		return REGISTER_USER_NOT_IN_PROCLAIM_DATABSE;

	}





	# inserts a record after validation
	private function insert_customer_record($email,$title,$forename,$surname,$phone,$password)
	{
		global	$gMysql;

		# creates a password hash that we don't know
		$safe_password		=	$this->createSafePassword($password);

		$strSQL	=	"INSERT into fp_client (id, email, title, password, forename, surname, phone, date_created)
		values (0,
		'" . $email ."',
		'" . $title ."',
		'" . $safe_password ."',
		'" . $forename."',
		'" . $surname."',
		'" . $phone."',
		Now())";


		$gMysql->insert($strSQL,__FILE__,__LINE__);


	}



	# activation details
	private function send_activation($forename,$activation_code,$to_array)
	{
		$name			=	'FairPlane UK';
		$from_email		=	$this->from_email;
		$subject		=	'Activate your FairPlane Account';
		$message		=	"Hello ". $forename ."<br><br>";
		$message		.=	"You need to click below to activate your account. Your activation link will only be valid for 24 hours. If you do not activate your account within this time, you will need to request another activation code on our website.<br><br>";
		$message		.=	"Activation Link: ".	$_SERVER['HTTP_HOST'] .	"/activate/?code=$activation_code<br><br><br>";

		$this->emailDetails($name,$from_email,$subject,$message,$to_array);

	}





	# requests a new password
	public function request_new_password($email)
	{
		global	$gMysql;
		$returnCode		=	PASSWORD_PERSON_NOT_FOUND;

		# grab user data
		if	(($data	=	$gMysql->queryRow("select * from fp_client where email='$email'",__FILE__,__LINE__)))
		{
			# creates a password hash that we don't know
			$email			=	$data['email'];
			$forename		=	$data['forename'];
			$password_token		=	getToken(16);

			$strSQL			=	"UPDATE fp_client set password_token='$password_token',password_token_time=now() where email='$email' ";
			$gMysql->update($strSQL,__FILE__,__LINE__);

			# we send details
			$to_email_array	=	array($email);
#			$to_email_array	=	array("cedric@boxlegal.co.uk");

			$this->send_new_password($forename,$password_token,$to_email_array);

			$returnCode		=	PASSWORD_OK;

		}

		return  $returnCode;
	}




	# activation details
	private function send_new_password($forename,$password_token,$to_array)
	{
		$name			=	'FairPlane UK';
		$from_email		=	$this->from_email;
		$subject		=	'Request to reset your FairPlane.co.uk password';
		$message		=	"Hello ". $forename ."<br><br>";
		$message		.=	"To securely choose a new password and sign into your account, click the link below and follow the instructions.<br><br>";
		$message		.=	"<a href='". $_SERVER['HTTP_HOST'] .	"/change-password/?hash=". $password_token ."'>Set new password</a> <br><br>";
		$message		.=	"For security reasons, this link will only be valid for a limited time.<br><br>";

		$this->emailDetails($name,$from_email,$subject,$message,$to_array);

	}








	# checks the code - shows an error message or
	public function activate_customer($activation_code)
	{
		global	$gMysql;

		if	(($data	=	$gMysql->queryRow("SELECT * from fp_client where activation_code='$activation_code'",__FILE__,__LINE__,MYSQL_CACHE_TIME_NORMAL)) != NULL)
		{
			$email				=	$data['email'];
			$activated			=	$data['activated'];

			if	($activated)
			{
				return REGISTRATION_ACTIVATION_ALREADY;
			}

			$gMysql->update("update fp_client set activated=1 where activation_code='$activation_code'",__FILE__,__LINE__,MYSQL_CACHE_TIME_NORMAL);

		}
		else
		{
			return REGISTRATION_ACTIVATION_INVALID;
		}

		return REGISTRATION_ACTIVATION_OK;

	}





	# checks the code - for a new password creation
	public function check_password_hash($password_hash)
	{
		global	$gMysql;

		if	(($data	=	$gMysql->queryRow("SELECT * from fp_client where password_token='$password_hash' and password_token_time > DATE_SUB(NOW(),INTERVAL 400 MINUTE)",__FILE__,__LINE__,MYSQL_CACHE_TIME_NORMAL)) != NULL)
		{
			return LOST_LINK_OK;
		}

		return LOST_LINK_EXPIRED;

	}


	# checks the code - for a new password creation
	public function set_new_password($password_hash,$password)
	{
		global	$gMysql;

		if	(($data	=	$gMysql->queryRow("SELECT * from fp_client where password_token='$password_hash' and password_token_time > DATE_SUB(NOW(),INTERVAL 400 MINUTE)",__FILE__,__LINE__,MYSQL_CACHE_TIME_NORMAL)) != NULL)
		{
			$safe_password		=	$this->createSafePassword($password);

			$gMysql->update("update fp_client set password='$safe_password', password_token='' where password_token='$password_hash'",__FILE__,__LINE__,MYSQL_CACHE_TIME_NORMAL);

			return LOST_LINK_OK;
		}

		return LOST_LINK_EXPIRED;

	}


	# checks the code - for a new password creation
	public function set_new_password_via_email($email,$password)
	{
		global	$gMysql;

		$safe_password		=	$this->createSafePassword($password);

		$gMysql->update("update fp_client set password='$safe_password'  where email='$email'",__FILE__,__LINE__,MYSQL_CACHE_TIME_NORMAL);
	}







	# logs in this person or returns error
	public function login_customer($email,$password)
	{
		global	$gMysql;
		$returnCode		=	LOGIN_BAD;

		# grab user data
		if	(($data	=	$gMysql->queryRow("select * from fp_client where email='$email'",__FILE__,__LINE__)))
		{
			# now check the type of password
			if (hash_equals($data['password'], crypt($password, $data['password'])) )
			{
				$gMysql->update("update fp_client set login_count=login_count+1,last_login=now() where email='$email'",__FILE__,__LINE__);

				$this->update_login_table($email);


				# sets session variables
				$this->setSessionVar("data",		$data);
				ini_set('session.gc_maxlifetime', 	SESSION_TIMEOUT);

				$returnCode	=	LOGIN_OK;
			}
			else
			{
				$returnCode	=	LOGIN_BAD_PASSWORD;
			}

		}

		return  $returnCode;
	}




	# sets session data
	public function setSessionVar($variable_name,$data)
	{
		$_SESSION[$this->session_id][$variable_name]	=	$data;
	}


	# gets session variable
	public function getSessionVar($variable_name="")
	{
		if	(!empty($variable_name))
		{
			if	($this->isSessionVarSet($variable_name) == true)
			{
				return $_SESSION[$this->session_id][$variable_name];
			}
		}
		else
		{
			return $_SESSION[$this->session_id];
		}
	}


	public function isSessionVarSet($variable_name)
	{
		return isset($_SESSION[$this->session_id][$variable_name]);
	}



	# gets session variable
	public function getSessionDataVar($variable_name="")
	{
		if	(!empty($variable_name))
		{
			if	($this->isSessionDataVarSet($variable_name) == true)
			{
				return $_SESSION[$this->session_id]['data'][$variable_name];
			}
		}
		else
		{
			return $_SESSION[$this->session_id]['data'];
		}
	}


	public function isSessionDataVarSet($variable_name)
	{
		return isset($_SESSION[$this->session_id]['data'][$variable_name]);
	}








	# check if I am logged in
	public function isLoggedIn()
	{
		if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > SESSION_TIMEOUT))
		{
			// last request was more than 30 minutes ago
			session_unset();     // unset $_SESSION variable for the run-time
			session_destroy();   // destroy session data in storage
		}
		# we can probably do some keep-alive timeout here
		if	($this->isSessionVarSet("data"))
		{
			$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

			return true;
		}
	}




	# handles the post
	public function loginCheck()
	{
		# check if we are logged in
		if	($this->isLoggedIn() == true)
		{

			$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

			return true;
		}
		else
		{
			gotoURL($this->login_url);
		}
	}






	# handles the post
	public function logout()
	{
		// last request was more than 30 minutes ago
		session_unset();     // unset $_SESSION variable for the run-time
		session_destroy();   // destroy session data in storage

		gotoURL($this->logged_out_url);

	}




	# keeps track of the logins
	public function update_login_table($login,$status="TRUE",$password="")
	{
		global $gMysql;

		$ip		=	$_SERVER['REMOTE_ADDR'];
		$gMysql->insert("insert into fp_flight_login_table (id,login,ip,status,extra,added) values(0,'$login','$ip','$status','$password',NOW()) ",__FILE__,__LINE__);
	}






















	# logs in this person or returns error
	public function login_admin($username,$password)
	{
		# grab user data
		if	($username == SESSION_ADMIN_USER && $password == SESSION_ADMIN_PASS)
		{
			$data	=	array(	"admin" => true, "email" => "claim@fairplane.co.uk" );

			# sets session variables
			$this->setSessionVar("data",		$data);
			ini_set('session.gc_maxlifetime', 	SESSION_TIMEOUT);

			$this->update_login_table($username);

			$returnCode	=	LOGIN_OK;
		}
		else
		{
			$this->update_login_table($username,"FALSE",$password);

			$returnCode	=	LOGIN_BAD;
		}

		return  $returnCode;
	}































































	# creates a safe password
	public static function createSafePassword($password)
	{
		// A higher "cost" is more secure but consumes more processing power
		$cost = 10;
		// Create a random salt
		$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');

		// Prefix information about the hash so PHP knows how to verify it later.
		// "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
		$salt = sprintf("$2a$%02d$", $cost) . $salt;

		// Value:
		// $2a$10$eImiTXuWVxfM37uY4JANjQ==

		// Hash the password with the salt
		$hash = crypt($password, $salt);

		return $hash;
	}







	# sends a message to us about something
	function emailDetails($name,$from_email,$subject,$message,$to_email_array)
	{
		$htmlMessage = '

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>'. $subject . '</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
	<body bgcolor="#ffffff" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px;">
	<table>
    <tbody>
		<tr>
			<td>
				<br>&nbsp;<br>
			</td>
		</tr>


		<tr>
			<td>
				<a href="https://www.fairplane.co.uk/"><img src="https://www.fairplane.co.uk/app/images/headers/head2.png" width="750" height="157" /></a>
			</td>
		</tr>
		<tr>
			<td>
				<p>'. $message .'</p>
			</td>
		</tr>
		<tr>
			<td>
				<img src="https://www.fairplane.co.uk/app/images/email/foot2.png" width="750" height="114" />
			</td>
		</tr>
	</table>



    </tbody>
  </table>

	</body>
	</html>
	';

		# sends the messages
		foreach ($to_email_array as $to_email)
		{
			if (strpos($_SERVER['HTTP_HOST'], "fairplane2") !== false)
			{
				sendEmailLocal($name, $from_email, $to_email, $subject, $htmlMessage, "");
			}
			else
			{
				sendEmailNew($name, $from_email, $to_email, $subject, $htmlMessage, "");
			}

		}
	}




}