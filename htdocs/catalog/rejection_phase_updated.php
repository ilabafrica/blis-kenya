<?php
#
# Shows confirmation for specimen type updation
#
include("redirect.php");
include("includes/header.php"); 
LangUtil::setPageId("catalog");
?>
<br>
<b><?php echo "Specimen Rejection Phase Updated"; ?></b>
 | <a href='catalog.php?show_rp=1'>&laquo; <?php echo LangUtil::$pageTerms['CMD_BACK_TOCATALOG']; ?></a>
<br><br>
<?php 
$rejection_phase = get_rejection_phase_by_id($_REQUEST['rp']);
$page_elems->getRejectionPhaseInfo($rejection_phase->name); 
echo $_REQUEST['rp'];
echo $rejection_phase->name;
include("includes/footer.php");
?>