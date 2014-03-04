<?php
#
# Adds result for a single test
# Called via ajax from results_entry.php
#

include("../includes/db_lib.php");
include("../includes/user_lib.php");
LangUtil::setPageId("results_entry");
include("../includes/page_elems.php");
LangUtil::setPageId("results_entry");
$page_elems = new PageElems();

$test_id = get_request_variable('tid');
$test = Test::getById($test_id);
$test_type = TestType::getById($test->testTypeId);
$specimen_id = $test->specimenId;
$modal_close_link_id = "m_c_l_id_$test_id";

?>
<div class="modal-header">
	<a id="<?php echo $modal_close_link_id; ?>" href="javascript:close_modal('<?php echo $modal_close_link_id; ?>');" class="close"></a>
	<h4><i class="icon-pencil"></i> Test Result: <?php echo $test_type->getName(); ?></h4>
</div>
<div class="modal-body">
<div class="portlet box grey">
<div class="portlet-title">
<h4>Results</h4>
</div>
<div class="portlet-body">
<table class="table table-striped table-bordered table-advance">
<thead>
<th>
Test Name
</th>
<th>
Results
</th>
<th>
Remarks
</th>
<th>
Entered by
</th>
<th>
Specimen TT
</th>
<th>
Test TT
</th>
<th>
Verified by
</th>
<th>
</th>
</thead>
<tbody>
 <?php $page_elems->getTestInfoRow($test, true);
 
 $child_tests = get_child_tests($test_type->testTypeId);
 if (count($child_tests)>0){
 	foreach($child_tests as $child_test)
 	{
 		$chid_test_entry = get_test_entry($specimen_id, $child_test['test_type_id']);
 			
 		$page_elems->getTestInfoRow($chid_test_entry, true);
 		$child_tests = get_child_tests($child_test['test_type_id']);
 		if (count($child_tests)>0){
 			foreach($child_tests as $child_test)
 			{
 				$chid_test_entry = get_test_entry($specimen_id, $child_test['test_type_id']);
 				$page_elems->getTestInfoRow($chid_test_entry, true);
 			}
 		}
 	}
 }
 
 
 ?>
 
 
 </tbody>
 </table>

</div>
</div>
</div>
<div class="modal-footer">
<a id="<?php echo $modal_close_link_id . "_2"; ?>" href="javascript:close_modal('<?php echo $modal_close_link_id . "_2"; ?>');" class='btn success'>Close</a>
</div>
<script>
function verify_result(test_id){
	var el = jQuery('.portlet .tools a.reload').parents(".portlet");
	App.blockUI(el);
	//Mark test as cancelled
		var url = 'ajax/result_entry_tests.php';
		$.post(url, 
		{a: test_id, t: 13}, 
		function(result) 
		{
			$('#verifydby'+test_id).removeClass('label-warning');
			$('#verifydby'+test_id).addClass('label-success');
			$('#verifybtn'+test_id).addClass('disabled');
			$('#verifydby'+test_id).html(result);
			$("tr#"+test_id).remove();
			App.unblockUI(el);
		}
		);
	
}

</script>
