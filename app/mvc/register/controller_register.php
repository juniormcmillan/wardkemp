<?php
/**
 * Created by PhpStorm.
 * User: junior
 * Date: 25/01/14
 * Time: 00:49
 */



class Register_Controller extends Page_Controller
{

	private $formEmail;
	private $formUsername;
	private $formPassword;
	private	$formRememberMe;
	private	$formAdminRememberMe;
	private	$formHash;

	public function __construct($route,$view_name)
	{

		parent::__construct($route,$view_name);

		# this will check the activation code and process the account
		$activation_code	=	$this->params['code'];

		if	(!empty($activation_code))
		{
			# session
			$gRegistration	=	new	Registration_Class();

			$retcode	=	$gRegistration->activate_customer($activation_code);

			if	($retcode == REGISTRATION_ACTIVATION_ALREADY )
			{
				gotoURL("/login/?pUpdate=activated");
			}

			else if	($retcode == REGISTRATION_ACTIVATION_INVALID )
			{
				gotoURL("/activate/?pUpdate=activateInvalid");
			}

			else if	($retcode == REGISTRATION_ACTIVATION_OK )
			{
				gotoURL("/login/?pUpdate=activateOK");
			}
		}

		# this is in case we have a password change hash
		# this will check the activation code and process the account
		$this->formHash	=	$this->params['hash'];

		if	(!empty($this->formHash))
		{
			# session
			$gRegistration	=	new	Registration_Class();

			$retcode	=	$gRegistration->check_password_hash($this->formHash);

			if	($retcode == LOST_LINK_EXPIRED)
			{
				gotoURL("/lost-password/?pUpdate=linkExpired");
			}

		}




		# if we have the checkbox/cookie for 'remember me' for the email address
		if (isset($_COOKIE['remember_me_email']))
		{
			$this->formEmail		=	$_COOKIE['remember_me_email'];
			$this->formRememberMe	=	"checked=checked";
		}

		# for admins, it's password and login info
		if (isset($_COOKIE['remember_me_username']))
		{
			$this->formPassword			=	$_COOKIE['remember_me_password'];
			$this->formUsername			=	$_COOKIE['remember_me_username'];
			$this->formAdminRememberMe	=	"checked=checked";

		}



	}







	# this just adds the remember me and tickbox
	public function render()
	{

		$tags	=	array(
			"{{formEmail}}"				=>	$this->formEmail,
			"{{formPassword}}"			=>	$this->formPassword,
			"{{formUsername}}"			=>	$this->formUsername,
			"{{formRememberMe}}"		=>	$this->formRememberMe,
			"{{formAdminRememberMe}}"	=>	$this->formAdminRememberMe,
			"{{formHash}}"				=>	$this->formHash,


		);

		$this->appendTags($tags);

		# ultimately show the page
		parent::render();
	}










}



