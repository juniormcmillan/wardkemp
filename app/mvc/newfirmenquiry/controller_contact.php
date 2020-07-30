<?php
/**
 * Created by PhpStorm.
 * User: junior
 * Date: 25/01/14
 * Time: 00:49



 now this page needs to be mapped to the correct class




 if page is found in the database, spawn a view based on the page found (mapped via dbase)

one idea is that the controller/view could be spawned after initial dbase mapping which is kept in the front controller








 This controller is used when displaying standard pages with pretty-urls


 */



class Contact_Controller extends Page_Controller
{
	protected	$formName;
	protected	$formTelephone;
	protected	$formMessage;
	protected	$formEmail;


	# error handling
	protected	$error_message		=	"";
	protected	$error_resubmit		=	false;






	# handles the post - updates will be to handle brute force and banned IP's
	public function post()
	{




		# test that data is valid and account is not already in the database
		if	($this->validate() == true)
		{
			# we should now insert data to database
			$this->send();

			# we should go to success page, or homepage with a popup
			gotoURL("/?pUpdate=contactSent");

			$this->template				=	"templates/index_blank.html";			#$this->params['template_blank'];
			$this->appendTags(array	("{{blank_title}}" 		=>	"THANK YOU"));
			$this->appendTags(array	("{{blank_message}}" 	=>	"<br><br>Your contact details have been received. A member of our team will contact you shortly"));


		}

		# ultimately show the page
		$this->render();

	}









	# validation for the registration form
	private function validate()
	{
		# set error message to none
		$error_string			=	"";
		$bReturn				=	true;
		$this->error_resubmit	=	"true";

		# we can have circumvented the validation, so serverside checks as well
		$this->formName			=	GetVariableString('formName',$this->params);
		$this->formEmail		=	GetVariableString('formEmail',$this->params);
		$this->formMessage		=	GetVariableString('formMessage',$this->params);
		$this->formTelephone	=	GetVariableString('formTelephone',$this->params);


		# GOOGLE CAPTCHA CHECK
		$captcha			=	GetVariableString('g-recaptcha-response',$this->params);
		$secret_key			=	"6LcrrFAUAAAAAIgmYI2W9qp8xyNpIFLdsh77UZLB";

		$response			=	file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
		$obj = json_decode($response);
		if($obj->success == false)
		{
			//error handling
			$error_string	.= "<li>Please check that you are not a robot</li>";
		}


		# validate form data
		if	(empty($this->formName))
		{
			$error_string	.= "<li>Please enter your Name</li>";
		}
		if	(empty($this->formEmail))
		{
			$error_string	.= "<li>Please enter your Email Address</li>";
		}
		if	(empty($this->formMessage))
		{
			$error_string	.= "<li>Please enter your Message</li>";
		}

		# if we have errors
		if	(!empty($error_string))
		{
			$this->error_message	= "You have some errors<br>Please correct them.<br>"	.	$error_string;

			$this->error_resubmit	=	true;

			$bReturn	=	false;

		}

		return 	$bReturn;

	}








	# login just has the menu as per most outside pages
	public function render()
	{



		# we can push all these pre-filled tags onto the page
		$tags	=	array(

			"{{formName}}"			=>	$this->formName,
			"{{formTelephone}}"		=>	$this->formTelephone,
			"{{formEmail}}"			=>	$this->formEmail,
			"{{formMessage}}"		=>	$this->formMessage,
			"{{formCompanyName}}"	=>	$this->formCompanyName,
			"{{error_message}}"		=>	$this->error_message,
			"{{error_resubmit}}"	=>	$this->error_resubmit

			);

		$this->appendTags($tags);

		# ultimately show the page
		parent::render();
	}





	# send details
	private function send()
	{

		$htmlMessage = "
		Message: " . $this->formMessage . "\n\n\n

		Name: " . $this->formName ."\n
		Email: " . $this->formEmail ."\n\n
		Telephone: " . $this->formTelephone ."\n
		";
#		sendEmailLocal("FairPlane Contact Form", $this->formEmail, "cedric@fairplane.co.uk", "Contact Form Message", $htmlMessage);
#		sendEmailLocal("FairPlane Contact Form", $this->formEmail, "claim@fairplane.co.uk", "Contact Form Message", $htmlMessage);


		$htmlMessage	=	str_replace('\r\n', "\r\n",$htmlMessage);

		$ch		=	curl_init();
		$curl_post_data = array(

			'message' 			=> $htmlMessage,
			'apikey' 			=> '784b9cbf053a6ec9302cc0c59ab692ce',
			'useridentifier' 	=> $this->formEmail,
			'department'		=> 'default', //department id
			'recipient' 		=> 'liveagent@fairplanenetwork.co.uk',
			'subject' 			=> 'FairPlane Contact Form',
			'status' => 'N',

			
		);


		curl_setopt($ch,CURLOPT_URL,"http://livechat.boxlegal.co.uk/api/conversations");
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curl_post_data);
		$curl_response=curl_exec($ch);
		if ($curl_response === false) {
			$info = curl_error($ch);
			curl_close($ch);
			die("error occured during curl exec. Additioanl info: " . var_export($info));
		}
		curl_close($ch);
		/* process $curl_response here */
		$response_string = (json_decode($curl_response));



	}








}



