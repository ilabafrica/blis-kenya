<?php
#
# Main page for adding new specimen type
#
include("redirect.php");
include("includes/header.php");
LangUtil::setPageId("catalog");
?>
<!-- BEGIN PAGE TITLE & BREADCRUMB-->		
						<h3>
						</h3>
						<ul class="breadcrumb">
							<li><i class='icon-cogs'></i> Specimen Rejection
							</li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
<!-- BEGIN ROW-FLUID-->   
<div class="row-fluid">
<div class="span12 sortable">
<br>
<b><?php echo "New Specimen Rejection Phase"; ?></b>
| <a href='lab_config_home.php?id=<?php $_SESSION['lab_config_id']; ?>'><?php echo LangUtil::$generalTerms['CMD_CANCEL']; ?></a>
<br><br>
<form name='new_rejection_phase_form' id='new_rejection_phase_form' action='rejection_phase_add.php' method='post'>


<?php echo LangUtil::$generalTerms['NAME']; ?><?php $page_elems->getAsterisk(); ?>
<input type='text' name='phase_name' id='phase_name' class='uniform_width' />
<?php echo LangUtil::$generalTerms['DESCRIPTION']; ?>
<textarea name='phase_descr' id='phase_descr' class='uniform_width'></textarea>

<input type='button' onclick='check_input();' value='<?php echo LangUtil::$generalTerms['CMD_SUBMIT']; ?>' />
&nbsp;&nbsp;&nbsp;&nbsp;
<a href='lab_config_home.php?id=<?php $_SESSION['lab_config_id']; ?>'> <?php echo LangUtil::$generalTerms['CMD_CANCEL']; ?></a>
</form>
</div>
</div>
</div>

<?php include("includes/scripts.php");
$script_elems->enableLatencyRecord();
?>
<script type='text/javascript'>
function check_input()
{
	// Validate
	var specimen_name = $('#specimen_name').attr("value");
	if(specimen_name == "")
	{
		alert("<?php echo LangUtil::$pageTerms['TIPS_MISSING_SPECIMENNAME']; ?>");
		return;
	}
	var ttype_entries = $('.ttype_entry');
	var ttype_selected = false;
	for(var i = 0; i < ttype_entries.length; i++)
	{
		if(ttype_entries[i].checked)
		{
			ttype_selected = true;
			break;
		}
	}
	if(ttype_selected == false)
	{
		<?php # Disabled to allow additions of new specimens that do not YET have compatible tests entered ?>
		//alert("Error: No tests selected");
		//return;
	}
	// All OK
	$('#new_specimen_form').submit();
}

</script>
<?php include("includes/footer.php"); ?>