<?php
#
# Main page for showing specimen info
#
include("redirect.php");
require_once("includes/db_lib.php");
require_once("includes/page_elems.php");
require_once("includes/script_elems.php");
include("barcode/barcode_lib.php");
$page_elems = new PageElems();
$script_elems = new ScriptElems();

LangUtil::setPageId("specimen_acceptance");
putUILog('specimen_acceptance', $uiinfo, basename($_SERVER['REQUEST_URI'], ".php"), 'X', 'X', 'X');
$sid = $_REQUEST['sid'];
$pid = $_REQUEST['pid'];
$uiinfo = "sid=".$_REQUEST['sid']."&pid=".$_REQUEST['pid'];
?>
<div class="modal-header">
    <a href="javascript:$('#specimen_acceptance_body').modal('hide');" class="close"></a>
    <h4><i class="icon-pencil"></i> <span class='page_title'><?php echo LangUtil::getTitle(); ?></span></h4>
</div>

<div class="modal-body">

                <div class="row-fluid">
                <div class="span12 sortable">
        <div class="portlet box green">
        		<div class="portlet-title">
        			<h4><i class="icon-reorder"></i>Specimen Accepted</h4>
        
        			<div class="tools">
        				<a href="javascript:;" class="collapse"></a>
        				<a href="javascript:;" class="reload"></a>
        				<a href="javascript:;" class="remove"></a>
        			</div>
        		</div>
        		<div class="portlet-body form">
                     <a href='find_patient.php?show_sc'>&laquo; <?php echo LangUtil::$generalTerms['CMD_BACK']; ?></a>
                    <br><br>
                    <?php
                    if(isset($_REQUEST['vd']))
                    {
                    	# Directed from specimen_verify_do.php
                    	?>
                    	<span class='clean-orange' id='msg_box'>
                    		<?php echo LangUtil::$pageTerms['TIPS_VERIFYDONE']; ?> &nbsp;&nbsp;<a href="javascript:toggle('msg_box');"><?php echo LangUtil::$generalTerms['CMD_HIDE']; ?></a>&nbsp;&nbsp;
                    	</span>
                    	<?php
                    }
                    else if(isset($_REQUEST['re']))
                    {
                    	# Directed form specimen_result_do.php
                    	?>
                    	<span class='clean-orange' id='msg_box'>
                    		<?php echo LangUtil::$pageTerms['TIPS_ENTRYDONE']; ?> &nbsp;&nbsp;<a href="javascript:toggle('msg_box');"><?php echo LangUtil::$generalTerms['CMD_HIDE']; ?></a>&nbsp;&nbsp;
                    	</span>
                    	<?php	
                    }
                    
                    ?>
                    <table id='result'>
                    	<tr valign='top'>
                    		<td>
                    		<?php $page_elems->getSpecimenInfo($sid); ?>
                    		</td>
                    		<td>
                    			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    		</td>
                    		<td>
                    			<?php $page_elems->getSpecimenInfoTaskList($sid, $pid); ?>
                    		</td>
                    	</tr>
                    </table>
                    <div id="barcodeData" style="display:none;">
                                                <input type="text" id="patientID" value='<?php echo encodePatientBarcode($_REQUEST['pid'],0); ?>' />
                                                <br><br>
                                                <div id="specimenBarcodeDiv"></div>
                                                </div>
                                    </div>
                  </div>
              </div>
         </div>
         </div>
         </div>
<?php
$script_elems->enableLatencyRecord();
$script_elems->enableJQueryForm();
$script_elems->enableAutocomplete();

$barcodeSettings = get_lab_config_settings_barcode();
//print_r($barcodeSettings);
$code_type = $barcodeSettings['type']; //"code39";
$bar_width = $barcodeSettings['width']; //2;
$bar_height = $barcodeSettings['height']; //40;
$font_size = $barcodeSettings['textsize']; //11;

?>

<script type='text/javascript'>
$(document).ready(function(){
    var code = $('#patientID').val();
    mark_as_pending();
    $("#patientBarcodeDiv").barcode(code, 
        '<?php echo $code_type; ?>',{barWidth:<?php echo $bar_width; ?>,
             barHeight:<?php echo $bar_height; ?>, 
             fontSize:<?php echo $font_size; ?>, output:'bmp'});            
});
function mark_as_pending()
{
    url = "ajax/specimen_change_status.php?sid=<?php echo $sid?>";
    $.ajax({
        type: "POST",
        url: url, async: false,
        success: function(data) {
             console.log("Sent");
        }
    });
}
function toggle_profile_divs()
{
    $('#profile_div').toggle();
    $('#profile_update_div').toggle();
    $('#profile_update_form').resetForm();
}

function print_specimen_barcode(pid, sid)
{
    s_id = parseInt(sid);
    url = "ajax/getSpecimenBarcode.php?sid="+sid;
    $.ajax({
        type: "GET",
        url: url,
                async: false,
        success: function(data) {
                         code = data;

        }
    });
    $("#specimenBarcodeDiv").barcode(code, '<?php echo $code_type; ?>',
    {barWidth:<?php echo $bar_width; ?>, barHeight:<?php echo $bar_height; ?>,
         fontSize:<?php echo $font_size; ?>, output:'bmp'});         
    Popup($('#specimenBarcodeDiv').html());
}

function print_patient_barcode()
{
    Popup($('#patientBarcodeDiv').html());
}

</script>