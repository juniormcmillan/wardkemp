<?php
$pageTitle = "User List";
$pageSection = "6";
$subSection = "1";
if ( ! isset($_SERVER['DOCUMENT_ROOT'] ) )
{
	$_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0-strlen($_SERVER['PHP_SELF']) ) );
}
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");

$dbRsList = mysql_query("SELECT id, name, status FROM sm_admin_user WHERE id > 1 ORDER BY name");
$count = mysql_num_rows($dbRsList);
?>
<div class="mainAdminPage">
    <div class="mainAdminFull">
        <h1><?php echo $pageTitle ?></h1>
        <?php
            if($count > 0)
            {
                echo '<table cellspacing="0" class="listTable">
                        <tr>
                            <th>Name</th>
                            <th width="110">&nbsp;</th>
                            <th width="60">&nbsp;</th>
                            <th width="60">&nbsp;</th>
                        </tr>';
                while($item = mysql_fetch_array($dbRsList))
                {
                    $id = $item["id"];
                    $name = $item["name"];
                    $status = $item["status"];
                    if($id == $user->userID)
					{
						$statusLink = "DEACTIVATE";
					}
					elseif($status == "A")
                    {
                        $statusLink = '<a href="#" rel="nofollow" onclick="do_action('.$id.',\'Deactivate\')">DEACTIVATE</a>';
                    }
                    else
                    {
                        $statusLink = '<a href="#" rel="nofollow" onclick="do_action('.$id.',\'Activate\')">ACTIVATE</a>';
                    }
                    if($id != $user->userID)
                    {
                        $deleteLink = '<a href="#" rel="nofollow" onclick="do_action('.$id.',\'Delete\')">DELETE</a>';
                    }
                    else
                    {
                        $deleteLink = 'DELETE';
                    }
                    echo '<tr class="trInitial" onmouseover="this.className=\'trHighlight\';" onmouseout="this.className=\'trInitial\';" >
                            <td><strong>'.urldecode($name).'</strong></td>
                            <td>'.$statusLink.'</td>
                            <td><a href="#" rel="nofollow" onclick="do_action('.$id.')">EDIT</a></td>
                            <td>'.$deleteLink.'</td>
                        </tr>';
                }
                echo '</table>';
            }
            else
            {
                echo '<p>There are no Admin Users to show</p>';
            }
        ?>
        <hr/>
        <ul>
            <li><a href="detail.php" rel="nofollow">New User</a></li>
        </ul>
    </div>
</div>
<?php
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>