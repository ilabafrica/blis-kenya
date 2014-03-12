<?php
#
# Returns test editing form
# Called via ajax from results_entry.php
#

require_once("../includes/db_lib.php");
require_once("../includes/page_elems.php");
require_once("../includes/ajax_lib.php");
require_once("../includes/user_lib.php");

$page_elems = new PageElems();

function get_result_form($test_type, $test, $num_tests, $patient)
{
	#Returns HTML form elements for given test type results
	global $form_id_list, $specimen_id, $page_elems, $measure_list;
	
	$curr_form_id = 'test_'.$test->testId;
	$form_id_list[] = $curr_form_id;
	?>
	<form name='<?php echo $curr_form_id; ?>' id='<?php echo $curr_form_id; ?>' action='' method=''>
	<input type='hidden' name='test_id' value='<?php echo $test->testId; ?>'></input>
	<input type='hidden' name='specimen_id' value='<?php echo $specimen_id; ?>'></input>
	<input type='hidden' name='parent_test_id' value='<?php echo $test->testId; ?>'></input>   
        <input type='hidden' name='edit_test_flag' value='1'></input>
	 <div id="ctbutton" style="display: none"> 
        <input type="button" value="Read results" class="btn" onclick="insertCelltacResults()"/>    
        </div>
        <div id="celltacerror" style="display: none">
            
        </div>
	<?php
    
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
    
    # Create form element for each measure
    $count = 0;
    foreach($measure_list as $measure)
	{
		$input_id = 'measure_'.$test_type->testTypeId."_".$measure->measureId;
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
		$clean_result = get_test_measure_result_value($test->testId, $measure->measureId);
		$res_dp = explode(",", $test->result);
		$res_dropdown = $res_dp[0];
		
		if($range_type == Measure::$RANGE_OPTIONS)
		{
		?>
			<select name='result[]' id='<?php echo $input_id; ?>' class='uniform_width' onchange="javascript:update_remarks(<?php echo $test_type->testTypeId; ?>, <?php echo count($measure_list); ?> ,<?php echo $patient->getAgeNumber(); ?>, '<?php echo $patient->sex;?>');">
			<option></option>
			<?php
			foreach($range_values as $option)
			{
				$option= str_replace('#', '/', $option);
				echo "<option value='$option' ".((strcmp($option, $clean_result) == 0)?"selected":"").">$option</option>";
			}
			?>
			</select>
		<?php
		}
		else if($range_type == Measure::$RANGE_NUMERIC)
		{
			# Continuous value range
			$age=$patient->getAgeNumber();
			?>
			<input class='uniform_width' type='text' name='result[]' id='<?php echo $input_id; ?>' value="<?php echo $clean_result ?>" onchange="javascript:update_remarks1();"></input>
			<span id='<?php echo $input_id; ?>_range'>
			&nbsp;(<?php 
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
		}else
		{	if(stripos($unit,":")!=false)
				{		
			$units=explode(":",$unit);
			echo $range_values[0]; ?><sup><?php echo $units[0] ?></sup>-<?php echo $range_values[1];?><sup><?php echo $units[0] ?></sup>)
		<?php 
	}
		else
		{
		echo $range_values[0]; ?>-<?php echo $range_values[1];?>)<?php } ?>
		</span>
			<?php
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
                        echo "<input name='result[]' id='$input_id' class='uniform_width results_entry' value='". $clean_result ."'></input>";
                  // echo "</div>";
                                	
		}
		if(stripos($measure->unit,":")!=false)
		{
		$units=explode(":",$measure->unit);
		echo $units[1];
		}else
		if(stripos($measure->unit,",")===false)
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
			<textarea name='comments' id='<?php echo $curr_form_id; ?>_comments'  class='uniform_width' 
				onfocus="javascript:update_remarks(<?php echo $test_type->testTypeId; ?>, 
				<?php echo count($measure_list); ?>, <?php echo $patient->getAgeNumber(); ?>, 
				'<?php echo $patient->sex;?>');" ><?php echo trim($test->getComments()) ?>
			</textarea>
			</span>
		</td>
	</tr>
	</table>
	</form>
	
	<?php
}

$form_id_list = array();

$test_id = get_request_variable('tid');
if($test_id == "" || $test_id == null)
{
        echo "<span class='error_string'>".LangUtil::$generalTerms['SPECIMEN_ID']."  ".$test_id." ".LangUtil::$generalTerms['MSG_NOTFOUND'].".</span>";
        return;
}

$test = Test::getById($test_id);
$specimen_id = $test->specimenId;
$specimen = Specimen::getById($specimen_id);
$patient = Patient::getById($specimen->patientId);
$test_type = TestType::getById($test->testTypeId);

$measure_list = $test_type->getMeasures(); # Fetch all measures for this test

$modal_link_id = "test_edit_link_$test_id";

?>	
<div class="modal-header">
	<a id="<?php echo $modal_link_id; ?>" href="javascript:close_modal('<?php echo $modal_link_id; ?>');" class="close"></a>
	<h4><i class="icon-pencil"></i> Results form - <?php echo $test_type->getName(); ?></h4>
</div>
<div class="modal-body">
	<div class="row-fluid">
	<div class="span6 sortable">
	<?php
		get_result_form($test_type, $test, 0, $patient);	  
	?>
	</div>
	<div class="span6 sortable">
	
	<div class="portlet box grey">
		<div class="portlet-title">
			<h4>Test Result</h4>
		</div>		
	<div class="portlet-body">
	<div class="scroller" data-height="300px" data-always-visible="1">
	<table class="table table-striped table-bordered table-advance">
		<thead><th>Test Name</th>
		<th>Results</th>
		<th>Remarks</th>
		<th>Entered by</th>
		</thead>
		<tbody>
		 <?php $page_elems->getTestInfoRowSmall($test, true);
		 ?>
		 </tbody>
		 </table>

	</div>
	</div>
	</div>
	</div>
	</div>
</div>
<div class="modal-footer">
	<input type='button' class="btn" value='<?php echo LangUtil::$generalTerms['CMD_SUBMIT']; ?>' onclick='javascript:submit_forms(<?php echo $test_id ?>);'></input>
	<a id="<?php echo $modal_link_id.'2'; ?>" href='javascript:close_modal("<?php echo $modal_link_id.'2'; ?>");' class='btn'><?php echo LangUtil::$generalTerms['CMD_CANCEL']; ?></a>
</div>
<input type='hidden' id='form_id_list' value='<?php echo implode(",", $form_id_list); ?>'></input>
<script type='text/javascript'>
	$(document).ready(function() {
	    
	    if ( <?php echo '"'.$test_type->getName().'"'; ?> == "Full Haemogram" ) {
            $.get( "http://192.168.1.5/blis/htdocs/results/emptyfile.php" );
             $('#ctbutton').show();
       }
	})
	
	function insertCelltacResults(){
	     
       if ( <?php echo '"'.$test_type->getName().'"'; ?> == "Full Haemogram" ) {
           //Fill results
           var jqxhr = $.getJSON( "http://192.168.1.5/blis/htdocs/ajax/results_celltac_get.php", function(data) {
            })
           .done(function(data) {
                console.log( "Success" );
                $RES = data;
                //Hardcoded the ID's for the full blood count inputs
                //to enable reading of dynamic results from celltac
                $('#measure_176_261').val($RES.WBC);
                $('#measure_176_260').val($RES.NE);
                $('#measure_176_259').val($RES.LY);
                $('#measure_176_258').val($RES.MO);
                $('#measure_176_257').val($RES.EO);
                $('#measure_176_256').val($RES.BA);
                $('#measure_176_262').val($RES.RBC);
                $('#measure_176_263').val($RES.HGB);
                $('#measure_176_264').val($RES.HCT);
                $('#measure_176_265').val($RES.MCV);
                $('#measure_176_266').val($RES.MCH);
                $('#measure_176_267').val($RES.MCHC);
                $('#measure_176_268').val($RES.RDW );                  
                $('#measure_176_269').val($RES.PLT);
                $('#measure_176_270').val($RES.PCT);
                $('#measure_176_272').val($RES.PDW);	
              $('#celltacerror').hide();
           })
           .fail(function() {
                console.log( "error" );
                 $('#celltacerror').show();
                $('#celltacerror').html("Print celltac results to read!");
           });
       }
	}
	
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
