<?php
#
# Adds result for a single test
# Called via ajax from results_entry.php
#

include("../includes/db_lib.php");
include("../includes/user_lib.php");
LangUtil::setPageId("results_entry");

$test_id = $_REQUEST['test_id'];
$parent_test_id = $_REQUEST['parent_test_id'];
$specimen_id = $_REQUEST['specimen_id'];
$comment = $_REQUEST['comments'];
$comment_1=$_REQUEST['comments_1'];
$dd_to=$_REQUEST['dd_to'];
$mm_to=$_REQUEST['mm_to'];
$yyyy_to=$_REQUEST['yyyy_to'];

$test_name = get_test_name_by_id($test_id);
$test = Test::getById($test_id);
$test_type = TestType::getById($test->testTypeId);
$specimen = Specimen::getById($specimen_id);
$patient = Patient::getById($specimen->patientId);

if($comment!=="" && $comment_1!="")
{
    $comments=$comment." , " . $comment_1;
}
else if($comment==""&& $comment_1!="")
{
    $comments=$comment_1;
}
else if($comment!=""&& $comment_1=="")
{
    $comments=$comment;
}

$result_values = $_REQUEST['result'];
$measure_count = 0;
$measure_list = $test_type->getMeasures();

    $submeasure_list = array();
    $comb_measure_list = array();
    
    foreach($measure_list as $measure)
    {
        
        $submeasure_list = $measure->getSubmeasuresAsObj();
        //echo "<br>".count($submeasure_list);
        //print_r($submeasure_list);
        $submeasure_count = count($submeasure_list);
        
        if($measure->checkIfSubmeasure() == 1)
        {
            continue;
        }
            
        if($submeasure_count == 0)
        {
            array_push($comb_measure_list, $measure);
        }
        else
        {
            array_push($comb_measure_list, $measure);
            foreach($submeasure_list as $submeasure)
                array_push($comb_measure_list, $submeasure); 
        }
    }
    $measure_list = $comb_measure_list;

foreach($measure_list as $measure)
{
	$range_type = $measure->getRangeType();
	if($range_type == Measure::$RANGE_AUTOCOMPLETE)
	{
		$result_value = $result_values[$measure_count];
		$result_to_push = $result_value;
		$autocomplete_selected_list = explode(",", $result_value);
		$value_string = "";
		foreach($autocomplete_selected_list as $value)
		{
			if(trim($value) == "")continue;
                        $value_string .= $value."_";
		}
                if( substr($value_string, -1) == "_")
                        $value_string = substr($value_string, 0, -1);
		$result_values[$measure_count] = $value_string;
	}
        else if($range_type == Measure::$RANGE_FREETEXT)
	{
		$result_value = $result_values[$measure_count];
		$result_to_push = $result_value ;
		$result_value = "[\$]".$result_value."[\/\$]";
		$result_values[$measure_count] = $result_value;
	}
	$measure_count++;
}

foreach($result_values as $result_val)
{
	if(trim($result_val) == "")
	{
		# Empty value. Do not add results
		?>
		<div class='sidetip_nopos'>
			<?php echo LangUtil::$pageTerms['MSG_RESULTSUBMITTED']; ?>
			&nbsp;<a href='javascript:specimen_info(<?php echo $specimen_id; ?>);'><?php echo LangUtil::$generalTerms['CMD_VIEW']; ?> &raquo;</a>
		</div>
		<?php
		return;
	}
}
$result_csv = implode(",", $result_values).",";
$user_id = $_SESSION['user_id'];
//NC3065
//$unix_ts = mktime(0,0,0,$mm_to,$dd_to,$yyyy_to);
//$ts =date("Y-m-d H:i:s", $unix_ts);
//-NC3065
add_test_result($test_id, $result_csv, $comments, "", $user_id, $ts, $patient->getHashValue());

update_specimen_status($specimen_id);
$test_list = get_tests_by_specimen_id($specimen_id);

$test_modified = Test::getById($test_id);

$result_to_push = strip_tags($test_modified->decodeResult());
$result_to_push = str_replace("&nbsp;","",$result_to_push);

API::updateExternalLabrequest($patient->surrogateId, $test->external_lab_no, $result_to_push, $comments);
# Show confirmation with details.
?>