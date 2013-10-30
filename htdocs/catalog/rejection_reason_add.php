<?php
#
# Adds a new test type to catalog in DB
#
include("redirect.php");
include("includes/db_lib.php");
include("lang/lang_xml2php.php");


putUILog('rejection_reason_add', 'X', basename($_SERVER['REQUEST_URI'], ".php"), 'X', 'X', 'X');


//$test_category_name = $_REQUEST['category_name'];
$rejection_phase = $_REQUEST['phase'];

	# Add new test category to catalog
	$new_reason_name = $_REQUEST['reason_name'];
	$new_reason_id = add_rejection_reason($new_reason_name, $rejection_phase);

# Update locale XML and generate PHP list again.
if($CATALOG_TRANSLATION === true)
	update_rejectionreason_xml($new_reason_id, $new_reason_name);
/*
echo "<br>Hi".$sam;
echo "<br>";
print_r($submeasure_names);
*/

header("location: rejection_reason_added.php?rr=$new_reason_name");
?>