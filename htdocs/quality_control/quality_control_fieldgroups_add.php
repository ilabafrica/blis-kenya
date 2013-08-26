<?php
#
# Adds a new test type to catalog in DB
#
include("redirect.php");
include("includes/db_lib.php");
include("lang/lang_xml2php.php");


putUILog('quality_control_fieldgroups_add', 'X', basename($_SERVER['REQUEST_URI'], ".php"), 'X', 'X', 'X');

	# Add new test category to catalog
	$new_quality_control_field_group = $_REQUEST['group_name'];
	$new_quality_control_field_group_id = add_quality_control_field_group($new_quality_control_field_group, "$user");


# Update locale XML and generate PHP list again.
if($CATALOG_TRANSLATION === true)
	update_qualitycontrolfieldgroups_xml($new_quality_control_field_group_id, $new_quality_control_field_group);
/*
echo "<br>Hi".$sam;
echo "<br>";
print_r($submeasure_names);
*/

header("location: quality_control_fieldgroups_added.php?qcc=$new_quality_control_field_group");
?>