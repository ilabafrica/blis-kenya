<?php
#
# Adds a new test type to catalog in DB
#
include("redirect.php");
include("includes/db_lib.php");
include("lang/lang_xml2php.php");


putUILog('rejection_phase_add', 'X', basename($_SERVER['REQUEST_URI'], ".php"), 'X', 'X', 'X');


//$test_category_name = $_REQUEST['category_name'];
$rejection_phase_description = $_REQUEST['phase_descr'];

	# Add new test category to catalog
	$new_phase_name = $_REQUEST['phase_name'];
	$new_phase_id = add_rejection_phase($new_phase_name, $rejection_phase_description);

# Update locale XML and generate PHP list again.
if($CATALOG_TRANSLATION === true)
	update_rejectionphase_xml($new_phase_id, $new_phase_name);
/*
echo "<br>Hi".$sam;
echo "<br>";
print_r($submeasure_names);
*/

header("location: rejection_phase_added.php?rp=$new_phase_name");
?>