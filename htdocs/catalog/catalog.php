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
LangUtil::setPageId("catalog");

putUILog('catalog', 'X', basename($_SERVER['REQUEST_URI'], ".php"), 'X', 'X', 'X');
$dialog_id = "dialog_deletecatalog";
?>



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
	<div id='rm_msg' class='clean-orange' style='display:none;width:200px;'>
		<?php echo LangUtil::$generalTerms['MSG_DELETED']; ?>&nbsp;&nbsp;<a href="javascript:toggle('rm_msg');"><?php echo LangUtil::$generalTerms['CMD_HIDE']; ?></a>
	</div>
	<div id='test_types_div' class='content_div'>
		<div class="portlet box green">
		<div class="portlet-title">
			<h4><i class="icon-reorder"></i><?php echo LangUtil::$generalTerms['TEST_TYPES']; ?></h4>
			<div class="tools">
				<a href="javascript:;" class="collapse"></a>
				
			</div>
		</div>
		<div class="portlet-body">
		<p style="text-align: right;"><a rel='facebox' href='#TestType_tc'>Page Help</a></p>
		 <a href='test_type_new.php' class="btn blue-stripe" title='Click to Add a New Test Type'><i class='icon-plus'></i> <?php echo LangUtil::$generalTerms['ADDNEW']; ?></a>
		<br><br>
		<div id='tdel_msg' class='clean-orange' style='display:none;'>
			<?php echo LangUtil::$generalTerms['MSG_DELETED']; ?>&nbsp;&nbsp;<a href="javascript:toggle('tdel_msg');"><?php echo LangUtil::$generalTerms['CMD_HIDE']; ?></a>
		</div>
		<?php $page_elems->getTestTypeTable($_SESSION['lab_config_id']); ?>
		</div>
		</div>
	</div>
	
	<div id='specimen_types_div' class='content_div'>
	<div class="portlet box green">
		<div class="portlet-title">
			<h4><i class="icon-reorder"></i><?php echo LangUtil::$generalTerms['SPECIMEN_TYPES']; ?></h4>
			<div class="tools">
				<a href="javascript:;" class="collapse"></a>
				
			</div>
		</div>
		<div class="portlet-body">
		<p style="text-align: right;"><a rel='facebox' href='#SpecimenType_tc'>Page Help</a></p>
		<a href='specimen_type_new.php' class="btn blue-stripe" title='Click to Add a New Specimen Type'><i class='icon-plus'></i> <?php echo LangUtil::$generalTerms['ADDNEW']; ?></a>
		<br><br>
		<div id='sdel_msg' class='clean-orange' style='display:none;'>
			<?php echo LangUtil::$generalTerms['MSG_DELETED']; ?>&nbsp;&nbsp;<a href="javascript:toggle('sdel_msg');"><?php echo LangUtil::$generalTerms['CMD_HIDE']; ?></a>
		</div>
		<?php $page_elems->getSpecimenTypeTable($_SESSION['lab_config_id']); ?>
		</div>
	</div>
	</div>
    
    <div id='test_categories_div' class='content_div'>
    <div class="portlet box green">
		<div class="portlet-title">
			<h4><i class="icon-reorder"></i><?php echo "Lab Sections"; ?></h4>
			<div class="tools">
				<a href="javascript:;" class="collapse"></a>
				
			</div>
		</div>
		<div class="portlet-body">
		<p style="text-align: right;"><a rel='facebox' href='#TestCategory_tc'>Page Help</a></p>
		<a href='javascript:add_section();' class="btn blue-stripe" title='Click to Add a New Test Category'><i class='icon-plus'></i> <?php echo LangUtil::$generalTerms['ADDNEW']; ?></a>
		<br><br>
		<div id='sdel_msg' class='clean-orange' style='display:none;'>
			<?php echo LangUtil::$generalTerms['MSG_DELETED']; ?>&nbsp;&nbsp;<a href="javascript:toggle('tcdel_msg');"><?php echo LangUtil::$generalTerms['CMD_HIDE']; ?></a>
		</div>
		<?php $page_elems->getTestCategoryTable($_SESSION['lab_config_id']); ?>
		</div>
	</div>
	</div>
    
       
	<div id='TestType_tc' class='right_pane' style='display:none;margin-left:10px;'>
		<ul>
			<li><?php echo LangUtil::$pageTerms['TIPS_TC_TESTTYPE_1']; ?></li>
			<li><?php echo LangUtil::$pageTerms['TIPS_TC_TESTTYPE_2']; ?></li>
			<li><?php echo LangUtil::$pageTerms['TIPS_TC_TESTTYPE_3']; ?></li>
		</ul>
	</div>
		
	<div id='SpecimenType_tc' class='right_pane' style='display:none;margin-left:10px;'>
		<ul>
			<li><?php echo LangUtil::$pageTerms['TIPS_TC_SPECIMENTYPE_1']; ?></li>
			<li><?php echo LangUtil::$pageTerms['TIPS_TC_SPECIMENTYPE_2']; ?></li>
			<li><?php echo LangUtil::$pageTerms['TIPS_TC_SPECIMENTYPE_3']; ?></li>
		</ul>
	</div>
    
    <div id='TestCategory_tc' class='right_pane' style='display:none;margin-left:10px;'>
		<ul>
			<li><?php echo LangUtil::$pageTerms['TIPS_TC_TESTCATEGORY_1']; ?></li>
			<li><?php echo LangUtil::$pageTerms['TIPS_TC_TESTCATEGORY_2']; ?></li>
			<li><?php echo LangUtil::$pageTerms['TIPS_TC_TESTCATEGORY_3']; ?></li>
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
			$ok_function = "delete_catalog_data();";
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

</div>
</div>
<div class='modal container hide fade' id='form' role="dialog" data-backdrop="static">
	
</div>
<!-- END ROW-FLUID-->  
<?php include("includes/scripts.php");
require_once("includes/script_elems.php");
$script_elems = new ScriptElems();
$script_elems->enableDatePicker();
?>
<script type='text/javascript'>
$(document).ready(function(){
	$('.content_div').hide();
	$('#test_types_div').hide();
	$('#specimen_types_div').hide();
	$('#test_categories_div').show();
	$('#specimen_rejection_div').hide();
	<?php
	if(isset($_REQUEST['show_t']))
	{
		?>
		load_right_pane('test_types_div');
		<?php
	}
	else if(isset($_REQUEST['show_s']))
	{
		?>
		load_right_pane('specimen_types_div');
		<?php
	}
	else if(isset($_REQUEST['show_tc']))
	{
		?>
		load_right_pane('test_categories_div');
		<?php
	}
	else if(isset($_REQUEST['show_sr']))
	{
		?>
		load_right_pane('specimen_rejection_div');
		<?php
	}
	else if(isset($_REQUEST['tdel']))
	{
		?>
		$('#tdel_msg').show();
		load_right_pane('test_types_div');
		<?php
	}
	else if(isset($_REQUEST['sdel']))
	{
		?>
		$('#sdel_msg').show();
		load_right_pane('specimen_types_div');
		<?php
	}
	else if(isset($_REQUEST['tcdel']))
	{
		?>
		$('#sdel_msg').show();
		load_right_pane('test_categories_div');
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

function delete_catalog_data()
{
	$('#remove_data_progress').show();
	var url_string = "ajax/catalog_deletedata.php";
	$.ajax({
		url: url_string, 
		success: function () {
			$('#remove_data_progress').hide();
			window.location='catalog.php?rm';
		}
	});
}
function add_section(){
	var el = jQuery('.portlet .tools a.reload').parents(".portlet");
	App.blockUI(el);
	
	var url = 'catalog/test_category_new.php';
	$('#form').html("");
	var target_div = "form";
	$("#"+ target_div).load(url, 
		{lab_config: "" }, 
		function() 
		{
			$('#'+target_div).modal('show');
			App.unblockUI(el);
		}
	);
	
}

function add_phase(){
	var el = jQuery('.portlet .tools a.reload').parents(".portlet");
	App.blockUI(el);
	
	var url = 'catalog/rejection_phase_new.php';
	$('#form').html("");
	var target_div = "form";
	$("#"+ target_div).load(url, 
		{lab_config: "" }, 
		function() 
		{
			$('#'+target_div).modal('show');
			App.unblockUI(el);
		}
	);
	
}

function add_reason(){
	var el = jQuery('.portlet .tools a.reload').parents(".portlet");
	App.blockUI(el);
	
	var url = 'catalog/rejection_reason_new.php';
	$('#form').html("");
	var target_div = "form";
	$("#"+ target_div).load(url, 
		{lab_config: "" }, 
		function() 
		{
			$('#'+target_div).modal('show');
			App.unblockUI(el);
		}
	);
	
}
</script>
<?php include("includes/footer.php"); ?>