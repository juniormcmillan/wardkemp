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



class Details_Controller extends Page_Controller
{
	protected	$formAccount;
	protected	$formAccountName;
	protected	$formSortCode1;
	protected	$formSortCode2;
	protected	$formSortCode3;

	protected	$case_key;
	protected	$forename;
	protected	$surname;
	protected	$childname;
	protected	$email;

	# error handling
	protected	$error_message;
	protected	$error_resubmit;



	# constructor
	public function __construct($route,$view_name)
	{
		# if we need to have a pupdate or blank page, we use a _blank page with TITLE and MESSAGE
		parent::__construct($route,$view_name);



		$this->case_key		=	(isset($this->params['case_key'])) ? 	$this->params['case_key']	:	"";
		$this->email		=	(isset($this->params['email'])) ? 		$this->params['email']		:	"";
		$this->surname		=	(isset($this->params['surname'])) ? 	$this->params['surname']	:	"";


		# we can have circumvented the validation, so serverside checks as well
		$this->formAccount		=	GetVariableString('formAccount',$this->params);
		$this->formAccountName	=	GetVariableString('formAccountName',$this->params);
		$this->formSortCode1	=	GetVariableString('formSortCode1',$this->params);
		$this->formSortCode2	=	GetVariableString('formSortCode2',$this->params);
		$this->formSortCode3	=	GetVariableString('formSortCode3',$this->params);



		# check the user
		$gUser	=	new User_Class();
		# now add the order_id
		if (($data = $gUser->getUser($this->case_key,$this->email)) == NULL)
		{
			# we should go to success page, or homepage with a popup
			gotoURL("/?pUpdate=noClaimRecord");
		}
		else
		{
			$this->forename	=	ucfirst(strtolower($data['forename']));
			$this->surname	=	ucfirst(strtolower($data['surname']));
		}

	}










	# login just has the menu as per most outside pages
	public function render()
	{

//AddComment("Render Function Called by $this->refcode  and $this->childname ($this->userBrowserName, $this->userBrowserVer)");
		# we can push all these pre-filled tags onto the page
		$tags	=	array(

			"{{formAccount}}"		=>	$this->formAccount,
			"{{formAccountName}}"	=>	$this->formAccountName,

			"{{formSortCode1}}"		=>	$this->formSortCode1,
			"{{formSortCode2}}"		=>	$this->formSortCode2,
			"{{formSortCode3}}"		=>	$this->formSortCode3,
			"{{error_message}}"		=>	$this->error_message,
			"{{error_resubmit}}"	=>	$this->error_resubmit,
			"{{case_key}}"			=>	$this->case_key,
			"{{forename}}"			=>	$this->forename,
			"{{surname}}"			=>	$this->surname,
			"{{email}}"				=>	$this->email

			);

		$this->appendTags($tags);

		# ultimately show the page
		parent::render();
	}






}



