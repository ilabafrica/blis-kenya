<?php
#
# Main page for adding new test category
#
include("redirect.php");
include("../includes/page_elems.php");
require_once("includes/script_elems.php");
$script_elems = new ScriptElems();
$page_elems = new PageElems();

LangUtil::setPageId("catalog");
?>
<script type='text/javascript'>
function check_input()
{
	// Validate
	var drug_name = $('#drug_type_name').val();
	if(drug_name == "")
	{
		alert("<?php echo LangUtil::$pageTerms['TIPS_MISSING_DRUGNAME']; ?>");
		return;
	}
	// All OK
	$('#new_drug_type_form').submit();
}

</script>
<br>
<b style="margin-left:50px;"><?php echo LangUtil::$pageTerms['NEW_DRUG_TYPE']; ?></b>
| <a href='catalog.php?show_d=1'><?php echo LangUtil::$generalTerms['CMD_CANCEL']; ?></a>
<br><br>
<div class='pretty_box' style='margin-left:50px;' >
<form name='new_drug_type_form' id='new_drug_type_form' action='drug_type_add.php' method='post'>
<table class='smaller_font'>
<tr>
<td style='width:150px;'><?php echo LangUtil::$generalTerms['NAME']; ?><?php $page_elems->getAsterisk(); ?></td>
<td><input type='text' name='drug_type_name' id='drug_type_name' class='span4 m-wrap' /></td>
</tr>
<tr valign='top'>
<td><?php echo LangUtil::$generalTerms['DESCRIPTION']; ?></td>
<td><textarea name='drug_type_desc' id='drug_type_desc' class='span4 m-wrap'></textarea></td>
<td></td></tr></table>
<br><br>
<div class="form-actions">
                              <button type="submit" onclick='check_input();' class="btn blue"><?php echo LangUtil::$generalTerms['CMD_SUBMIT']; ?></button>
                              <a href='catalog.php?show_d=1' class='btn'> <?php echo LangUtil::$generalTerms['CMD_CANCEL']; ?></a>
                              </div>
</form>
</div>
<div id='drug_type_help' style='display:none'>
<small>
Use Ctrl+F to search easily through the list. Ctrl+F will prompt a box where you can enter the drug you are looking for.
</small>
</div>
<?php //include("includes/footer.php"); ?>

