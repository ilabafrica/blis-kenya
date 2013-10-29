<?php
#
# Main page for adding new specimen rejection phase
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
	var phase_name = $('#phase_name').val();
	if(phase_name == "")
	{
		alert("<?php echo LangUtil::$pageTerms['TIPS_MISSING_CATNAME']; ?>");
		return;
	}
	else{
	// All OK
	$('#rejection_phase_form').submit();
	}
}

</script>
<br>
<b style="margin-left:50px;"><?php echo "Specimen Rejection Phase"; ?></b>
| <a href='catalog.php?show_tc=1'><?php echo LangUtil::$generalTerms['CMD_CANCEL']; ?></a>
<br><br>
<div class='pretty_box' style='margin-left:50px;' >
<form name='rejection_phase_form' id='rejection_phase_form' action='rejection_phase_add.php' method='post'>
<table class='smaller_font'>
<tr>
<td style='width:150px;'><?php echo LangUtil::$generalTerms['NAME']; ?><?php $page_elems->getAsterisk(); ?></td>
<td><input type='text' name='phase_name' id='phase_name' class='span4 m-wrap' /></td>
</tr>
<tr valign='top'>
<td><?php echo LangUtil::$generalTerms['DESCRIPTION']; ?></td>
<td><textarea name='phase_descr' id='phase_descr' class='span4 m-wrap'></textarea></td>
<td></td></tr></table>
<br><br>
<div class="form-actions">
                              <button type="submit" onclick='check_input();' class="btn blue"><?php echo LangUtil::$generalTerms['CMD_SUBMIT']; ?></button>
                              <a href='catalog.php?show_sr=1' class='btn'> <?php echo LangUtil::$generalTerms['CMD_CANCEL']; ?></a>
                              </div>
</form>
</div>
<div id='rejection_phase_help' style='display:none'>
<small>
Use Ctrl+F to search easily through the list. Ctrl+F will prompt a box where you can enter the rejection phase you are looking for.
</small>
</div>

