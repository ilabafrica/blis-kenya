<?php
#
# Main page for adding new test category
#
include("redirect.php");
include("includes/header.php");
LangUtil::setPageId("quality");
?>
<br>
<b><?php echo "New Quality Control"; ?></b>
| <a href='quality.php?show_qc=1'><?php echo LangUtil::$generalTerms['CMD_CANCEL']; ?></a>
<br><br>
<div class='pretty_box'>
<div id="my_form_builder">
</div>
<div id='quality_control_help' style='display:none'>
<small>
Use Ctrl+F to search easily through the list. Ctrl+F will prompt a box where you can enter the test category you are looking for.
</small>
</div>

<script type='text/javascript'>
function check_input()
{
	// Validate
	var category_name = $('#category_name').val();
	if(category_name == "")
	{
		alert("<?php echo "Error: Missing quality control category name"; ?>");
		return;
	}
	// All OK
	$('#new_quality_control_category_form').submit();
}

</script>

<?php 
$script_elems->enableFormBuilder();
$script_elems->enableFacebox();
$script_elems->enableBootstrap();
include("includes/footer.php"); ?>