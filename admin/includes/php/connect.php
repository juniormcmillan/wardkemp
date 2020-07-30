<?php
$host = $_SERVER['HTTP_HOST'];

// CLEAN THE DIRTY SPAM
function cleanup($dirty) {
	// IF HACKED, $hacked IS GREATER THAN ZERO
	$hacked=0;
	
	// SPAM TERMS, DON'T USE COLONS FOR MORE UNUSUAL TERMS TO CATCH MORE SPAM
	$spam_terms=array();
	$spam_terms[]='bcc:';
	$spam_terms[]='cc:';
	$spam_terms[]='content-type';
	$spam_terms[]='mime-version';
	$spam_terms[]='to:';
	$spam_terms[]='from:';
	$spam_terms[]='content-transfer-encoding';
	
	// CHECK INPUT FOR SPAM TERMS
	foreach ($spam_terms as $val) {
		if (stristr($dirty,$val)) {
			$hacked++; } }
	
	// IF SPAMMED - DIE AND EMAIL MESSAGE WITH IP ADDRESS TO support@surefiremedia.co.uk
		$clean=$dirty;
		// RETURN CLEAN VARIABLE READY TO SEND IN EMAIL
		return $clean;
		}

?>