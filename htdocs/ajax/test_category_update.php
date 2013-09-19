<?php
#
# Main page for test category type info
# Called via Ajax from test_category_edit.php
#

include("../includes/db_lib.php");
include("../lang/lang_xml2php.php");

putUILog('test_category_update', 'X', basename($_SERVER['REQUEST_URI'], ".php"), 'X', 'X', 'X');

$updated_entry = new TestCategory();
$updated_entry->testCategoryId = $_REQUEST['tcid'];
$updated_entry->name = $_REQUEST['name'];
$updated_entry->description = $_REQUEST['description'];
$reff = 1;
update_test_category($updated_entry);
# Update locale XML and generate PHP list again.
if($CATALOG_TRANSLATION === true)
	update_testcategory_xml($updated_entry->testCategoryId, $updated_entry->name);
?>