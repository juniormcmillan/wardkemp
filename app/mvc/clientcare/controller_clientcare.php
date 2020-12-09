<?php
/**
 * Created by PhpStorm.
 * User: junior
 * Date: 25/01/14
 * Time: 00:49

# ***** This code grabs the signed document and stores it in a folder ***********
#			$client		= new DigiSignerClient($this->digisigner_key);
#			# extracted document stored in folder
#			$client->getDocument($this->document_id , $this->folder . '/' . $this->document_id  . '.pdf');


 */

#require_once($_SERVER["DOCUMENT_ROOT"]	."/../codelibrary/includes/fpdf/fpdf.php");
#require_once($_SERVER["DOCUMENT_ROOT"]	."/../codelibrary/includes/fpdf/writehtml.php");


// include the pdf factory
require_once($_SERVER["DOCUMENT_ROOT"]	."/../codelibrary/includes/fpdf_multicell/pdfFactory.php" );

// Include the Advanced Multicell Class
require_once($_SERVER["DOCUMENT_ROOT"]	."/../codelibrary/includes/fpdf_multicell/classes/pdfmulticell.php" );

/**
 * Include my Custom PDF class This is required only to overwrite the header
 */
require_once($_SERVER["DOCUMENT_ROOT"]	."/../codelibrary/includes/fpdf_multicell/mypdf-multicell.php" );



require_once($_SERVER["DOCUMENT_ROOT"]	."/../codelibrary/includes/digisigner-php-client/DigiSignerClient.php");

//import needed classed from DigiSigner namespace
use DigiSigner\DigiSignerClient;
use DigiSigner\SignatureRequest;
use DigiSigner\DigiSignerException;
use DigiSigner\Document;
use DigiSigner\ExistingField;
use DigiSigner\Signer;
use DigiSigner\Field;


class Clientcare_Controller extends Page_Controller
{
    # these fields possibly extracted from database via reference
    private	$case_key	   	=	"";
    private	$clientcare_fullname 	=	"";
    private	$forename        	=	"";
    private	$surname         	=	"";
    private	$email  		    = 	"";
	private	$address  	        = 	"";
	private	$city	  	        = 	"";
	private	$postcode  	        = 	"";

    private $firm_address       =   "Westgate House, Harlow, Essex , CM20 1YS";

    private $refcode			=	"";
    private $filename			=	"test121.pdf";

    # this folder is just for the signed documents temporary
    private $folder             = 	"files/clientcare-tmp/";


    private $digisigner_key     =   "aadd887d-fdfc-425c-b19b-550e49203e33";
    private $signer_email       =   "cedric@boxlegal.co.uk";
    private $document_id		=	"";
    private $sign_doc_url       =   "";

	public  $solicitor_code;
	public	$policy_type;
	public  $client_text;
	public 	$pPolicyText;
	public 	$pSolicitor;
	public 	$logo;

	public 	$company_id;
	public 	$company_name;



public $menu	=	array(

array(	"name" => "Introduction", 														"link" =>	"clientcare/introduction"								),
array(	"name" => "Client Care Letter", 												"link" =>	"clientcare/letter",									),
array(	"name" => "Protecting Yourself Financially (ATE Legal Expense Insurance)", 		"link" =>	"clientcare/ate-insurance"								),
array(	"name" => "Insurance Product Information Document (IPID)", 						"link" =>	"clientcare/ipid"										),
array(	"name" => "Conditional Fee Agreement (No win no fee agreement)", 				"link" =>	"clientcare/cfa",						"cc"		=> 	"yes",	"var" => "cfa" ),
array(	"name" => "Instruction to Act Form of Authority", 								"link" =>	"clientcare/instructions",				"cc"		=> 	"yes",	"var" => "clientcare"  	),
array(	"name" => "Form of Authority instructing your Landlord ", 						"link" =>	"clientcare/landlord",					"cc"		=> 	"yes",	"var" => "landlord"  	),
array(	"name" => "Instruction to Phone Network Provider", 								"link" =>	"clientcare/phone",						"cc"		=> 	"yes",	"var" => "mobile" ),
	#			array(	"name" => "DSAR", 																"link" =>	"clientcare/dsar",						"dsar"		=>	"yes"	),
	);


# constructor
	public function __construct($route,$view_name)
	{
		global $gMysql;
		# if we need to have a pupdate or blank page, we use a _blank page with TITLE and MESSAGE
		parent::__construct($route, $view_name);

		# check for a valid user
		$this->email		=	strtolower(GetVariableString('email',$this->params));
		$this->case_key		=	GetVariableString('case_key',$this->params);

		# check the user
		$gUser	=	new User_Class();
		# now add the order_id
		if (($data = $gUser->getUser($this->case_key,$this->email)) == NULL)
		{
			AddComment(" does not exist1");

			if	((isset($this->params['completed']) || ($this->checkAllSigned() == true)))
			{
				# no header / footer version
				$this->displayDoneIframe();
				$this->render(false);
				exit;

			}
			else
			{
				AddComment(" does not exist");
				# we should go to success page, or homepage with a popup
				gotoURL("/?pUpdate=noClaimRecord");
			}
		}
		else
		{
			if	((isset($this->params['completed']) ))
			{
				# no header / footer version
				$this->displayDoneIframe();
				return;
			}

		}

		# get company_id
		$this->company_id		=	strtoupper($data['company_id']);

		/* TEMP */
		if (empty($this->company_id))
		{
			$this->company_id		=	"DRISCOLL";
		}

		# grab the solicitor name
		$gMysql->setDB("boxlegal");
		$this->company_name		= $gMysql->QueryItem("SELECT name FROM solicitor where code='$this->company_id'", __FILE__, __LINE__);
		$gMysql->setDB("wardkemp");

		# base level text about the case type
		$this->policy_type		=	GetVariableString('code',$this->params);
		# based on the firm, we can use specific templates, or specific text on the page
		$this->pPolicyText		=	getPolicyText($this->policy_type);

		# if we cannot find the text for this code
		if ($this->pPolicyText == NULL)
		{
			gotoURL("/?pUpdate=noPolicyType");
		}


		$this->client_text				=	$this->pPolicyText['text']['client-care']['introduction'];
		$this->logo						=	$this->pPolicyText['logo'];



		$this->title	=	ucfirst(strtolower($data['title']));
		$this->forename	=	ucfirst(strtolower($data['forename']));
		$this->surname	=	ucfirst(strtolower($data['surname']));


		# set that the user has visited website
		if (($val = $gMysql->QueryItem("SELECT visited FROM ppi_user where case_key='$this->case_key'", __FILE__, __LINE__)) == '0000-00-00 00:00:00')
		{
			$gMysql->update("update ppi_user set visited=NOW() where case_key='$this->case_key'", __FILE__, __LINE__);
		}


	}


	public function nothing()
	{
	}




	# related to the authority part 1
	public function mobile($bRenderHeader)
	{


		global $gMysql;
		$bRenderHeader	=	true;

		if	(isset($this->params['completed']))
		{
			# no header / footer version
			$this->displayDoneIframe();
		}
		else
		{

			# variable GetVariableString from table names - passes pid
			# here we are grabbing the proclaim_id variable and passing it through the post
			# so that we can use it in the next line of code.
			$this->email		=	strtolower(GetVariableString('email',$this->params));
			$this->case_key		=	GetVariableString('case_key',$this->params);
			$this->show			= 	GetVariableString('show',$this->params);

			# check the user
			$gUser	=	new User_Class();
			# now add the order_id
			if (($data = $gUser->getUser($this->case_key,$this->email)) == NULL)
			{
				AddComment(" does not exist");
				# we should go to success page, or homepage with a popup
				gotoURL("/?pUpdate=noClaimRecord");
			}
			else
			{

				$this->title	=	ucfirst(strtolower($data['title']));
				$this->forename	=	ucfirst(strtolower($data['forename']));
				$this->surname	=	ucfirst(strtolower($data['surname']));
				$full_name		=	$this->title ." ". $this->forename ." ".$this->surname;
				$address		=	$data['address1']  . " "	.	$data['address2'] . " ".	$data['town']. " ".	$data['postcode'];
				$defendant		=	$data['defendant'];
				$full_date		=	$today = date("F j, Y");
				$this->mobile	=	$data['mobile'];
				$this->dob		=	date( 'F j, Y', strtotime($data['dob']));
				$this->solicitor_ref	=	$data['solicitor_ref'];


				# date
				$d    = date('d');
				$m    = date('m');
				$y    = date('y');
				$date = $d . $m . $y;

				$this->filename = "DS" . $this->case_key . "_" . $date . "S.pdf";


				# decide if we need to build a new pdf, sign it or exit
				if($this->isCreated("mobile") == true)
				{


					# if we have not signed it, we should display it and get it signed
					if(($this->isSigned("mobile") == true) && ($this->show != 'yes'))
					{
						AddComment("er?");
						#						gotoURL("/clientcare/instructions?case_key=" .$this->case_key. "&email=" .$this->email."&reload=yes");
						#						exit;
						# goto the first unsigned or display done
						$this->gotoUnSigned();

						$this->displayDoneIframe();
					}
					# this will show the pdf from within the iframe
					else
					{
						$cachebuster			= 	rand(0,100000);
						$this->sign_doc_url     =   $data['mobile_sign_doc_url'] . "&embedded=true&cachebuster=$cachebuster";

						$this->appendTags(array	("{{meta_title}}" 			=>	"Ward Kemp"));
						$this->appendTags(array	("{{sign_doc_url}}" 		=>	$this->sign_doc_url));

					}
				}
				else
				{

					$client		=	new DigiSignerClient('aadd887d-fdfc-425c-b19b-550e49203e33');
					$request 	=	new SignatureRequest;

					# mobile document
					$template = Document::withID('3ba6ca94-c4d1-4f6d-9410-eee72db3d728');
					$template->setTitle('mobile - '.$this->case_key);
					$request->addDocument($template);


					# this is within the iframe though
					$request->setRedirectAfterSigningToUrl("https://www.wardkemp.uk/clientcare/?completed=yes");   // HERE YOU DEFINE THE REDIRECT URL


					$signer = new Signer('cedric@boxlegal.co.uk');
					$signer->setRole('Signer 1');
					$template->addSigner($signer);



					# name
					$field1 = new ExistingField('ddf11147-d0e5-4fac-bb33-5a657e299cb5');
					$field1->setContent($full_name);
					$field1->setReadOnly(true);
					$signer->addExistingField($field1);

					# address
					$field2 = new ExistingField('7d1ec900-2fe9-495c-b63d-4828f1616faa');
					$field2->setContent($address);
					$field2->setReadOnly(true);
					$signer->addExistingField($field2);


					# telephone
					$field3 = new ExistingField('3db395ad-1bd5-4a5b-9365-8e265136cabc');
					$field3->setContent($this->mobile);
					$field3->setReadOnly(true);
					$signer->addExistingField($field3);


					# dob
					$field4 = new ExistingField('22fde3fd-71d1-4052-87d5-88c40c4adde9');
					$field4->setContent($this->dob);
					$field4->setReadOnly(true);
					$signer->addExistingField($field4);


					# dated
					$field3 = new ExistingField('ba27f7e4-e087-4802-995e-b4fe407217b9');
					$field3->setContent($full_date);
					$field3->setReadOnly(true);
					$signer->addExistingField($field3);

					# ref
					$field3 = new ExistingField('0572327c-36bd-4628-bc38-68900a0b76df');
					$field3->setContent($this->solicitor_ref);
					$field3->setReadOnly(true);
					$signer->addExistingField($field3);






					$signatureRequest = $client->sendSignatureRequest($request);
					$signature_request_id	=	$signatureRequest->getId();
					$document = $signatureRequest->getDocuments();
					$document = $document[0];
					$signer = $document->getSigners();
					$signer = $signer[0];

					$this->document_id  =   $document->getId();
					$this->sign_doc_url =   $signer->getSignDocumentUrl();


					# insert pdf digisigner details into database
					$gMysql->update("update  ppi_user set mobile_signed='', mobile_signature_request_id='$signature_request_id' ,mobile_document_id='$this->document_id', mobile_sign_doc_url='$this->sign_doc_url', mobile_created_date=NOW() where case_key='$this->case_key'", __FILE__, __LINE__);

					$cachebuster			= 	rand(0,100000);
					$this->sign_doc_url     =   $this->sign_doc_url . "&embedded=true&cachebuster=$cachebuster";

					$this->appendTags(array	("{{meta_title}}" 			=>	"Ward Kemp"));
					$this->appendTags(array	("{{sign_doc_url}}" 		=>	$this->sign_doc_url));

				}

			}


			if (isset($this->params['reload']))
			{
				$bRenderHeader	=	false;
			}


			$this->render($bRenderHeader);
		}
	}




























	# related to the authority part 1
	public function cfa($bRenderHeader)
	{


		global $gMysql;
		$bRenderHeader	=	true;

		if	(isset($this->params['completed']))
		{
			# no header / footer version
			$this->displayDoneIframe();
		}
		else
		{

			# variable GetVariableString from table names - passes pid
			# here we are grabbing the proclaim_id variable and passing it through the post
			# so that we can use it in the next line of code.
			$this->email		=	strtolower(GetVariableString('email',$this->params));
			$this->case_key		=	GetVariableString('case_key',$this->params);
			$this->show			= 	GetVariableString('show',$this->params);

			# check the user
			$gUser	=	new User_Class();
			# now add the order_id
			if (($data = $gUser->getUser($this->case_key,$this->email)) == NULL)
			{
				AddComment(" does not exist");
				# we should go to success page, or homepage with a popup
				gotoURL("/?pUpdate=noClaimRecord");
			}
			else
			{

				$this->title	=	ucfirst(strtolower($data['title']));
				$this->forename	=	ucfirst(strtolower($data['forename']));
				$this->surname	=	ucfirst(strtolower($data['surname']));
				$full_name		=	$this->title ." ". $this->forename ." ".$this->surname;
				$address		=	$data['address1']  . " "	.	$data['address2'] . " ".	$data['town']. " ".	$data['postcode'];
				$defendant		=	$data['defendant'];
				$full_date		=	$today = date("F j, Y");



				# date
				$d    = date('d');
				$m    = date('m');
				$y    = date('y');
				$date = $d . $m . $y;

				$this->filename = "DS" . $this->case_key . "_" . $date . "S.pdf";


				# decide if we need to build a new pdf, sign it or exit
				if($this->isCreated("cfa") == true)
				{


					# if we have not signed it, we should display it and get it signed
					if(($this->isSigned("cfa") == true) && ($this->show != 'yes'))
					{
AddComment("er?");
#						gotoURL("/clientcare/instructions?case_key=" .$this->case_key. "&email=" .$this->email."&reload=yes");
#						exit;
						# goto the first unsigned or display done
						$this->gotoUnSigned();

						$this->displayDoneIframe();
					}
					# this will show the pdf from within the iframe
					else
					{
						$cachebuster			= 	rand(0,100000);
						$this->sign_doc_url     =   $data['cfa_sign_doc_url'] . "&embedded=true&cachebuster=$cachebuster";

						$this->appendTags(array	("{{meta_title}}" 			=>	"Ward Kemp"));
						$this->appendTags(array	("{{sign_doc_url}}" 		=>	$this->sign_doc_url));

					}
				}
				else
				{

					$client		=	new DigiSignerClient('aadd887d-fdfc-425c-b19b-550e49203e33');
					$request 	=	new SignatureRequest;

					# cfa document
					$template = Document::withID('4b1d95a0-724d-4d73-b81a-39e40a75a2cf');
					$template->setTitle('CFA - '.$this->case_key);
					$request->addDocument($template);


					# this is within the iframe though
					$request->setRedirectAfterSigningToUrl("https://www.wardkemp.uk/clientcare/instructions?case_key=".$this->case_key."&email=".$this->email."&reload=yes&page=instructions");   // HERE YOU DEFINE THE REDIRECT URL


					$signer = new Signer('cedric@boxlegal.co.uk');
					$signer->setRole('Signer 1');
					$template->addSigner($signer);



					# name
					$field1 = new ExistingField('47a7c545-d1fd-423a-9361-9c3aa03b91d7');
					$field1->setContent($full_name);
					$field1->setReadOnly(true);
					$signer->addExistingField($field1);

					# address
					$field2 = new ExistingField('c8767a1a-92a1-40b6-9864-a612d11539d8');
					$field2->setContent($address);
					$field2->setReadOnly(true);
					$signer->addExistingField($field2);


					#date
					$field3 = new ExistingField('d68aaa27-582b-460a-9d67-ca04cb822969');
					$field3->setContent($full_date);
					$field3->setReadOnly(true);
					$signer->addExistingField($field3);



					$signatureRequest = $client->sendSignatureRequest($request);
					$signature_request_id	=	$signatureRequest->getId();
					$document = $signatureRequest->getDocuments();
					$document = $document[0];
					$signer = $document->getSigners();
					$signer = $signer[0];

					$this->document_id  =   $document->getId();
					$this->sign_doc_url =   $signer->getSignDocumentUrl();


					# insert pdf digisigner details into database
					$gMysql->update("update  ppi_user set cfa_signed='', cfa_signature_request_id='$signature_request_id' ,cfa_document_id='$this->document_id', cfa_sign_doc_url='$this->sign_doc_url', cfa_created_date=NOW() where case_key='$this->case_key'", __FILE__, __LINE__);

					$cachebuster			= 	rand(0,100000);
					$this->sign_doc_url     =   $this->sign_doc_url . "&embedded=true&cachebuster=$cachebuster";

					$this->appendTags(array	("{{meta_title}}" 			=>	"Ward Kemp"));
					$this->appendTags(array	("{{sign_doc_url}}" 		=>	$this->sign_doc_url));

				}

			}


			if (isset($this->params['reload']))
			{
				$bRenderHeader	=	false;
			}


			$this->render($bRenderHeader);
		}
	}







	# related to the authority
	public function instructionsToAct($bRenderHeader)
	{
		global $gMysql;
		$bRenderHeader	=	true;

		if	(isset($this->params['completed']))
		{
			# no header / footer version
			$this->displayDoneIframe();
		}
		else
		{

			# variable GetVariableString from table names - passes pid
			# here we are grabbing the proclaim_id variable and passing it through the post
			# so that we can use it in the next line of code.
			$this->email		=	strtolower(GetVariableString('email',$this->params));
			$this->case_key		=	GetVariableString('case_key',$this->params);
			$this->show			= 	GetVariableString('show',$this->params);

			# check the user
			$gUser	=	new User_Class();
			# now add the order_id
			if (($data = $gUser->getUser($this->case_key,$this->email)) == NULL)
			{

AddComment(" does not exist");
				# we should go to success page, or homepage with a popup
				gotoURL("/?pUpdate=noClaimRecord");
			}
			else
			{

				$this->title	=	ucfirst(strtolower($data['title']));
				$this->forename	=	ucfirst(strtolower($data['forename']));
				$this->surname	=	ucfirst(strtolower($data['surname']));
				$full_name		=	$this->title ." ". $this->forename ." ".$this->surname;
				$address		=	$data['address1']  . " "	.	$data['address2'] . " ".	$data['town']. " ".	$data['postcode'];
				$defendant		=	$data['defendant'];
				$full_date		=	$today = date("F j, Y");


				# date
				$d    = date('d');
				$m    = date('m');
				$y    = date('y');
				$date = $d . $m . $y;

				$this->filename = "DS" . $this->case_key . "_" . $date . "S.pdf";

				# decide if we need to build a new pdf, sign it or exit
				if($this->isCreated("clientcare") == true)
				{
					# if we have not signed it, we should display it and get it signed
					if(($this->isSigned("clientcare") == true) && ($this->show != 'yes'))
					{
					#	gotoURL("/clientcare/landlord?case_key=" .$this->case_key. "&email=" .$this->email."&reload=yes");
					#	exit;
AddComment("done");

						# goto the first unsigned or display done
						$this->gotoUnSigned();

						$this->displayDoneIframe();



					}
					# this will show the pdf from within the iframe
					else
					{
						AddComment("cb done");
						$cachebuster			= 	rand(0,100000);
						$this->sign_doc_url     =   $data['clientcare_sign_doc_url'] . "&embedded=true&cachebuster=$cachebuster";

						$this->appendTags(array	("{{meta_title}}" 			=>	"Ward Kemp"));
						$this->appendTags(array	("{{sign_doc_url}}" 		=>	$this->sign_doc_url));

					}
				}
				else
				{


					$client		=	new DigiSignerClient('aadd887d-fdfc-425c-b19b-550e49203e33');
					$request 	=	new SignatureRequest;

					# cfa document
					$template = Document::withID('8046a2a9-fa92-4ee1-a4e9-d096ffc5ca45');
					$template->setTitle('Form of Authority - '.$this->case_key);
					$request->addDocument($template);


					# this is within the iframe though
					$request->setRedirectAfterSigningToUrl("https://www.wardkemp.uk/clientcare/landlord?case_key=".$this->case_key."&email=".$this->email."&reload=yes&page=landlord");   // HERE YOU DEFINE THE REDIRECT URL


					$signer = new Signer('cedric@boxlegal.co.uk');
					$signer->setRole('Signer 1');
					$template->addSigner($signer);



					# name
					$full_name	=	"I, ".$full_name.", conﬁrm that:-";
					$field1 = new ExistingField('0a0752ab-48c6-4949-ad4c-9220fc913979');
					$field1->setContent($full_name);
					$field1->setReadOnly(true);
					$signer->addExistingField($field1);

					# address
					$field2 = new ExistingField('965d2709-fd00-4bc9-b712-100c312a5e44');
					$field2->setContent($address);
					$field2->setReadOnly(true);
					$signer->addExistingField($field2);


					# date
					$field3 = new ExistingField('9b72a0ce-24ac-403d-8c9a-6e9d48c66dd4');
					$field3->setContent($full_date);
					$field3->setReadOnly(true);
					$signer->addExistingField($field3);

					# email
					$field4 = new ExistingField('a385d08a-ec7d-48e2-9cf7-87e5ad7e36bb');
					$field4->setContent($this->email);
					$field4->setReadOnly(true);
					$signer->addExistingField($field4);



					$signatureRequest = $client->sendSignatureRequest($request);
					$signature_request_id	=	$signatureRequest->getId();
					$document = $signatureRequest->getDocuments();
					$document = $document[0];
					$signer = $document->getSigners();
					$signer = $signer[0];

					$this->document_id  =   $document->getId();
					$this->sign_doc_url =   $signer->getSignDocumentUrl();


					# insert pdf digisigner details into database
					$gMysql->update("update  ppi_user set clientcare_signed='', clientcare_signature_request_id='$signature_request_id' ,clientcare_document_id='$this->document_id', clientcare_sign_doc_url='$this->sign_doc_url', clientcare_created_date=NOW() where case_key='$this->case_key'", __FILE__, __LINE__);

					$cachebuster			= 	rand(0,100000);
					$this->sign_doc_url     =   $this->sign_doc_url . "&embedded=true&cachebuster=$cachebuster";

					$this->appendTags(array	("{{meta_title}}" 			=>	"Ward Kemp"));
					$this->appendTags(array	("{{sign_doc_url}}" 		=>	$this->sign_doc_url));


				}

			}

			if (isset($this->params['reload']))
			{
				$bRenderHeader	=	false;
			}



			$this->render($bRenderHeader);
		}
	}
















	# related to the authority
	public function landlord($bRenderHeader)
	{
		global $gMysql;
		$bRenderHeader	=	true;

		if	(isset($this->params['completed']))
		{
			# no header / footer version
			$this->displayDoneIframe();
		}
		else
		{

			# variable GetVariableString from table names - passes pid
			# here we are grabbing the proclaim_id variable and passing it through the post
			# so that we can use it in the next line of code.
			$this->email		=	strtolower(GetVariableString('email',$this->params));
			$this->case_key		=	GetVariableString('case_key',$this->params);
			$this->show			= 	GetVariableString('show',$this->params);

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

				$this->title	=	ucfirst(strtolower($data['title']));
				$this->forename	=	ucfirst(strtolower($data['forename']));
				$this->surname	=	ucfirst(strtolower($data['surname']));
				$full_name		=	$this->title ." ". $this->forename ." ".$this->surname;
				$address		=	$data['address1']  . " "	.	$data['address2'] . " ".	$data['town']. " ".	$data['postcode'];
				$defendant		=	$data['defendant'];
				$full_date		=	$today = date("F j, Y");


				# date
				$d    = date('d');
				$m    = date('m');
				$y    = date('y');
				$date = $d . $m . $y;

				$this->filename = "DS" . $this->case_key . "_" . $date . "S.pdf";

				# decide if we need to build a new pdf, sign it or exit
				if($this->isCreated("landlord") == true)
				{
					# if we have not signed it, we should display it and get it signed
					if(($this->isSigned("landlord") == true) && ($this->show != 'yes'))
					{

						# goto the first unsigned or display done
						$this->gotoUnSigned();

						$this->displayDoneIframe();

					}
					# this will show the pdf from within the iframe
					else
					{
						$cachebuster			= 	rand(0,100000);
						$this->sign_doc_url     =   $data['landlord_sign_doc_url'] . "&embedded=true&cachebuster=$cachebuster";

						$this->appendTags(array	("{{meta_title}}" 			=>	"Ward Kemp"));
						$this->appendTags(array	("{{sign_doc_url}}" 		=>	$this->sign_doc_url));


					}
				}
				else
				{

					$client		=	new DigiSignerClient('aadd887d-fdfc-425c-b19b-550e49203e33');
					$request 	=	new SignatureRequest;

					# cfa document
					$template = Document::withID('6f683e5b-a4c3-44a5-b3c5-aebc697ac243');
					$template->setTitle('Landlord Form of Authority - '.$this->case_key);
					$request->addDocument($template);


					# this is within the iframe though
					$request->setRedirectAfterSigningToUrl("https://www.wardkemp.uk/clientcare/phone?case_key=".$this->case_key."&email=".$this->email."&reload=yes&page=phone");   // HERE YOU DEFINE THE REDIRECT URL


					$signer = new Signer('cedric@boxlegal.co.uk');
					$signer->setRole('Signer 1');
					$template->addSigner($signer);



					# name
					$form_text	=	"I, $full_name residing at $address authorise my landlord to release all relevant documents requested by $this->company_name including, but not limited to, a copy of my tenancy agreement, documents or computerised records relating to notice given, disrepair reported, inspection reports or repair works to the property and any other documents in the tenancy file. ";



					# form
					$field1 = new ExistingField('ad95487e-7aa5-4ff9-aec7-5e4fb34d405b');
					$field1->setContent($form_text);
					$field1->setReadOnly(true);
					$signer->addExistingField($field1);

					# name
					$field2 = new ExistingField('1ffa0a08-6474-40ca-8bcb-468742627b6c');
					$field2->setContent($full_name);
					$field2->setReadOnly(true);
					$signer->addExistingField($field2);


					# date
					$field3 = new ExistingField('41131fbd-ade4-466f-b299-c2ed1b5352f6');
					$field3->setContent($full_date);
					$field3->setReadOnly(true);
					$signer->addExistingField($field3);


					$signatureRequest = $client->sendSignatureRequest($request);
					$signature_request_id	=	$signatureRequest->getId();
					$document = $signatureRequest->getDocuments();
					$document = $document[0];
					$signer = $document->getSigners();
					$signer = $signer[0];

					$this->document_id  			=   $document->getId();
					$this->sign_doc_url 			=   $signer->getSignDocumentUrl();


					# insert pdf digisigner details into database
					$gMysql->update("update  ppi_user set landlord_signed='', landlord_signature_request_id='$signature_request_id' ,landlord_document_id='$this->document_id', landlord_sign_doc_url='$this->sign_doc_url', landlord_created_date=NOW() where case_key='$this->case_key'", __FILE__, __LINE__);

					$cachebuster			= 	rand(0,100000);
					$this->sign_doc_url     =   $this->sign_doc_url . "&embedded=true&cachebuster=$cachebuster";

					$this->appendTags(array	("{{meta_title}}" 			=>	"Ward Kemp"));
					$this->appendTags(array	("{{sign_doc_url}}" 		=>	$this->sign_doc_url));

				}

			}


			if (isset($this->params['reload']))
			{
				$bRenderHeader	=	false;
			}

			$this->render($bRenderHeader);
		}
	}


































	# login just has the menu as per most outside pages
	public function render($bRenderHeader=true)
	{
		# creates the navigation
		$navbar_clientcare	=	$this->createNavBar();

		if (isset($this->params['reload']))
		{
			$javascript	=	"window.parent.location = document.referrer;";
		}


		//AddComment("Render Function Called by $this->refcode  and $this->childname ($this->userBrowserName, $this->userBrowserVer)");
		# we can push all these pre-filled tags onto the page
		$tags	=	array(

#			"{{javascript}}"	=>	$javascript,
			"{{email}}"			=>	$this->email,
			"{{case_key}}"		=>	$this->case_key,
			"{{uri}}"			=>	$this->params['uri'],



			"{{title}}"			=>	$this->title,
			"{{forename}}"		=>	$this->forename,
			"{{surname}}"		=>	$this->surname,
			"{{full_name}}"		=>	$this->full_name,
			"{{address}}"		=>	$this->address,
			"{{navbar_clientcare}}"		=>	$navbar_clientcare,

			"{{code}}"					=>	$this->policy_type,
			"{{client_text}}"			=>	$this->client_text,
			"{{logo}}"					=>	$this->logo,
			"{{company_name}}"			=>	$this->company_name,




		);

		$this->appendTags($tags);


		if ($bRenderHeader == true)
		{
			# ultimately show the page
			parent::render();
		}
		else
		{
			# pointer to the template library and pass it the template (we can probably do without passing the cache pointer)
			$gTemplate		=	new	Template_Library($this->getTemplate());

			# check for popup and append the old way
			$this->checkAppendPopup();
			$this->appendTags(array	("{{header}}" 		=>	""));
			$this->appendTags(array	("{{footer}}" 		=>	""));


			# how we want to display the page
			$gTemplate->display($this->tags);
			exit;

#			$this->view->render();
		}
	}






	# we do it this way, because we are potentially within an iframe when this is called, so it could
	private function displayNextIframe()
	{
		$this->template									=	"iframe.html";
		$this->appendTags(array	("{{meta_title}}" 		=>	"COMPLETED"));
		$this->appendTags(array	("{{page_title}}" 		=>	"Ward Kemp"));

		$this->appendTags(array	("{{blank_message}}" 	=>	"Thank You. We will now continue to the next form to sign..."));




	}





	# we do it this way, because we are potentially within an iframe when this is called, so it could
	private function displayDoneIframe()
	{
		$this->template									=	"iframe.html";
		$this->appendTags(array	("{{meta_title}}" 		=>	"COMPLETED"));
		$this->appendTags(array	("{{page_title}}" 		=>	"Ward Kemp"));
		$this->appendTags(array	("{{blank_message}}" 	=>	"Thank You. We will now continue to progress your claim. We will update you in due course."));
	}



	# we do it this way, because we are potentially within an iframe when this is called, so it could
	private function displayDone()
	{
		gotoURL("/?pUpdate=formSigned");

	}





























	# checks if this PDF is created
    private function isCreated($var)
    {

        global $gMysql;

        if 	(empty($var))
		{
			return false;
		}


		$var_signed			=	$var."_signed";
		$var_document_id	=	$var."_document_id";
		$var_sign_doc_url	=	$var."_sign_doc_url";
		$var_created_date	=	$var."_created_date";

        # check if it exists in the database
        $document_id	=	 $gMysql->QueryItem("SELECT $var_document_id FROM ppi_user where case_key='$this->case_key'", __FILE__, __LINE__);


		$client	=	new DigiSignerClient('aadd887d-fdfc-425c-b19b-550e49203e33');
   		$result	=	$client->getDocument($document_id, 'document.pdf');

        if	((empty($document_id)) || (empty($result)))
        {
            AddComment("PDF isCreated() document_id:$document_id NOT In DIGISIGNER, So removing this instance from the database so we can create another");
            # reset the document PDF / Digisigner details
            $gMysql->update("update ppi_user  set $var_signed='', $var_document_id='', $var_sign_doc_url='', $var_created_date='' where case_key='$this->case_key'", __FILE__, __LINE__);

            return false;
        }
        else
        {
            AddComment("PDF isCreated() document_id:$document_id VALID");
            return true;
        }

    }

    # checks if this PDF is signed
    private function isSigned($var)
    {
		global $gMysql;

		if 	(empty($var))
		{
			return false;
		}


		$var_signed			=	$var."_signed";

        # check if it exists in the database
        return $gMysql->QueryItem("SELECT count(*) FROM ppi_user where case_key='$this->case_key' and $var_signed='yes'", __FILE__, __LINE__);
    }






	# check if all docs signed
	public function checkAllSigned()
	{
		# check the user
		$gUser	=	new User_Class();

		$menu 		=	$this->menu;

		foreach	($menu as $menu_item)
		{
			$link			=	$menu_item['link'];
			$name			=	$menu_item['name'];

			# check this is signable
			if (isset($menu_item['cc']))
			{
				$var				=	$menu_item['var'];
				# has this been signed?
				$var_signed			=	$var."_signed";

				# check if we have signed the document
				if ($gUser->isSigned($this->case_key,$this->email,$var_signed) == false)
				{
					# we should head to that page (without the 'reload')
					return false;
				}
			}
		}

		return true;
	}





	# check if all docs signed
	public function gotoUnSigned()
	{
		# check the user
		$gUser	=	new User_Class();

		$menu 		=	$this->menu;

		foreach	($menu as $menu_item)
		{
			$link			=	$menu_item['link'];
			$name			=	$menu_item['name'];

			# check this is signable
			if (isset($menu_item['cc']))
			{
				$var				=	$menu_item['var'];
				# has this been signed?
				$var_signed			=	$var."_signed";

				# check if we have signed the document
				if ($this->isDocumentSigned($this->case_key,$this->email,$var) == false)
				{
					$url	=	"/$link/?case_key=" .$this->case_key. "&email=" .$this->email."&reload=yes";
AddComment("gotoUnSigned() $link ($url) as it is unsigned");
					gotoURL($url);
					exit;
				}
			}
		}

		return true;
	}





	# has user accepted
	public function isDocumentSigned($case_key,$email,$var)
	{

		global $gMysql;

		$var_signature_request_id 	=	$var ."_signature_request_id";

		AddComment("Is $var_signature_request_id signed?");

		$signature_request_id	= $gMysql->queryItem("select $var_signature_request_id from ppi_user where email='$email' and case_key='$case_key'",__FILE__,__LINE__);

		$client = new DigiSignerClient('aadd887d-fdfc-425c-b19b-550e49203e33');
		$signatureRequest = $client->getSignatureRequest($signature_request_id);

		$document = $signatureRequest->getDocuments();
		if (!empty($document))
		{
			$document = $document[0];
			$signer = $document->getSigners();
			$signer = $signer[0];
			$bIsSigned =   $signer->isSignatureCompleted();
			if ($bIsSigned)
			{
				AddComment("$var_signature_request_id IS!");
				return true;
			}
		}
		AddComment("$var_signature_request_id IS NOT!!!");
	}






	# based on page, set to active
	public function createNavBar()
	{

		$menu 		=	$this->menu;

		$email		=	$this->email;
		$case_key	=	$this->case_key;
		$code		=	$this->policy_type;

		# check the user
		$gUser	=	new User_Class();


		$number	=	0;


		$string	=	"
<div style='text-align:left;padding:0px;margin:0px;background-color:#f2f2f1;padding-bottom:10px;'>
		<ul class='nav nav-stacked nav-pills'>";


		foreach ($menu as $menu_item)
		{
			$link			=	$menu_item['link'];
			$name			=	$menu_item['name'];

			if (strcasecmp($link,$this->params['uri']) == 0)
			{
				$active_class	=	"active";
			}
			else
			{
				$active_class	=	"normal";

			}

			# green bar for CC if not signed
			if (isset($menu_item['cc']))
			{
				$number++;

				$icon	=			"";

				$var				=	$menu_item['var'];
				# has this been signed?
				$var_signed			=	$var."_signed";

				# check if we have signed the document
				if ($this->isDocumentSigned($case_key,$email,$var) == false)
				{
					$active_class	=	"sign";

					$icon	=		"<i class='fas fa-file-signature fa-lg' aria-hidden='true' ></i>";
				}
				else
				{
					continue;
				}
#				$string	.=	"<li class='$active_class'  onclick='signaturePreCheck();' data-email='$email' data-case_key='$case_key' style='cursor:pointer;'><a class='precheck' >$icon $name </a></li>";
				$string	.=	"<li class='$active_class'><a class='precheck' href='/$link?case_key=$case_key&email=$email'>$icon Sign Documents</a></li>";
				break;
			}

			else
			{
				$string	.=	"<li class='$active_class'><a href='/$link?case_key=$case_key&email=$email'>$name</a></li>";
			}


#			$string	.=	"<li class='$active_class'><a href='/$link?case_key=$case_key&email=$email'>$name<i class='fa fa-arrow-circle-down fa-lg pull-right' aria-hidden='true'></i></a></li>";



		}

		$string		.=	"

			</ul>


	</div><!-- /.navbar-collapse -->


<div class='clearBoth' style='clear:both;'></div>
		";

		return $string;
	}








































}



