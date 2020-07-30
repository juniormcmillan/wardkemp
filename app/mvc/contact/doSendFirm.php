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
$formAddress			=	str_replace($invalid_characters, "", $formData['formAddress']);
$formPostcode			=	str_replace($invalid_characters, "", $formData['formPostcode']);
$formMessage			=	str_replace($invalid_characters, "", $formData['formMessage']);
$formWhatAbout			=	str_replace($invalid_characters, "", $formData['formWhatAbout']);

$formWebsite			=	str_replace($invalid_characters, "", $formData['formWebsite']);


$formWho				=	GetVariableString('formWho',$_POST);

$returnCode			=	"success";
# this is the return array with all the commands
$returnArray		=	array();



if (empty($message))
{

	# GOOGLE CAPTCHA CHECK
	$captcha			=	$formData['g-recaptcha-response'];
	$secret_key			=	"6LchmrIZAAAAANnzYiUe44wjef9EiuP6mDNAoHXk";

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



$htmlMessage = "
					<b>New $formWho Enquiry</b>\n\n
					Company: $formCompanyName\n
					Address: $formAddress\n
					Postcode: $formPostcode\n
					Telephone: $formPhone\n
					Website: $formWebsite\n
					Contact name: $formFullName\n
					Email: $formEmail\n
					About: $formWhatAbout\n
					Message: $formMessage";




$htmlMessage	=	str_replace('\r\n', "\r\n",$htmlMessage);

$ch		=	curl_init();
$curl_post_data = array(

		'message' 			=> $htmlMessage,
		'apikey' 			=> '543973945d28ad2e30ad9e56ae3e976e',
		'useridentifier' 	=> $formEmail,
		'department'		=> '8a20937f', //department id
		'recipient' 		=> 'boxlegal@fairplanenetwork.co.uk',
		'subject' 			=> 'New '. $formWhatAbout. ' Enquiry',
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
	die("error occured during curl exec. Additional info: " . var_export($info));
}
curl_close($ch);
/* process $curl_response here */
$response_string = (json_decode($curl_response));



# from email as there will be no reply
$email	=	"website@wardkemp.co.uk";
$subject =	"New $formWho Enquiry";

#sendEmailLocal("Ward Kemp Website", $email, "kirsten@boxlegal.co.uk", $subject, nl2br($htmlMessage));
sendEmailLocal("Ward Kemp Website", $email, "cedric@boxlegal.co.uk", $subject, nl2br($htmlMessage));





# returns the last N messages from an owner
$returnArray		=	array_merge($returnArray,

	array	(
		"returncode" 		=> 		$returnCode,
		"message"			=>		$message,

	)

);



# return two variables
echo json_encode($returnArray);
