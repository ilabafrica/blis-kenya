<?php
#
# Main page for modifying an existing specimen type
#
include("redirect.php");
include("includes/header.php");
include("includes/ajax_lib.php");
LangUtil::setPageId("catalog");


$rejection_phase = get_rejection_phase_by_id($_REQUEST['rp']);
?>

<br>
<b><?php echo "Edit Specimen Rejection Phase"; ?></b>
| <a href="catalog.php?show_rp=1"><?php echo LangUtil::$generalTerms['CMD_CANCEL']; ?></a>
<br><br>
<?php
if($rejection_phase == null)
{
?>
	<div class='sidetip_nopos'>
	<?php echo LangUtil::$generalTerms['MSG_NOTFOUND']; ?>
	</div>
<?php
	include("includes/footer.php");
	return;
}
$page_elems->getRejectionPhaseInfo($rejection_phase->name, true);
?>
<br>
<br>
<div class='pretty_box'>
<form name='edit_rejection_phase_form' id='edit_rejection_phase_form' action='ajax/rejection_phase_update.php' method='post'>
<input type='hidden' name='tcid' id='tcid' value='<?php echo $_REQUEST['tcid']; ?>'></input>
	<table cellspacing='4px'>
		<tbody>
			<tr valign='top'>
				<td style='width:150px;'><?php echo LangUtil::$generalTerms['NAME']; ?><?php $page_elems->getAsterisk(); ?></td>
				<td><input type='text' name='name' id='name' class='span12 m-wrap' value='<?php echo $rejection_phase->getName(); ?>' class='uniform_width'></input></td>
			</tr>
			<tr valign='top'>
				<td><?php echo LangUtil::$generalTerms['DESCRIPTION']; ?></td>
				<td><textarea type='text' name='description' id='description' class='span12 m-wrap'><?php echo trim($rejection_phase->description); ?></textarea></td>
			</tr>

			<tr>
				<td></td>
				<td>
                
                <div class="form-actions">

                      <input class='btn yellow' type='button' value='<?php echo LangUtil::$generalTerms['CMD_SUBMIT']; ?>' onclick='javascript:update_rejection_phase_category();'></input>
                      <a href='catalog.php?show_tc=1' class='btn'> <?php echo LangUtil::$generalTerms['CMD_CANCEL']; ?></a>
                </div>
               	<span id='update_rejection_phase_progress' style='display:none;'>
						<?php $page_elems->getProgressSpinner(LangUtil::$generalTerms['CMD_SUBMITTING']); ?>
					</span>
				</td>
			</tr>
		</tbody>
	</table>
    
</form>
</div>
<div id='test_help' style='display:none'>
<small>
Use Ctrl+F to search easily through the list. Ctrl+F will prompt a box where you can enter the test name you are looking for.
</small>
</div>
<script type='text/javascript'>
function update_rejection_phase_category()
{
	if($('#name').attr("value").trim() == "")
	{
		alert("<?php echo LangUtil::$pageTerms['TIPS_MISSING_CATNAME']; ?>");
		return;
	}
	$('#update_rejection_phase_progress').show();
	$('#edit_rejection_phase_form').ajaxSubmit({
		success: function(msg) {
			$('#update_testcategory_progress').hide();
			window.location="rejection_phase_updated.php?rp=<?php echo $_REQUEST['rp']; ?>";
		}
	});
}
</script>
<?php 
include("includes/scripts.php");
$script_elems->enableDatePicker();
$script_elems->enableJQuery();
$script_elems->enableJQueryForm();
$script_elems->enableTokenInput();
$script_elems->enableFacebox();
include("includes/footer.php"); ?>