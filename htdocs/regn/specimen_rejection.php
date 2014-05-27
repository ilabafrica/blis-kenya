<?php
#
# Main page for registering new specimen(s) in a single session/accession
#

include("redirect.php");
require_once("includes/db_lib.php");
require_once("includes/page_elems.php");
require_once("includes/script_elems.php");
$page_elems = new PageElems();
$script_elems = new ScriptElems();

LangUtil::setPageId("specimen_rejection");
putUILog('specimen_rejection', $uiinfo, basename($_SERVER['REQUEST_URI'], ".php"), 'X', 'X', 'X');
$script_elems->enableLatencyRecord();
$script_elems->enableJQueryForm();
$script_elems->enableAutocomplete();
$sid = $_REQUEST['sid'];
static $STATUS_REJECTED = 6;
//FUNCTION TO SAVE REJECTION REASONS
?>
<?php
//END FUNCTION
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
$uiinfo = "sid=".$_REQUEST['sid']."&dnum=".$_REQUEST['dnum'];
$modal_link_id = "spec_link_$test_id";
?>	
<div class="modal-header">
	<a id="<?php echo $modal_link_id; ?>" onclick="close_modal('<?php echo $modal_link_id; ?>');"  href="javascript:void(0);" class="close"></a>
	<h4><i class="icon-pencil"></i> Specimen Rejection Form</h4>
</div>
<div class="modal-body">
	<div class="row-fluid">
	<div class="span7 sortable">
		

<div class="portlet-body form">
<span class='page_title'><?php echo "Specimen Rejection"; ?></span>
 | <?php echo LangUtil::$generalTerms['ACCESSION_NUM']; ?> <?php echo $session_num; ?>
<br>
<br>
<?php
# Check if Patient ID is valid
$specimen = get_specimen_by_id($sid);
if($specimen == null)
{
	?>
	<div class='sidetip_nopos'>
	<?php
	echo LangUtil::$generalTerms['ERROR'].": ".LangUtil::$generalTerms['SPECIMEN_ID']." ".$sid." ".LangUtil::$generalTerms['MSG_NOTFOUND']; ?>.
	<br><br>
	<a href='find_patient.php'>&laquo; <?php echo LangUtil::$generalTerms['CMD_BACK']; ?></a>
	</div>
	<?php
	include("includes/footer.php");
	return;
}
?>
<?php
$specimen = get_specimen_by_id($sid);
$patient = get_patient_by_id($specimen->patientId);
?>

<!-- BEGIN FORM-->
<div id="result"></div>
<form id="reject" method="post" action="accept_reject_specimen.php">
<table border="0" class="table table-striped table-bordered table-advance table-hover">
  <tr>
    <td class="highlight">Patient ID</td>
    <td><?php echo $patient->patientId ?></td>
  </tr>
  <tr>
    <td class="highlight">Patient Number</td>
    <td><?php echo $specimen->dailyNum ?></td>
  </tr>
  <tr>
    <td class="highlight">Patient Name</td>
    <td><?php echo $patient->getName()." (".$patient->sex." ".$patient->getAgeNumber().") "; ?></td>
  </tr>
  <tr>
    <td class="highlight">Specimen Type</td>
    <td><?php echo $specimen->getTypeName(); ?></td>
  </tr>
  <tr>
    <td class="highlight">Tests</td>
    <td>
    <?php 
	echo $specimen->getTestNames();
	?></td>
  </tr>
  <tr>
    <td class="highlight">Reasons for Rejection</td>
    <input name="specimen" id="specimen" type="hidden" value="<?php echo $sid; ?>" />
    	<td>
            <textarea class="large m-wrap" rows="3" id="reasons" name="reasons"></textarea>
        </td>
    </tr>
    <tr>
        <td class="highlight">Person Talked To</td>
    	<td>
            <input type='text' name='referred_to_name' id='referred_to_name' class='span4 m-wrap' />
        </td>
  </tr>
</table>
</form>
<br>
<!-- END FORM-->                
										</div>
									</div>
								</div>

<div class="modal-footer">
<input type="button" class="btn yellow" name="add_button" id="add_button" onclick="check_input();" value="Reject" size="20" />
&nbsp;&nbsp;&nbsp;&nbsp;
	<a id="<?php echo $modal_link_id.'2'; ?>" class="btn" onclick="close_modal('<?php echo $modal_link_id; ?>');" href='javascript:void(0)' class='btn'><?php echo LangUtil::$generalTerms['CMD_CANCEL']; ?></a>
	
&nbsp;&nbsp;&nbsp;&nbsp;

	<span id='progress_spinner' style='display:none;'>
	<?php $page_elems->getProgressSpinner(LangUtil::$generalTerms['CMD_SUBMITTING']); ?>
</span>
</div>
</div>

<script type='text/javascript'>
function check_input()
{
	// Validate
	var reasons = $('#reasons').val();
	var referred_to = $('#referred_to_name').val();
	if(reasons == "")
	{
		alert("<?php echo "Error: Missing reasons for rejection."; ?>");
		return;
	}
	if(referred_to_name == ""){
		alert("<?php echo "Error: Missing Person talked to."; ?>");
		return;
	}
	// All OK
	$('#reject').submit();
}

</script>