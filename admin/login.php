<?php
/*ini_set('display_errors',1);
error_reporting(E_ALL);*/
$checkLogin = false;
require_once ($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/access.class.php");
$user = new flexibleAccess();
if ( $user->is_loaded() )
{
	header("Location: /admin/");
}
elseif ( $_GET['logout'] == 1 )
{
	$user->logout('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
}
elseif ( isset($_POST['uname']) && isset($_POST['pwd']))
{
	if ( !$user->login($_POST['uname'],$_POST['pwd'],$_POST['remember'] ))
	{//Mention that we don't have to use addslashes as the class do the job
		$msg = 'Wrong username and/or password';
  	}
	else
	{//user is now loaded
		header("Location: /admin/");
  	}
}
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");
?>
<div class="mainAdminFull">
	<h1>Login</h1>
	<form name="frmLogin" id="frmLogin" method="post" action="login.php" class="formData" />
	 <p><label for="uname">Username:</label><input type="text" name="uname" id="uname" class="formInputData" /></p>
	 <p><label for="pwd">Password:</label> <input type="password" name="pwd" id="pwd" class="formInputData" /></p>
	 <p><label>&nbsp;</label><input type="submit" value="Login" class="formButton" /></p>
	</form>
</div>
<?php
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>