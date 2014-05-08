<?php
#
# Adds result for a single test
# Called via ajax from results_entry.php
#

include("../includes/db_lib.php");
include("../includes/user_lib.php");
include("../ajax/push_results.php");
LangUtil::setPageId("results_entry");

$test_id = get_request_variable('test_id');
$test_name = get_test_name_by_id($test_id);
$test = Test::getById($test_id);
$test_type = TestType::getById($test->testTypeId);
$specimen_id = get_request_variable('specimen_id');
$specimen = Specimen::getById($specimen_id);
$patient = Patient::getById($specimen->patientId);
$comment = get_request_variable('comments');
$comment_1=get_request_variable('comments_1');
$edit_test_flag=get_request_variable('edit_test_flag');
$comments = "";

/*$dd_to=$_REQUEST['dd_to'];
$mm_to=$_REQUEST['mm_to'];
$yyyy_to=$_REQUEST['yyyy_to'];*/

if($comment!=="" && $comment_1!="")
{
$comments=$comment." , " . $comment_1;
}
else
if($comment==""&& $comment_1!="")
{
$comments=$comment_1;
}
else
if($comment!=""&& $comment_1=="")
{
$comments=$comment;
}

$result_values = get_request_variable('result');
$measure_count = 0;
$range_measure = array();
$measure_list = $test_type->getMeasures();
//Holds a key value pair of measureId and result to put in test_measure table the results table.
$measure_result = array();

$submeasure_list = array();
                $comb_measure_list = array();
               // print_r($measure_list);
                
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
			if(trim($value) == "")
				continue;
				$value_string .= $value."_";
		}
                if( substr($value_string, -1) == "_")
                        $value_string = substr($value_string, 0, -1);
		$result_values[$measure_count] = $value_string;
		//Stuffing measureId and result in measure_result array
		$measure_result[$measure->measureId] = $value_string;
	}
        else if($range_type == Measure::$RANGE_FREETEXT)
	{
		$result_value = $result_values[$measure_count];
		$result_to_push = $result_value ;
		$result_value = "[\$]".$result_value."[\/\$]";
		$result_values[$measure_count] = $result_value;
		//Stuffing measureId and result in measure_result array
		$measure_result[$measure->measureId] = $result_to_push;
	}
	else if ($range_type == Measure::$RANGE_NUMERIC) {


		$range = $measure->getNumericRangeString($patient);
		$unit = $measure->unit;
		$range_measure[$measure->measureId] = " $unit $range";

		$result_value = $result_values[$measure_count];
		$measure_result[$measure->measureId] = $result_value;
	}
	else 
	{
		$result_value = $result_values[$measure_count];
		$measure_result[$measure->measureId] = $result_value;
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
// $unix_ts = mktime(0,0,0,$mm_to,$dd_to,$yyyy_to);
// $ts =date("Y-m-d H:i:s", $unix_ts);
 $ts =date("Y-m-d H:i:s");
//-NC3065
add_test_result($test_id, $result_csv, $comments, "", $user_id, $ts, $patient->getHashValue(), $measure_result, $patient->surrogateId, $range_measure);

update_specimen_status($specimen_id);
$test_list = get_tests_by_specimen_id($specimen_id);

$test_modified = Test::getById($test_id);

$rubbish= array("<br>" , "&nbsp;", "<b>","</b>", "[$]", ",", "[/$]" );
$result_to_push = str_replace($rubbish, "", $test_modified->decodeResult());

//Finding ":" helps us know if test has measures so that we update done for main test
if(strpos($test_modified->decodeResult(), ":" ) == true){
	API::updateExternalLabrequest($patient->surrogateId, $test->external_lab_no, "Done", $comments);
}

# If this is an EDIT request then reset the 'result_returned' flag for in external_lab_request
if($edit_test_flag==1) resend_test_results($test_id);

//Sends results back to Sanitas :ajax/push_results.php
send_result_to_externalS();

# Show confirmation with details.
$modal_close_link_id = "m_c_l_id_$test_id";
?>
<div class="modal-header">
	<a id="<?php echo $modal_close_link_id; ?>" href="javascript:close_modal('<?php echo $modal_close_link_id; ?>');" class="close"></a>
	<h4><i class="icon-pencil"></i> <?php echo LangUtil::$pageTerms['MSG_RESULTSUBMITTED']; ?></h4>
</div>
<div class="modal-body">
	<div class="portlet box grey">
	<div class="portlet-title">
	<h4>Results</h4>
	</div>
	<div class="portlet-body">
<?php
if($_SESSION['sid'] != 0)
{
	echo LangUtil::$generalTerms['SPECIMEN_ID'].": ";
	$specimen->getAuxId();
	echo "<br>";
}
//if($_SESSION['pnamehide'] == 0)
if($_SESSION['user_level'] == $LIS_TECH_SHOWPNAME)
{
	echo LangUtil::$generalTerms['PATIENT'].": $patient->name ($patient->sex ".$patient->getAgeNumber().") <br>";
}
else
{
	echo LangUtil::$generalTerms['GENDER']."/".LangUtil::$generalTerms['AGE'].": $patient->sex /".$patient->getAgeNumber()."<br>";
}
foreach($test_list as $test)
{
	$test_name = get_test_name_by_id($test->testTypeId);
	echo "<b>$test_name</b>&nbsp;";
	if($test->isPending() === true)
	{
		echo LangUtil::$generalTerms['PENDING_RESULTS'];
	}
	else
	{
		echo $test->decodeResult();
	}
	echo "<br>";
}
?>
<br>
<a href='javascript:specimen_info(<?php echo $specimen_id; ?>);' class="btn"><i class="icon-info-sign"></i> <?php echo LangUtil::$generalTerms['DETAILS']; ?> &raquo;</a>
<?php
# Check if all other tests on the same accession are complete.
# If yes, show link to patient report
$session_num = $specimen->sessionNum;
if(trim($session_num) == "" || $session_num == null)
{
	// Do nothing
}
else
{
	$specimen_list = search_specimens_by_session_exact($session_num);
	$all_done = true;
	foreach($specimen_list as $specimen)
	{
		$test_list = get_tests_by_specimen_id($specimen->specimenId);
		foreach($test_list as $test)
		{
			if(trim($test->result) == "" || $test->result == null)
			{
				# This test pending
				$all_done = false;
			}
		}
	}
	if($all_done)
	{
		echo "<br><br>";
		echo LangUtil::$pageTerms['MSG_RESULTSUBMITTEDALL'];
		echo "<br>";
		$original_specimen = Specimen::getById($_REQUEST['specimen_id']);
		$today = $original_specimen->dateRecvd;
		$today_parts = explode("-", $today);
		$url_string = "reports_testhistory.php?patient_id=".$patient->patientId."&location=".$_SESSION['lab_config_id']."&yf=".$today_parts[0]."&mf=".$today_parts[1]."&df=".$today_parts[2]."&yt=".$today_parts[0]."&mt=".$today_parts[1]."&dt=".$today_parts[2]."&ip=0";
		?>
		
		<a href='<?php echo $url_string; ?>' target='_blank'><?php echo $LANG_ARRAY['reports']['MENU_PATIENT']; ?> &raquo;</a>
		<?php
	}
	echo "<br><br>";
	?>
	<!--  
	<a href='javascript:hide_result_confirmation(<?php echo $specimen_id; ?>);'><?php echo LangUtil::$generalTerms['CMD_HIDE']; ?></a>	
	-->
	<?php
}
?>
</div>
</div>
</div>
<div class="modal-footer">
<!-- a href='' class='btn danger'>Edit</a-->
<a id="<?php echo $modal_close_link_id ."_2"; ?>" href="javascript:close_modal('<?php echo $modal_close_link_id ."_2"; ?>');" class='btn success'>Close</a>
</div>
