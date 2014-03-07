<?php
#
# Main page for registering new specimen(s) in a single session/accession
#
include("redirect.php");
require_once("includes/db_lib.php");
require_once("includes/page_elems.php");
require_once("includes/script_elems.php");
require_once("includes/user_lib.php");
$page_elems = new PageElems();
$script_elems = new ScriptElems();

LangUtil::setPageId("new_specimen");
$pid = $_REQUEST['pid'];
$ex = $_REQUEST['ex'];

if(isset($_REQUEST['dnum']))
	$dnum = (string)$_REQUEST['dnum'];
else
	$dnum = get_daily_number();

if(isset($_REQUEST['session_num']))
	$session_num = $_REQUEST['session_num'];
else
	$session_num = get_session_number();
	
/* check discrepancy between dnum and session number and correct 
if ( substr($session_num,strpos($session_num, "-")+1 ) )
	$session_num = substr($session_num,0,strpos($session_num, "-"))."-".$dnum;
*/
	
$doc_array= getDoctorList();
$php_array= addslashes(implode("%", $doc_array));

?>

<p style="text-align: right;"><a rel='facebox' href='#NEW_SPECIMEN'>Page Help</a></p>
<span class='page_title'><?php echo LangUtil::getTitle(); ?></span>
 | Lab No:<?php //echo LangUtil::$generalTerms['ACCESSION_NUM']; ?> <?php echo $session_num; ?>
 | <a href="find_patient.php?div=reception">Back</a>
<br>
<br>
<?php
$patient==null;
$tests_requested = null;
# Check if Patient ID is valid
$patient = get_patient_by_id($pid);
#nullify patient if same id is founf in internal system
if ($ex=="true")$patient=null;
//search from external lab request table


if($patient==null){
	$patient = Patient::getBySurrId($pid);
}
//echo "pid second =>".$patient->surrogateId;
if ($ex == "true"){
if ($patient!=null){
	$tests_requested = API::getExternalLabRequest($patient->surrogateId);
} else 
	$tests_requested = API::getExternalLabRequest($pid);

}

if ($patient==null && $tests_requested!=null){
	$patient = get_patient_by_external_id($pid);
	$is_external_patient=true;
	$patient = Patient::getBySurrId($patient->surrogateId);	
}
$pid = $patient->patientId;

if($patient == null)
{
	?>
	<div class='sidetip_nopos'>
	<?php
	echo LangUtil::$generalTerms['ERROR'].": ".LangUtil::$generalTerms['PATIENT_ID']." ".$pid." ".LangUtil::$generalTerms['MSG_NOTFOUND']; ?>.
	<br><br>
	<a href='find_patient.php'>&laquo; <?php echo LangUtil::$generalTerms['CMD_BACK']; ?></a>
	</div>
	<?php
	include("includes/footer.php");
	return;
}
?>
<div class="row-fluid">
<div class="span6">
<?php 
if($ex == "true") {
?>
	<table class ="table table-striped table-bordered table-advance" style="width:400px">
		<thead>
			<th>
			Tests Requested
			</th>
			<th>
			Requesting Clinician
			</th>
			<th>
			Date Requested
			</th>
		</thead>
		<tbody>
			<?php
			
			$clinician = array();
			$clinician['clinician']=$tests_requested['requestingClinician'];
            
			//$testspec is an array containing specimenID and testId as the key value pairs respectively
            //$testSpec = array();
            $testNmSpecID = array();
			
			foreach ($tests_requested as $test)
			{	//Filling up the $testspec array for some manipulation down below
				$test_name = $test['investigation'];
			    $test_id=TestType::getIdByName($test_name);
                $specimen_id=TestType::getSpecimenIdByTestName($test_id);
                //$testSpec[$specimen_id]  = $test_id;
				$testNmSpecID[$test_name] =  $specimen_id;   			      
			?>
			<tr>
				<td><?php echo $test['investigation'];?></td>
				<td><?php echo $test['requestingClinician'];?></td>
				<td><?php echo $test['requestDate'];?></td>
			</tr>
			<?php }?>
		</tbody>
	</table>
    
    <table cellpadding='5px'>
    <tbody>
        <tr valign='top'>
            <td>
                <span id='specimenboxes'>
                <?php 
                $testcount = 1;
                //$unqSpecimen = array_unique($testSpec);
                //$uniqspecid = array_keys($unqSpecimen);
                //$SpecimenCount = count($unqSpecimen);

                foreach ($tests_requested as $test)
				{
                echo $page_elems->getNewSpecimenForm($testcount, $pid, $dnum, $session_num, $tests_requested);
                    $testcount++;
                    //$SpecimenCount--;
                }
                ?>
                </span>
                <br>
                <span id='sbox_progress_spinner' style='display:none;'>
                    <?php $page_elems->getProgressSpinner(LangUtil::$generalTerms['CMD_FETCHING']); ?>
                </span>
            </td>
        </tr>
    </tbody>
</table>    

<?php 
}
else {
    ?>
<table cellpadding='5px'>
    <tbody>
        <tr valign='top'>
            <td>
                <span id='specimenboxes'>
                <?php echo $page_elems->getNewSpecimenForm(1, $pid, $dnum, $session_num); ?>
                </span>
                <br>
                <a href='javascript:add_specimenbox();'><?php echo LangUtil::$pageTerms['ADD_ANOTHER_SPECIMEN']; ?> &raquo;</a>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <span id='sbox_progress_spinner' style='display:none;'>
                    <?php $page_elems->getProgressSpinner(LangUtil::$generalTerms['CMD_FETCHING']); ?>
                </span>
            </td>
        </tr>
    </tbody>
</table>    
<?php
}
?>

<br>
&nbsp;&nbsp;
<input type="button" name="add_sched" class="btn green" id="add_button" onclick="add_specimens();" value="<?php echo LangUtil::$generalTerms['CMD_SUBMIT']; ?>" size="20" />
&nbsp;&nbsp;&nbsp;&nbsp;
<small><a href='javascript:askandback();' class="btn red icn-only"><?php echo LangUtil::$generalTerms['CMD_CANCEL']; ?></a></small>
<hr />
</div>
<div class="span4">
<u><b>Patient details</b></u>
<?php echo $page_elems->getPatientInfo($pid, 400, $is_external_patient); ?>
</div>
</div>

<div>
<br>
<u><b><?php echo LangUtil::$generalTerms['CMD_THISTORY']; ?></b></u>
<?php $page_elems->getPatientHistory($pid); ?>
<div/>

<div id='NEW_SPECIMEN' class='right_pane' style='display:none;margin-left:10px;'>
	<ul>
		<?php
		if(LangUtil::$pageTerms['TIPS_REGISTRATION_SPECIMEN']!="-") {
			echo "<li>";
			echo LangUtil::$pageTerms['TIPS_REGISTRATION_SPECIMEN'];
			echo "</li>";
		}	
		if(LangUtil::$pageTerms['TIPS_REGISTRATION_SPECIMEN_1']!="-") {
			echo "<li>";
			echo LangUtil::$pageTerms['TIPS_REGISTRATION_SPECIMEN_1'];
			echo "</li>";
		}	
		?>
	</ul>
</div>
<span id='progress_spinner' style='display:none;'>
	<?php $page_elems->getProgressSpinner(LangUtil::$generalTerms['CMD_SUBMITTING']); ?>
</span>
<br>
<?php
$script_elems->enableDatePicker();
$script_elems->enableLatencyRecord();
$script_elems->enableJQueryForm();
$script_elems->enableAutocomplete();
?>
<script>
// <!-- <![CDATA[
<?php
      //count($unqSpecimen);
 	 if($tests_requested != null) {
 	   	$SpecimenCount = count($tests_requested);
        echo "specimen_count = ".$SpecimenCount.";";
  	 }
     else {
        echo "specimen_count = 1;";
     }
 ?>
patient_exists = false;
$(document).ready(function(){
    //var data = "Core Selectors Attributes Traversing Manipulation CSS Events Effects Ajax Utilities".split(" ");
    var data_string="<?php echo $php_array;?>";
    var data=data_string.split("%"); 
    $("#doc_row_1_input").autocomplete(data);
    
    $('#specimen_id').focus();
    $('a[rel*=facebox]').facebox()
    <?php
    if(isset($_REQUEST['pid']))
    {
        echo "; get_patient_info('".$pid."');";
        echo " patient_exists = true;";
    }
   if(is_array($tests_requested) && $tests_requested != null) {
		#Get Tests
		//$length = count($tests_requested);
		//$test_check="";
		//$testNames="";
		//for($i=0; $i<$length; $i++){
		//	#prevent repeated tests
		//	if($test_check != $tests_requested[$i]['investigation'])
		//	$testNames.=$tests_requested[$i]['investigation'];
		//	$test_check = $tests_requested[$i]['investigation'];
		//	if ($i!=$length-1)$testNames.=',';
		//}
		
      $formcount = 1;
      //$specarraycount = 0;
      foreach ($tests_requested as $test)
				{
          $testBox = specimenform_.$formcount._testbox;
          $specType = specimenform_.$formcount._stype;
		  $testname = $test['investigation'];
          ?>
        
            $("#<?php echo $testBox; ?>").load(
                "ajax/test_type_options.php", 
                {
                    stype: <?php echo $testNmSpecID[$testname] ?>, ext: "<?php echo $testname; ?>"
                });
           $("#<?php echo $specType; ?>").val(<?php echo  $testNmSpecID[$testname] ?>);     
        <?php 
        $formcount++;
        //$SpecimenCount--;
        //$specarraycount++;
        }
    }
    ?>
    App.init(); // init the rest of plugins and elements
});

function get_patient_info()
{
    var patient_id = <?php echo $_REQUEST['pid']; ?>;//$("#card_num").val();
    if(patient_id == "")
    {
        $('#specimen_patient').html("");
        return;
    }
    $('#specimen_patient').load(
        "ajax/patient_info.php", 
        {
            pid: patient_id
        }, 
        function(){
            var return_html = $('#specimen_patient').html();
            if(return_html.indexOf("<?php echo LangUtil::$generalTerms['PATIENT']." ".LangUtil::$generalTerms['MSG_NOTFOUND']; ?>") == -1)
                patient_exists = true;
            else
                patient_exists = false;
        }
    );
}

function check_specimen_id(specimen_div_id, err_div_id)
{
    var specimen_id = $('#'+specimen_div_id).val();
    if(specimen_id == "")
    {   
        $('#'+err_div_id).html("");
        return;
    }
    if(isNaN(specimen_id))
    {
        var msg_string = "<small><font color='red'>"+"Invalid ID. Only numbers allowed.</font></small>";
        $('#'+err_div_id).html(msg_string);
        return;
    }
    $('#'+err_div_id).load(
        "ajax/specimen_check_id.php", 
        { 
            sid: specimen_id
        }
    );
}

function contains(a, obj){
  for(var i = 0; i < a.length; i++) {
    if(a[i] === obj){
      return true;
    }
  }
  return false;
}

function set_compatible_tests()
{
    var specimen_type_id = $("#s_type").val();
    if(specimen_type_id == "")
    {   
        $('#test_type_box').html("Select specimen type to view compatible tests");
        return;
    }
    $('#test_type_box').load(
        "ajax/test_type_options.php", 
        {
            stype: specimen_type_id
        }
    );
}

function add_specimens()
{
    for(var j = 1; j <= specimen_count; j++)
    {
        // Validate each form
        var form_id = 'specimenform_'+j;
        var form_elem = $('#'+form_id);
        if( form_elem == undefined || 
            form_elem == null )
            continue;
        if( $("#"+form_id+" [name='stype']").val() == null || 
            $("#"+form_id+" [name='stype']").val() == undefined )
            continue;
        var stype = $("#"+form_id+" [name='stype']").val();
        if(stype.trim() == "")
        {
            alert("<?php echo LangUtil::$generalTerms['ERROR'].": ".LangUtil::$pageTerms['MSG_STYPE_MISSING']; ?>");
            return;
        }
        var ttype_list = $("#"+form_id+" [name='t_type_list[]']");
        
        var ttype_notselected = true;
        if (ttype_list.val()!= null) ttype_notselected = false;
        
        if(ttype_notselected == true)
        {
            alert("<?php echo LangUtil::$generalTerms['ERROR'].": ".LangUtil::$pageTerms['MSG_NOTESTS_SELECTED']; ?>");
            return;
        }
        var sid = $("#"+form_id+" [name='specimen_id']").val();
        if(sid.trim() == "")
        {
            alert("<?php echo LangUtil::$generalTerms['ERROR'].": ".LangUtil::$pageTerms['MSG_SID_MISSING']; ?>");
            return;
        }
        var specimen_valid = $("#specimen_msg_"+j).html();
        if(specimen_valid != "")
        {
            alert("<?php echo LangUtil::$generalTerms['ERROR'].": ".LangUtil::$pageTerms['MSG_SID_INVALID']; ?>");
            return;
        }
        //Validate custom fields
        var custfields = $('#'+form_id+' input[name^="custom"]');
        var empty_fields = false;
        
        if($('#'+form_id+' #ref_out_1').is(':checked')) {
        
        $.each(custfields, function(key, content){
           if (content.value == null || content.value == ""){
               empty_fields = true;
               alert("<?php echo "Error: Some inputs are empty" ?>");
               return false;
           }
        });
        
        }
        
        if(empty_fields) return;
        
        
            //All okay
    }
    $('#progress_spinner').show();
    
    for(var j = 1; j <= specimen_count; j++)
    {
        // Submit each form
        var form_id = 'specimenform_'+j;
        
        $('#'+form_id).ajaxSubmit({async: false});
        //$('#'+form_id).submit();
    }
    var dnum_val = $('#dnum').val();
    <?php
    $today = date("Ymd");
    switch($_SESSION['dnum_reset'])
    {
        case LabConfig::$RESET_DAILY:
            $today = date("Ymd");
            break;
        case LabConfig::$RESET_WEEKLY:
            $today = date("Y_W");
            break;
        case LabConfig::$RESET_MONTHLY:
            $today = date("Ym");
            break;
        case LabConfig::$RESET_YEARLY:
            $today = date("Y");
            break;
    }
    ?>
    /*
    var dnum_string= "<?php echo $today; ?>";
    var url_string = "ajax/daily_num_update.php?dnum="+dnum_string+"&dval="+dnum_val;
    $.ajax({ url: url_string, async: false, success: function() {}}); 
    
    var url_string = "ajax/session_num_update.php?snum=<?php echo date("Ymd"); ?>";
    $.ajax({ url: url_string, async: false, success: function() {
        $('#progress_spinner').hide();
        window.location="specimen_added.php?snum=<?php echo $session_num; ?>";
    }});
    */
   window.location="specimen_added.php?snum=<?php echo $session_num; ?>";
}

function add_specimenbox()
{
    specimen_count++;
    var doc = $('#doc_row_1_input').val();
    var title= $('#doc_row_1_title').val();
    var dnumInit = "<?php echo $dnum; ?>";
    dnum = dnumInit.toString();
    var url_string = "ajax/specimenbox_add.php?num="+specimen_count+"&pid=<?php echo $pid; ?>"+"&dnum="+dnum+"&doc="+doc+"&title="+title+"&session_num=<?php echo $session_num; ?>";
    $('#sbox_progress_spinner').show();
    $.ajax({ 
        url: url_string, 
        success: function(msg){
            $('#specimenboxes').append(msg);
            $('#sbox_progress_spinner').hide();
            App.init();
        }
    });
}

function get_testbox(testbox_id, stype_id, external_tests)
{
    var stype_val = $('#'+stype_id).val();
    if(stype_val == "")
    {
        $('#'+testbox_id).html("-<?php echo LangUtil::$pageTerms['MSG_SELECT_STYPE']; ?>-");
        return;
    }
    
    $('#'+testbox_id).html("<?php $page_elems->getProgressSpinner(LangUtil::$generalTerms['CMD_FETCHING']); ?>");
    $('#'+testbox_id).load(
        "ajax/test_type_options.php", 
        {
            stype: stype_val, ext: external_tests
        }
    );
}

function show_dialog_box(div_id)
{
    var dialog_id = div_id+"_dialog";
    $('#'+dialog_id).show();
}

function hide_dialog_box(div_id)
{
    var dialog_id = div_id+"_dialog";
    $('#'+dialog_id).hide();
}

function remove_specimenbox(box_id)
{
    hide_dialog_box(box_id);
    specimen_count--;
    $('#'+box_id).remove();
}

function askandback()
{
    var todo = confirm("<?php echo LangUtil::$pageTerms['TIPS_SURETOABORT']; ?>");
    if(todo == true)
        history.go(-1);
}

function checkandtoggle(select_elem, div_id)
{
    var input_id = div_id+"_input";
    var report_to_val = select_elem.value;
    if(report_to_val == 1)
    {
        $('#'+div_id).hide();
    }
    else if(report_to_val == 2)
    {
        $('#'+div_id).show();
    }
    
}

function checkandtoggle_ref(ref_check_id, ref_row_id)
{    
        $('.'+ref_row_id).toggle();   
}
// And here is the end.

// ]]> -->
</script>
