<?php
#
# Main page for test category type info
# Called via Ajax from test_category_edit.php
#

include("../includes/db_lib.php");
include("../lang/lang_xml2php.php");

putUILog('drug_type_update', 'X', basename($_SERVER['REQUEST_URI'], ".php"), 'X', 'X', 'X');

$updated_entry = new DrugType();
$updated_entry->drugTypeId = $_REQUEST['did'];
$updated_entry->name = $_REQUEST['name'];
$updated_entry->description = $_REQUEST['description'];
$reff = 1;
update_drug_type($updated_entry);
# Update locale XML and generate PHP list again.
if($CATALOG_TRANSLATION === true)
	update_drug_type_xml($updated_entry->drugTypeId, $updated_entry->name);
?>