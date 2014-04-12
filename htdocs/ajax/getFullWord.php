<?php


/*
* Returns the full word for the abbreviation provided
* i.e SA may be Staphilococuss Aureaus or something else depends on what is set
*/

// include("../includes/libdata.php");
// Still testing

$abbreviation = $_REQUEST['abb'];

$word = '{"abb": "Staphilococuss Aureaus"}';//getFullword($abbreviation);

if ($word != "") {
	echo $word;
}
?>