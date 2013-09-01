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
		| <a href='quality_control.php' title='Click to Add a New Quality Control'><?php echo LangUtil::$generalTerms['ADDNEW']; ?></h5></a>
		<div id='tdel_msg' class='clean-orange' style='display:none;'>
			<?php echo LangUtil::$generalTerms['MSG_DELETED']; ?>&nbsp;&nbsp;<a href="javascript:toggle('qcdel_msg');"><?php echo LangUtil::$generalTerms['CMD_HIDE']; ?></a>
		</div>
		<?php //$page_elems->getQualityControlsTable($_SESSION['lab_config_id']); ?>
										<pre>&lt;div class=&quot;tabbable&quot;&gt;
  &lt;ul class=&quot;nav nav-tabs&quot;&gt;
    &lt;li class=&quot;active&quot;&gt;&lt;a href=&quot;#pane1&quot; data-toggle=&quot;tab&quot;&gt;Tab 1&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href=&quot;#pane2&quot; data-toggle=&quot;tab&quot;&gt;Tab 2&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href=&quot;#pane3&quot; data-toggle=&quot;tab&quot;&gt;Tab 3&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href=&quot;#pane4&quot; data-toggle=&quot;tab&quot;&gt;Tab 4&lt;/a&gt;&lt;/li&gt;
  &lt;/ul&gt;
  &lt;div class=&quot;tab-content&quot;&gt;
    &lt;div id=&quot;pane1&quot; class=&quot;tab-pane active&quot;&gt;
      &lt;h4&gt;The Markup&lt;/h4&gt;
      &lt;pre&gt;Code here ...&lt;/pre&gt;
    &lt;/div&gt;
    &lt;div id=&quot;pane2&quot; class=&quot;tab-pane&quot;&gt;
    &lt;h4&gt;Pane 2 Content&lt;/h4&gt;
      &lt;p&gt; and so on ...&lt;/p&gt;
    &lt;/div&gt;
    &lt;div id=&quot;pane3&quot; class=&quot;tab-pane&quot;&gt;
      &lt;h4&gt;Pane 3 Content&lt;/h4&gt;
    &lt;/div&gt;
    &lt;div id=&quot;pane4&quot; class=&quot;tab-pane&quot;&gt;
      &lt;h4&gt;Pane 4 Content&lt;/h4&gt;
    &lt;/div&gt;
  &lt;/div&gt;&lt;!-- /.tab-content --&gt;
&lt;/div&gt;&lt;!-- /.tabbable --&gt;</pre>
									</div>
									<div class="tab-pane" id="tabs1-pane2">
										<p style="text-align: right;"><a rel='facebox' href='#QualityControlCategories_tc'>Page Help</a></p>
		<h5><?php echo LangUtil::$generalTerms['QUALITY_CONTROL_CATEGORIES']; ?>
		| <a href='#quality_control_category_new' rel='facebox' title='Click to Add a New Quality Control Category'><?php echo LangUtil::$generalTerms['ADDNEW']; ?></a></h5>
		<div id='tdel_msg' class='clean-orange' style='display:none;'>
			<?php echo LangUtil::$generalTerms['MSG_DELETED']; ?>&nbsp;&nbsp;<a href="javascript:toggle('qccdel_msg');"><?php echo LangUtil::$generalTerms['CMD_HIDE']; ?></a>
		</div>
		<?php //$page_elems->getQualityControlCategoriesTable($_SESSION['lab_config_id']); ?>
										<pre>
										<div id='quality_control_category_new' class='right_pane' style='display:none;margin-left:10px;'>
											<!-------------------------div tag to begin new quality control category form-------------------->
                                    
                                   <div id='quality_control_category_new' class='right_pane' style='display:none;margin-left:10px;'>
					<div class="modal-header">
										<h3 id="myModalLabel1">Reasons for Rejection</h3>
									</div>
									<div class="modal-body">
										<div class="controls">
                                        <form id="accept_reject" method="post">
                                        
                                          <textarea class="large m-wrap" name="reasons<?php echo $specimen->specimenId; ?>" id="reasons<?php echo $specimen->specimenId; ?>" rows="3"></textarea>
                                       </div>
									</div>
									<div class="modal-footer">
										<button class="btn yellow" type="submit" onClick="javascript:getReason(<?php echo $specimen->specimenId; ?>);">Save</button>
                                        
                                        </form>
									</div>
				</div>
                                    <!-------------------------end div tag to end new quality control category form-------------------->
										</div>
										&lt;div class=&quot;tabbable&quot;&gt;
  &lt;ul class=&quot;nav nav-tabs&quot;&gt;
    &lt;li class=&quot;active&quot;&gt;&lt;a href=&quot;#pane1&quot; data-toggle=&quot;tab&quot;&gt;Tab 1&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href=&quot;#pane2&quot; data-toggle=&quot;tab&quot;&gt;Tab 2&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href=&quot;#pane3&quot; data-toggle=&quot;tab&quot;&gt;Tab 3&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href=&quot;#pane4&quot; data-toggle=&quot;tab&quot;&gt;Tab 4&lt;/a&gt;&lt;/li&gt;
  &lt;/ul&gt;
  &lt;div class=&quot;tab-content&quot;&gt;
    &lt;div id=&quot;pane1&quot; class=&quot;tab-pane active&quot;&gt;
      &lt;h4&gt;The Markup&lt;/h4&gt;
      &lt;pre&gt;Code here ...&lt;/pre&gt;
    &lt;/div&gt;
    &lt;div id=&quot;pane2&quot; class=&quot;tab-pane&quot;&gt;
    &lt;h4&gt;Pane 2 Content&lt;/h4&gt;
      &lt;p&gt; and so on ...&lt;/p&gt;
    &lt;/div&gt;
    &lt;div id=&quot;pane3&quot; class=&quot;tab-pane&quot;&gt;
      &lt;h4&gt;Pane 3 Content&lt;/h4&gt;
    &lt;/div&gt;
    &lt;div id=&quot;pane4&quot; class=&quot;tab-pane&quot;&gt;
      &lt;h4&gt;Pane 4 Content&lt;/h4&gt;
    &lt;/div&gt;
  &lt;/div&gt;&lt;!-- /.tab-content --&gt;
&lt;/div&gt;&lt;!-- /.tabbable --&gt;</pre>
									</div>
									<div class="tab-pane" id="tabs1-pane3">
										<p style="text-align: right;"><a rel='facebox' href='#QualityControlFieldGroups_tc'>Page Help</a></p>
		<h5><?php echo LangUtil::$generalTerms['QUALITY_CONTROL_FIELD_GROUPS']; ?>
		| <a href='quality_control_field_groups.php' title='Click to Add a New Quality Control Field Group'><?php echo LangUtil::$generalTerms['ADDNEW']; ?></h5></a>
		<div id='tdel_msg' class='clean-orange' style='display:none;'>
			<?php echo LangUtil::$generalTerms['MSG_DELETED']; ?>&nbsp;&nbsp;<a href="javascript:toggle('qcfgdel_msg');"><?php echo LangUtil::$generalTerms['CMD_HIDE']; ?></a>
		</div>
		<?php //$page_elems->getQualityControlFieldGroupsTable($_SESSION['lab_config_id']); ?>
										<pre>&lt;div class=&quot;tabbable&quot;&gt;
  &lt;ul class=&quot;nav nav-tabs&quot;&gt;
    &lt;li class=&quot;active&quot;&gt;&lt;a href=&quot;#pane1&quot; data-toggle=&quot;tab&quot;&gt;Tab 1&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href=&quot;#pane2&quot; data-toggle=&quot;tab&quot;&gt;Tab 2&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href=&quot;#pane3&quot; data-toggle=&quot;tab&quot;&gt;Tab 3&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href=&quot;#pane4&quot; data-toggle=&quot;tab&quot;&gt;Tab 4&lt;/a&gt;&lt;/li&gt;
  &lt;/ul&gt;
  &lt;div class=&quot;tab-content&quot;&gt;
    &lt;div id=&quot;pane1&quot; class=&quot;tab-pane active&quot;&gt;
      &lt;h4&gt;The Markup&lt;/h4&gt;
      &lt;pre&gt;Code here ...&lt;/pre&gt;
    &lt;/div&gt;
    &lt;div id=&quot;pane2&quot; class=&quot;tab-pane&quot;&gt;
    &lt;h4&gt;Pane 2 Content&lt;/h4&gt;
      &lt;p&gt; and so on ...&lt;/p&gt;
    &lt;/div&gt;
    &lt;div id=&quot;pane3&quot; class=&quot;tab-pane&quot;&gt;
      &lt;h4&gt;Pane 3 Content&lt;/h4&gt;
    &lt;/div&gt;
    &lt;div id=&quot;pane4&quot; class=&quot;tab-pane&quot;&gt;
      &lt;h4&gt;Pane 4 Content&lt;/h4&gt;
    &lt;/div&gt;
  &lt;/div&gt;&lt;!-- /.tab-content --&gt;
&lt;/div&gt;&lt;!-- /.tabbable --&gt;</pre>
									</div>
									</div><!-- /.tab-content -->
							</div><!-- /.tabbable -->
						</div><!-- .tabs-basic -->
						</div>
						<!-- END TAB PORTLET-->
    <!-- BEGIN PATIENT SAMPLE REJECTION -->
<div id="quality_controls_div" class='reg_subdiv' style='display:none;'>
	<div class="portlet box yellow">
		<div class="portlet-title">
			<h4><i class="icon-reorder"></i><?php echo "Quality Control"; ?></h4>
			<div class="tools">
				<a href="javascript:;" class="collapse"></a>
				<a href="javascript:;" class="reload"></a>
				<a href="javascript:;" class="remove"></a>
			</div>
		</div>
		<div class="portlet-body form">
			<p style="text-align: right;"><a rel='facebox' href='#QualityControls_tc'>Page Help</a></p>
		<b><?php echo LangUtil::$generalTerms['QUALITY_CONTROLS']; ?></b>
		| <a href='quality_control.php' title='Click to Add a New Quality Control'><?php echo LangUtil::$generalTerms['ADDNEW']; ?></a>
        <!------
        --->
       <!--<h1>jQuery Form Builder Plugin Demo</h1>-->
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
	</div>
</div>
<!-- END PATIENT SAMPLE REJECTION -->
    
	
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

<?php include("includes/footer.php"); ?>