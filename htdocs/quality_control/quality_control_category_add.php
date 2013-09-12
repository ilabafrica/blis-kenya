<?php
#
# Adds a new test type to catalog in DB
#
include("redirect.php");
include("includes/db_lib.php");
include("lang/lang_xml2php.php");


putUILog('quality_control_category_add', 'X', basename($_SERVER['REQUEST_URI'], ".php"), 'X', 'X', 'X');

	# Add new test category to catalog
	$new_quality_control_cat_name = $_REQUEST['category_name'];
	$new_quality_control_cat_id = add_quality_control_category($new_quality_control_cat_name);
	//$cat_code = $new_cat_id;

# Update locale XML and generate PHP list again.
if($CATALOG_TRANSLATION === true)
	update_qualitycontrolcategories_xml($new_quality_control_cat_id, $new_quality_control_cat_name);
/*
echo "<br>Hi".$sam;
echo "<br>";
print_r($submeasure_names);
*/

header("location: quality_control_category_added.php?qcc=$new_quality_control_cat_name");
?>