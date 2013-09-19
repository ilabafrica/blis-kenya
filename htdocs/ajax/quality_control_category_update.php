<?php
#
# Main page for updating specimen type info
# Called via Ajax from specimen_type_edit.php
#

include("../includes/db_lib.php");
include("../lang/lang_xml2php.php");

$updated_entry = new QualityControlCategories();
$updated_entry->qccId = $_REQUEST['qccid'];
$updated_entry->name = $_REQUEST['name'];
$reff = 1;
$qcc_list = get_quality_control_categories($lab_config_id, $reff);
$updated_qcc_list = array();
foreach($qcc_list as $key=>$value)
{
	$field_tocheck = "qc_category_".$key;
	if(isset($_REQUEST[$field_tocheck]))
	{
		$updated_qcc_list[]  = $key;
	}
}
update_quality_control_category($updated_entry, $updated_qcc_list);
# Update locale XML and generate PHP list again.
if($CATALOG_TRANSLATION === true)
	update_quality_control_category_xml($updated_entry->qccId, $updated_entry->name);
?>