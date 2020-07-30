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



class Loggedin_Controller extends Page_Controller
{
	public $fairplane_id;
	public $email;
	public $forename;
	public $surname;
	public $phone;
	public $admin;

	# constructor
	public function __construct($route,$view_name)
	{
		global $gRegistration;
		# checks if this page is admin only
		if ($route['admin_only'] == true)
		{
			# session
			$gRegistration	=	new	Registration_Class( array(

					"session_id"			=>	SESSION_ADMIN_ID,
					"logged_in_url"			=>	SESSION_ADMIN_LOGGED_IN_URL,
					"logged_out_url"		=>	SESSION_ADMIN_LOGGED_OUT_URL,
					"login_url"				=>	SESSION_ADMIN_LOGIN_URL,
				)
			);
		}




		# checks for login, else goes to login page
		$gRegistration->loginCheck();

		$this->email			=	$gRegistration->getSessionDataVar('email');
		$this->forename			=	$gRegistration->getSessionDataVar('forename');
		$this->surname			=	$gRegistration->getSessionDataVar('surname');
		$this->title			=	$gRegistration->getSessionDataVar('title');
		$this->phone			=	$gRegistration->getSessionDataVar('phone');
		$this->admin		 	=	$gRegistration->getSessionDataVar("admin");

		parent::__construct($route,$view_name);


	}


	# render function
	public function render()
	{
		global $gDefault_menu;
		global $gDefault_menu;
		$pMenu						=	new Menus_Library;
		$panel_menu_string			=	$pMenu->createMenu($gDefault_menu,false,true,false,$this->route['pattern'],true,true);

		$panel_mobile_menu_string	=	$pMenu->createMenu($gDefault_menu,true,false,false,$this->route['pattern'],true);


		# This hash contains the tags that need to be replaced.
		# these need to happen at the end
		$tags	=

			array(


				"{{panel_menu_string}}"				=>	$panel_menu_string,
				"{{panel_mobile_menu_string}}"		=>	$panel_mobile_menu_string,

			);

		$this->appendTags($tags);


		# ultimately show the page
		parent::render();
	}












}



