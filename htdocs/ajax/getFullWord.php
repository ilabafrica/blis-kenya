<?php

/**
 *	 Returns the full word for the abbreviation provided 
 *	 i.e SA may be Staphilococuss Aureaus 
 */

include("../includes/libdata.php");

$abbreviation = $_REQUEST['abb'];

$obj = Abbreviations::searchByAbbreviation($abbreviation);

if ($obj->word != null) {
	echo json_encode($obj);
}
?>