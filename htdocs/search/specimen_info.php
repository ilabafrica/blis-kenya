<?php
#
# Main page for showing specimen info
#
$is_modal=false;
if(isset($_REQUEST['modal']))$is_modal=true;

if(!$is_modal){
	include("redirect.php");
	include("includes/header.php");
}else if ($is_modal){
	require_once("../includes/db_lib.php");
	require_once("../includes/page_elems.php");
	require_once("../includes/script_elems.php");
	$script_elems = new ScriptElems();
	$page_elems = new PageElems();
}
LangUtil::setPageId("specimen_info");
$sid = $_REQUEST['sid'];
?>
<?php if(!$is_modal){?>
<!-- BEGIN PAGE TITLE & BREADCRUMB-->       
                        <h3>
                        </h3>
                        <ul class="breadcrumb">
                            <li>
                                <i class="icon-download-alt"></i>
                                <a href="index.php">Home</a> 
                            </li>
                        </ul>
                        <!-- END PAGE TITLE & BREADCRUMB-->
                    </div>
                </div>
                <!-- END PAGE HEADER-->
<?php }
if($is_modal){?>
<div class="modal-header">
	<a href="javascript:remove_modal('specimen_info');" class="close"></a>
	<h4><i class="icon-info-sign"></i> Specimen Details</h4>
</div>
<?php }?>
             <div class="row-fluid">
                <div class="span12 sortable">

                    <div class="portlet box green" id="specimenresult_div">
                   <?php 
                    if(!$is_modal){?>
                        <div class="portlet-title" >
                            <h4><i class="icon-reorder"></i> <?php echo LangUtil::getTitle(); ?> </h4>           
                        </div>
                   <?php }?>
                          <div class="portlet-body" >
                                <?php 
                                if(!$is_modal){
									?>
									<br>
                                 <a href='javascript:history.go(-1);' class="btn">&laquo; <?php echo LangUtil::$generalTerms['CMD_BACK']; ?></a>
                                 <br><br>
                                 <?php }?>
                                
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
                                <table>
                                    <tr valign='top'>
                                        <td>
                                        <?php $page_elems->getSpecimenInfo($sid); ?>
                                        </td>
                                        <td>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </td>
                                        <td>
                                            <?php $page_elems->getSpecimenTaskList($sid); ?>
                                        </td>
                                    </tr>
                                </table>
                                <span id='fetch_progress_bar' style='display:none;'>
                                                    <?php $page_elems->getProgressSpinner(LangUtil::$generalTerms['CMD_SEARCHING']); ?>
                                                </span> 
                                <div class='result_form_pane' id='result_form_pane_<?php echo $sid; ?>'>
                                        </div>
                                <br>
                                <hr />
                                <b><?php echo LangUtil::$pageTerms['REGDTESTS']; ?></b><br>
                                <?php 
                                $page_elems->getSpecimenTestsTable($sid); 
                                ?>
                          </div>
                  </div>
              </div>
           </div>   

<?php if(!$is_modal){
	include("includes/footer.php"); 
}else if($is_modal){
?>
<div class="modal-footer">
<button type="button" data-dismiss="modal" class="btn" onclick='javascript:remove_modal("specimen_info");'>Close</button>
</div>
<?php 
}
?>