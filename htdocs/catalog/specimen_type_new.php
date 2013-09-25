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
							<li><i class='icon-cogs'></i> Test Catalog
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
<b><?php echo LangUtil::$pageTerms['NEW_SPECIMEN_TYPE']; ?></b>
| <a href='catalog.php?show_s=1'><?php echo LangUtil::$generalTerms['CMD_CANCEL']; ?></a>
<br><br>
<form name='new_specimen_form' id='new_specimen_form' action='specimen_type_add.php' method='post'>


<?php echo LangUtil::$generalTerms['NAME']; ?><?php $page_elems->getAsterisk(); ?>
<input type='text' name='specimen_name' id='specimen_name' class='uniform_width' />
<?php echo LangUtil::$generalTerms['DESCRIPTION']; ?>
<textarea name='specimen_descr' id='specimen_descr' class='uniform_width'></textarea>

<?php echo LangUtil::$generalTerms['COMPATIBLE_TESTS']; ?> <?php $page_elems->getAsterisk(); ?>[<a href='#test_help' rel='facebox'>?</a>]

<?php $page_elems->getTestTypeCheckboxes(); ?>

<input type='button' onclick='check_input();' value='<?php echo LangUtil::$generalTerms['CMD_SUBMIT']; ?>' />
&nbsp;&nbsp;&nbsp;&nbsp;
<a href='catalog.php?show_s=1'> <?php echo LangUtil::$generalTerms['CMD_CANCEL']; ?></a>
</form>
<div id='test_help' style='display:none'>
<small>
Use Ctrl+F to search easily through the list. Ctrl+F will prompt a box where you can enter the test name you are looking for.
</small>
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