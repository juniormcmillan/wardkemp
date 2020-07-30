<?php
/**
 * Created by PhpStorm.
 * User: Jack
 * Date: 13/06/2018
 * Time: 15:47
 */

if(!isset($pageTitle))
{
    $pageTitle = "ATE Policy Type";
}
if(!isset($pageSection))
{
    $pageSection = "2";
}
//phpinfo();
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/header.php");
?>
    <div class="mainAdminPage">
        <h1><?php echo $pageTitle; ?></h1>
        <div class="mainAdminLeft">
            <ul>
                <li><a href="/admin/ate_policy_type/edit_cert/">Edit Certificate</a></li>
            </ul>
        </div>
    </div>
<?php
include($_SERVER["DOCUMENT_ROOT"]."/admin/includes/php/footer.php");
?>