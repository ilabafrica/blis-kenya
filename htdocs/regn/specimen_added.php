<?php
#
# Main page for showing specimen addition confirmation
# Called from new_specimen.php
#
include("redirect.php");
include("includes/header.php");
LangUtil::setPageId("specimen_added");

$session_num = $_REQUEST['snum'];


//$script_elems->enablePulsate();


//$session_num = get_session_current_number();
$specimen_list = get_specimens_by_session($session_num);

?>
<br />
<!-- BEGIN PAGE TITLE & BREADCRUMB-->       
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
                <!-- BEGIN REGISTRATION PORTLETS-->   
<div class="row-fluid">
    <div class="span12 sortable">
                
<div id="specimen_added">
    <div class="portlet box blue">
        <div class="portlet-title">
            <h4><i class="icon-reorder"></i><?php echo LangUtil::getTitle(); ?></h4>
            <div class="tools">
            <a href="javascript:;" class="collapse"></a>
            <a href="javascript:;" class="reload"></a>
            </div>
        </div>
        
        <div class="portlet-body form">
        <!--<p style="text-align: right;"><a rel='facebox' href='#SpecimenAdded'>Page Help</a></p>-->
        <div class="span4" style="position: absolute;top: 180px;right: 30px;">
						<?php if(count($specimen_list) != 0)
            					{ ?><div class="well text-info">
						<?php
							echo "<li>";
							echo "This page allows us to view a summary of both patient and the specimens we registered.";
							echo "</li>";
						
							echo "<li>"; 
							echo "Once a specimen has been registered, we can use this page to get the specimen number for labelling of the specimen.";
							echo "</li>";
						
							echo "<li>"; 
							echo "The specimen number is indicated in in bigger fonts than the other details (e.g. MIC-5210).";
							echo "</li>";
							?>
						</div><?php } ?>				
					</div>
            <div id='specimen_added_body'> </div>           
            
            Lab_No. <?php echo $session_num; ?>
            <?php
            if(count($specimen_list) > 1)
            {
                ?>
             | <?php echo LangUtil::$generalTerms['SPECIMEN']; ?>: <?php echo count($specimen_list); ?>
             <?php
            }
            ?>
             | <a href='session_print.php?snum=<?php echo $session_num; ?>' target='_blank'><?php echo LangUtil::$generalTerms['CMD_PRINT']; ?></a>
             | <a href='find_patient.php'>&laquo; <?php echo LangUtil::$pageTerms['MSG_NEXTSPECIMEN']; ?></a>
            <br><br>
            <?php
            if(count($specimen_list) == 0)
            {
                ?>
                <div class='sidetip_nopos'>
                    <?php echo LangUtil::$generalTerms['ERROR'].": ".LangUtil::$generalTerms['ACCESSION_NUM']." ".LangUtil::$generalTerms['INVALID']; ?>
                </div>
                <?php
                include("includes/footer.php");
                return;
            }
            $patient_id = $specimen_list[0]->patientId;
            ?>
            <table cellpadding='4px'>
                <tbody>
                    <tr valign='top'>
                        <td>
                            <?php $page_elems->getPatientInfo($patient_id); ?>
                        </td>
                        <td>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <br><br>
            <small><b><?php echo LangUtil::$generalTerms['SPECIMENS']; ?></b></small>
            <table cellpadding='4px'>
                <tbody>
                    <tr valign='top'>
                    <?php
                    $count = 1;
                    foreach($specimen_list as $specimen)
                    {
                        echo "<td>";
                        echo "<div class='pretty_box'>";
                        $page_elems->getSpecimenInfo($specimen->specimenId); 
                        echo "</div>";
                        echo "</td>";
                        if($count % 2 == 0)
                        {
                            echo "</tr><tr valign='top'>";
                        }
                        $count++;
                    }
                    ?>
                    </tr>
                </tbody>
            </table>        
        </div>
    </div>
</div>
<div id='SpecimenAdded' class='right_pane' style='display:none;margin-left:10px;'>
					<ul>
						<?php
						
							echo "<li>";
							echo "This page allows us to view a summary of both patient and the specimens we registered.";
							echo "</li>";
						
							echo "<li>"; 
							echo "Once a specimen has been registered, we can use this page to get the specimen number for labelling of the specimen.";
							echo "</li>";
						
							echo "<li>"; 
							echo "The specimen number is indicated in in bigger fonts than the other details (e.g. MIC-52510).";
							echo "</li>"; 
						
						?>
					</ul>
				</div>
</div>
<?php
include("includes/scripts.php");
$script_elems->enableDatePicker();
$script_elems->enableTableSorter();
$script_elems->enableFaceBox();
include("includes/footer.php");?>