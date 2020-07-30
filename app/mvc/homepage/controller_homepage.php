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



class Homepage_Controller extends Page_Controller
{

	protected	$formFlight		=	"";
	protected	$formDate		=	"";
	protected	$ccformFlight	=	"";
	protected	$ccformDate		=	"";
	protected	$claimCalculator	=	"";
	protected	$day;
	protected	$month;
	protected	$year				=	"";
	protected	$datepicker_code	=	"";
	protected	$flightcheck_code	=	"";


	protected	$papCookie;
	protected	$a_aid;
	protected	$a_bid;
	public	$order_id;



	# constructor
	public function __construct($route,$view_name)
	{


		$invalid_characters 	=	array("$", "%", "#", "<", ">", "|", " ");

		# if we need to have a pupdate or blank page, we use a _blank page with TITLE and MESSAGE
		parent::__construct($route,$view_name);

	}

	// sends the data from the registrations
	public function doRegister()
	{
		global $gMysql;

		$invalid_characters 	=	array("$", "%", "#", "<", ">", "|");
		$formEmail				=	str_replace($invalid_characters, "", $this->params['formEmail']);
		$formPhone				=	str_replace($invalid_characters, "", $this->params['formTelephone']);
		$formFullName			=	str_replace($invalid_characters, "", $this->params['formName']);

		# REGISTER_DATE_FORNAME_SURNAME_NUM



/*

		if(count($_FILES['filesToUpload']['name']))
		{

			# ID proof version
			$prefix			=	"REGISTER_";
			$date			=	date("Y-m-d");


			$target			=	"files/register/";
			$count			=	0;
			foreach ($_FILES['filesToUpload']['name'] as $filename)
			{

				$fileType 		=	pathinfo($_FILES['filesToUpload']["name"][$count],PATHINFO_EXTENSION);


				# get the new name
				$temp			=	$temp.basename($filename);
				$new_name		=	$prefix .  .  "_" . $date	. "." .$fileType;
				$new_name		=	$targetPath. $new_name;



				$temp	=	$target;
				$tmp	=	$_FILES['filesToUpload']['tmp_name'][$count];
				$count	=	$count + 1;
				$temp	=	$temp.basename($filename);
				if (move_uploaded_file($tmp,$temp))
				{
					$temp='';
					$tmp='';
				}
			}
		}
*/


/*
		# loop for naming the files and saving at once
		foreach($_FILES['file']['name'] as $index=>$name)
		{
			$filename 		=	$name;
			$fileType 		=	pathinfo($_FILES['file']["name"][$index],PATHINFO_EXTENSION);


			$tempFile 		=	$_FILES['file']['tmp_name'][$index];          //3
			$targetPath 	=	"../../../files/id/";

			# get the new name
			$new_name		=	$prefix . $proclaim_id .  "_" . $date	. "." .$fileType;
			$new_name		=	$targetPath. $new_name;

			$targetFile 	=  	$targetPath. $_FILES['file']['name'];  //5

			move_uploaded_file($tempFile,$new_name); //6


			$prefix++;
		}

*/

		# ID proof version
		$prefix		=	"ID";


		$date			=	date("Y-m-d");




		# ultimately show the page
		parent::render();



	}


	# display the contents of the page (this part should be in the view)
	public function render()
	{
		global $gMysql;




		# we can push all these pre-filled tags onto the page
		$tags	=	array(



		);

		$this->appendTags($tags);

		# ultimately show the page
		parent::render();


	}






}



