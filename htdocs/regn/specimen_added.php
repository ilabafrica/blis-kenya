<?php
#
# Main page for showing specimen addition confirmation
# Called from new_specimen.php
#
require_once("../includes/db_lib.php");
require_once("../includes/page_elems.php");
LangUtil::setPageId("specimen_added");
$page_elems = new PageElems();
$session_num = $_REQUEST['snum'];

$specimen_list = get_specimens_by_session($session_num);
?>                

<div class="modal-header">
	<a href="javascript:$('#specimen_registered').modal('hide');" class="close"></a>
	<h4><i class="icon-pencil"></i> <span class='page_title'><?php echo LangUtil::getTitle(); ?> 
</div>

<div class="modal-body">
           
            
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
             <!-- | <a href='find_patient.php'>&laquo; <?php echo LangUtil::$pageTerms['MSG_NEXTSPECIMEN']; ?></a> -->
        	<br />
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
            <table cellpadding='40px'>
                <tbody>
                    <tr valign='top'>
	                      <?php
			                    $count = 1;
			                    foreach($specimen_list as $specimen)
			                    {
			                        echo "<td>";
									echo "<small><b>". LangUtil::$generalTerms['SPECIMENS'] ."</b></small>";
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
