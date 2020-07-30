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



class Offerupload_Controller extends Page_Controller
{

	public  $forename;
	public  $surname;
	public  $email;
	public  $refcode	=	"";
	public  $case_key;

	# constructor
	public function __construct($route,$view_name)
	{
		global $gSession;

		# if we need to have a pupdate or blank page, we use a _blank page with TITLE and MESSAGE
		parent::__construct($route,$view_name);

		$this->email		=	strtolower(GetVariableString('email',$this->params));
		$this->case_key		=	GetVariableString('case_key',$this->params);
		$this->forename		=	ucfirst(strtolower(GetVariableString('forename',$this->params)));


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

#		# this should check the tenant dbase also using proclaim_id
#		$gCmd	=	new Cmd_Class();
#		$data	=	$gCmd->getTenantViaProclaimID($this->proclaim_id,$this->email);

#		# record does not exist
#		if 	(empty($data))
#		{
#			$gSession->gotoURL("/?pUpdate=noClaimRecord");
#		}
#		else
#		{
#			$this->forename	=	ucfirst(strtolower($data['forename']));
#			$this->surname	=	ucfirst(strtolower($data['surname']));
#		}


	}




	# new post version
	public function post()
	{

		$this->render();


	}



	# display the contents of the page (this part should be in the view)
	public function render()
	{
		# we can push all these pre-filled tags onto the page
		$tags	=	array(

			"{{forename}}"				=>	$this->forename,
			"{{surname}}"				=>	$this->surname,
			"{{refcode}}"				=>	$this->refcode,
			"{{email}}"					=>	$this->email,
			"{{case_key}}"				=>	$this->case_key,


		);

		$this->appendTags($tags);

		# ultimately show the page
		parent::render();


	}




}