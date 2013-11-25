<?php
#
# Adds a new specimen to DB and redirects to specimen_added.php
# Called via Ajax from new_specimen.php

include("../includes/db_lib.php");
$saved_session = SessionUtil::save();
$session_num = $_REQUEST['session_num'];

# Helper function
function get_custom_value($custom_field)
{
	# Fetched custom field value from $_REQUEST
	$name_prefix = "custom_".$custom_field->id;
	if
	(
		$custom_field->fieldTypeId == CustomField::$FIELD_FREETEXT ||
		$custom_field->fieldTypeId == CustomField::$FIELD_OPTIONS || 
		$custom_field->fieldTypeId == CustomField::$FIELD_MULTISELECT ||
		$custom_field->fieldTypeId == CustomField::$FIELD_NUMERIC
	)
	{
		return $_REQUEST[$name_prefix];
	}
	else if($custom_field->fieldTypeId == CustomField::$FIELD_DATE)
	{
	    $value_date = $name_prefix."_datename";
		$date_value =  $_REQUEST[$value_date];
		return $date_value;
	}
}


//$session_num = get_session_number();

$specimen_id = bcadd(get_max_specimen_id(),$_REQUEST['specimen_id']);
$dnum = $_REQUEST['dnum'];
$dnum = preg_replace("/[^a-zA-Z0-9\/\s]/", "", $dnum);
$prefixed_dnum = date("Ymd")."-".$dnum;
$addl_id = $_REQUEST['addl_id'];
$addl_id = preg_replace("/[^a-zA-Z0-9\/\s]/", "", $addl_id);
$date_recvd = date("Y-m-d");//$_REQUEST['receipt_yyyy']."-".$_REQUEST['receipt_mm']."-".$_REQUEST['receipt_dd']; //now()
/*
if(trim($_REQUEST['collect_yyyy']) == "" && trim($_REQUEST['collect_mm']) == "" && trim($_REQUEST['collect_dd']) == "")
	# date_collected not entered. Copy value from date_received
	$date_collected = $date_received;
else
	$date_collected = $_REQUEST['collect_yyyy']."-".$_REQUEST['collect_mm']."-".$_REQUEST['collect_dd'];
 */
//$time_collected = $_REQUEST['ctime_hh'].":".$_REQUEST['ctime_mm'];
$date_collected = $_REQUEST['spec_date'];
$time_collected = $_REQUEST['spec_time'];
$patient_id = $_REQUEST['pid'];
$specimen_type_id = $_REQUEST['stype'];
$comments = $_REQUEST['comments'];
$comments = preg_replace("/[^a-zA-Z0-9\s]/", "", $comments);
$report_to = $_REQUEST['report_to'];
$doctor = $_REQUEST['doctor'];
$doctor = preg_replace("/[^a-zA-Z\s]/", "", $doctor);
$title=$_REQUEST['title'];
$tests_list = $_REQUEST['t_type_list'];
$external_lab_no = $_REQUEST['external_lab_no'];


begin_transaction();

$specimen = new Specimen();
$specimen->sessionNum = $session_num;
$specimen->specimenId = $specimen_id;
$specimen->auxId = $addl_id;
$specimen->dateCollected = $date_collected;
$specimen->timeCollected = $time_collected;
$specimen->dateRecvd = $date_recvd; //now()
$specimen->patientId = $patient_id;
$specimen->specimenTypeId = $specimen_type_id;
$specimen->comments = $comments;
$specimen->userId = $_SESSION['user_id'];
$specimen->statusCodeId = Specimen::$STATUS_NOT_COLLECTED;
$specimen->dailyNum = $prefixed_dnum;
$specimen->external_lab_no=$external_lab_no;
# If marked for referral, set appropriate status and store hospital/lab name
if(isset($_REQUEST['ref_out']) && $_REQUEST['ref_out'] == "Y")
{
	$specimen->statusCodeId = Specimen::$STATUS_REFERRED;
	$specimen->referredToName = $_REQUEST['ref_out_name'];
	# Add entries to 'specimen_custom_data'
    $custom_field_list = get_custom_fields_specimen();
    foreach($custom_field_list as $custom_field)
    {
        $custom_value = get_custom_value($custom_field);
        $custom_data = new SpecimenCustomData();
        $custom_data->fieldId = $custom_field->id;
        $custom_data->fieldValue = $custom_value;
        $custom_data->specimenId = $specimen_id;
        add_custom_data_specimen($custom_data);
    }
}
$specimen->referredTo = 0;
$specimen->reportTo = $report_to;

		if($doctor!="")
$specimen->doctor = $title.$doctor;
else
$specimen->doctor=$doctor;
# Add entry to 'specimen' table
add_specimen($specimen);
$patient = get_patient_by_id($patient_id);


# Add entries to 'test' table
foreach($tests_list as $test_type_id)
{
	$test = new Test();
	$test->specimenId = $specimen_id;
	$test->testTypeId = $test_type_id;
	$test->comments = "";
	$test->userId = $_SESSION['user_id'];
	$test->result = "";
	
	$ex = API::getExternalParentLabNo($patient->surrogateId,  get_test_name_by_id($test_type_id, $_SESSION['lab_config_id']));
	if ($ex =='' || $ex==null) $ex = 0;
	$test->external_parent_lab_no= $ex;
	add_test($test);
	
	//Add two levels of child tests form external system (sanitas)	
	$child_tests = get_child_tests($test_type_id);
	if (count($child_tests)>0){
		foreach($child_tests as $child_test)
		{
			$test = new Test();
			$test->specimenId = $specimen_id;
			$test->testTypeId = $child_test['test_type_id'];
			$test->comments = "";
			$test->userId = $_SESSION['user_id'];
			$test->result = "";
			$test->external_lab_no=API::getExternalLabNo($patient->surrogateId, get_test_name_by_id($child_test['test_type_id'], $_SESSION['lab_config_id']));
			$ex = API::getExternalParentLabNo($patient->surrogateId,  get_test_name_by_id($child_test['test_type_id'], $_SESSION['lab_config_id']));
			if ($ex =='' || $ex==null) $ex = 0;
			$test->external_parent_lab_no= $ex;
			add_test($test);
			
			$child_tests = get_child_tests($child_test['test_type_id']);
			if (count($child_tests)>0){
				foreach($child_tests as $child_test)
				{
					$test = new Test();
					$test->specimenId = $specimen_id;
					$test->testTypeId = $child_test['test_type_id'];
					$test->comments = "";
					$test->userId = $_SESSION['user_id'];
					$test->result = "";
					$test->external_lab_no=API::getExternalLabNo($patient->surrogateId, get_test_name_by_id($child_test['test_type_id'], $_SESSION['lab_config_id']));
					$ex = API::getExternalParentLabNo($patient->surrogateId,  get_test_name_by_id($child_test['test_type_id'], $_SESSION['lab_config_id']));
					if ($ex =='' || $ex==null) $ex = 0;
					$test->external_parent_lab_no= $ex;
					add_test($test);
				}
			}
		}
	}
	

}

commit_transaction();
API::updateExternalLabRequestStatus($external_lab_no, Specimen::$STATUS_NOT_COLLECTED);

SessionUtil::restore($saved_session);
?>