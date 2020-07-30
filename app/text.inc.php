<?php


# gets details about a solicitor's text based on the type of code
# blank is the default
function getSolicitor($solicitor_code)
{
	global $gSolicitorText;
	foreach	($gSolicitorText as $solicitor)
	{
		if	($solicitor['solicitor_code']	== $solicitor_code)
		{
			return	$solicitor;
		}
	}

}



function getSolicitorViaCompany($company_id,$policy_type)
{
	global $gSolicitorText;
	foreach	($gSolicitorText as $solicitor)
	{
		if	($solicitor['company_id'] == $company_id)
		{
			if	($solicitor['policy_type'] == $policy_type)
			{
				return	$solicitor;
			}
		}
	}

}



# gets actual text pointer based on the policy type and version
function getPolicyText($policy_type,$text_version="")
{

	global $gPolicyText;
	foreach	($gPolicyText as $policy_text)
	{
		if	($policy_text['policy_type'] == $policy_type)
		{
			if (empty($text_version))
			{
				return $policy_text;
			}
			else if	($policy_text['text_version'] == $text_version)
			{
				return $policy_text;
			}
		}
	}
}



# gets data block of text based on the type of policy and version based on  firm
function getSolicitorPolicyText($solicitor_code)
{
	$solicitor	=	getSolicitor($solicitor_code);

	if (($pText	=	getPolicyText($solicitor['policy_type'],$solicitor['text_version'])) != NULL)
	{
		return	$pText['text'];
	}

}



# gets data block of text based on the type of policy and version based on  firm
function getCompanyPolicyText($company_id,$policy_type)
{
	$solicitor	=	getSolicitorViaCompany($company_id,$policy_type);

	if (($pText	=	getPolicyText($solicitor['policy_type'],$solicitor['text_version'])) != NULL)
	{
		return	$pText['text'];
	}

}









# this is used to find the correct text to use for a policy_type for a solicitor
# we may want to pass back the solicitor_code, so that SC = SCOTREESHD and SCOTREESPPI are known as SCOTREES the company
$gSolicitorText	=	array(

	array("solicitor_code" =>	"SCOTTREESHD"		,	"company_id"	=>		"SCOTTREES",			"policy_type"	=>	"HD",	"text_version"	=>	"A",		"logo"	=> "wk-7.jpg",	"name"	=>	"Scott Rees"),
	array("solicitor_code" =>	"SCOTREESSERPS"		,	"company_id"	=>		"SCOTTREES",			"policy_type"	=>	"B",	"text_version"	=>	"B",		"logo"	=> "ppi-solicitors-logo.png",	"name"	=>	"Scott Rees"),
	array("solicitor_code" =>	"BOXLEGALPPI"		,	"company_id"	=>		"BOXLEGAL",				"policy_type"	=>	"D",	"text_version"	=>	"A",		"logo"	=> "ppi-solicitors-logo.png",	"name"	=>	"Scott Rees"),

);


# pass the type of policy and the version we are using, then you can access the item of the page
$gPolicyText	=

	array(

		array("policy_type"	=>	"HD",	"text_version"	=>	"A",	"logo"	=> "website-wk-hd-logo.png",	"text"	=>	array(

			"case-accept"	=>	array(
				"accept" => "Please indicate that you have accepted this case by clicking on the relevant button below.<br><br><br><br>
			Many thanks",
				"reject" => "Please indicate that you have rejected this case by clicking on the relevant button below.<br><br><br><br>
			Many thanks",
			),


			"id-upload"	=>	array(
				"client_text" => "HOUSING DISREPAIR ID - We are required to carry out various identity checks when dealing with claims and so we need two pieces of ID from you before we can proceed any further.<br><br>
			The first is a photo ID e.g. the photo page from your passport or a driving licence. The second item is a household bill with your name on it which is less than 3 months old. You can take a photo of these if you like and upload the two documents below where indicated. We will keep them secure on our system and will not share them with anyone else.<br><br><br><br>
			Many thanks",
			),
			"damage-upload"	=>	array(
				"client_text" => "HOUSING DISREPAIR DAMAGE - We are required to carry out various identity checks when dealing with claims and so we need two pieces of ID from you before we can proceed any further.<br><br>
			The first is a photo ID e.g. the photo page from your passport or a driving licence. The second item is a household bill with your name on it which is less than 3 months old. You can take a photo of these if you like and upload the two documents below where indicated. We will keep them secure on our system and will not share them with anyone else.<br><br><br><br>
			Many thanks",
			),


			"client-care"	=>	array(
				"introduction" => "HOUSING DISREPAIR CLIENTCARE INTRO - 		<b>Thank you for choosing PPI Solicitors (trading name of Fairplane UK Ltd) to represent you regarding your PPI claim.</b><br><br>
			The information in this pack sets out the terms on which we will act for you. We are sorry that it is rather lengthy, but it needs to cover a number of practical matters as well as dealing with various regulatory issues which cannot be avoided.<br><br>
			Please read it carefully and let us know if anything is unclear. We suggest you print it for future reference in case you have any queries at a later date, however you will also be able to access this webpage at any time while you claim is ongoing.<br><br>
			If you are happy for us to act for you on the basis of these terms then you will need to concompany this by completing the electronic <u>form of authority</u> at the end of this pack. Please note we will not be able to start any work on your claim until this form of authority is completed.<br><br>
			",
				"financial-interests" => 'HOUSING DISREPAIR CLIENTCARE INTRO - 		
				
				<ol >

					<li> Financial Interests

						<ol >
							<li id="5_1">Referral fees/fee sharing</li>
							<div class="ppi-agreement-info" >
								We sometimes share up to 40% of the legal fees we deduct from your damages with an introducer of work. This does not increase our charges to you but is payment for marketing and for the referral of your claim to us.<br><br>
								If your claim was introduced to us by Ward Kemp Ltd then please note that our principal directors also own shares in Ward Kemp Ltd, and they will therefore derive a benefit from any referral fee which we pay to Ward Kemp Ltd.<br><br>
								Our referral fee arrangements vary from case by case, so please contact us if you require details of the referral fee applicable for your own claim.<br><br>

							</div>

							<li id="5_2">ATE legal expense insurance</li>
							<div class="ppi-agreement-info" >
								Please note that the broker of this policy is Box Legal Limited (“Box Legal”) which is owned by our principal directors. Box Legal does not receive any direct benefit or commission as a result of the policy recommended in section 3, but it is possible that it may in the future receive some indirect benefit from the policy’s insurer, although what type of benefit that may be, and the amount of it, is currently unknown.<br><br>

								Our principal directors also have an interest in Leeward Insurance Company Limited (“Leeward”) - the insurer of the policy recommended in section 3, and may receive a payment from Leeward in respect of Leeward’s annual profit, which will include Leeward’s profit (if any) on the premium for that policy. It is not possible to estimate the amount by which our directors will benefit from this policy (if at all), because any payment to them will depend on claim levels and Leeward’s overall profit in any year.<br><br>

								We are, however, confident that recommending this legal expense insurance product is nonetheless in your best interests, because it protects you against the financial risks of losing this claim and is at a cost which is competitive compared with the general ATE Insurance legal expense market.<br><br>


							</div>





						</ol>
					</li>

				</ol>
				
				
				
				',


			),



		)),
		array("policy_type"	=>	"B",	"text_version"	=>	"B",	"text"	=>	array("homepage"	=>	array("top"	=>	"dsdfds", "mid" => "xfdsfsdfsdfsdf", "bot"=> "ghfdfgerte"))),
		array("policy_type"	=>	"D",	"text_version"	=>	"A",	"text"	=>	array("homepage"	=>	array("top"	=>	"dsdfds", "mid" => "xfdsfsdfsdfsdf", "bot"=> "ghfdfgerte"))),
	);




