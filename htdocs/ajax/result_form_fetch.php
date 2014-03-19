<?php
#
# Returns specimen result entry form
# Called via ajax from results_entry.php
#

include("../includes/db_lib.php");
include("../includes/page_elems.php");
include("../includes/ajax_lib.php");
include("../includes/user_lib.php");
LangUtil::setPageId("results_entry");
$page_elems = new PageElems();

function get_result_form($test_type, $test, $num_tests, $patient)
{
	#Returns HTML form elements for given test type results
	global $form_id_list, $specimen_id, $page_elems, $edit_flag;

	$curr_form_id = 'test_'.$test->testId;
	$form_id_list[] = $curr_form_id;
	?>
	<form name='<?php echo $curr_form_id; ?>' id='<?php echo $curr_form_id; ?>' action='' method=''>
	<input type='hidden' name='test_id' value='<?php echo $test->testId; ?>'></input>
	<input type='hidden' name='specimen_id' value='<?php echo $specimen_id; ?>'></input>
	<input type='hidden' name='parent_test_id' value='<?php echo $test_type->parent_test_type_id; ?>'></input>   
	<label>Edit FLAG: <?php echo $edit_flag; ?></label>
	<?php
	# Fetch all measures for this test
	$measure_list = $test_type->getMeasures();
    
	$submeasure_list = array();
        $comb_measure_list = array();

        foreach($measure_list as $measure)
        {
            $submeasure_list = $measure->getSubmeasuresAsObj();
            
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
        
        if($edit_flag == 1)
            $results = explode(" ", trim(str_replace("&nbsp;", " ", strip_tags($test->decodeResultWithoutMeasures()))));
        else
            $results = array();

        # Create form element for each measure
        $count = 0;
        foreach($measure_list as $measure)
	{
		$input_id = 'measure_'.$test_type->testTypeId."_".$count;
		$decName = "";
		if($measure->checkIfSubmeasure() == 1)
		{
			$decName = $measure->truncateSubmeasureTag();
			$decName.":";
		}
		else
		{
			$decName = $measure->getName().":";
		}
		?>
		<label for='<?php echo $input_id; ?>'><?php echo $decName; ?></label>
		<?php
		$range = $measure->range;
		$range_type = $measure->getRangeType();
		$range_values = $measure->getRangeValues($patient);
		$arr_out = array();
		
		if($range_type == Measure::$RANGE_OPTIONS)
		{
		?>
			<select name='result[]' id='<?php echo $input_id; ?>' class='uniform_width' onchange="javascript:update_remarks(<?php echo $test_type->testTypeId; ?>, <?php echo count($measure_list); ?> ,<?php echo $patient->getAgeNumber(); ?>, '<?php echo $patient->sex;?>');">
			<option></option>
			<?php
			foreach($range_values as $option)
			{
				$option= str_replace('#', '/', $option);
				$lev = levenshtein($option, $results[$count]);
				$arr_out[] = array($option, $results[$count], $lev);
				if($edit_flag ==1 && $lev <= 1)
                                    $selected = " selected";
                                else
                                    $selected = "";
				?>
				<option <?php echo "value='$option' $selected"; ?>><?php echo str_replace('#', '/', $option); ?></option>
				<?php
			}
			?>
			</select>
			<pre>
			<?php var_dump($arr_out); ?>
			</pre>
		<?php
		}
		else if($range_type == Measure::$RANGE_NUMERIC)
		{
			# Continuous value range
			$age=$patient->getAgeNumber();
                ?>
			<input class='uniform_width' type='text' name='result[]' id='<?php echo $input_id; ?>' onchange="javascript:update_remarks1();"
                            <?php echo ($edit_flag==1?" value='".$results[$count]."'":"")?>></input>
			<span id='<?php echo $input_id; ?>_range'>
			&nbsp;(
                <?php 
			$unit=$measure->unit;
			if(stripos($unit,",")!=false)
                        {
                            $units=explode(",",$unit);
                            $lower_parts=explode(".",$range_values[0]);
                            $upper_parts=explode(".",$range_values[1]);
                            if($lower_parts[0]!=0)
                            {
                                echo $lower_parts[0];
                                echo $units[0];
                            }
                            if($lower_parts[1]!=0)
                            {
                                echo $lower_parts[1];
                                echo $units[1];
                            }
                            ?>-<?php
                            if($upper_parts[0]!=0)
                            {
                                echo $upper_parts[0];
                                echo $units[0];
                            }
                            if($upper_parts[1]!=0)
                            {
                                echo $upper_parts[1];
                                echo $units[1];
                            }
                            ?>)<?php
                        }
                        else
                        {
                            if(stripos($unit,":")!=false)
                            {		
                                $units=explode(":",$unit);
                                echo $range_values[0]."<sup>".$units[0]."</sup>-".$range_values[1]."<sup>".$units[0]."</sup>)";
                            }
                            else
                            {
                                echo $range_values[0]."-".$range_values[1].")";
                            }
                            echo "</span>";
			}
		}
		else if($range_type == Measure::$RANGE_AUTOCOMPLETE)
		{
			# Autocomplete values
			# Use jquery.token-input plugin
			$url_string = "ajax/measure_autocomplete.php?id=".$measure->measureId;
			$hint_text = "Type to enter results";
			echo "<div>";
			$page_elems->getTokenList($count, $input_id, "result[]", $url_string, $hint_text,"");
			echo "</div>";
			
		}
                else if($range_type == Measure::$RANGE_FREETEXT)
		{
                        # Text box
                    //echo "<div>";
                        echo "<input name='result[]' id='$input_id' class='uniform_width results_entry'";
                        echo ($edit_flag==1?" value='".$results[$count]."'":"")."></input>";
                  // echo "</div>";
                                	
		}
		if(stripos($measure->unit,":")!=false)
		{
                    $units=explode(":",$measure->unit);
                    echo $units[1];
		}
		else if(stripos($measure->unit,",")===false)
                    echo $measure->unit;

                if($num_tests > 1 && $count == 0)
		{
			# Checkbox to skip results for this test type
			?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<small>
			<input type='checkbox' id='<?php echo $curr_form_id; ?>_skip' title='Tick this box if results are not yet available and are to be entered later' onclick="javascript:toggle_form('<?php echo $curr_form_id; ?>', this);">
			<?php echo LangUtil::$generalTerms['CMD_SKIP']; ?>
			</input>
			</small>
			<?php
		}
		echo "<br>";
		$count++;
	}
	?>
	<table>
	<tr>
		<td>
			<label for='<?php echo $curr_form_id; ?>_comments'>
				Result Interpretation
			</label>
		
			<span id='<?php echo $curr_form_id; ?>_comments_span'>
			<?php $result_comment = trim(str_replace("&nbsp;", " ", strip_tags($test->getComments()))); ?>
			<?php 
                            echo "<textarea name='comments' id='$curr_form_id"."_comments' class='uniform_width' ";
                            echo "onfocus=\"javascript:update_remarks(".$test_type->testTypeId.", ".count($measure_list).", ".$patient->getAgeNumber().", '".$patient->sex."');\"";
                            echo ">".(($edit_flag ==1)?$result_comment:"")."</textarea>";
                        ?>
			</span>
		</td>
	</tr>
	</table>
	</form>
	
	<?php
}

$form_id_list = array();
$test_id = $_REQUEST['tid'];
if($test_id == "" || $test_id == null)
{
        echo "<span class='error_string'>".LangUtil::$generalTerms['SPECIMEN_ID']."  $test_id ".LangUtil::$generalTerms['MSG_NOTFOUND'].".</span>";
        return;
}
$test = Test::getById($test_id);
$test_type_id = $test->testTypeId;
$specimen_id = $test->specimenId;
$specimen = Specimen::getById($specimen_id);
$patient = Patient::getById($specimen->patientId);
$test_type = TestType::getById($test_type_id);

$edit_flag = $_REQUEST['ef'];

# Print HTML results form
?>	
<div class="modal-header">
	<a href="javascript:remove('<?php echo $test_id; ?>');" class="close"></a>
	<h4><i class="icon-pencil"></i> Results form - <?php echo $test_type->getName(); ?></h4>
</div>
<div class="modal-body">
	<div class="row-fluid">
	<div class="span6 sortable">
	<?php
	get_result_form($test_type, $test, 0, $patient);	  
	$child_tests = get_child_tests($test_type_id);
	if (count($child_tests)>0){
		foreach($child_tests as $child_test)
		{
			$test_type = TestType::getById($child_test['test_type_id']);
			$chid_test_entry = get_test_entry($specimen_id, $child_test['test_type_id']);
			
			get_result_form($test_type, $chid_test_entry, 0, $patient);
			$child_tests = get_child_tests($child_test['test_type_id']);
			if (count($child_tests)>0){
				foreach($child_tests as $child_test)
				{
					$test_type = TestType::getById($child_test['test_type_id']);
					$chid_test_entry = get_test_entry($specimen_id, $child_test['test_type_id']);
					get_result_form($test_type, $chid_test_entry, 0, $patient);
				}
			}
		}
	}
	
	?>
	</div>
	<div class="span6 sortable">
	
	<div class="portlet box grey">
		<div class="portlet-title">
			<h4>Patient Test history</h4>
		</div>		
	<div class="portlet-body">
	<div class="scroller" data-height="300px" data-always-visible="1">
	<?php 		
	$page_elems->getPatientHistory($patient->patientId, true);
	?>
	</div>
	</div>
	</div>
	</div>
	</div>
</div>
<div class="modal-footer">
	<input type='button' class="btn" value='<?php echo LangUtil::$generalTerms['CMD_SUBMIT']; ?>' onclick='javascript:submit_forms(<?php echo $test_id ?>);'></input>
	<a href='javascript:hide_test_result_form(<?php echo $test_id ?>);' class='btn'><?php echo LangUtil::$generalTerms['CMD_CANCEL']; ?></a>
</div>
<input type='hidden' id='form_id_list' value='<?php echo implode(",", $form_id_list); ?>'></input>
<script type='text/javascript'>
	$(document).ready(function() {
	    
	    
	})
	
	function update_remarks1()
	{
		var result_elems = $("input[name='result[]']").attr("value");
				if(isNaN(result_elems))
		{	
			alert("Value expected for result is numeric.");
			return;
		}
		update_remarks(<?php echo $test_type->testTypeId; ?>, <?php echo count($measure_list); ?>, <?php echo $patient->getAgeNumber(); ?>, '<?php echo $patient->sex;?>');
	}
	</script>
