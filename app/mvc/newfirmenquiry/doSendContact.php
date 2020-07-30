<?php
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");

# mysql
$gMysql			=	new Mysql_Library();


# grab the basic details
$formData	=	array();
parse_str($_POST['formData'],$formData);

$invalid_characters 	=	array("$", "%", "#", "<", ">", "|");
$formEmail				=	str_replace($invalid_characters, "", $formData['formEmail']);
$formPhone				=	str_replace($invalid_characters, "", $formData['formTelephone']);
$formFullName			=	str_replace($invalid_characters, "", $formData['formName']);
$formCompanyName		=	str_replace($invalid_characters, "", $formData['formCompanyName']);
$formMessage			=	str_replace($invalid_characters, "", $formData['formMessage']);
$formHowFound			=	str_replace($invalid_characters, "", $formData['formHowFound']);



$returnCode			=	"success";
# this is the return array with all the commands
$returnArray		=	array();


# error checks
if (empty($formFullName))
{
	$message		=	"Please enter your name in the box provided";
}
else if (empty($formPhone))
{
	$message		=	"Please enter your phone number in the box provided";
}
else if (empty($formEmail))
{
	$message		=	"Please enter your email address in the box provided";
}
else if (empty($formMessage))
{
	$message		=	"Please enter your a message in the box provided";
}

if (empty($message))
{

	# GOOGLE CAPTCHA CHECK
	$captcha			=	$formData['g-recaptcha-response'];
	$secret_key			=	"6LcrrFAUAAAAAIgmYI2W9qp8xyNpIFLdsh77UZLB";

	$response			=	file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
	$obj = json_decode($response);
	if($obj->success == false)
	{
		//error handling
		$message	.= "Please check that you are not a robot";
	}
}

if (!empty($message))
{
	$returnCode		=	"error";
	$returnArray	=

		array	(
			"returncode" 		=> 		$returnCode,
			"message"			=>		$message,
		);
	# return two variables
	echo json_encode($returnArray);
	exit;
}

$message	=	"Your message has been received.<br>We will contact you shortly.";



$htmlMessage = "
		Name: " . $formName ."\n
		Email: " . $formEmail ."\n\n
		Telephone: " . $formPhone ."\n\n
		Company Name: " . $formCompanyName."\n\n
		Heard About Us?: " . $formHowFound."\n\n
		Message: " . $formMessage . "\n\n\n

		";

$htmlMessage	=	str_replace('\r\n', "\r\n",$htmlMessage);

$ch		=	curl_init();
$curl_post_data = array(

		'message' 			=> $htmlMessage,
		'apikey' 			=> '543973945d28ad2e30ad9e56ae3e976e',
		'useridentifier' 	=> $formEmail,
		'department'		=> '8a20937f', //department id
		'recipient' 		=> 'boxlegal@fairplanenetwork.co.uk',
		'subject' 			=> 'Enquiry from Box Legal Website',
		'status' => 'N',
		'recipient_name'	=>	$formFullName,


	);


curl_setopt($ch,CURLOPT_URL,"http://livechat.boxlegal.co.uk/api/conversations");
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $curl_post_data);
$curl_response=curl_exec($ch);
if ($curl_response === false)
{
	$info = curl_error($ch);
	curl_close($ch);
	die("error occured during curl exec. Additioanl info: " . var_export($info));
}
curl_close($ch);
/* process $curl_response here */
$response_string = (json_decode($curl_response));









# returns the last N messages from an owner
$returnArray		=	array_merge($returnArray,

	array	(
		"returncode" 		=> 		$returnCode,
		"message"			=>		$message,

	)

);



# return two variables
echo json_encode($returnArray);
