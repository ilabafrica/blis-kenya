<?php
#
# Adds a new test type to catalog in DB
#
include("redirect.php");
include("includes/db_lib.php");
include("lang/lang_xml2php.php");


putUILog('drug_type_add', 'X', basename($_SERVER['REQUEST_URI'], ".php"), 'X', 'X', 'X');


$drug_description = $_REQUEST['drug_type_desc'];

	# Add new drug
	$new_drug_name = $_REQUEST['drug_type_name'];
	$new_cat_id = add_drug_type($new_drug_name, $drug_description);

header("location: drug_type_added.php?d=$new_drug_name");
?>