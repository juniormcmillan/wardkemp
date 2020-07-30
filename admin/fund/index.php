<?php
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");

require($_SERVER["DOCUMENT_ROOT"]."/lib/lib_mysql.php");
require($_SERVER["DOCUMENT_ROOT"]."/lib/common.php");


# create new class
$gMysql	=	new	MySQL_class;
# create connection to dbase
$gMysql->Create(null);


$pageTitle = "ATE Fund Message";


	$fund	=	$gMysql->QueryItem("SELECT fund FROM system",__FILE__,__LINE__);

	if	($fund==0)
	{
		$checked_off		=	'checked="checked"';
		$checked_on	=	'';
	}
	else
	{
		$checked_on		=	'checked="checked"';
		$checked_off	=	'';
	}



//echo $strSQL;exit();
$dbRsList = mysql_query($strSQL);
$count = mysql_num_rows($dbRsList);
?>

<link href="/includes/jquery-ui/css/flick/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/includes/js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="/includes/jquery-ui/js/jquery-ui-1.8.16.custom.min.js"></script>

<script language="javascript">

    jQuery(document).ready(function($){

    });
</script>


<div class="mainAdminFull">
	<h1>Display Fund Calculation Error Message</h1>
    <form name="frmSearch" id="frmLogin" method="post" action="./" class="formData" />
    	<fieldset>
		<p style="">
		<table   border=0 cellpading=10 cellspacing=10 style="font-size:12pt;">
		<tr>
			<td><input type="radio" name="fundStatus" id="fundStatusOff" <? echo $checked_off; ?> value=0>Off</td>
			<td><input type="radio" name="fundStatus" id="fundStatusOn" <? echo $checked_on; ?> value=1>On</td>
		</tr>
		</table>
		A message will appear on the Partner Statistics page to alert solicitors of a Fund Calculation Error
		</p>
     </fieldset>


	</form>
	</div>

<script>
/* this makes adjustments */



$(document).ready(function()
{
	$('input[type=radio][name=fundStatus]').change(function()
	{
		var value = $('input:radio[name=fundStatus]:checked').val();

		$.ajax(
		{

			url:		'doUpdateFundStatus.php',
			type:		'POST',
			cache:		false,
			async:		true,
			dataType:	'json',
			data:		{	value : value	},
			success:	function(data)
			{
				// if we have any error text
				if	(data.returncode == 'error')
				{
					alert('error');
				}
				else if (data.returncode == 'success')
				{
					alert('updated');
				}
			},

			failure:	function()
			{
				alert('failure');
			}
		}
	);

    });
});


</script>

