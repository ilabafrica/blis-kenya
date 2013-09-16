<?php 
include("redirect.php");
include("includes/header.php");
LangUtil::setPageId("quality");

putUILog('quality', 'X', basename($_SERVER['REQUEST_URI'], ".php"), 'X', 'X', 'X');


$dialog_id = "dialog_deletequality";
$script_elems->enableFormBuilder();
$script_elems->enableFacebox();
$script_elems->enableBootstrap();
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
<td id=''>
	<div id='rm_msg' class='clean-orange' style='display:none;width:200px;'>
		<?php echo LangUtil::$generalTerms['MSG_DELETED']; ?>&nbsp;&nbsp;<a href="javascript:toggle('rm_msg');"><?php echo LangUtil::$generalTerms['CMD_HIDE']; ?></a>
	</div>
    <!--- Tabbable tabs --->
    <div class="row-fluid ">
					<div class="span12">				
					<div class="tab-content">
						<div class="tab-pane active" id="tabs-basic">
							<div class="tabbable">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#tabs1-pane1" data-toggle="tab"><?php echo LangUtil::$generalTerms['QUALITY_CONTROLS']; ?></a></li>
									<li><a href="#tabs1-pane2" data-toggle="tab"><?php echo LangUtil::$generalTerms['QUALITY_CONTROL_CATEGORIES']; ?></a></li>
									<li><a href="#tabs1-pane3" data-toggle="tab"><?php echo LangUtil::$generalTerms['QUALITY_CONTROL_FIELD_GROUPS']; ?></a></li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="tabs1-pane1">
										<p style="text-align: right;"><a rel='facebox' href='#QualityControls_tc'>Page Help</a></p>
		<h5><?php echo LangUtil::$generalTerms['QUALITY_CONTROLS']; ?>
		| <a href='#tabs1-pane4' data-toggle='tab' title='Click to Add a New Quality Control'><?php echo LangUtil::$generalTerms['ADDNEW']; ?></h5></a>
		<div id='tdel_msg' class='clean-orange' style='display:none;'>
			<?php echo LangUtil::$generalTerms['MSG_DELETED']; ?>&nbsp;&nbsp;<a href="javascript:toggle('qcdel_msg');"><?php echo LangUtil::$generalTerms['CMD_HIDE']; ?></a>
		</div>
		<?php //$page_elems->getQualityControlsTable($_SESSION['lab_config_id']); ?>
										<pre>
										</pre>
									</div>
									<div class="tab-pane" id="tabs1-pane2">
										<p style="text-align: right;"><a rel='facebox' href='#QualityControlCategories_tc'>Page Help</a></p>
		<h5><?php echo LangUtil::$generalTerms['QUALITY_CONTROL_CATEGORIES']; ?>
		| <a href='quality_control_category_new.php' title='Click to Add a New Quality Control Category'><?php echo LangUtil::$generalTerms['ADDNEW']; ?></a></h5>
		<div id='tdel_msg' class='clean-orange' style='display:none;'>
			<?php echo LangUtil::$generalTerms['MSG_DELETED']; ?>&nbsp;&nbsp;<a href="javascript:toggle('qccdel_msg');"><?php echo LangUtil::$generalTerms['CMD_HIDE']; ?></a>
		</div>
		<?php $page_elems->getQualityControlCategoriesTable($_SESSION['lab_config_id']); ?>
										<pre>
										
											<!-------------------------div tag to begin new quality control category form-------------------->
                                    
                                   <div id='quality_control_category_new' class='right_pane' style='display:none;margin-left:10px;'>
					<div class="modal-header">
										<h4 id="myModalLabel1"><?php echo "New Quality Control Category"; ?></h4>
									</div>
									<div class="modal-body">
										<div class="controls">
                                        <form id="new_quality_control_category_form" method="post" action="quality_control_category_add.php">
                                        
                                          <input type='text' name='category_name' id='category_name' class='span4 m-wrap' />
                                       </div>
									</div>
									<div class="modal-footer">
									<input type='button' class="btn yellow" onclick='check_input();' value='<?php echo LangUtil::$generalTerms['CMD_SUBMIT']; ?>' />
									</form>
									</div>
				</div>
                                    <!-------------------------end div tag to end new quality control category form-------------------->
									</pre>
									</div>
									<div class="tab-pane" id="tabs1-pane3">
										<p style="text-align: right;"><a rel='facebox' href='#QualityControlFieldGroups_tc'>Page Help</a></p>
		<h5><?php echo LangUtil::$generalTerms['QUALITY_CONTROL_FIELD_GROUPS']; ?>
		| <a href='#quality_control_field_group_new' rel='facebox' title='Click to Add a New Quality Control Field Group'><?php echo LangUtil::$generalTerms['ADDNEW']; ?></h5></a>
		<div id='tdel_msg' class='clean-orange' style='display:none;'>
			<?php echo LangUtil::$generalTerms['MSG_DELETED']; ?>&nbsp;&nbsp;<a href="javascript:toggle('qcfgdel_msg');"><?php echo LangUtil::$generalTerms['CMD_HIDE']; ?></a>
		</div>
		<?php //$page_elems->getQualityControlFieldGroupsTable($_SESSION['lab_config_id']); ?>
										<pre>
										<!-------------------------div tag to begin new quality control field groups-------------------->
                                    
                                   <div id='quality_control_field_group_new' class='right_pane' style='display:none;margin-left:10px;'>
					<div class="modal-header">
										<h4 id="myModalLabel1"><?php echo "New Quality Control Field Group"; ?></h4>
									</div>
									<div class="modal-body">
										<div class="controls">
                                        <form id="new_quality_control_field_group" method="post">
                                        
                                          <input type='text' name='field_group_name' id='field_group_name' class='span4 m-wrap' />
                                       </div>
									</div>
									<div class="modal-footer">
									<input name='qcfg' id='qcfg' type='button' class="btn yellow" onclick='check_input();' value='<?php echo LangUtil::$generalTerms['CMD_SUBMIT']; ?>' />
									</form>
									</div>
				</div>
                                    <!-------------------------end div tag to end new quality control field groups-------------------->
									</pre>
									</div>
									</div><!-- /.tab-content -->
							</div><!-- /.tabbable -->
						</div><!-- .tabs-basic -->

<div class="tab-pane" id="tabs1-pane4">
										<p style="text-align: right;"><a rel='facebox' href='#QualityControls_tc'>Page Help</a></p>
		<h5><?php echo LangUtil::$generalTerms['QUALITY_CONTROLS']; ?>
		| <a href='#quality_control_new' rel='facebox' title='Click to Add a New Quality Control'><?php echo LangUtil::$generalTerms['ADDNEW']; ?></h5></a>
		<div id='tdel_msg' class='clean-orange' style='display:none;'>
			<?php echo LangUtil::$generalTerms['MSG_DELETED']; ?>&nbsp;&nbsp;<a href="javascript:toggle('qcdel_msg');"><?php echo LangUtil::$generalTerms['CMD_HIDE']; ?></a>
		</div>
		<?php //$page_elems->getQualityControlsTable($_SESSION['lab_config_id']); ?>
										<pre><div id="my_form_builder"></div></pre>
									</div>

						</div>
						<!-- END TAB PORTLET-->

    <div id='quality_control_categories_div' class='content_div'>
		<p style="text-align: right;"><a rel='facebox' href='#QualityControlCategories_tc'>Page Help</a></p>
		<b><?php echo LangUtil::$generalTerms['QUALITY_CONTROL_CATEGORIES']; ?></b>
		| <a href='quality_control_category_new.php' title='Click to Add a New Quality Control Category'><?php echo LangUtil::$generalTerms['ADDNEW']; ?></a>
		<br><br>
		<div id='tdel_msg' class='clean-orange' style='display:none;'>
			<?php echo LangUtil::$generalTerms['MSG_DELETED']; ?>&nbsp;&nbsp;<a href="javascript:toggle('qccdel_msg');"><?php echo LangUtil::$generalTerms['CMD_HIDE']; ?></a>
		</div>
		<?php //$page_elems->getQualityControlCategoriesTable($_SESSION['lab_config_id']); ?>
	</div>
    
    <div id='quality_control_field_groups_div' class='content_div'>
		<p style="text-align: right;"><a rel='facebox' href='#QualityControlFieldGroups_tc'>Page Help</a></p>
		<b><?php echo LangUtil::$generalTerms['QUALITY_CONTROL_FIELD_GROUPS']; ?></b>
		| <a href='quality_control_field_groups.php' title='Click to Add a New Quality Control Field Group'><?php echo LangUtil::$generalTerms['ADDNEW']; ?></a>
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
<script type="text/javascript">
	function check_input()
{
	// Validate
	var category_name = $('#category_name').val();
	if(category_name == "")
	{
		alert(category_name);
		return;
	}
	// All OK
	$('#new_quality_control_category_form').submit();
}

</script>
<?php include("includes/footer.php"); ?>