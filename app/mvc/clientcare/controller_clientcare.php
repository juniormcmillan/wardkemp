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
			if	(isset($this->params['completed']))
			{
				# no header / footer version
				$this->displayDoneIframe();
				return;

			}
			else
			{
				# we should go to success page, or homepage with a popup
				gotoURL("/?pUpdate=noClaimRecord");
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




	# related to the authority
	public function authority()
	{
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


				# date
				$d    = date('d');
				$m    = date('m');
				$y    = date('y');
				$date = $d . $m . $y;

				$this->filename = "DS" . $this->case_key . "_" . $date . "S.pdf";

				# decide if we need to build a new pdf, sign it or exit
				if($this->isCreated() == true)
				{
					# if we have not signed it, we should display it and get it signed
					if(($this->isSigned() == true) && ($this->show != 'yes'))
					{
						$this->displayDone();
					}
					# this will show the pdf from within the iframe
					else
					{
						$this->displayPDF();
					}
				}
				else
				{
					# creates the pdf document and places copy on server
					$pdf	    =	$this->createPDF();
					# uploads pdf to digisigner repository
					$uPdf	    =	$this->uploadPDF();
					# insert pdf digisigner details into database
					$this->insertPDF();
					# display of the PDF from within the Digisigner system in an iframe
					$this->displayPDF();


				}
			}


			$this->render();
		}
	}






	# related to the authority
	public function clientCare()
	{
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


				# date
				$d    = date('d');
				$m    = date('m');
				$y    = date('y');
				$date = $d . $m . $y;

				$this->filename = "DS" . $this->case_key . "_" . $date . "S.pdf";

/*				# decide if we need to build a new pdf, sign it or exit
				if($this->isCreated() == true)
				{
					# if we have not signed it, we should display it and get it signed
					if(($this->isSigned() == true) && ($this->show != 'yes'))
					{
						$this->displayDone();
					}
					# this will show the pdf from within the iframe
					else
					{
						$this->displayPDF();
					}
				}
				else
*/				{

					$client		=	new DigiSignerClient('aadd887d-fdfc-425c-b19b-550e49203e33');
					$request 	=	new SignatureRequest;

					$template = Document::withID('82c76a82-b5c2-4706-a60c-c752db2a7117');
					$template->setTitle('Form Of Authority - '.$this->case_key);
					$request->addDocument($template);




					# this is within the iframe though
					$request->setRedirectAfterSigningToUrl("http://www.wardkemp.uk/clientcare/?case_key=".$this->case_key."&email=".$this->email."&reload=yes");   // HERE YOU DEFINE THE REDIRECT URL


					$signer = new Signer('cedric@boxlegal.co.uk');
					$signer->setRole('Signer 1');
					$template->addSigner($signer);




					$signatureRequest = $client->sendSignatureRequest($request);
					$document = $signatureRequest->getDocuments();
					$document = $document[0];
					$signer = $document->getSigners();
					$signer = $signer[0];

					$this->document_id  =   $document->getId();
					$this->sign_doc_url =   $signer->getSignDocumentUrl();


					# insert pdf digisigner details into database
					$this->insertPDF();
					# display of the PDF from within the Digisigner system in an iframe
					$this->displayPDF();
				}
			}


			$this->render();
		}
	}











	# login just has the menu as per most outside pages
	public function render()
	{
		# creates the navigation
		$navbar_clientcare	=	$this->createNavBar();

		$this->getData();

		# check the user
		$gUser	=	new User_Class();
		# now add the order_id
		if (($data = $gUser->getUserPage($this->case_key,$this->email,$this->params['uri'])) != NULL)
		{
			$accepted	=	"accepted";
		}
		else
		{
			$accepted	=	"";
		}


		$signature_string	=	"

<button class='btn  btn-success text-block'   type='button'  id='signClientAuthority'  name='signClientAuthority' onclick='signaturePreCheck();'>Sign Client Authority Letter&nbsp;&nbsp;<i class='fas fa-file-signature fa-lg' aria-hidden='true'></i></button>	

		";



		# check if we have signed the document
		if ($gUser->isClientCareSigned($this->case_key,$this->email) == true)
		{
			$signature_string	=	"

<button class='btn  btn-success text-block'   type='button'  id='signClientAuthority'  name='signClientAuthority' onclick='signaturePreCheck();'>Sign DSAR Letter&nbsp;&nbsp;<i class='fas fa-file-signature fa-lg' aria-hidden='true'></i></button>	

		";

		}
		else if ($gUser->isDsarSigned($this->case_key,$this->email) == true)
		{
			$signature_string	=	"";
		}





		//AddComment("Render Function Called by $this->refcode  and $this->childname ($this->userBrowserName, $this->userBrowserVer)");
		# we can push all these pre-filled tags onto the page
		$tags	=	array(

			"{{email}}"			=>	$this->email,
			"{{case_key}}"		=>	$this->case_key,
			"{{uri}}"			=>	$this->params['uri'],

			"{{accepted}}"			=>	$accepted,
			"{{signature_string}}"			=>	$signature_string,




			"{{title}}"			=>	$this->title,
			"{{forename}}"		=>	$this->forename,
			"{{surname}}"		=>	$this->surname,
			"{{full_name}}"		=>	$this->full_name,
			"{{address}}"		=>	$this->address,
			"{{navbar_clientcare}}"		=>	$navbar_clientcare,

			"{{code}}"					=>	$this->policy_type,
			"{{client_text}}"			=>	$this->client_text,
			"{{logo}}"					=>	$this->logo,
			"{{company_name}}"					=>	$this->company_name,




		);

		$this->appendTags($tags);

		# ultimately show the page
		parent::render();
	}





	# we do it this way, because we are potentially within an iframe when this is called, so it could
	private function displayDoneIframe()
	{
		$this->template									=	"iframe.html";
		$this->appendTags(array	("{{meta_title}}" 		=>	"COMPLETED"));
		$this->appendTags(array	("{{page_title}}" 		=>	"PPI Solicitors"));
		$this->appendTags(array	("{{blank_message}}" 	=>	"Thank You. We will now continue to progress your claim. We will update you in due course."));
	}



	# we do it this way, because we are potentially within an iframe when this is called, so it could
	private function displayDone()
	{
		gotoURL("/?pUpdate=formSigned");

	}


	# checks if this PDF is created
    private function isCreated()
    {
        global $gMysql;
        # check if it exists in the database
        $document_id	=	 $gMysql->QueryItem("SELECT clientcare_document_id FROM ppi_user where case_key='$this->case_key'", __FILE__, __LINE__);

        $client	=	new DigiSignerClient('aadd887d-fdfc-425c-b19b-550e49203e33');
        $result	=	$client->getDocument($document_id, 'document.pdf');


        if	(empty($result))
        {
            AddComment("PDF isCreated() document_id:$document_id NOT In DIGISIGNER, So removing this instance from the database so we can create another");
            # reset the document PDF / Digisigner details
            $gMysql->update("update ppi_user  set clientcare_signed='', clientcare_document_id='', clientcare_sign_doc_url='', clientcare_created_date='' where case_key='$this->case_key'", __FILE__, __LINE__);

            return false;
        }
        else
        {
            AddComment("PDF isCreated() document_id:$document_id VALID");
            return true;
        }

    }

    # checks if this PDF is signed
    private function isSigned()
    {
        global $gMysql;
        # check if it exists in the database
        return $gMysql->QueryItem("SELECT count(*) FROM ppi_user where case_key='$this->case_key' and clientcare_signed='yes'", __FILE__, __LINE__);
    }

    # insert pdf details into database
    private function insertPDF()
    {
        global $gMysql;

        $gMysql->update("update  ppi_user set clientcare_signed='', clientcare_document_id='$this->document_id', clientcare_sign_doc_url='$this->sign_doc_url', clientcare_created_date=NOW() where case_key='$this->case_key'", __FILE__, __LINE__);
    }




	# grab the pdf info from the database and prep for display
	private function displayPDF()
	{
		global $gMysql;
		# check if it exists in the database
		if  (($data = $gMysql->QueryRow("SELECT * FROM ppi_user where case_key='$this->case_key'", __FILE__, __LINE__)) != NULL)
		{
			$cachebuster			= 	rand(0,100000);
			$this->document_id      =   $data['clientcare_document_id'];
			$this->sign_doc_url     =   $data['clientcare_sign_doc_url'] . "&embedded=true&cachebuster=$cachebuster";

			$this->appendTags(array	("{{meta_title}}" 			=>	"Fairplane Authority"));
			$this->appendTags(array	("{{sign_doc_url}}" 		=>	$this->sign_doc_url));
		}

	}


    # grab the pdf info from the database and prep for display
    private function getData()
    {
        global $gMysql;

        # we have passed through the case_key from the database here we are going to ask for all (*) of the items from
        if (($data = $gMysql->queryRow("SELECT * FROM ppi_user WHERE case_key='$this->case_key'  ", __FILE__, __LINE__)) != NULL)
		{
	        # else the variable $this->forename is equal to the data from the database under $data['forename'] (** this is data in the database **) etc ...
			$this->title	 	= $data['title'];
			$this->forename 	= $data['forename'];
            $this->surname 		= $data['surname'];

            $this->full_name	=	$this->forename	." ".$this->surname;

			$this->address1		= $data['address1'];
			$this->address2		= $data['address2'];
			$this->town 		= $data['town'];
			$this->postcode 	= $data['postcode'];

			$this->address		=	$this->address1 . ", ";
			if ($this->address2)
				$this->address	.=	$this->address2 . ", ";
			if ($this->town)
				$this->address	.=	$this->town . ", ";
			$this->address	.=	$this->postcode;
        }

    }





    # this uploads the PDF document to the digisigner repository
    private function uploadPDF()
    {
        # upload the PDF so we can then sign it
        $client		= new DigiSignerClient($this->digisigner_key);
        $request	= new SignatureRequest;
        $request->setEmbedded(true);
        $request->setSendEmails(false);
        $request->setUseTextTags(true);
#		$request->setHideTextTags(true);
        $document	= new Document($this->folder . $this->filename);
        $document->setMessage('Hello world!');
        $document->setSubject('Please, sign my sample document!');
        $signer 	= new Signer($this->signer_email);
        $signer->setRole('signer_role');
        $field 		= new Field(0, array(98,485,535,525), Field::TYPE_SIGNATURE);
        $signer->addField($field);

        $document->addSigner($signer);
        $request->addDocument($document);

		# this is within the iframe though
		$request->setRedirectAfterSigningToUrl("https://www.ppisolicitors.co.uk/clientcare/dsar?case_key=".$this->case_key."&email=".$this->email."&completed=true");   // HERE YOU DEFINE THE REDIRECT URL
#		$request->setRedirectAfterSigningToUrl("http://www.wardkemp.uk/clientcare/introduction?case_key=&email=&completed=true");   // HERE YOU DEFINE THE REDIRECT URL

        $signatureRequest = $client->sendSignatureRequest($request);
        $document = $signatureRequest->getDocuments();
        $document = $document[0];
        $signer = $document->getSigners();
        $signer = $signer[0];

        $this->document_id  =   $document->getId();
        $this->sign_doc_url =   $signer->getSignDocumentUrl();


#		AddComment("DSAR:",var_dump($signatureRequest));
    }




	# this creates the PDF document
    private function createPDF()
    {
        $this->clientcare_fullname = $this->forename . " " . $this->surname;

		$name_of_solicitor	=	$this->company_name;


        $today_date = date('jS F, Y');


        $factory = new pdfFactory();
        // create new PDF document
        $oPdf = new myPdfMulticell("P", "mm", "A4");
        $factory->initPdfObject( $oPdf );
        $oMulticell = new PdfMulticell( $oPdf );

        $oPdf->SetFont('Arial', '', 20);

        # tag,font,style,size,color
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 9, "0,0,0" );
        $oMulticell->SetStyle( "b", $oPdf->getDefaultFontName(), "B", 9, "0,0,0" );
        $oMulticell->setStyle( "i", $oPdf->getDefaultFontName(), "I", 7, "0,0,0" );
        $oMulticell->SetStyle( "h1", $oPdf->getDefaultFontName(), "", 9, "80,80,260" );
        $oMulticell->SetStyle( "h3", $oPdf->getDefaultFontName(), "B", 9, "203,0,48" );
        $oMulticell->SetStyle( "h4", $oPdf->getDefaultFontName(), "BI", 9, "0,151,200" );
        $oMulticell->SetStyle( "hh", $oPdf->getDefaultFontName(), "B", 9, "255,189,12" );
        $oMulticell->SetStyle( "ss", $oPdf->getDefaultFontName(), "", 7, "203,0,48" );
        $oMulticell->SetStyle( "font", $oPdf->getDefaultFontName(), "", 9, "0,0,255" );
        $oMulticell->SetStyle( "style", $oPdf->getDefaultFontName(), "BI", 9, "0,0,220" );
        $oMulticell->SetStyle( "size", $oPdf->getDefaultFontName(), "BI", 9, "0,0,120" );
        $oMulticell->SetStyle( "smallest", $oPdf->getDefaultFontName(), "", 8, "0,0,0" );

        $oPdf->SetCreator("WardKemp");
        $oPdf->SetFont('Arial', 'B', 20);


        $oPdf->SetFillColor(221, 221, 221);
        $oPdf->SetDrawColor(10, 10, 10);

        $oPdf->SetXY(10,10);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 16, "0,0,0" );
        $oMulticell->MultiCell(200, 8, '<p>Form Of Authority</p>', 0, "C", false);


        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );


        $oPdf->SetXY(20, 30);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
        $oMulticell->MultiCell(200, 4,
            '<p>I confirm I have read the '. $name_of_solicitor.' client care pack, including: </p>'
            , 0, "J", false);


		$oPdf->SetXY(25, 40);

		$oMulticell->MultiCell(200, 4,
			'<p>1.  Conditional Fee Agreement (No win no fee agreement)</p>'
			, 0, "J", false);



		$oPdf->SetXY(25, 45);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
        $oMulticell->MultiCell(200, 4,
            '<p>2.  Protecting Yourself Financially (ATE Legal Expense Insurance)</p>'
            , 0, "J", false);



		$oPdf->SetXY(25, 50);
		$oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
		$oMulticell->MultiCell(200, 4,
			'<p>3. Insurance Product Information Document (IPID)</p>'
			, 0, "J", false);




		$oPdf->SetXY(25, 55);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
        $oMulticell->MultiCell(200, 4,
            '<p>4. Client Care Information</p>'
            , 0, "J", false);

        $oPdf->SetXY(20, 65);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
        $oMulticell->MultiCell(175, 4,
            "<p>I instruct Ward Kemp to refer my case to ". $name_of_solicitor." and to pass them my contact details, having read Ward Kemp's terms and conditions and in particular the section titled Financial Interests.

I would like ". $name_of_solicitor." to act on my behalf and help me pursue my claim for damages on the basis of the CFA, with the protection from legal costs as provided by the ATE legal expense insurance policy.

In the event that my claim is successful, I irrevocably authorise ". $name_of_solicitor." to have any damages recovered paid to them and for them to deduct the agreed charges, in accordance with the CFA and for them to further deduct the ATE legal expense insurance premium, where appropriate, and pay this to the insurer on my behalf.
  
</p>"
            , 0, "J", false);


        // Sign section
        $oPdf->SetXY(20, 124);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
        $oMulticell->SetStyle( "b", $oPdf->getDefaultFontName(), "b", 10, "0,0,0" );
        $oMulticell->MultiCell(200, 4,
            '<p><b>Signed:  </b></p>'
            , 0, "J", false);

        $oPdf->SetXY(34, 125);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
        $oMulticell->SetStyle( "b", $oPdf->getDefaultFontName(), "b", 10, "0,0,0" );
        $oMulticell->MultiCell(200, 4,
            '<p><b>..............................................................................................................................................................</b></p>'
            , 0, "J", false);

        $oPdf->SetXY(20, 134);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
        $oMulticell->SetStyle( "b", $oPdf->getDefaultFontName(), "b", 10, "0,0,0" );
        $oMulticell->MultiCell(200, 4,
            '<p><b>Name:  ' . $this->clientcare_fullname .  '</b></p>'
            , 0, "J", false);

        $oPdf->SetXY(31, 135);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
        $oMulticell->SetStyle( "b", $oPdf->getDefaultFontName(), "b", 10, "0,0,0" );
        $oMulticell->MultiCell(200, 4,
            '<p><b>.................................................................................................................................................................</b></p>'
            , 0, "J", false);

        $oPdf->SetXY(20, 145);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
        $oMulticell->SetStyle( "b", $oPdf->getDefaultFontName(), "b", 10, "0,0,0" );
        $oMulticell->MultiCell(200, 4,
            '<p><b>Date:  ' . $today_date . '</b></p>'
            , 0, "J", false);


        $oPdf->SetXY(31, 146);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
        $oMulticell->SetStyle( "b", $oPdf->getDefaultFontName(), "b", 10, "0,0,0" );
        $oMulticell->MultiCell(200, 4,
            '<p><b>.................................................................................................................................................................</b></p>'
            , 0, "J", false);


        $oPdf->Line(10, 290, 200, 290);

        # variable is equal to oPdf->Output( to
        $pdf_string = $oPdf->Output('file', 'S');

        $filename = $this->folder . $this->filename;

        # store the PDF we have generated
        file_put_contents($filename, $pdf_string);


        # we can push all these pre-filled tags onto the page
        $tags	=	array(

             "{{arr_airport_name}}"		=>	$this->arr_airport_name,

        );

        $this->appendTags($tags);




     # send the pdf to the browser
		/* $oPdf->Output();
		 exit;*/






    }




    function bullet($pdf,$cellwidth = '0')
    {
        //bullet point is chr(127)
        $pdf->Cell($cellwidth, 5, chr(127), 0, 0, 'R');

    }


    function checkbox ( $pdf, $x, $y, $checked= false,$size=4 )
    {
        $pdf->Rect( $x, $y, $size, $size );
        if ( $checked)
        {
            $w		=	$size/2;
            $offset	=	$w/2;

            $pdf->Line( $x+$offset, $y+$offset, $x + $w + $offset, $y + $w + $offset);

            $pdf->Line( $x+$offset, $y+$offset + $w, $x + $offset + $w, $y + $offset);

        }
    }



	# based on page, set to active
	public function createNavBar()
	{

		$email		=	$this->email;
		$case_key	=	$this->case_key;
		$code		=	$this->policy_type;


		$menu	=	array(

			array(	"name" => "Introduction", 																	"link" =>	"clientcare/introduction"					),
#			array(	"name" => "Your claim", 																	"link" =>	"clientcare/your-claim"					),
#			array(	"name" => "Our charges (No Win No Fee Agreements)", 										"link" =>	"clientcare/our-charges"						),
			array(	"name" => "Client Care Letter", 															"link" =>	"clientcare/letter",										),
			array(	"name" => "Conditional Fee Agreement (No win no fee agreement)", 							"link" =>	"clientcare/cfa"										),
			array(	"name" => "Protecting Yourself Financially (ATE Legal Expense Insurance)", 						"link" =>	"clientcare/ate-insurance"								),
			array(	"name" => "Insurance Product Information Document (IPID)", 									"link" =>	"clientcare/ipid"										),
#			array(	"name" => "Regulatory status and complaints", 												"link" =>	"clientcare/regulatory-status"							),
#			array(	"name" => "Financial Interests ",									 						"link" =>	"clientcare/financial-interests"						),
			array(	"name" => "Sign Client Authority Letter", 																	"link" =>	"clientcare/sign",						"cc"		=> 	"yes" ),
#			array(	"name" => "Form of Authority", 																"link" =>	"clientcare/authority",					"cc"		=> 	"yes" ),
#			array(	"name" => "DSAR", 																			"link" =>	"clientcare/dsar",						"dsar"		=>	"yes"	),
		);


		# for menu items
		$bDSARSigned	=	false;
		$bCCSigned		=	false;

		# check the user
		$gUser	=	new User_Class();
		

		# check if we have signed the document
		if ($gUser->isClientCareSigned($case_key,$email) == true)
		{
			$bCCSigned	=	true;
		}
		else if ($gUser->isDsarSigned($case_key,$email) == true)
		{
			$bDSARSigned	=	true;
		}



/*
 *
 *
 *
 <ul class="nav nav-pills nav-stacked col-md-3">
    <li><a href="#a" data-toggle="tab">1</a></li>
    <li><a href="#b" data-toggle="tab">2</a></li>
    <li><a href="#c" data-toggle="tab">3</a></li>
</ul>
 *
 * */




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
				# not signed
				if ($bCCSigned == false)
				{
					$active_class	=	"sign";
				}
				$string	.=	"<li class='$active_class'  onclick='signaturePreCheck();' data-email='$email' data-case_key='$case_key' style='cursor:pointer;'><a class='precheck' >$name</a></li>";
			}
			# we have signed the CC
			else if (isset($menu_item['dsar']))
			{
				# CC not signed, so skip this row
				if ($bCCSigned == false)
				{
					continue;
				}
				if ($bDSARSigned == false)
				{
					$active_class	=	"sign";
				}
				$string	.=	"<li class='$active_class'  onclick='signaturePreCheck();' data-email='$email' data-case_key='$case_key' style='cursor:pointer;'><a class='precheck' >$name</a></li>";
			}
			else
			{
				$string	.=	"<li class='$active_class'><a href='/$link?case_key=$case_key&email=$email&code=$code'>$name</a></li>";
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


































	# login just has the menu as per most outside pages
	public function renderFinancial()
	{

		$this->client_text				=	$this->pPolicyText['client-care']['financial-interests'];

		$this->render();
	}















}



