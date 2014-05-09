<?php
#
# Returns test editing form
# Called via ajax from results_entry.php
#

require_once("../includes/db_lib.php");
require_once("../includes/page_elems.php");
require_once("../includes/script_elems.php");
require_once("../includes/ajax_lib.php");
require_once("../includes/user_lib.php");

$page_elems = new PageElems();
$script_elems = new ScriptElems();
$script_elems->enableValidation();

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
			<select name='result[]' id='<?php echo $input_id; ?>' class='uniform_width validate[required]' onchange="javascript:update_remarks(<?php echo $test_type->testTypeId; ?>, <?php echo count($measure_list); ?> ,<?php echo $patient->getAgeNumber(); ?>, '<?php echo $patient->sex;?>');">
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
			<input class='uniform_width validate[required]' type='text' name='result[]' id='<?php echo $input_id; ?>' value="<?php echo $clean_result ?>" onchange="javascript:update_remarks1();"></input>
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
                        echo "<input name='result[]' id='$input_id' class='uniform_width results_entry abbreviation validate[required]' value='". $clean_result ."'></input>";
                  // echo "</div>";
                                	
		}
		else if($range_type == Measure::$RANGE_TEXTAREA)
		{
                        # Text area
                   echo "<textarea name='result[]' id='$input_id'  class='results_entry abbreviation validate[required]' data-required='1' style='height:140px;width:275px'>". $clean_result ."</textarea>";
                  
                                	
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
			<textarea name='comments' id='<?php echo $curr_form_id; ?>_comments'  class='uniform_width abbreviation validate[required]' 
				onfocus="javascript:update_remarks(<?php echo $test_type->testTypeId; ?>, 
				<?php echo count($measure_list); ?>, <?php echo $patient->getAgeNumber(); ?>, 
				'<?php echo $patient->sex;?>');" ><?php echo trim($test->getComments()) ?>
			</textarea>
			</span>
		</td>
	</tr>
	</table>
	</form>
	<!-- Show worksheet conditionally-->
	<?php if ($test_type->showCultureWorkSheet) {?>
	<br />
	<h5>CULTURE OBSERVATION AND WORKUP</h5>
	<table class="table table-bordered table-advanced table-condensed">
			<thead>
				<tr>
					<th>Date</th>
					<th>Initials</th>
					<th>Observations and work-up</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody id="tbbody_<?php echo $test->testId ?>">
			<?php 
				$obsv = Culture::getAllObservations($test->testId);
				if($obsv != null){
				foreach ($obsv as  $Observation) { ?>
					<tr>
					<td><?php echo time_elapsed_pretty($Observation->time_stamp); ?></td>
					<td><?php echo get_username_by_id($Observation->userId) ?></td>
					<td><?php echo $Observation->observation ?></td>
					<td></td>
					</tr>
					<?php } ?>
					<tr>
					<td><?php echo time_elapsed_pretty() ?></td>
					<td><?php echo $_SESSION['username'] ?></td>
					<td><textarea id="txtObsv_<?php echo $test->testId ?>" style="width:390px"></textarea></td>
					<td><a class="btn mini" href="javascript:void(0)" onclick="saveObservation(<?php echo $test->testId ?>, <?php echo "'".$_SESSION['username']."'" ?>)">Save</a> </td>
					</tr>
				<?php
				}
				else { ?>
					<tr>
					<td><?php echo time_elapsed_pretty() ?></td>
					<td><?php echo $_SESSION['username'] ?></td>
					<td><textarea id="txtObsv_<?php echo $test->testId ?>" style="width:390px"></textarea></td>
					<td><a class="btn mini" href="javascript:void(0)" onclick="saveObservation(<?php echo $test->testId ?>, <?php echo "'".$_SESSION['username']."'" ?>)">Save</a> </td>
					</tr>
					<?php
				}
				?>			
			</tbody>
	</table>

	<!-- Begin Drug Susceptibility Tests table -->
	<br />
	<div class="portlet box yellow ">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-reorder"></i> <h5>Susceptibility Test Results</h5>
							</div>
							
						</div>
						<div class="portlet-body form">
							<form role="form" id="drugs_susceptibility">
								<div class="form-body">
									<table class="table table-bordered table-advanced table-condensed">
										<thead>
											<tr>
												<th>Drug</th>
												<th>Zone (mm)</th>
												<th>Interpretation (S,I,R)</th>
											</tr>
										</thead>
										<tbody id="enteredResults">
										<?php 
											$test_type_id = get_test_type_id_from_test_id($test->testId);
											$drug = get_compatible_drugs($test_type_id);
											if($drug != null){
												foreach ($drug as  $drugs) { $drugs_value = DrugType::getById($drugs);
												$sensitivity = DrugSusceptibility::getDrugSusceptibility($test->testId,$drugs);
												?>
												<tr>
												<input type="hidden" name="test[]" id="test[]" value="<?php echo $test->testId; ?>">
												<input type="hidden" name="drug[]" id="drug[]" value="<?php echo $drugs; ?>">
												<td><?php echo $drugs_value->name; ?></td>
												<td><input type="text" name="zone[]" id="zone[]" class="span6 m-wrap" value="<?php if($sensitivity!=null){echo $sensitivity['zone'];} ?>"></td>
												<td><select class="span4 m-wrap" id="interpretation[]" name="interpretation[]">
												                <option value="S" <?php if($sensitivity['interpretation']=='S'){ ?>selected="selected"<?php } ?>>S</option>
							                                    
							                                    <option value="I" <?php if($sensitivity['interpretation']=='I'){ ?>selected="selected"<?php } ?>>I</option>
							                                    <option value="R" <?php if($sensitivity['interpretation']=='R'){ ?>selected="selected"<?php } ?>>R</option>
															</select></td>
												</tr>
												<?php } 
												}
												else{
												?>
												<tr>
												<td colspan="4"><?php echo "No Drugs linked to this test. Please consult the Lab In-Charge." ?></td>
												</tr>
												<?php } ?>			
										</tbody>
										
								</table>
								</div>
								<div class="form-actions right" id="submit_drug_susceptibility">
									<button type="submit" class="btn green" onclick="updateDrugSusceptibility(<?php echo $test->testId ?>)">Save Changes</button>
								</div>
							</form>
						</div>
					</div>
	<!-- End Drug Susceptibility Tests table -->

	<?php
	} //End Show worksheet conditionally
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
	<a id="<?php echo $modal_link_id; ?>" onclick="close_modal('<?php echo $modal_link_id; ?>');"  href="javascript:void(0);" class="close"></a>
	<h4><i class="icon-pencil"></i> Results form - <?php echo $test_type->getName(); ?></h4>
</div>
<div class="modal-body">
	<div class="row-fluid">
	<div class="span7 sortable">
	<?php
		get_result_form($test_type, $test, 0, $patient);	  
	?>
	</div>
	<div class="span5 sortable">
	
	<div class="portlet box grey">
		<div class="portlet-title">
			<h4>Summary</h4>
		</div>		
	<div class="portlet-body">
	<div class="scroller" data-height="300px" data-always-visible="1">
	
		 <?php $page_elems->getTestInfoRowSmall($test, true);
		 ?>
		

	</div>
	</div>
	</div>
	</div>
	</div>
</div>
<div class="modal-footer">
	<input type='button' class="btn yellow" id="sanitas" value='<?php echo "Send to Sanitas"//LangUtil::$generalTerms['CMD_SUBMIT']; ?>'></input>
	<a id="<?php echo $modal_link_id.'2'; ?>" class="btn red" href='javascript:close_modal("<?php echo $modal_link_id.'2'; ?>");' class='btn'><?php echo LangUtil::$generalTerms['CMD_CANCEL']; ?></a>
	<input type='button' class="btn green" id="blis" value='<?php echo "Save to BLIS"//LangUtil::$generalTerms['CMD_SUBMIT']; ?>'></input>
</div>
<input type='hidden' id='form_id_list' value='<?php echo implode(",", $form_id_list); ?>'></input>
<script type='text/javascript'>
	$(document).ready(function() {
alert(<?php echo $test->testId; ?>);
	    if ( <?php echo '"'.$test_type->getName().'"'; ?> == "Full Haemogram" ) {
            $.get( "http://192.168.1.5/blis/htdocs/results/emptyfile.php" );
             $('#ctbutton').show();
       }
	/*Begin Validation*/
       jQuery("#test_"+<?php echo $test->testId; ?>).validationEngine();
       /*End Validation*/
	});

	$(function(){
    $('#sanitas').click(function(e){
         e.preventDefault();

         //if invalid do nothing
         if(!$("#test_"+<?php echo $test->testId; ?>).validationEngine('validate')){
         return false;
          }
          submit_forms(<?php echo $test->testId ?>, "send");

      
      return false;
    })

    $('#blis').click(function(e){
         e.preventDefault();

         //if invalid do nothing
         if(!$("#test_"+<?php echo $test->testId; ?>).validationEngine('validate')){
         return false;
          }
          submit_forms(<?php echo $test->testId ?>, "save");

      
      return false;
    })
});
	
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

	/*Begin update drug susceptibility*/	
	function updateDrugSusceptibility(tid){
		event.preventDefault();
		/*Get the form variables*/
		/*var testId = tid;
		var drugs = $("input#drug[]").val();
		var zones = $("input#zone[]").val();
		var interpretations = $("input#interpretation[]").val();

		/*Data string*/
		/*var dataString = '&testId=' + tid + '&drugs=' + drugs + '&zones=' + zones + '&interpretations=' + interpretations;
		$.each(dataString, function(key, object) { alert($(this).val());
		});*/
		var dataString = $("#drugs_susceptibility").serialize();
		//alert(dataString);
		
		$.ajax({
			type: 'POST',
			url:  'ajax/update_susceptibility.php',
			data: dataString,
			success: function(){
				renderDrugSusceptibility(tid);
			}
		});
	}
	/*End save drug susceptibility*/
	function saveObservation(tid, username){
		txtarea = "txtObsv_"+tid;
		observation = $("#"+txtarea).val();

		$.ajax({
			type: 'POST',
			url:  'ajax/culture_worksheet.php',
			data: {obs: observation, testId: tid, action: "add"},
			success: function(){
				drawCultureWorksheet(tid , username);
			}
		});
	}

	/**
	 * Request a json string from the server containing contents of the culture_worksheet table for this test
	 * and then draws a table based on this data.
	 * @param  {int} tid      Test Id of the test
	 * @param  {string} username Current user
	 * @return {void}          No return
	 */
	function drawCultureWorksheet(tid, username){
		$.getJSON('ajax/culture_worksheet.php', { testId: tid, action: "draw"}, 
			function(data){
				var tableBody ="";
				$.each(data, function(index, elem){
					tableBody += "<tr>"
					+" <td>"+elem.time_stamp+" </td>"
					+" <td>"+elem.userId+"</td>"
					+" <td>"+elem.observation+"</td>"
					+" <td> </td>"
					+"</tr>";
				});
				tableBody += "<tr>"
					+"<td>Just now</td>"
					+"<td>"+username+"</td>"
					+"<td><textarea id='txtObsv_"+tid+"' style='width:390px'></textarea></td>"
					+"<td><a class='btn mini' href='javascript:void(0)' onclick='saveObservation("+tid+", &quot;"+username+"&quot;)'>Save</a></td>"
					+"</tr>";
				$("#tbbody_"+tid).html(tableBody);
			}
		);
	}

	/**
	 * Binds any input element with class .abbreviation to the keydown event and gets
	 * the last word typed[abbreviation] and sends it to the server.
	 * The return is the full words. 
	 * @param  {string}  Name of the class. 
	 * @return {Void}
	 * 
	 * @todo Move this code into a function 
	 */
	 /*Function to render drug susceptibility table after successfully saving the results*/
	 function renderDrugSusceptibility(tid){
		$.getJSON('ajax/drug_susceptibility.php', { testId: tid, action: "results"}, 
			function(data){
				var tableRow ="";
				var tableBody ="";
				$.each(data, function(index, elem){
					tableRow += "<tr>"
					+" <td>"+elem.drugName+" </td>"
					+" <td>"+elem.zone+"</td>"
					+" <td>"+elem.interpretation+"</td>"
					+"</tr>";
				});
				//tableBody +="<tbody>"+tableRow+"</tbody>";
				$( "#enteredResults" ).html(tableRow);
				$("#submit_drug_susceptibility").hide();
			}
		);
	}
	/*End drug susceptibility table rendering script*/
	$(".abbreviation").keydown(function(keydata){
			if (keydata.ctrlKey == true) 
			{
				currentdiv = this;
				typedWords = this.value;
				words = typedWords.split(" ");
				abbrv = words[words.length-1];

				if(abbrv == ""){
					return;
				}

				abbUrl = "ajax/getFullWord.php";
				$.getJSON(
					abbUrl, 
					{abb : abbrv}, 
					function(data)
					{
						if (data == null)
						{
							return;
						}
						replacedAbbString = typedWords.replace(abbrv, data.word);
						currentdiv.value = replacedAbbString; 
					});
			};
		});	


	</script>
