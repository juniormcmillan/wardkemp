<?php
/**
 * Created by PhpStorm.
 * User: McMillan
 * Date: 19-Feb-16
 * Time: 9:14 AM
 *
 * 	 this is not just a job, it has tasks within it
 *
 * 	we can have job templates that specify order of tasks
 * 	we can re-start a job from any step along the way.
 * 	we need to also tag the records created with a particular job, so we can remove them if needed with a consignment / package number

 *
 */


require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/autoload.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/../codelibrary/includes/php/common.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/config.inc.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/includes/classes/job.class.php");
require_once($_SERVER["DOCUMENT_ROOT"]	.	"/app/includes/classes/claim.class.php");


# file imported from Austrian DBASE (12-col)
define('CONSIGNMENT_STATUS_CREATED',							0);
# file imported from Austrian DBASE (12-col)
define('CONSIGNMENT_STATUS_IMPORTED_FROM_AUSTRIA',				1);
# file imported from partner wqith all fields filled in
define('CONSIGNMENT_STATUS_IMPORTED_FROM_PARTNER',				2);



class Consignment_Class
{
	public $directory			=	"";
	public	$filename			=	"";
	public	$extension			=	"";

	private $partner_id			=	"";
	private $job_id		=	"";
	private $added				=	"";
	private $last_updated		=	"";
	private $status				=	"";


	public function __construct($partner_id=0)
	{
#		$this->createConsignment($partner_id);
	}

	public function getConsignmentID()
	{
		return $this->job_id;
	}














	# uploads a file
	public function uploadFile($file_data,$new_base_filename,$partner_id,$job_id)
	{
		$filename 				=	$file_data["name"];

		# base directory via $_SERVER["DOCUMENT_ROOT"]
		$this->directory		=	"../../../files/partner/" 	. "$partner_id/consignment/$job_id/uploads/";

		# check if it exists, and if not, create it, exit if we cant
		if ($this->checkCreateDir($this->directory) == true)
		{
			$this->extension		=	pathinfo($filename,PATHINFO_EXTENSION);
			$this->filename			=	$new_base_filename;

			# save file into the path
			$new_filename	=	$this->directory . "" . $new_base_filename . "." . $this->extension;

			//upload the file
			if (move_uploaded_file($file_data["tmp_name"], $new_filename) == true)
			{
				return true;
			}

			return false;
		}

		return false;
	}


	# creates a directory for the partner if it doesnt exist
	function checkCreateDir($directory)
	{
		if	(is_dir($directory))
		{
			return true;
		}
		if (!mkdir($directory, 0777, true))
		{
			return false;
		}

		return true;
	}







}












