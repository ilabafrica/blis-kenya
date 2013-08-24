<?php
#
# Main page for adding new quality control
#
include("redirect.php");
include("includes/header.php");
LangUtil::setPageId("catalog");
$script_elems->enableLatencyRecord();
?>
<script type='text/javascript'>
function check_input()
{
	// Validate
	var category_name = $('#category_name').attr("value");
	if(category_name == "")
	{
		alert("<?php echo LangUtil::$pageTerms['TIPS_MISSING_CATNAME']; ?>");
		return;
	}
	// All OK
	$('#new_test_category_form').submit();
}

</script>
<br>
<b><?php echo LangUtil::$pageTerms['NEW_LAB_SECTION']; ?></b>
| <a href='catalog.php?show_tc=1'><?php echo LangUtil::$generalTerms['CMD_CANCEL']; ?></a>
<br><br>
<div class='pretty_box'>
<form name='new_test_category_form' id='new_test_category_form' action='test_category_add.php' method='post'>
<table class='smaller_font'>
<tr>
<td style='width:150px;'><?php echo LangUtil::$generalTerms['NAME']; ?><?php $page_elems->getAsterisk(); ?></td>
<td><input type='text' name='category_name' id='category_name' class='uniform_width' /></td>
</tr>
<tr valign='top'>
<td><?php echo LangUtil::$generalTerms['DESCRIPTION']; ?></td>
<td><textarea name='category_descr' id='category_descr' class='uniform_width'></textarea></td>
<td></td></tr></table>
<br><br>
<input type='button' onclick='check_input();' value='<?php echo LangUtil::$generalTerms['CMD_SUBMIT']; ?>' />
&nbsp;&nbsp;&nbsp;&nbsp;
<a href='catalog.php?show_tc=1'> <?php echo LangUtil::$generalTerms['CMD_CANCEL']; ?></a>
</form>
</div>
<div id='test_category_help' style='display:none'>
<small>
Use Ctrl+F to search easily through the list. Ctrl+F will prompt a box where you can enter the test category you are looking for.
</small>
</div>
<?php include("includes/footer.php"); ?>