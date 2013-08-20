<?php
#
# Adds a new test type to catalog in DB
#
include("redirect.php");
include("includes/db_lib.php");
include("lang/lang_xml2php.php");


putUILog('test_category_add', 'X', basename($_SERVER['REQUEST_URI'], ".php"), 'X', 'X', 'X');


//$test_category_name = $_REQUEST['category_name'];
$test_category_description = $_REQUEST['category_descr'];

	# Add new test category to catalog
	$new_cat_name = $_REQUEST['category_name'];
	$new_cat_id = add_test_category($new_cat_name);
	//$cat_code = $new_cat_id;

# Update locale XML and generate PHP list again.
if($CATALOG_TRANSLATION === true)
	update_testtype_xml($new_cat_id, $new_cat_name);
/*
echo "<br>Hi".$sam;
echo "<br>";
print_r($submeasure_names);
*/

header("location: test_category_added.php?tcn=$new_cat_name");
?>