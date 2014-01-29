<?php
#
# Returns list of patients matched with list of pending specimens
# Called via Ajax form result_entry.php
#
include("../includes/db_lib.php");
include("../includes/user_lib.php");
LangUtil::setPageId("results_entry");

$attrib_value = $_REQUEST['a'];
$attrib_type = $_REQUEST['t'];
$date_from = $_REQUEST['df'];
$date_to = $_REQUEST['dt'];
$status = $_REQUEST['s'];
$search_term = $_REQUEST['st'];
$dynamic = 1;
$search_settings = get_lab_config_settings_search();
$rcap = $search_settings['results_per_page'];
$lab_config = LabConfig::getById($_SESSION['lab_config_id']);
$uiinfo = "op=".$_REQUEST['t']."&qr=".$_REQUEST['a'];
putUILog('result_entry_tests', $uiinfo, basename($_SERVER['REQUEST_URI'], ".php"), 'X', 'X', 'X');
?>
<?php
if(!isset($_REQUEST['result_cap']))
    $result_cap = $rcap;
else
    $result_cap = $_REQUEST['result_cap'];

if(!isset($_REQUEST['result_counter']))
    $result_counter = 1;
else
    $result_counter = $_REQUEST['result_counter'];

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
            
    } elseif($attrib_type == 10)
    {
            # Get all specimens with pending status
            $query_string = 
                    "SELECT *, p.name AS patient_name, st.name as specimen_name, tt.name AS test_name, tc.name AS category_name, t.status_code_id AS status
						FROM test t
						LEFT JOIN specimen s ON t.specimen_id = s.specimen_id
						LEFT JOIN patient p ON s.patient_id = p.patient_id
						LEFT JOIN specimen_type st ON s.specimen_type_id = st.specimen_type_id
						LEFT JOIN test_type tt ON t.test_type_id = tt.test_type_id
						LEFT JOIN test_category tc ON tt.test_category_id = tc.test_category_id
            		WHERE";
            
            if ($status!="all")
            	$query_string.="
                    	 t.status_code_id=$status AND
            			 s.status_code_id NOT IN (".Specimen::$STATUS_NOT_COLLECTED.") AND
            			 t.external_lab_no!='' AND ";
            
            $query_string.="	
                    	tt.parent_test_type_id = 0 AND t.external_parent_lab_no = '0'
            			AND (p.surr_id = '$search_term' or p.name LIKE '%$search_term%' or tt.name LIKE '%$search_term%' OR tc.name LIKE '%$search_term%' or s.specimen_id = '$search_term')
                    	ORDER BY s.date_recvd DESC, s.ts DESC";
                    	/*LIMIT 0,10 ";
						/*WHERE s.ts BETWEEN '$date_from' AND '$date_to' ORDER BY s.ts DESC";*/
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
		#Update specimen to started status code
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
 
 	$pagination_array = setPagination($query_string, $limit, $page, $url, $num_records);
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
			<th style='width:100px;'><?php echo "Section";?></th>
			<?php 
			if($_SESSION['sid'] != 0)
			{
			?>
				<th style='width:75px;'><?php echo LangUtil::$generalTerms['SPECIMEN_ID']; ?></th>
			<?php
			}
			?>
				<th style='width:100px;'><?php echo "Time Collected";?></th>
			<?php
			if($_SESSION['pid'] != 0)
			{
			?>
				<th style='width:75px;'><?php echo "Patient ID"; ?></th>
			<?php
			}
			if(false) //Not displaying Lab no
			{
			?>
				<th style='width:100px;'><?php echo "Lab. No"; ?></th>
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
			<th style='width:100px;'></th>
			<?php if($attrib_type==10){
			?>
			<th style='width:100px;'></th>
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
			<td style='width:100px;'><?php echo $test_category->getCategoryName(); ?></td>
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
			}
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
			<td style='width:200px;'><?php echo $patient->getPatientName()." (".$patient->sex." ".$patient->getAgeNumber().") "; ?></td>
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
				
			
			echo '<td class="hidden-phone"><span id=span'.$test->testId.' class="label ';
			
			if($test_status == Specimen::$STATUS_PENDING){
				if($specimen_status == Specimen::$STATUS_NOT_COLLECTED){
					echo 'label">Not Collected';
					echo '</span></td>';
					echo '
						<td id=actionA'.$test->testId.' style="width:130px;">
							<a href="javascript:accept_specimen('.$quote.$specimen->specimenId.$quote.', '.$quote.$test->testId.$quote.');" class="btn mini green"><i class="icon-thumbs-up"></i> Accept</a>
                        </td>
						<td id=actionB'.$test->testId.' style="width:130px;">
						<a href="javascript:specimen_info('.$quote.$specimen->specimenId.$quote.');" title="View specimen details" class="btn mini">
							<i class="icon-search"></i> View Details
						</a>
						</td>';
				}else{
					
						echo 'label-important">Pending';
						echo '</span></td>';
						echo '<div id=action'.$test->testId.'>
					<td id=actionA'.$test->testId.' style="width:130px;"><a href="javascript:start_test('.$quote.$test->testId.$quote.');" title="Click to begin testing this Specimen" class="btn red mini">
						<i class="icon-ok"></i> '.LangUtil::$generalTerms['START_TEST'].'</a>
					</td>
					<td id=actionB'.$test->testId.' style="width:130px;">
						<a href="javascript:specimen_info('.$quote.$specimen->specimenId.$quote.');" title="View specimen details" class="btn mini">
							<i class="icon-search"></i> View Details
						</a>
					</td></div>';
					}
			}else
			if($test_status == Specimen::$STATUS_DONE){
				echo 'label-info">Completed';
				echo '</span></td>';
				echo '
			<td style="width:130px;"><a href="javascript:start_test('.$quote.$specimen->specimenId.$quote.');" title="Click to view results" class="btn blue-stripe mini">
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
			<td style="width:100px;"><a href="javascript:start_test('.$quote.$specimen->specimenId.$quote.');" title="Click to begin testing this Specimen" class="btn red mini">
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
			<td style="width:150px;">
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
			<td style="width:100px;"><a href="javascript:start_test('.$quote.$specimen->specimenId.$quote.');" title="Click to begin testing this Specimen" class="btn red mini">
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
			<td style="width:100px;"><a href="javascript:start_test('.$quote.$specimen->specimenId.$quote.');" title="Click to begin testing this Specimen" class="btn red mini">
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
			<td style="width:100px;"><a href="javascript:start_test('.$quote.$specimen->specimenId.$quote.');" title="Click to begin testing this Specimen" class="btn red mini">
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
			<td id=action'.$test->testId.' style="width:130px;"><a href="javascript:fetch_test_result_form('.$quote.$test->testId.$quote.');" title="Click to Enter Results for this Specimen" class="btn yellow mini">
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
			<td style="width:130px;"><a href="javascript:view_test_result('.$quote.$test->testId.$quote.','.Specimen::$STATUS_VERIFIED.');" title="Click to view results" class="btn green mini">
				<i class="icon-edit"></i> View Results</a>
			</td>
			<td style="width:130px;"><a href="javascript:specimen_info('.$quote.$specimen->specimenId.$quote.');" title="View specimen details" class="btn mini">
				<i class="icon-search"></i> View Details</a>
			</td>';
			}else 
			{
				echo '';
			}
			
			?>
		</tr>
		
		<div class='modal container hide fade' id='result_form_pane_<?php echo $test->testId; ?>' role="dialog" aria-hidden="true" data-backdrop="static">
	
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
	var s = 'all';
	var search_term= $("#search_tests").val();
    if(code == 13) {
    	fetch_tests(s,null,search_term);
    }
});



$("#refresh").click(function() {
	fetch_tests(0,null,null);
});
</script>