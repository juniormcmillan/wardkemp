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



class NewPolicies_Controller extends Page_Controller
{

	protected	$papCookie;
	protected	$a_aid;
	protected	$a_bid;
	public		$order_id;



	# constructor
	public function __construct($route,$view_name)
	{

		# if we need to have a pupdate or blank page, we use a _blank page with TITLE and MESSAGE
		parent::__construct($route,$view_name);

	}

	// sends the data from the registrations
	public function doRegister()
	{
		global $gMysql;

		$invalid_characters 	=	array("$", "%", "#", "<", ">", "|");
		$solicitor_code			=	strtoupper(str_replace($invalid_characters, "", $this->params['sc']));


		# ultimately show the page
		parent::render();



	}


	# display the contents of the page (this part should be in the view)
	public function render()
	{
		global $gMysql;

		$invalid_characters 	=	array("$", "%", "#", "<", ">", "|");
		$solicitor_code			=	strtoupper(str_replace($invalid_characters, "", $this->params['sc']));

		# based on the firm, we can use specific templates, or specific text on the page
		$pPolicyText			=	getSolicitorPolicyText($solicitor_code);

		#


		# we can push all these pre-filled tags onto the page
		$tags	=	array(



		);

		$this->appendTags($tags);

		# ultimately show the page
		parent::render();


	}






}



