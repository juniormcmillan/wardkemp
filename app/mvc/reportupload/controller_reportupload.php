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



class Reportupload_Controller extends Page_Controller
{

	public  $company_id;
	public  $case_key;

	# constructor
	public function __construct($route,$view_name)
	{
		global $gSession;

		# if we need to have a pupdate or blank page, we use a _blank page with TITLE and MESSAGE
		parent::__construct($route,$view_name);

		$this->case_key		=	GetVariableString('case_key',$this->params);
		$this->company_id	=	GetVariableString('company_id',$this->params);

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

			"{{company_id}}"			=>	$this->company_id,
			"{{case_key}}"				=>	$this->case_key,


		);

		$this->appendTags($tags);

		# ultimately show the page
		parent::render();


	}




}