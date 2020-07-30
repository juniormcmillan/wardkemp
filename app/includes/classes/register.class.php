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


define('LOGIN_BAD_PASSWORD',		-2);
define('LOGIN_BAD',					-1);
define('LOGIN_NOT_VERIFIED',		0);
define('LOGIN_OK',					1);

define('PASSWORD_PERSON_NOT_FOUND',	-3);
define('PASSWORD_EMPTY',			-2);
define('PASSWORD_MISMATCH',			-1);
define('PASSWORD_OK',				1);



class Register_Class
{
	protected	$from_email	=	"noreply@fairplane.co.uk";
	protected	$login_link	=	"https://test.fairplane.co.uk/login";

	protected $table;
	protected $formName;
	protected $formEmail;
	protected $formPassword;
	protected $formFairplaneID;
	protected $data;


	public function __construct($table="fp_customer")
	{
		$this->table	=	$table;
	}

	# grab the data
	public function getData()
	{
		return	$this->data;
	}

	# register for fairplane
	public function register_fairplane($email,$fname,$lname,$phone)
	{
		# create a password, if none exists
		$password		=	$this->generatePassword();

		# unique password that has not been used before for this email address
		$fairplane_id	=	$this->generateUniqueID(8);


		# test that data is valid and account is not already in the database
		if	($this->validate($email,$fname,$lname,$phone,false) == true)
		{
			if	(LOGIN_AVAILABLE == false)
			{
				return false;
			}

			$this->formName			=	$fname;
			$this->formEmail		=	$email;
			$this->formPassword		=	$password;
			$this->formFairplaneID	=	$fairplane_id;

			$this->insertRecord($email,$fname,$lname,$phone,$password,$fairplane_id);

			# we send details
			$to_email_array	=	array($email,"cedric@boxlegal.co.uk");
			$this->send($fname,$password,$fairplane_id,$to_email_array);

			return	true;
		}
	}



	# logs in this person or returns error
	public function login_fairplane($fairplane_id,$password)
	{
		global	$gMysql;
		$returnCode		=	LOGIN_BAD;

		# grab user data
		if	(($this->data	=	$gMysql->queryRow("select * from " . $this->table	.	" where fairplane_id='$fairplane_id'",__FILE__,__LINE__)))
		{
			# now check the type of password
			if (hash_equals($this->data['password'], crypt($password, $this->data['password'])) )
			{
				$returnCode	=	LOGIN_OK;
			}
			else
			{
				$returnCode	=	LOGIN_BAD_PASSWORD;
			}

		}

		return  $returnCode;
	}




	# requests a new password
	public function request_fairplane_password($fairplane_id)
	{
		global	$gMysql;
		$returnCode		=	PASSWORD_PERSON_NOT_FOUND;

		# grab user data
		if	(($this->data	=	$gMysql->queryRow("select * from " . $this->table	.	" where fairplane_id='$fairplane_id '",__FILE__,__LINE__)))
		{
			# creates a password hash that we don't know
			$email			=	$this->data['email'];
			$fname			=	$this->data['fname'];
			$password		=	$this->generatePassword();
			$safe_password	=	$this->createSafePassword($password);

			$strSQL			=	"UPDATE $this->table set password='$safe_password',password_plaintext='$password' where fairplane_id='$fairplane_id' ";

			# older version has levels too
			$gMysql->update($strSQL,__FILE__,__LINE__);

			# we send details
			$to_email_array	=	array($email,"cedric@boxlegal.co.uk");
			$this->send($fname,$password,$fairplane_id,$to_email_array);

			$returnCode		=	PASSWORD_OK;

		}

		return  $returnCode;
	}






	# validation and a test for unique emails
	private function validate($email,$fname,$lname,$phone,$bEmail=true)
	{
		global	$gMysql;
		$error_string	="";
		# set error message to none
		$bReturn				=	true;

		# validate form data
		if	(empty($fname))
		{
			$error_string	.= "<li>Please enter your Forename</li>";
		}
		if	(empty($lname))
		{
			$error_string	.= "<li>Please enter your Surname</li>";
		}
		if	(empty($phone))
		{
			$error_string	.= "<li>Please enter your Phone Number</li>";
		}
		# validate the email address?
		if	($bEmail == true)
		{
			if	(empty($email))
			{
				$error_string	.= "<li>Please enter your Email Address</li>";
			}
			else
			{
				# we will need to do some dbase checks as well
				if (($num = $gMysql->queryItem("SELECT count(*) from $this->table where email='$email'",__FILE__,__LINE__,MYSQL_CACHE_TIME_NORMAL)) != 0)
				{
					$error_string	.= "<li>This email address has been taken</li>";
				}
			}
		}

		# if we have errors
		if	(!empty($error_string))
		{
			$error_message	= "You have some errors<br>Please correct them.<br>"	.	$error_string;
			$bReturn		=	false;
		}

		return 	$bReturn;
	}









	# inserts a record after validation
	private function insertRecord($email,$fname,$lname,$phone,$password,$fairplane_id)
	{
		global	$gMysql;

		# creates a password hash that we don't know
		$safe_password	=	$this->createSafePassword($password);


		$strSQL	=	"INSERT into $this->table (id, fairplane_id,email, password, password_plaintext, fname, lname, phone, date_created)
		values (0,
		'" . $fairplane_id ."',
		'" . $email ."',
		'" . $safe_password ."',
		'" . $password ."',
		'" . $fname."',
		'" . $lname."',
		'" . $phone."',
		Now())";

		# older version has levels too
		$gMysql->insert($strSQL,__FILE__,__LINE__);



	}


	# make sure this is not used already
	function generateUniqueID($length=6)
	{
		global	$gMysql;

		do
		{
			$id	=	$this->generatePassword($length);

			if (($num = $gMysql->queryItem("SELECT count(*) from $this->table where fairplane_id='$id'",__FILE__,__LINE__,MYSQL_CACHE_TIME_NORMAL)) == 0)
			{
				return	$id;
			}
		} while (true);
	}


	# create a password
	function generatePassword($length = 6)
	{
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@-+';
		$count = strlen($chars);

		for ($i = 0, $result = ''; $i < $length; $i++) {
			$index = rand(0, $count - 1);
			$result .= substr($chars, $index, 1);
		}

		return $result;
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





	private function send($fname,$password,$fairplane_id,$to_array)
	{

		$name			=	'Fairplane UK';
		$from_email		=	$this->from_email;
		$subject		=	'Fairplane Login Details';
		$message		=	"Hello ". $fname ."<br><br>";
		$message		.=	"Here are your login details:<br>";
		$message		.=	"Login ID: ".	$fairplane_id	.	"<br>";
		$message		.=	"Password: ".	$password	.	"<br><br><br>";
		$message		.=	"Login Link: ".	$_SERVER['HTTP_HOST'] .	"/login <br><br><br>";

		$this->emailDetails($name,$from_email,$subject,$message,$to_array);

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
				<a href="http://fairplane.co.uk/"><img src="http://'.$_SERVER['HTTP_HOST'].'/app/images/email/head2.png" width="750" height="157" /></a>
			</td>
		</tr>
		<tr>
			<td>
				<p>'. $message .'</p>
			</td>
		</tr>
		<tr>
			<td>
				<img src="http://'.$_SERVER['HTTP_HOST'].'/app/images/email/foot2.png" width="750" height="114" />
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
			if	(strcasecmp($_SERVER['HTTP_HOST'],"test.fairplane2.co.uk") == 0)
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