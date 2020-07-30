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



class Caseaccept_Controller extends Page_Controller
{
	public  $case_key;
	public  $solicitor_code;
	public  $policy_code;
	public  $company_id;
	public  $text_string;
	public 	$pPolicyText;
	public 	$pPolicyType;
	public 	$logo;


	# constructor
	public function __construct($route,$view_name)
	{

		# if we need to have a pupdate or blank page, we use a _blank page with TITLE and MESSAGE
		parent::__construct($route,$view_name);


		$this->case_key				=	GetVariableString('case_key',$this->params);
		$this->policy_code			=	GetVariableString("code", $this->params);
		$this->company_id			=	GetVariableString("company_id", $this->params);
		$this->accept				=	GetVariableString("accept", $this->params);


		# based on the firm, we can use specific templates, or specific text on the page
	#	$this->pPolicyText			=	getCompanyPolicyText($this->company_id,$this->policy_code);


		# this simpler version assumes there are no company-specific texts
		$this->pPolicyText				= 	getPolicyText($this->policy_code);

		# if we cannot find the text for this code
		if ($this->pPolicyText == NULL)
		{
			gotoURL("/?pUpdate=noSolicitorCode");
		}

		$this->logo						=	$this->pPolicyText['logo'];

	}

	// show the accept
	public function doCaseAccept()
	{
		$this->text_string				=	$this->pPolicyText['text']['case-accept']['accept'];

		$tags	=	array(
			"{{value}}"					=>	"accept",


		);


		$this->appendTags($tags);

		# ultimately show the page
		$this->render();

	}


	// show the accept
	public function doCaseReject()
	{
		$this->text_string				=	$this->pPolicyText['text']['case-accept']['reject'];

		$tags	=	array(

				"{{value}}"					=>	"reject",


		);


		$this->appendTags($tags);

		# ultimately show the page
		$this->render();

	}











	# display the contents of the page (this part should be in the view)
	public function render()
	{

		$tags	=	array(

			"{{case_key}}"				=>	$this->case_key,
			"{{code}}"					=>	$this->policy_code,
			"{{company_id}}"			=>	$this->company_id,
			"{{text_string}}"			=>	$this->text_string,
			"{{logo}}"					=>	$this->logo,
			"{{accept}}"				=>	$this->accept,


		);

		$this->appendTags($tags);

		# ultimately show the page
		parent::render();


	}






}



