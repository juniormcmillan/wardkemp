<?php
/* * * * * * * * * * * * * * * * * * * * *
 *   getPolicyCertificateData Function   *
 *                                       *
 *  This function can be used to select  *
 *  data from a database table           *
 *  policy_document_details where the    *
 *  variable proclaim_code exists        *
 *  if the variable data is empty then   *
 *  return false.                        *
 *                                       *
 * * * * * * * * * * * * * * * * * * * * */
function getPolicyCertificateData($proclaim_code)
{
    # attempts to make a connection to the database
    global $gMysql;


    # gMysql attempting to SELECT the entire table called policy_document_details from proclaim_code, the info is stored in $data
    $data	=	$gMysql->queryRow("SELECT * from policy_document_details WHERE proclaim_code='$proclaim_code'",__FILE__,__LINE__);

    # if the data variable is empty return false;
    if(empty($data)) {
        return false;
    }

    # if not return the data
    return $data;

}