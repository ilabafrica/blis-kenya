<?php

/**
 *	Helper for handling updates deletions and additions of abbreviation and words
 *
 */

include("../includes/libdata.php");

$id = $_REQUEST['id'];
$word = $_REQUEST['word'];
$abb = $_REQUEST['abb'];
$action = $_REQUEST['action'];

if($action == "update")
{
	Abbreviations::updateAbbreviation($id, $abb, $word);
}
else if ($action == "delete") {
	Abbreviations::removeAbbreviation($id);
}
else if ($action == "add") {
	Abbreviations::addabbreviation($abb, $word);
}
else {
	// Do nothing
	return false;
}




?>