<?php
/**
 * This file handles the default page request
 */
class Base_Controller
{

	protected	$route		=	array();

	public		$tags		=	array();

	public		$params		=	array();

	protected	$method		=	"";

	protected	$view		=	"";

	public		$directory;

	# template
	protected	$template;


	# constructor
	public function __construct($route,$view_name)
	{
		global $gRegistration;
		global	$gSession;

		$this->route	=	$route;

		# create a view pointer (should be passed along with construction)
		$this->view			=	new $view_name($this);

		$this->directory	=	"mvc/" . $this->route['controller'] .	"/";

		# template used
		$this->template		=	(!empty($this->route['passed_params']['template'])) ?  $this->route['passed_params']['template']	:	"index.html";


		# merge the params
		$this->appendParams($route['passed_params']);

		# we can perform a special function if we have been passed a variable
		if	(isset($this->params['logout']))
		{
			# this is for legacy box legal
			session_start();
			# box legal version
			setcookie('ckSavePass', '', time()-3600);
			setcookie('boxlegal', '', time()-3600);
			session_destroy();

			# checks if this page is admin only
			if ($route['admin_only'] == true)
			{
				if	($gRegistration)
				{
					$gRegistration->logout();
				}

			}

			else
			{
				if	($gSession)
				{
					global	$gMysql;

					if	($gMysql)
					{
			//		$gMysql->addLog("Logout");
					}
					$gSession->logout();
				}
			}
		}


		# we can have top and side menus, so lets make sure we are covered
		if	(!isset($this->params['side_menu']))
		{
			$this->params['side_menu']	=	"";
		}
		if	(!isset($this->params['top_menu']))
		{
			$this->params['top_menu']	=	"";

		}

		# check for referrer and set
		if	(isset($this->params['refcode']))
		{
			if	($gSession)
			{
				$gSession->setSessionVar('refcode', $this->params['refcode']);
			}
		}

		# check for referrer and set
		else if	(isset($this->params['ref']))
		{
			if	($gSession)
			{
				$gSession->setSessionVar('refcode', $this->params['ref']);
			}
		}

		# check for referrer and set
		if	(isset($this->params['v']))
		{
			if	($gSession)
			{
				$gSession->setSessionVar('v', $this->params['v']);
			}
		}



		# check for referrer and set
		if	($gSession)
		{
			if	($gSession->isSessionVarSet('HTTP_REFERER') == false)
			{
				if (isset($_SERVER['HTTP_REFERER']))
				{
					$gSession->setSessionVar('HTTP_REFERER', $_SERVER['HTTP_REFERER']);
				}
				else
				{
					$gSession->setSessionVar('HTTP_REFERER', $_SERVER['PHP_SELF']);
				}
			}
		}



		$this->method	=	$_SERVER['REQUEST_METHOD'];


		# check that param is sent and set in cookie if it is
		$a_aid	=	GetVariableString('a_aid',$this->params);
		if (!empty($a_aid))
		{
			setcookie ("a_aid",$a_aid,time()+ (10 * 365 * 24 * 60 * 60), "/");
		}


		foreach ($this->params as $key => $value)
		{
			$this->appendTags(array	("{{".$key."}}"		=>	$value));
		}


	}


	public function appendParams($params)
	{
		$this->params	=	array_merge($this->params,$params);
	}

	public function prependTags($tags)
	{
		$this->tags	=	$tags + $this->tags;
	}




	public function appendTags($tags)
	{
		$this->tags	=	array_merge($this->tags,$tags);
	}



	# check for popup and append the old way - this
	public function checkAppendPopup()
	{
		global 	$gMysql;
		global	$gPopupLookUp;
		global	$version;

		if (isset($this->route['passed_params']['pUpdate']))
		{
			# go through all popup connotations
		    if	(array_key_exists ($this->route['passed_params']['pUpdate'],$gPopupLookUp) == true )
			{
				# appends a popup
				$this->appendTags(array	("{{page_popup}}" =>	$gPopupLookUp[ $this->route['passed_params']['pUpdate'] ]['html'] ));

				return;
			}
		}


		$this->appendTags(array	("{{page_popup}}"		=>	""));
		$this->appendTags(array	("{{version}}" 			=>	$version));


	}




	# gets the template
	public function getTemplate()
	{
		$fn		=	DOCUMENT_ROOT . $this->directory . $this->template;
		if	(!file_exists($fn))
		{
			$fn		=	DOCUMENT_ROOT  .$this->template;
			if	(!file_exists($fn))
			{
				echo "getTemplate Error: File not found: $fn";
				exit;
			}
		}
		return	$fn;
	}


	# gets the header
	public function getHeader()
	{
		return	"templates/" . $this->params['header'];
	}


	# gets the footer
	public function getFooter()
	{
		return	"templates/" . $this->params['footer'];
	}




	# prepare the contents of the page
	public function render()
	{
		# adds additional tags
		$tags			=	array(
#			"{{images}}"			=>	IMAGES_LOCATION,
				"/app/images"		=>	IMAGES_LOCATION . 	"/app/images",

				"/app/includes/js"	=>	IMAGES_LOCATION	.	"/app/includes/js",

#				"/legacy"	=>	LEGACY_IMAGES_LOCATION,

				"menu_string"		=>	MENU_STRING,


		);

		$this->appendTags($tags);


		# sets the templates used for the view (we can do this in the router)
		$this->view->render();


	}







}

