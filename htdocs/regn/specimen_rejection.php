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
<script type='text/javascript'>
function check_input()
{
	// Validate
	var reasons = $('#reasons').attr("value");
	if(reasons == "")
	{
		alert("<?php echo "Error: Missing reasons for rejection."; ?>");
		return;
	}
	// All OK
	$('#reject').submit();
}

</script>
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
?>
<p style="text-align: right;"><a rel='facebox' href='#NEW_SPECIMEN'>Page Help</a></p>
<span class='page_title'><?php echo "Specimen Rejection"; ?></span>
 | <?php echo LangUtil::$generalTerms['ACCESSION_NUM']; ?> <?php echo $session_num; ?>
 | <a href='javascript:history.go(-1);'><?php echo LangUtil::$generalTerms['CMD_CANCEL']; ?></a>
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
$main_query = mysql_query(stripslashes("SELECT DISTINCT s.specimen_id as sid, s.patient_id as patient_number, s.specimen_type_id, s.daily_num as daily_number, st.name FROM specimen s, specimen_type st WHERE s.specimen_type_id=st.specimen_type_id AND s.specimen_id=".$sid.";")) or die(mysql_error());
$main_rs = mysql_fetch_assoc($main_query);
$patient = get_patient_by_id($main_rs['patient_number']);
?>
<div id="result"></div>
<form id="reject" method="post" action="accept_reject_specimen.php">
<table width="95%" border="0" class="table table-striped table-bordered table-advance table-hover">
  <tr>
    <td class="highlight"><strong>Patient ID</strong></td>
    <td><?php echo $main_rs['patient_number']; ?></td>
  </tr>
  <tr>
    <td class="highlight"><strong>Patient Number</strong></td>
    <td><?php echo $main_rs['daily_number']; ?></td>
  </tr>
  <tr>
    <td class="highlight"><strong>Patient Name</strong></td>
    <td><?php echo $patient->getName()." (".$patient->sex." ".$patient->getAgeNumber().") "; ?></td>
  </tr>
  <tr>
    <td class="highlight"><strong>Specimen Type</strong></td>
    <td><?php echo $main_rs['name']; ?></td>
  </tr>
  <tr>
    <td class="highlight"><strong>Tests</strong></td>
    <?php $sql_query = mysql_query(stripslashes("SELECT t.test_type_id, tt.name as tests FROM test t, test_type tt WHERE t.test_type_id=tt.test_type_id AND t.specimen_id=".$main_rs['sid'])) or die(mysql_error());
	 ?>
    <td><?php while($sql_rs = mysql_fetch_assoc($sql_query)){
	echo $sql_rs['tests'].'<br>';
	}?></td>
  </tr>
  <tr>
    <td class="highlight"><strong>Reasons for Rejection</strong></td>
    <input name="specimen" id="specimen" type="hidden" value="<?php echo $sid; ?>" />
    <td>
                                          <textarea class="large m-wrap" rows="3" id="reasons" name="reasons"></textarea>
                                       </td>
  </tr>
</table>
<br>
&nbsp;&nbsp;
<input type="button" class="btn yellow" name="add_button" id="add_button" onclick="check_input();" value="<?php echo LangUtil::$generalTerms['CMD_SUBMIT']; ?>" size="20" />
&nbsp;&nbsp;&nbsp;&nbsp;
	<a href='find_patient.php?show_sc'>&laquo; <?php echo LangUtil::$generalTerms['CMD_BACK']; ?></a></form>
&nbsp;&nbsp;&nbsp;&nbsp;
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