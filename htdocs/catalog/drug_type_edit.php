<?php
#
# Main page for modifying an existing specimen type
#
include("redirect.php");
include("includes/header.php");
include("includes/ajax_lib.php");
LangUtil::setPageId("catalog");


$drug_type = get_drug_type_by_id($_REQUEST['did']);
?>

<br>
<b><?php echo LangUtil::$pageTerms['EDIT_DRUG_TYPE']; ?></b>
| <a href="catalog.php?show_tc=1"><?php echo LangUtil::$generalTerms['CMD_CANCEL']; ?></a>
<br><br>
<?php
if($drug_type == null)
{
?>
	<div class='sidetip_nopos'>
	<?php echo LangUtil::$generalTerms['MSG_NOTFOUND']; ?>
	</div>
<?php
	include("includes/footer.php");
	return;
}
$page_elems->getDrugTypeInfo($drug_type->name, true);
?>
<br>
<br>
<div class='pretty_box'>
<form name='edit_drug_type_form' id='edit_drug_type_form' action='ajax/drug_type_update.php' method='post'>
<input type='hidden' name='did' id='did' value='<?php echo $_REQUEST['did']; ?>'></input>
	<table cellspacing='4px'>
		<tbody>
			<tr valign='top'>
				<td style='width:150px;'><?php echo LangUtil::$generalTerms['NAME']; ?><?php $page_elems->getAsterisk(); ?></td>
				<td><input type='text' name='name' id='name' class='span12 m-wrap' value='<?php echo $drug_type->getName(); ?>' class='uniform_width'></input></td>
			</tr>
			<tr valign='top'>
				<td><?php echo LangUtil::$generalTerms['DESCRIPTION']; ?></td>
				<td><textarea type='text' name='description' id='description' class='span12 m-wrap'><?php echo trim($drug_type->description); ?></textarea></td>
			</tr>

			<tr>
				<td></td>
				<td>
                
                <div class="form-actions">

                      <input class='btn yellow' type='button' value='<?php echo LangUtil::$generalTerms['CMD_SUBMIT']; ?>' onclick='javascript:update_drug_type();'></input>
                      <a href='catalog.php?show_d=1' class='btn'> <?php echo LangUtil::$generalTerms['CMD_CANCEL']; ?></a>
                </div>
               	<span id='update_drug_type_progress' style='display:none;'>
						<?php $page_elems->getProgressSpinner(LangUtil::$generalTerms['CMD_SUBMITTING']); ?>
					</span>
				</td>
			</tr>
		</tbody>
	</table>
    
</form>
</div>
<div id='drug_help' style='display:none'>
<small>
Use Ctrl+F to search easily through the list. Ctrl+F will prompt a box where you can enter the test name you are looking for.
</small>
</div>
<script type='text/javascript'>
function update_drug_type()
{
	if($('#name').attr("value").trim() == "")
	{
		alert("<?php echo LangUtil::$pageTerms['TIPS_MISSING_DRUGNAME']; ?>");
		return;
	}
	$('#update_drug_type_progress').show();
	$('#edit_drug_type_form').ajaxSubmit({
		success: function(msg) {
			$('#update_drug_type_progress').hide();
			window.location="drug_type_updated.php?did=<?php echo $_REQUEST['did']; ?>";
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