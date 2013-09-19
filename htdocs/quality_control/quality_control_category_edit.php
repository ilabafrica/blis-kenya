<?php
#
# Main page for modifying an existing specimen type
#
include("redirect.php");
include("includes/header.php");
include("includes/ajax_lib.php");
LangUtil::setPageId("quality");

$qcc = get_quality_control_category_by_id($_REQUEST['qccid']);
?>
<script type='text/javascript'>
function update_quality_control_category()
{
	if($('#name').attr("value").trim() == "")
	{
		alert("<?php echo "Error: Missing Quality Control Category Name."; ?>");
		return;
	}
	$('#update_qcc_progress').show();
	$('#edit_quality_control_category_form').ajaxSubmit({
		success: function(msg) {
			$('#update_qcc_progress').hide();
			window.location="quality_control_category_updated.php?qccid=<?php echo $_REQUEST['qccid']; ?>";
		}
	});
}
</script>
<br>
<b><?php echo "Edit Quality Control Category"; ?></b>
| <a href="quality.php?show_qcc=1"><?php echo LangUtil::$generalTerms['CMD_CANCEL']; ?></a>
<br><br>
<?php
if($qcc == null)
{
?>
	<div class='sidetip_nopos'>
	<?php echo LangUtil::$generalTerms['MSG_NOTFOUND']; ?>
	</div>
<?php
	include("includes/footer.php");
	return;
}
$page_elems->getQualityControlCategoryInfo($qcc->name, true);
?>
<br>
<br>
<div class='pretty_box'>
<form name='edit_quality_control_category_form' id='edit_quality_control_category_form' action='ajax/quality_control_category_update.php' method='post'>
<input type='hidden' name='qccid' id='qccid' value='<?php echo $_REQUEST['qccid']; ?>'></input>
	<table cellspacing='4px'>
		<tbody>
			<tr valign='top'>
				<td style='width:150px;'><?php echo LangUtil::$generalTerms['NAME']; ?><?php $page_elems->getAsterisk(); ?></td>
				<td><input type='text' name='name' id='name' class='span12 m-wrap' value='<?php echo $qcc->getName(); ?>' class='uniform_width'></input></td>
			</tr>
			<tr>
				<td></td>
				<td>
                
                <div class="form-actions">
                      <button type="submit" onclick='javascript:update_quality_control_category();' class="btn blue"><?php echo LangUtil::$generalTerms['CMD_SUBMIT']; ?></button>
                      <a href='quality.php?show_qcc=1' class='btn'> <?php echo LangUtil::$generalTerms['CMD_CANCEL']; ?></a>
                </div>
               	<span id='update_qcc_progress' style='display:none;'>
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
<?php 
$script_elems->enableJQuery();
$script_elems->enableTokenInput();
include("includes/footer.php"); ?>