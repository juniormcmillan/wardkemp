<?php
/* * * * * * * * * * * * * * * * * * * * * * * * *
 *   GetAdditionalCertificateWording Function    *
 *                                               *
 *  This function can be used to select          *
 *  data from a database table                   *
 *  master_code where the variable $type         *
 *  exists if the variable data is empty         *
 *  then return false.                           *
 *                                               *
 * * * * * * * * * * * * * * * * * * * * * * * * */
function getAdditionalCertificateWording($type)
{
    # attempts to make a connection to the database
    global $gMysql;

    # gMysql attempting to SELECT the entire table called policy_document_details from proclaim_code, the info is stored in $data
    $data	=	$gMysql->queryRow("SELECT * from master_code WHERE type='$type'",__FILE__,__LINE__);

    # if the data variable is empty return false;
    if(empty($data)) {
        return false;
    }

    # if not return the data
    return $data;

}