<?php
#
# Returns list of patients matched with list of pending specimens
# Called via Ajax form result_entry.php
#
include("../includes/db_lib.php");
include("../includes/user_lib.php");
LangUtil::setPageId("results_entry");

$attrib_value = get_request_variable('a');
$attrib_type = get_request_variable('t');
$date_from = get_request_variable('df');
$date_to = get_request_variable('dt');
$status = get_request_variable('s');
$search_term = get_request_variable('st');
$dynamic = 1;
$search_settings = get_lab_config_settings_search();
$rcap = $search_settings['results_per_page'];
$lab_config = LabConfig::getById($_SESSION['lab_config_id']);
$uiinfo = "op=".$attrib_type."&qr=".$attrib_value;
$quote='';
putUILog('result_entry_tests', $uiinfo, basename($_SERVER['REQUEST_URI'], ".php"), 'X', 'X', 'X');
?>
<?php
$result_cap = get_request_variable('result_cap', $rcap);
$result_counter = get_request_variable('result_counter', 1);

$query_string = "";
if($dynamic == 0)
{
    if($attrib_type == 5)
    {
            # Search by specimen aux ID
            $query_string = 
                    "SELECT s.specimen_id FROM specimen s, test t, patient p ".
                    "WHERE p.patient_id=s.patient_id ".
                    "AND s.aux_id='$attrib_value'".
                    "AND s.specimen_id=t.specimen_id ".
                    "AND t.result = '' ";
    }
    if($attrib_type == 0)
    {
            # Search by patient ID
            $query_string = 
                    "SELECT s.specimen_id FROM specimen s, test t, patient p ".
                    "WHERE p.patient_id=s.patient_id ".
                    "AND p.surr_id='$attrib_value'".
                    "AND s.specimen_id=t.specimen_id ".
                    "AND t.result = '' ";
    }
    else if($attrib_type == 1)
    {
            # Search by patient name
            $query_string = 
                    "SELECT COUNT(*) AS val FROM patient WHERE name LIKE '%$attrib_value%'";
            $record = query_associative_one($query_string);
            if($record['val'] == 0)
            {
                    # No patients found with matching name
                    ?>
                    <div class='sidetip_nopos'>
                    <b>'<?php echo $attrib_value; ?>'</b> - <?php echo LangUtil::$generalTerms['MSG_SIMILARNOTFOUND']; ?>
                    <?php
                    return;
            }
            $query_string = 
                    "SELECT s.specimen_id FROM specimen s, test t, patient p ".
                    "WHERE s.specimen_id=t.specimen_id ".
                    "AND t.result = '' ".
                    "AND s.patient_id=p.patient_id ".
                    "AND p.name LIKE '%$attrib_value%'";
    }
    else if($attrib_type == 3)
    {
            # Search by patient daily number
            $query_string = 
                    "SELECT specimen_id FROM specimen ".
                    "WHERE daily_num LIKE '%-$attrib_value' ".
                    "AND ( status_code_id=".Specimen::$STATUS_PENDING." ".
                    "OR status_code_id=".Specimen::$STATUS_REFERRED." ) ".
                    "ORDER BY date_collected DESC";
    }
}
else
{
    if($attrib_type == 5)
    {
            # Search by specimen aux ID
            $query_string = 
                    "SELECT s.specimen_id FROM specimen s, test t, patient p ".
                    "WHERE p.patient_id=s.patient_id ".
                    "AND s.aux_id='$attrib_value'".
                    "AND s.specimen_id=t.specimen_id ".
                    "AND t.result = '' LIMIT 0,$rcap ";
    }
    if($attrib_type == 0)
    {
            # Search by patient ID
            $query_string = 
                    "SELECT s.specimen_id FROM specimen s, test t, patient p ".
                    "WHERE p.patient_id=s.patient_id ".
                    "AND p.surr_id='$attrib_value'".
                    "AND s.specimen_id=t.specimen_id ".
                    "AND t.result = '' LIMIT 0,$rcap ";
    }
    else if($attrib_type == 1)
    {
            # Search by patient name
            $query_string = 
                    "SELECT COUNT(*) AS val FROM patient WHERE name LIKE '%$attrib_value%'";
            $record = query_associative_one($query_string);
            if($record['val'] == 0)
            {
                    # No patients found with matching name
                    ?>
                    <div class='sidetip_nopos'>
                    <b>'<?php echo $attrib_value; ?>'</b> - <?php echo LangUtil::$generalTerms['MSG_SIMILARNOTFOUND']; ?>
                    <?php
                    return;
            }
            $query_string = 
                    "SELECT s.specimen_id FROM specimen s, test t, patient p ".
                    "WHERE s.specimen_id=t.specimen_id ".
                    "AND t.result = '' ".
                    "AND s.patient_id=p.patient_id ".
                    "AND p.name LIKE '%$attrib_value%' LIMIT 0,$rcap";
    }
    else if($attrib_type == 3)
    {
            # Search by patient daily number
            $query_string = 
                    "SELECT specimen_id FROM specimen ".
                    "WHERE daily_num LIKE '%-$attrib_value' ".
                    "AND ( status_code_id=".Specimen::$STATUS_PENDING." ".
                    "OR status_code_id=".Specimen::$STATUS_REFERRED." ) ".
                    "ORDER BY date_collected DESC LIMIT 0,$rcap";
    } 
    else if($attrib_type == 9)
    {
            # Search by patient specimen id
                $decoded = decodeSpecimenBarcode($attrib_value);
            $query_string = 
                    "SELECT specimen_id FROM specimen ".
                    "WHERE specimen_id = $decoded[1] ".
                    "AND ( status_code_id=".Specimen::$STATUS_PENDING." ".
                    "OR status_code_id=".Specimen::$STATUS_REFERRED." ) ".
                    "ORDER BY date_collected DESC LIMIT 0,$rcap";
            
    }  
    elseif($attrib_type == 10)
    {
            # Get all specimens with pending status
            $query_string = "";
            if ($status!="request_pending"){
                $query_string .= "
                    SELECT *, 
                            p.name AS patient_name, 
                            st.name as specimen_name, 
                            tt.name AS test_name, 
                            tc.name AS category_name,
                            t.status_code_id AS status, 
                            s.ts AS sorting_time,
                            t.patientVisitNumber AS patient_visit_number,
                            NULL AS receiptNumber,
                            NULL AS receiptType,
                            NULL as orderStage
                        FROM test t
                            LEFT JOIN specimen s ON t.specimen_id = s.specimen_id
                            LEFT JOIN patient p ON s.patient_id = p.patient_id
                            LEFT JOIN specimen_type st ON s.specimen_type_id = st.specimen_type_id
                            LEFT JOIN test_type tt ON t.test_type_id = tt.test_type_id
                            LEFT JOIN test_category tc ON tt.test_category_id = tc.test_category_id
                        WHERE (t.external_parent_lab_no = '0' OR t.external_parent_lab_no = '')
                            AND (p.surr_id = '$search_term' 
                            OR replace(p.name, ' ', '') LIKE replace('%$search_term%', ' ', '') 
                            OR tt.name LIKE '%$search_term%' 
                            OR tc.name LIKE '%$search_term%' 
                            OR s.specimen_id = '$search_term'
                            OR t.patientVisitNumber = '$search_term') ";
            }
            
            if($status!="all" && $status==Specimen::$STATUS_REJECTED)
            {
                $query_string.=" AND s.status_code_id = '$status'";
            }
            else
            if ($status!="all" && $status!=Specimen::$STATUS_NOT_COLLECTED && $status!="request_pending"){
                $query_string.=" AND
                            t.status_code_id='$status' AND
                            s.status_code_id NOT IN (".Specimen::$STATUS_NOT_COLLECTED.",".Specimen::$STATUS_REJECTED.") ";
            }else if($status==Specimen::$STATUS_NOT_COLLECTED){
                $query_string.=" AND s.status_code_id = '$status' ";
            }
            
            if ($status=="all"){
                $query_string.= " UNION ALL ";
            }
            
            if ($status=="all" || $status=="request_pending" ){
                $query_string.= "
                            SELECT
                                    NULL AS test_id,
                                    NULL AS test_type_id,
                                    NULL AS result,
                                    NULL AS ts_started,
                                    NULL AS ts_result_entered,
                                    NULL AS comments,
                                    NULL AS user_id,
                                    NULL AS verified_by,
                                    NULL AS ts,
                                    NULL AS specimen_id,
                                    NULL AS date_verified,
                                    NULL AS status_code_id,
                                    labNo AS external_lab_no,
                                    NULL AS external_parent_lab_no,
                                    NULL AS patientVisitNumber,
                                    NULL AS specimen_id,
                                    NULL AS patient_id,
                                    NULL AS specimen_type_id,
                                    NULL AS user_id,
                                    NULL AS ts,
                                    NULL AS status_code_id,
                                    NULL AS referred_to,
                                    NULL AS comments,
                                    NULL AS aux_id,
                                    NULL AS date_collected,
                                    requestDate AS date_recvd,
                                    NULL AS session_num,
                                    NULL AS time_collected,
                                    NULL AS report_to,
                                    NULL AS doctor,
                                    NULL AS date_reported,
                                    NULL AS referred_to_name,
                                    NULL AS daily_num,
                                    labNo AS external_lab_no,
                                    requestDate AS ts_collected,
                                    NULL AS patient_id,
                                    NULL AS addl_id,
                                    NULL AS name,
                                    gender AS sex,
                                    NULL AS age,
                                    dateOfBirth AS dob,
                                    NULL AS created_by,
                                    NULL AS ts,
                                    NULL AS partial_dob,
                                    patient_id AS surr_id,
                                    NULL AS hash_value,
                                    NULL AS specimen_type_id,
                                    NULL AS name,
                                    NULL AS description,
                                    NULL AS ts,
                                    NULL AS disabled,
                                    NULL AS test_type_id,
                                    NULL AS parent_test_type_id,
                                    NULL AS specimen,
                                    NULL AS test_name,
                                    NULL AS description,
                                    NULL AS test_category_id,
                                    NULL AS ts,
                                    NULL AS is_panel,
                                    NULL AS disabled,
                                    NULL AS clinical_data,
                                    NULL AS hide_patient_name,
                                    NULL AS prevalence_threshold,
                                    NULL AS target_tat,
                                    NULL AS test_category_id,
                                    NULL AS name,
                                    NULL AS description,
                                    NULL AS ts,
                                    full_name AS patient_name,
                                    NULL AS specimen_name,
                                    investigation AS test_name,
                                    NULL AS category_name,
                                    NULL AS status,
                                    requestDate AS sorting_time,
                                    patientVisitNumber AS patient_visit_number,
                                    receiptNumber AS receiptNumber,
                                    receiptType AS receiptType,
                                    orderStage as orderStage
                                    FROM
                                        $GLOBAL_DB_NAME.external_lab_request
                                    WHERE
                                        test_status = 0 
                                        AND (labNo != '' OR labNo IS NOT NULL) 
                                        AND (patient_id != '' OR patient_id IS NOT NULL) 
                                        AND requestDate >= CURDATE() - 365
                                        AND parentLabNo = 0
                                        AND (patient_id = '$search_term' 
                                            OR replace(full_name, ' ', '') LIKE replace('%$search_term%', ' ', '') 
                                            OR investigation LIKE '%$search_term%'
                                            OR patientVisitNumber = '$search_term')
                                     ";
                        }
//                         if ($status!="all" && $status !='request_pending')
//                             $query_string.=" AND test_status=99";
                            
                      $query_string.=    " ORDER BY date_recvd DESC, sorting_time DESC";
                //$query_string.= " ORDER BY s.date_recvd DESC, s.ts DESC";
//                  echo $query_string;
                //error_log("\n".$query_string, 3, "../logs/my.error.log");
    }
    elseif($attrib_type == 11)
    {
		    # Get all specimens that have been started with pending results
		    	$query_string =
		    	"SELECT s.specimen_id FROM specimen s, test t, patient p ".
		        "WHERE p.patient_id=s.patient_id ".
		        "AND (status_code_id=".Specimen::$STATUS_STARTED.") ".
		        "AND s.specimen_id=t.specimen_id ".
		        "AND t.result = ''";
	}else if($attrib_type == 12)
	{	
		# Update specimen to started status code
		$ts = date("Y-m-d H:i:s");
		$query_string = "UPDATE test SET 
                    		status_code_id = ".Specimen::$STATUS_STARTED.",
                    		ts_started = '$ts',
                    		ts = '$ts' 
						WHERE test_id ='$attrib_value'";
	}
	else if($attrib_type == 13)
	{
		#Update specimen to verified status code
		$ts = date("Y-m-d H:i:s");
		$query_string = "UPDATE test SET 
                    		status_code_id = ".Specimen::$STATUS_VERIFIED.",
                    		ts = '$ts',
                    		date_verified = '$ts',
                    		verified_by = ".$_SESSION['user_id']."  
                    		WHERE test_id ='$attrib_value'";
	}
	
}
//RUN QUERY DEPENDING ON PARAMENTERS
if($attrib_type == 12||$attrib_type == 13)
{
	$resultset = query_update($query_string);
	switch ($attrib_type){
	case 12:
		echo '<a href="javascript:fetch_test_result_form('.$quote.$attrib_value.$quote.');" title="Click to Enter Results for this Specimen" class="btn yellow mini"><i class="icon-pencil"></i>Enter Results</a>%
	          <a href="javascript:fetch_specimen2('.$quote.$attrib_value.$quote.');" title="View specimen details" class="btn mini"><i class="icon-search"></i> View Details</a>';
		return;
	break;
	case 13:
		echo get_username_by_id($_SESSION['user_id']);
		return;
	break;
	}
}
else{
 	
 	$num_records = count(query_associative_all($query_string, $row_count));
 	//Set Pagination
 	$page = $_REQUEST['p'];
 	$url="javascript:fetch_tests('$status'";
 	$limit=10;
 
 	$pagination_array = setPagination($query_string, $limit, $page, $url, $num_records, $search_term);
	$resultset = query_associative_all($pagination_array['query_string'], $row_count);
}

// $specimen_id_list = array();
// foreach($resultset as $record)
// {
// 	$specimen_id_list[] = $record['specimen_id'];
// }
# Remove duplicates that might come due to multiple pending tests
//$specimen_id_list = array_values(array_unique($specimen_id_list));
?>
<div class="row-fluid">
	<!--div class="span4">Date: <br>
		<div id="form-date-range" class="btn date-range">
			<i class="icon-calendar"></i> &nbsp;<span></span> 
			<b class="caret"></b>
		</div>
	</div-->
	<br>
	<div class="span3 alert alert-info">
		<div class='sidetip_nopos'>
		<?php 
		if($num_records == 0 || $resultset == null)
		{
		
		if($attrib_type == 0)
			echo " ".LangUtil::$generalTerms['PATIENT_ID']." ";
		else if($attrib_type == 1)
			echo " ".LangUtil::$generalTerms['PATIENT_NAME']." ";
		else if($attrib_type == 3)
			echo " ".LangUtil::$generalTerms['PATIENT_DAILYNUM']." ";
	   	if($attrib_type == 9)
	    {
	       echo LangUtil::$pageTerms['MSG_PENDINGNOTFOUND'];
	       echo '<br>'.'Try searching by patient name';
	    }
	    if($attrib_type == 12)
	    {
	    	echo LangUtil::$pageTerms['MSG_PENDINGNOTFOUND'];
	    	echo '<br>'.'Try searching by patient name';
	    }
	    
	    else
	    {
			echo LangUtil::$pageTerms['MSG_PENDINGNOTFOUND'];
	    }
	    
	    }
	    else echo $num_records." records found.";
		?> 
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="span1">
	Refresh:
	<span>
	<button id="refresh" class="btn blue icn-only"><i class="icon-refresh m-icon-white"></i>
	</button></span> 
	</div>
	<div class="span3">
	
	Search: <span id="search">
	
	<input type=text id="search_tests"></input>
	
	
	</span> 
	</div>
	<!--div class="span3">Lab Section: <span id="section"></span> </div-->
	<div class="span3">Status: <span id="status"></span> </div>
	<!-- div class="span3">Specimen Type: <span id="specimen_type"></span> </div>
	<div class="span3">Test Type: <span id="test_type"></span> </div-->
</div>
<table class="table tale-striped table-condensed" id="<?php echo $attrib_type; ?>">
	<thead>
	
		<tr>
			<?php 
			if($_SESSION['sid'] != 0)
			{
			?>
				<th style='width:75px;'><?php echo LangUtil::$generalTerms['SPECIMEN_ID']; ?></th>
			<?php
			}
			?>
				<th style='width:100px;'><?php echo "Date Ordered";?></th>
			<?php
			if($_SESSION['pid'] != 0)
			{
			?>
				<th style='width:75px;'>Patient No</th>
			<?php
			}
			?>
			<th style='width:100px;'>Visit No</th>
			<?php
			if(false) //Not displaying Lab no
			{
			?>
				<th style='width:100px;'>Lab. No</th>
			<?php
			}
			if($_SESSION['p_addl'] != 0)
			{
			?>
				<th style='width:75px;'><?php echo LangUtil::$generalTerms['ADDL_ID']; ?></th>
			<?php
			}
			
			if($_SESSION['s_addl'] != 0)
			{
			?>
				<th style='width:90px;'><?php echo LangUtil::$generalTerms['SPECIMEN_ID']; ?></th>
			<?php
			}
			?>
			<?php
			//Showing patient name to all
			//if($lab_config->hidePatientName == 0)
			//if($_SESSION['user_level'] == $LIS_TECH_SHOWPNAME)
			?>
			<th style='width:200px;'><?php echo LangUtil::$generalTerms['PATIENT_NAME']; ?></th>
			<th style='width:100px;'><?php echo LangUtil::$generalTerms['SPECIMEN_TYPE']; ?></th>
			<th style='width:100px;'><?php echo "Test"; ?></th>
			<th style='width:100px;'><?php echo "Order Stage"; ?></th>
			<th style='width:50px;'><?php echo "Status";?></th>			
			<th style='width:100px;' class="test-actions"></th>
			<?php if($attrib_type==10){
			?>
			<th style='width:100px;' class="test-actions"></th>
			<?php 
			}?>
		</tr>
	</thead>
	<tbody>
<?php
	$count = 1;
	foreach($resultset as $record)
	{  
		$specimen = Specimen::getObject($record);
		$patient = Patient::getObject($record);
		$specimen_type = SpecimenType::getObject($record);
		$test = Test::getObject($record);
		$test_type = TestType::getObject($record);
		$test_category = TestCategory::getObject($record);
		?>
		
		<tr <?php
		if($attrib_type == 3 && $count != 1)
		{
			# Fetching by patient daily number. Hide all records except the latest one
			echo " class='old_pnum_records' style='display:none' ";
		}
		?> id="<?php echo $test->testId; ?>">
			<?php
			if($_SESSION['sid'] != 0)
			{
			?>
				<td style='width:75px;'><?php echo $test->getLabSectionByTest(); ?></td>
			<?php
			}
			?>
				<td style='width:75px;'><?php echo $specimen->ts_collected; ?></td>
			<?php
			if($_SESSION['pid'] != 0)
			{
			?>
				<td style='width:75px;'><?php echo $patient->getSurrogateId(); ?></td>
			<?php
			}?>
			
				<td style='width:75px;'><?php echo $record['patient_visit_number']; ?></td>
			<?php
			if(false) // Stopping displaying the Lab No
			{
			?>
				<td style='width:80px;'><?php echo $specimen->getDailyNumFull(); ?></td>
			<?php
			}
			if($_SESSION['p_addl'] != 0)
			{
			?>
				<td style='width:75px;'><?php echo $patient->getAddlId(); ?></td>
			<?php
			}
			if($_SESSION['s_addl'] != 0)
			{
			?>
				<td style='width:75px;'><?php echo $specimen->getAuxId(); ?></td>
			<?php
			}
			?>
			<?php
			//if($lab_config->hidePatientName == 0)
			?>
			<td style='width:200px;'><?php echo $patient->getPatientName()." (".substr($patient->sex, 0,1)." ".$patient->getAgeNumber().") "; ?></td>
			<td style='width:100px;'><?php echo $specimen_type->getSpecimenName(); ?></td>
			<td style='width:100px;'>
			<?php
			echo $test_type->getTestName();
			?>
			</td>
			<td style='width:100px;'><?php echo $test->getTestOrderStage($test->external_lab_no); ?></td>
			<?php 
			$specimen_status = $specimen->statusCodeId;
			$test_status = $test->getStatusCode();
			
			if (isset($test->testId))
			{	
				echo '<td class="hidden-phone"><span id=span'.$test->testId.' class="label ';
			}else {
				echo '<td class="hidden-phone"><span id=span'.$record["external_lab_no"].' class="label ';
			}
			
			if($test_status == Specimen::$STATUS_PENDING && isset($test_status)){
				if($specimen_status == Specimen::$STATUS_NOT_COLLECTED){
					echo 'label-inverse">Not Collected';
					echo '</span></td>';
					echo '
						<td id=actionA'.$test->testId.' style="width:130px;" class="test-actions">
							<a href="javascript:accept_specimen('.$quote.$specimen->specimenId.$quote.', '.$quote.$test->testId.$quote.')" class="btn mini green"><i class="icon-thumbs-up"></i> Accept</a>
							<a href="javascript:load_specimen_rejection('.$quote.$specimen->specimenId.$quote.')" class="btn mini yellow"><i class="icon-thumbs-down"></i> Reject</a>
                                                </td>
						<td id=actionB'.$test->testId.' style="width:130px;">
						<a href="javascript:specimen_info('.$quote.$specimen->specimenId.$quote.');" title="View specimen details" class="btn mini">
							<i class="icon-search"></i> View Details
						</a>
						</td>';
				}else
					if($specimen_status == Specimen::$STATUS_REJECTED){
						echo 'label">Rejected';
						echo '</span></td>';
						echo '
					
						<td style="width:130px;"><a href="javascript:specimen_info('.$quote.$specimen->specimenId.$quote.');" title="View specimen details" class="btn mini">
							<i class="icon-search"></i> View Details
						</a>
						<td style="width:130px;"><a href="javascript:rejection_report('.$quote.$specimen->specimenId.$quote.');" title="Click to view Specimen Rejection Report" class="btn blue-stripe mini">
						<i class="icon-print"></i> Print Report</a>
					</td>
					</td>';
					}else{echo 'label-important">Pending';
						
						echo '</span></td>';
						echo '<div id=action'.$test->testId.'>
					<td id=actionA'.$test->testId.' style="width:130px;" class="test-actions"><a href="javascript:start_test('.$quote.$test->testId.$quote.');" title="Click to begin testing this Specimen" class="btn red mini">
						<i class="icon-ok"></i> '.LangUtil::$generalTerms['START_TEST'].'</a>
					</td>
					<td id=actionB'.$test->testId.' style="width:130px;">
						<a href="javascript:specimen_info('.$quote.$specimen->specimenId.$quote.');" title="View specimen details" class="btn mini">
							<i class="icon-search"></i> View Details
						</a>
					</td></div>';
					}
			}
			else
			if($test_status == Specimen::$STATUS_DONE){
				echo 'label-info">Completed';
				echo '</span></td>';
				echo '
			<td style="width:130px;" class="test-actions"><a href="javascript:start_test('.$quote.$specimen->specimenId.$quote.');" title="Click to view results" class="btn blue-stripe mini">
				<i class="icon-edit"></i> View Results</a>
			</td>
			<td style="width:130px;"><a href="javascript:fetch_specimen2('.$quote.$specimen->specimenId.$quote.');" title="View specimen details" class="btn green-stripe mini">
				<i class="icon-ok"></i> Verify</a>
			</td>';
			}else
			if($test_status == Specimen::$STATUS_REFERRED){
				echo 'label-warning">Referred';
				echo '</span></td>';
				echo '
			<td style="width:100px;" class="test-actions"><a href="javascript:start_test('.$quote.$specimen->specimenId.$quote.');" title="Click to begin testing this Specimen" class="btn red mini">
				<i class="icon-ok"></i>'.LangUtil::$generalTerms['START_TEST'].'</a>
			</td>
			<td style="width:100px;"><a href="javascript:fetch_specimen2('.$quote.$specimen->specimenId.$quote.');" title="View specimen details" class="btn blue mini">
				<i class="icon-group"></i>'.LangUtil::$generalTerms['ASSIGN_TO'].'</a>
			</td>';
			}else
			if($test_status == Specimen::$STATUS_TOVERIFY){
				echo 'label-info">Tested';
				echo '</span></td>';
				echo '
			<td style="width:150px;" class="test-actions">
			<a href="javascript:fetch_test_edit_form('.$quote.$test->testId.$quote.');" title="Click to Edit results" class="btn blue mini">
				<i class="icon-edit"></i> Edit</a>
			<a href="javascript:view_test_result('.$quote.$test->testId.$quote.');" title="Click to view and verify results of this Specimen" class="btn blue mini">
				<i class="icon-edit"></i> Verify</a> 
			</td>
			<td style="width:130px;"><a href="javascript:specimen_info('.$quote.$specimen->specimenId.$quote.');" title="View specimen details" class="btn mini">
				<i class="icon-search"></i> View Details</a>
			</td>';
			}else
			if($test_status == Specimen::$STATUS_REPORTED){
				echo 'label-success">Reported';
				echo '</span></td>';
				echo '
			<td style="width:100px;" class="test-actions"><a href="javascript:start_test('.$quote.$specimen->specimenId.$quote.');" title="Click to begin testing this Specimen" class="btn red mini">
				<i class="icon-ok"></i>'.LangUtil::$generalTerms['START_TEST'].'</a>
			</td>
			<td style="width:100px;"><a href="javascript:fetch_specimen2('.$quote.$specimen->specimenId.$quote.');" title="View specimen details" class="btn blue mini">
				<i class="icon-group"></i>'.LangUtil::$generalTerms['ASSIGN_TO'].'</a>
			</td>';
			}else
			if($test_status == Specimen::$STATUS_RETURNED){
				echo 'label-info warning">Returned';
				echo '</span></td>';
				echo '
			<td style="width:100px;" class="test-actions"><a href="javascript:start_test('.$quote.$specimen->specimenId.$quote.');" title="Click to begin testing this Specimen" class="btn red mini">
				<i class="icon-ok"></i>'.LangUtil::$generalTerms['START_TEST'].'</a>
			</td>
			<td style="width:100px;"><a href="javascript:fetch_specimen2('.$quote.$specimen->specimenId.$quote.');" title="View specimen details" class="btn blue mini">
				<i class="icon-group"></i>'.LangUtil::$generalTerms['ASSIGN_TO'].'</a>
			</td>';
			}else
			if($test_status == Specimen::$STATUS_REJECTED){
				echo 'label-inverse">Rejected';
				echo '</span></td>';
				echo '
			<td style="width:100px;" class="test-actions"><a href="javascript:start_test('.$quote.$specimen->specimenId.$quote.');" title="Click to begin testing this Specimen" class="btn red mini">
				<i class="icon-ok"></i>'.LangUtil::$generalTerms['START_TEST'].'</a>
			</td>
			<td style="width:100px;"><a href="javascript:fetch_specimen2('.$quote.$specimen->specimenId.$quote.');" title="View specimen details" class="btn mini">
				<i class="icon-group"></i>'.LangUtil::$generalTerms['ASSIGN_TO'].'</a>
			</td>';
			}else
			if($test_status == Specimen::$STATUS_STARTED){
				echo 'label-warning">Started';
				echo '</span></td>';
				echo '
			<td id=actionA'.$test->testId.' style="width:130px;" class="test-actions"><a href="javascript:fetch_test_result_form('.$quote.$test->testId.$quote.');" title="Click to Enter Results for this Specimen" class="btn yellow mini">
				<i class="icon-pencil"></i> Enter Results</a>
			</td>
			<td style="width:130px;"><a href="javascript:specimen_info('.$quote.$specimen->specimenId.$quote.');" title="View specimen details" class="btn mini">
				<i class="icon-search"></i> View Details</a>
			</td>';
			}else
			if($test_status == Specimen::$STATUS_VERIFIED){
				echo 'label-success">Verified';
				echo '</span></td>';
				echo '
			<td style="width:130px;" class="test-actions"><a href="javascript:view_test_result('.$quote.$test->testId.$quote.','.Specimen::$STATUS_VERIFIED.');" title="Click to view results" class="btn green mini">
				<i class="icon-edit"></i> View Results</a>
			</td>
			<td style="width:130px;"><a href="javascript:specimen_info('.$quote.$specimen->specimenId.$quote.');" title="View specimen details" class="btn mini">
				<i class="icon-search"></i> View Details</a>
			</td>';
			}else if (!isset($test_status)){

                if($patient->getAgeNumber() >= 5 && $record['orderStage'] == "op" && $record['receiptNumber'] == "" && $record['receiptType'] == ""){
                    echo 'label">Not Paid';
                    echo '</span></td>';
                    echo '
                <td id=actionA'.$record["external_lab_no"].' style="width:130px;" class="test-actions"> 
                </td>
                <td id=actionB'.$record["external_lab_no"].' style="width:130px;">
                </td>';

                     } 
                else {       
    				echo 'label">Not Recieved';
    				echo '</span></td>';
    				echo '
    			<td id=actionA'.$record["external_lab_no"].' style="width:130px;" class="test-actions"><a href="javascript:load_specimen_reg('.$quote.$patient->surrogateId.$quote.',true, '.$record["external_lab_no"].');" title="Click to add lab request" class="btn mini red-stripe">
    				<i class="icon-sign-up"></i> Receive request</a>
    			</td>
    			<td id=actionB'.$record["external_lab_no"].' style="width:130px;">
    			</td>';
                    }
			}	
			else
			{
				echo '';
			}
			
			?>
		</tr>
		<?php if (isset($test->testId))
			{	?>
		<div class='modal container hide fade' id='result_form_pane_<?php echo $test->testId; ?>' role="dialog" aria-hidden="true" data-backdrop="static">
		<?php }else{?>
		<div class='modal container hide fade' id='result_form_pane_<?php echo $record["external_lab_no"]; ?>' role="dialog" aria-hidden="true" data-backdrop="static">			
		<?php } ?>
		</div>
		<?php
		$count++;
	}
	?>
	</tbody>
</table>
<?php echo $pagination_array["pagination"];?>

<?php
if($attrib_type == 3 && $count > 2)
{
	# Show "view more" link for revealing earlier patient records
	?>
	<a href='javascript:show_more_pnum();' id='show_more_pnum_link'><small>View older entries &raquo;</small></a>
	<br><br>
	<?php
}
?>
<script type="text/javascript">
$("#search_tests").keypress(function(e) {
	var code = e.keyCode || e.which;
	//get value of selected option for status';
	var s = $('select', $("#status")).val();
	var status = null;
	
	switch(s)
	{
	case "All":
	  status = "all"
	break;
	case "Not Received":
		status = "request_pending"
	break;
	case "Not Collected":
		status = "<?php echo Specimen::$STATUS_NOT_COLLECTED?>"
	break;
	case "Rejected":
		status = "<?php echo Specimen::$STATUS_REJECTED?>"
	break;
	case "Pending":
		status = "<?php echo Specimen::$STATUS_PENDING?>"
	break;
	case "Started":
		status = "<?php echo Specimen::$STATUS_STARTED?>"
	break;
	case "Tested":
		status = "<?php echo Specimen::$STATUS_TOVERIFY?>"
	break;
	case "Tested & Verified":
		status = "<?php echo Specimen::$STATUS_VERIFIED?>"
	break;
	default:
		status = "all"
	}
	var search_term= $("#search_tests").val();
    if(code == 13) {
    	fetch_tests(status,null,search_term);
    }
});



$("#refresh").click(function() {
	fetch_tests("all",null,null);
});

</script>
