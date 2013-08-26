<?php 
#
# (c) C4G, Santosh Vempala, Ruban Monu and Amol Shintre
# Main page for showing list of test/specimen types in catalog, with options to add/modify
#

include("../users/accesslist.php");
if( !(isAdmin(get_user_by_id($_SESSION['user_id'])) && in_array(basename($_SERVER['PHP_SELF']), $adminPageList)) )
	header( 'Location: home.php' );
	
include("redirect.php");
include("includes/header.php");
LangUtil::setPageId("quality");

putUILog('quality', 'X', basename($_SERVER['REQUEST_URI'], ".php"), 'X', 'X', 'X');


$dialog_id = "dialog_deletequality";
$script_elems->enableFormBuilder();
?>
<script type='text/javascript'>
$(document).ready(function(){
	$('div.content_div').hide();
	$('#quality_controls_div').hide();
	$('#quality_control_categories_div').hide();
	$('#quality_control_field_groups_div').hide();
	$('#<?php echo $dialog_id; ?>').show();
	<?php
	if(isset($_REQUEST['show_qc']))
	{
		?>
		load_right_pane('quality_controls_div');
		<?php
	}
	else if(isset($_REQUEST['show_qcc']))
	{
		?>
		load_right_pane('quality_control_categories_div');
		<?php
	}
	else if(isset($_REQUEST['show_qcfg']))
	{
		?>
		load_right_pane('quality_control_field_groups_div');
		<?php
	}
	else if(isset($_REQUEST['qcdel']))
	{
		?>
		$('#tdel_msg').show();
		load_right_pane('quality_controls_div');
		<?php
	}
	else if(isset($_REQUEST['qccdel']))
	{
		?>
		$('#sdel_msg').show();
		load_right_pane('quality_control_categories_div');
		<?php
	}
	else if(isset($_REQUEST['qcfgdel']))
	{
		?>
		$('#sdel_msg').show();
		load_right_pane('quality_control_field_groups_div');
		<?php
	}
	else if (isset($_REQUEST['rm']))
	{
		?>
		$('#rm_msg').show();
		<?php
	}
	?>
});

function load_right_pane(div_id)
{
	$('#rm_msg').hide();
	$('div.content_div').hide();
	$('#'+div_id).show();
	$('.menu_option').removeClass('current_menu_option');
	$('#'+div_id+'_menu').addClass('current_menu_option');
}

function hide_right_pane()
{
	$('div.content_div').hide();
	$('.menu_option').removeClass('current_menu_option');
}

function delete_quality_data()
{
	$('#remove_data_progress').show();
	var url_string = "ajax/quality_deletedata.php";
	$.ajax({
		url: url_string, 
		success: function () {
			$('#remove_data_progress').hide();
			window.location='quality.php?rm';
		}
	});
}
</script>
<br>
<table cellpadding='10px' width="100%">
<tr valign='top'>
<td id='left_pane' class='left_menu' width='150'>
<a href="javascript:load_right_pane('quality_controls_div');" class='menu_option' id='quality_controls_div_menu'>
	<?php echo 'Quality Controls'; ?>
</a>
<br><br>
<a href="javascript:load_right_pane('quality_control_categories_div');" class='menu_option' id='quality_control_categories_div_menu'>
	<?php echo 'Quality Control Categories'; ?>
</a>
<br><br>
<a href="javascript:load_right_pane('quality_control_field_groups_div');" class='menu_option' id='quality_control_field_groups_div_menu'>
	<?php echo 'Quality Control Field Groups'; ?>
</a>
<br><br>
<?php
$user = get_user_by_id($_SESSION['user_id']);
if(is_super_admin($user) || is_country_dir($user))
{
	# Allow deletion of all catalog data
	?>
	<a href="javascript:load_right_pane('remove_data_div');" class='menu_option' id='remove_data_div_menu'>
		<?php echo LangUtil::$pageTerms['MENU_REMOVEDATA']; ?>
	</a>
	<br><br>
<?php
}
?>

</td>
<td id='right_pane'>
	<div id='rm_msg' class='clean-orange' style='display:none;width:200px;'>
		<?php echo LangUtil::$generalTerms['MSG_DELETED']; ?>&nbsp;&nbsp;<a href="javascript:toggle('rm_msg');"><?php echo LangUtil::$generalTerms['CMD_HIDE']; ?></a>
	</div>
	<div id='quality_controls_div' class=''>
		<p style="text-align: right;"><a rel='facebox' href='#QualityControls_tc'>Page Help</a></p>
		<b><?php echo 'Quality Controls'; ?></b>
		| <a href='quality_control_new.php' title='Click to Add a New Quality Control'><?php echo LangUtil::$generalTerms['ADDNEW']; ?></a>
        <!------
        --->
        <h1>jQuery Form Builder Plugin Demo</h1>
		<p><a href="form_builder/example-html.php">View sample rendered html</a>.</p>
		<div id="my_form_builder"></div>
        <!----
        --->
		<br><br>
		<div id='tdel_msg' class='clean-orange' style='display:none;'>
			<?php echo LangUtil::$generalTerms['MSG_DELETED']; ?>&nbsp;&nbsp;<a href="javascript:toggle('qcdel_msg');"><?php echo LangUtil::$generalTerms['CMD_HIDE']; ?></a>
		</div>
		<?php //$page_elems->getQualityControlsTable($_SESSION['lab_config_id']); ?>
	</div>	
    
    <div id='quality_control_categories_div' class='content_div'>
		<p style="text-align: right;"><a rel='facebox' href='#QualityControlCategories_tc'>Page Help</a></p>
		<b><?php echo 'Quality Control Categories'; ?></b>
		| <a href='quality_control_category_new.php' title='Click to Add a New Quality Control Category'><?php echo LangUtil::$generalTerms['ADDNEW']; ?></a>
		<br><br>
		<div id='tdel_msg' class='clean-orange' style='display:none;'>
			<?php echo LangUtil::$generalTerms['MSG_DELETED']; ?>&nbsp;&nbsp;<a href="javascript:toggle('qccdel_msg');"><?php echo LangUtil::$generalTerms['CMD_HIDE']; ?></a>
		</div>
		<?php //$page_elems->getQualityControlCategoriesTable($_SESSION['lab_config_id']); ?>
	</div>
    
    <div id='quality_control_field_groups_div' class='content_div'>
		<p style="text-align: right;"><a rel='facebox' href='#QualityControlFieldGroups_tc'>Page Help</a></p>
		<b><?php echo 'Quality Control Field Groups'; ?></b>
		| <a href='quality_control_field_groups_new.php' title='Click to Add a New Quality Control Field Group'><?php echo LangUtil::$generalTerms['ADDNEW']; ?></a>
		<br><br>
		<div id='tdel_msg' class='clean-orange' style='display:none;'>
			<?php echo LangUtil::$generalTerms['MSG_DELETED']; ?>&nbsp;&nbsp;<a href="javascript:toggle('qcfgdel_msg');"><?php echo LangUtil::$generalTerms['CMD_HIDE']; ?></a>
		</div>
		<?php //$page_elems->getQualityControlFieldGroupsTable($_SESSION['lab_config_id']); ?>
	</div>
    
	<div id='QualityControls_tc' class='right_pane' style='display:none;margin-left:10px;'>
		<ul>
			<li><?php echo LangUtil::$pageTerms['TIPS_QC_1']; ?></li>
			<li><?php echo LangUtil::$pageTerms['TIPS_QC_2']; ?></li>
			<li><?php echo LangUtil::$pageTerms['TIPS_QC_3']; ?></li>
		</ul>
	</div>
    
    <div id='QualityControlCategories_tc' class='right_pane' style='display:none;margin-left:10px;'>
		<ul>
			<li><?php echo LangUtil::$pageTerms['TIPS_QCC_1']; ?></li>
			<li><?php echo LangUtil::$pageTerms['TIPS_QCC_2']; ?></li>
			<li><?php echo LangUtil::$pageTerms['TIPS_QCC_3']; ?></li>
		</ul>
	</div>
    
    <div id='QualityControlFieldGroups_tc' class='right_pane' style='display:none;margin-left:10px;'>
		<ul>
			<li><?php echo LangUtil::$pageTerms['TIPS_QCFG_1']; ?></li>
			<li><?php echo LangUtil::$pageTerms['TIPS_QCFG_2']; ?></li>
			<li><?php echo LangUtil::$pageTerms['TIPS_QCFG_3']; ?></li>
		</ul>
	</div>
	
	<?php
	if(is_super_admin($user) || is_country_dir($user))
	{
	?>
		<div id='remove_data_div' class='content_div'>
			<b><?php echo LangUtil::$pageTerms['MENU_REMOVEDATA']; ?></b> |
			<a href='javascript:hide_right_pane()'><?php echo LangUtil::$generalTerms['CMD_CANCEL']; ?></a>
			<br><br>
			<?php
			$message = LangUtil::$pageTerms['TIPS_REMOVEDATA'];
			$ok_function = "delete_quality_data();";
			$cancel_function = "hide_right_pane();";
			$page_elems->getConfirmDialog($dialog_id, $message, $ok_function, $cancel_function);
			?>
			<span id='remove_data_progress' style='display:none;'>
				<br>
				&nbsp;<?php $page_elems->getProgressSpinner(" ".LangUtil::$generalTerms['CMD_SUBMITTING']); ?>
			</span>
		</div>
	<?php
	}
	?>
</td>
</tr>
</table>
<br>

<?php include("includes/footer.php"); ?>