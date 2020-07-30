<?php
if(!isset($pageSection))
{
	$pageSection = "1";
}
switch($pageSection)
{
	case 1:
		echo '<li><a href="/admin/login.php?logout=1" onclick="return confirm(\'Are you sure?\');" rel="nofollow">Logout</a></li>';
		break;
	case 2:
		echo '<li><a href="detail.php" rel="nofollow">New Course</a></li>';
		break;
	case 3:
		echo '<li><a href="detail.php" rel="nofollow">New Course</a></li>
				<li><a href="./" rel="nofollow">Course List</a></li>';
		break;
	default:
		echo '<li><a href="/admin/login.php?logout=1" onclick="return confirm(\'Are you sure?\');" rel="nofollow">Logout</a></li>';
}
?>