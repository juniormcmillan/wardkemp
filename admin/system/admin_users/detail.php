<?php
$pageTitle = "User Detail";
$pageSection = "6";
$subSection = "1";
if ( ! isset($_SERVER['DOCUMENT_ROOT'] ) )
{
	$_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0-strlen($_SERVER['PHP_SELF']) ) );
}
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");
$formErrors = '';
$tmpDbAction = 'Insert';
$tmpName = '';
$tmpLogin = '';
$tmpPassword = '';
$tmpEmail = '';

if(isset($_POST["dbAction"]))
{
	$dbAction = $_POST["dbAction"];
}
elseif(isset($_GET["dbAction"]))
{
	$dbAction = $_GET["dbAction"];
}
else
{
	$dbAction = '';
}

if(isset($_POST["iId"]))
{
	$tmpId = intval($_POST["iId"]);
}
elseif(isset($_GET["iId"]))
{
	$tmpId = intval($_GET["iId"]);
}

# CM - 20/4/15
if	(empty($tmpId))
{
	$dbAction	=	"Insert";
}


if($dbAction == "Deactivate" && $tmpId > 0)
{
	mysql_query("UPDATE sm_admin_user SET status = 'D' WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}
elseif($dbAction == "Activate" && $tmpId > 0)
{
	mysql_query("UPDATE sm_admin_user SET status = 'A' WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}
elseif($dbAction == "Delete" && $tmpId > 0)
{
	mysql_query("DELETE FROM sm_admin_user WHERE id = $tmpId");
	ob_end_clean();
	header("Location: ./");
	exit();
}

if(isset($_POST["txtName"]) && isset($_POST["txtLogin"]))
{
	$tmpName = urlencode($_POST["txtName"]);
	$tmpLogin = $_POST["txtLogin"];
	$tmpPassword = $_POST["txtPassword"];
	$tmpEmail = urlencode($_POST["txtEmail"]);

	if($formErrors == "")
	{
		if($dbAction == "Insert")
		{
			$strSQL = "INSERT INTO sm_admin_user (name,login,password,email,status) VALUES ('$tmpName','$tmpLogin','$tmpPassword','$tmpEmail','A')";
			mysql_query($strSQL);
		}
		else
		{
			$strSQL = "UPDATE sm_admin_user SET name = '$tmpName', login = '$tmpLogin', password = '$tmpPassword', email = '$tmpEmail' WHERE id = $tmpId";
			mysql_query($strSQL);
		}
		ob_end_clean();
		header("Location: ./");
		exit();
	}
}

if($tmpId > 0 && $formErrors == "")
{
	$tmpDbAction = "Update";
	$dbRsItem = mysql_query("SELECT * FROM sm_admin_user WHERE id = " . $tmpId);
	$item = mysql_fetch_array($dbRsItem);
	$tmpName = $item["name"];
	$tmpLogin = $item["login"];
	$tmpPassword = $item["password"];
	$tmpEmail = $item["email"];
}
else
{
	$tmpDbAction = $dbAction;
}

if($tmpId > 0)
{
	$msgAction = "edit a User";
}
else
{
	$msgAction = "add a User";
}

?>
<div class="mainAdminPage">
	<div class="mainAdminFull"> 
        <h1><?php echo $pageTitle ?></h1>
        <?php
            if($formErrors != "")
            {
                echo "<p><strong>ERROR:</strong></p><ul>$formErrors</ul>";
            }
        ?>
        <p>Use this form to <?php echo $msgAction; ?></p>
        <form name="frmData" id="frmData" method="post" class="formEditor" enctype="multipart/form-data" />
            <input type="hidden" name="dbAction" id="dbAction" value="<?php echo $tmpDbAction; ?>" />
            <input type="hidden" name="iId" id="iId" value="<?php echo $tmpId; ?>" />
            <fieldset>
                <legend>User Details</legend>
                <p>
                    <label for="txtName">Name</label>
                    <input type="text" name="txtName" id="txtName" maxlength="100" value="<?php echo urldecode($tmpName); ?>" class="formInputData" />
                </p>
                <p>
                    <label for="txtLogin">Login</label>
                    <input type="text" name="txtLogin" id="txtLogin" maxlength="30" value="<?php echo $tmpLogin; ?>" class="formInputData" />
                </p>
                <p>
                    <label for="txtPassword">Password</label>
                    <input type="text" name="txtPassword" id="txtPassword" maxlength="20" value="<?php echo $tmpPassword; ?>" class="formInputData" />
                </p>
                <p>
                    <label for="txtEmail">Email</label>
                    <input type="text" name="txtEmail" id="txtEmail" maxlength="255" value="<?php echo urldecode($tmpEmail); ?>" class="formInputData" />
                </p>
            </fieldset>
            <p>
                <input type="button" name="btnBack" id="btnBack" value="Back" onClick="window.location='./';" class="formButton" />
                <input type="submit" name="btnSubmit" id="btnSubmit" value="Save" class="formButton" />
            </p>
        </form>
	</div>
</div>
    
<?
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>