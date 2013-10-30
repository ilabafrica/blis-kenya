<?php
#
# Main page for adding new specimen rejection reason
#
include("redirect.php");
include("../includes/page_elems.php");
require_once("includes/script_elems.php");
require_once("includes/db_lib.php");
$script_elems = new ScriptElems();
$page_elems = new PageElems();

LangUtil::setPageId("catalog");
?>
<script type='text/javascript'>
function check_input()
{
	// Validate
	var reason_name = $('#reason_name').val();
	if(reason_name == "")
	{
		alert("<?php echo LangUtil::$pageTerms['TIPS_MISSING_CATNAME']; ?>");
		return;
	}
	else{
	// All OK
	$('#rejection_reason_form').submit();
	}
}

</script>
<br>
<b style="margin-left:50px;"><?php echo "Specimen Rejection Reason"; ?></b>
| <a href='catalog.php?show_tc=1'><?php echo LangUtil::$generalTerms['CMD_CANCEL']; ?></a>
<br><br>
<div class='pretty_box' style='margin-left:50px;' >
<form name='rejection_reason_form' id='rejection_reason_form' action='rejection_reason_add.php' method='post'>
<table class='smaller_font'>
<tr>
<td style='width:150px;'><?php echo LangUtil::$generalTerms['NAME']; ?><?php $page_elems->getAsterisk(); ?></td>
<td><input type='text' name='reason_name' id='reason_name' class='span4 m-wrap' /></td>
</tr>
<tr valign='top'>
<td><?php echo "Specimen Rejection Phase"; ?><?php $page_elems->getAsterisk(); ?></td>
<td><select name="phase" id="phase"><?php $page_elems->getRejectionPhasesSelect(); ?></select></td>
<td></td></tr></table>
<br><br>
<div class="form-actions">
                              <button type="submit" onclick='check_input();' class="btn blue"><?php echo LangUtil::$generalTerms['CMD_SUBMIT']; ?></button>
                              <a href='catalog.php?show_sr=1' class='btn'> <?php echo LangUtil::$generalTerms['CMD_CANCEL']; ?></a>
                              </div>
</form>
</div>
<div id='rejection_reason_help' style='display:none'>
<small>
Use Ctrl+F to search easily through the list. Ctrl+F will prompt a box where you can enter the rejection reason you are looking for.
</small>
</div>

