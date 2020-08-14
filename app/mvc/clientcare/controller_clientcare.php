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
			# we should go to success page, or homepage with a popup
			gotoURL("/?pUpdate=noClaimRecord");
		}


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



	# sets out questions to be asked that forms the client statement
	public function client_statement()
	{
		# check the user
		$gUser	=	new User_Class();
		# now the questions
		$questions_string	= $gUser->getUserStatementData($this->case_key);


		//AddComment("Render Function Called by $this->refcode  and $this->childname ($this->userBrowserName, $this->userBrowserVer)");
		# we can push all these pre-filled tags onto the page
		$tags	=	array(

			"{{email}}"				=>	$this->email,
			"{{case_key}}"			=>	$this->case_key,
			"{{questions_string}}"	=>	$questions_string,

			"{{title}}"				=>	$this->title,
			"{{forename}}"			=>	$this->forename,
			"{{surname}}"			=>	$this->surname,

			"{{code}}"					=>	$this->policy_type,
			"{{client_text}}"			=>	$this->client_text,
			"{{logo}}"					=>	$this->logo,

		);

		$this->appendTags($tags);

		parent::render();
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

					$client		=	new DigiSignerClient('aadd887d-fdfc-425c-b19b-550e49203e33');
					$request 	=	new SignatureRequest;

					$template = Document::withID('839f923b-5eea-4435-acfd-9b713040731b');
					$template->setTitle('Form Of Authority - '.$this->case_key);
					$request->addDocument($template);




					# this is within the iframe though
					$request->setRedirectAfterSigningToUrl("https://www.ppisolicitors.co.uk/clientcare/dsar?case_key=".$this->case_key."&email=".$this->email."&reload=yes");   // HERE YOU DEFINE THE REDIRECT URL


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
        $field 		= new Field(0, array(98,425,535,455), Field::TYPE_SIGNATURE);
        $signer->addField($field);

#  		$field2 	= new Field(0, array(485,193,545,215), Field::TYPE_DATE);
#		$signer->addField($field2);

        $document->addSigner($signer);
        $request->addDocument($document);

		# this is within the iframe though
#		$request->setRedirectAfterSigningToUrl("https://www.ppisolicitors.co.uk/clientcare/dsar?case_key=".$this->case_key."&email=".$this->email."&completed=true");   // HERE YOU DEFINE THE REDIRECT URL
		$request->setRedirectAfterSigningToUrl("https://www.ppisolicitors.co.uk/clientcare/dsar/?case_key=&email=&completed=true");   // HERE YOU DEFINE THE REDIRECT URL

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

		$full_address	=	$this->address	.	", "	.	$this->city	.	", "	.	$this->postcode;

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

        $oPdf->SetCreator("Fairplane");
        $oPdf->SetFont('Arial', 'B', 20);


        $oPdf->SetFillColor(221, 221, 221);
        $oPdf->SetDrawColor(10, 10, 10);

        $oPdf->SetXY(10,10);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 16, "0,0,0" );
        $oMulticell->MultiCell(200, 8, '<p>Data Subject Access Request Authorisation</p>', 0, "C", false);


        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );

        // 1st section
        $oPdf->SetXY(20, 28);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
        $oMulticell->MultiCell(200, 4,
            '<p>This authority relates to:</p>'
            , 0, "J", false);

        $oPdf->SetXY(20, 35);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
        $oMulticell->MultiCell(200, 4,
            '<p>1.    All information, documents and data held in relation to flight number ' .$this->airline_code.$this->flight_number . ' from ' . $this->dep_airport_name . '</p>'
            , 0, "J", false);


		$oPdf->SetXY(20, 40);

		$oMulticell->MultiCell(200, 4,
			'<p>to ' . $this->arr_airport_name . ' on the ' . $this->flight_date . ' and operated by ' . $this->airline_name . '</p>'
			, 0, "J", false);



		$oPdf->SetXY(20, 45);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
        $oMulticell->MultiCell(200, 4,
            '<p>or any other airline or flight operator including booking reference. </p>'
            , 0, "J", false);

        $oPdf->SetXY(20, 55);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
        $oMulticell->MultiCell(200, 4,
            '<p>2.   All information, documents and data held by '. $this->airline_name .' or any other airline or flight operator in  </p>'
            , 0, "J", false);

        $oPdf->SetXY(20, 60);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
        $oMulticell->MultiCell(200, 4,
            '<p>relation to flights on which I have travelled which were booked with them, such data to include Flight Number, </p>'
            , 0, "J", false);

        $oPdf->SetXY(20, 65);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
        $oMulticell->SetStyle( "b", $oPdf->getDefaultFontName(), "b", 10, "0,0,0" );
        $oMulticell->MultiCell(200, 4,
            '<p>Flight Date, Departure Airport and Arrival Airport and booking references, for the 6 years immediately preceding </p>'
            , 0, "J", false);

        $oPdf->SetXY(20, 70);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
        $oMulticell->SetStyle( "b", $oPdf->getDefaultFontName(), "b", 10, "0,0,0" );
        $oMulticell->MultiCell(200, 4,
            '<p>the date of this authority, the above being defined as (&#34;My Data&#34;). </p>'
            , 0, "J", false);

        // 2nd section
        $oPdf->SetXY(20, 83);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
        $oMulticell->MultiCell(200, 4,
            '<p>I hereby: </p>'
            , 0, "J", false);

        $oPdf->SetXY(20, 90);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
        $oMulticell->MultiCell(200, 4,
            '<p>A)   Authorise my solicitor FairPlane UK Limited of ' . $this->firm_address . ' to request</p>'
            , 0, "J", false);

        $oPdf->SetXY(20, 95);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
        $oMulticell->MultiCell(200, 4,
            '<p>disclosure of My Data (under General Data Protection Regulation 2016/679 or otherwise) held by</p>'
            , 0, "J", false);

        $oPdf->SetXY(20, 100);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
        $oMulticell->MultiCell(200, 4,
            '<p>'. $this->airline_name . ' or any other airline or flight operator, and </p>'
            , 0, "J", false);

        $oPdf->SetXY(20, 105);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
        $oMulticell->MultiCell(200, 4,
            '<p>B)   authorise '. $this->airline_name .' or any other airline or flight operator to supply My Data </p>'
            , 0, "J", false);

        $oPdf->SetXY(20, 110);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
        $oMulticell->SetStyle( "b", $oPdf->getDefaultFontName(), "b", 10, "0,0,0" );
        $oMulticell->MultiCell(200, 4,
            '<p>directly to FairPlane UK Limited, and</p>'
            , 0, "J", false);

        $oPdf->SetXY(20, 115);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
        $oMulticell->SetStyle( "b", $oPdf->getDefaultFontName(), "b", 10, "0,0,0" );
        $oMulticell->MultiCell(200, 4,
            '<p>C)   authorise Fairplane UK Limited to receive My Data directly from the organisation to whom the above </p>'
            , 0, "J", false);

        $oPdf->SetXY(20, 120);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
        $oMulticell->SetStyle( "b", $oPdf->getDefaultFontName(), "b", 10, "0,0,0" );
        $oMulticell->MultiCell(200, 4,
            '<p>request is made.</p>'
            , 0, "J", false);

        // Sign section
        $oPdf->SetXY(20, 144);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
        $oMulticell->SetStyle( "b", $oPdf->getDefaultFontName(), "b", 10, "0,0,0" );
        $oMulticell->MultiCell(200, 4,
            '<p><b>Signed:  </b></p>'
            , 0, "J", false);

        $oPdf->SetXY(34, 145);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
        $oMulticell->SetStyle( "b", $oPdf->getDefaultFontName(), "b", 10, "0,0,0" );
        $oMulticell->MultiCell(200, 4,
            '<p><b>..............................................................................................................................................................</b></p>'
            , 0, "J", false);

        $oPdf->SetXY(20, 154);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
        $oMulticell->SetStyle( "b", $oPdf->getDefaultFontName(), "b", 10, "0,0,0" );
        $oMulticell->MultiCell(200, 4,
            '<p><b>Name:  ' . $this->clientcare_fullname .  '</b></p>'
            , 0, "J", false);

        $oPdf->SetXY(31, 155);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
        $oMulticell->SetStyle( "b", $oPdf->getDefaultFontName(), "b", 10, "0,0,0" );
        $oMulticell->MultiCell(200, 4,
            '<p><b>.................................................................................................................................................................</b></p>'
            , 0, "J", false);


        $oPdf->SetXY(20, 165);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
        $oMulticell->SetStyle( "b", $oPdf->getDefaultFontName(), "b", 10, "0,0,0" );
        $oMulticell->MultiCell(200, 4,
            '<p><b>Address:  ' . $full_address . '</b></p>'
            , 0, "J", false);


        $oPdf->SetXY(36, 166);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
        $oMulticell->SetStyle( "b", $oPdf->getDefaultFontName(), "b", 10, "0,0,0" );
        $oMulticell->MultiCell(200, 4,
            '<p><b>............................................................................................................................................................</b></p>'
            , 0, "J", false);



        $oPdf->SetXY(20, 175);
        $oMulticell->SetStyle( "p", $oPdf->getDefaultFontName(), "", 10, "0,0,0" );
        $oMulticell->SetStyle( "b", $oPdf->getDefaultFontName(), "b", 10, "0,0,0" );
        $oMulticell->MultiCell(200, 4,
            '<p><b>Dated:  ' . $today_date . '</b></p>'
            , 0, "J", false);


        $oPdf->SetXY(31, 176);
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

            "{{flight_number}}"			=>	$this->flight_number,
            "{{dep_airport_code}}"		=>	$this->dep_airport_code,
            "{{arr_airport_code}}"		=>	$this->arr_airport_code,
            "{{airline_name}}"			=>	$this->airline_name,
            "{{flight_date}}"			=>	$this->flight_date,
            "{{dep_airport_name}}"		=>	$this->dep_airport_name,
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
			array(	"name" => "Agreement", 																		"link" =>	"clientcare/agreement"						),
			array(	"name" => "Your claim", 																	"link" =>	"clientcare/your-claim"					),
			array(	"name" => "Our charges (No Win No Fee Agreements)", 										"link" =>	"clientcare/our-charges"						),
			array(	"name" => "Conditional Fee Agreement", 														"link" =>	"clientcare/cfa"						),
			array(	"name" => "Protecting yourself financially (ATE expense Insurance)", 						"link" =>	"clientcare/ate-insurance"					),
#			array(	"name" => "IPID", 								"link" =>	"clientcare/ipid"							),
			array(	"name" => "Regulatory status and complaints", 												"link" =>	"clientcare/regulatory-status"							),
			array(	"name" => "Financial Interests ",									 						"link" =>	"clientcare/financial-interests"			),
			array(	"name" => "Form of Authority", 																"link" =>	"clientcare/authority",					"cc"		=> 	"yes" ),
			array(	"name" => "DSAR", 																			"link" =>	"clientcare/dsar",						"dsar"		=>	"yes"	),
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



		$string	=	"
	<div class='ppi-website-navbar collapse navbar-collapse' id='bs-example-navbar-collapse-1' style='text-align:left;padding:0px;margin:0px;background-color:#f2f2f1;padding-bottom:10px;'>
		<ul class='nav nav-stacked vertical-menu'>";

	
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
				$active_class	=	"";

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



