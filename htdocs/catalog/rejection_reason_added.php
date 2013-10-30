<?php
#
# Shows confirmation for new rejection reason addition
#
include("redirect.php");
include("includes/header.php"); 
LangUtil::setPageId("catalog");
?>
<br>
<b><?php echo "Specimen Rejection Reason Successfully Added"; ?></b>
 | <a href='catalog.php?show_rp=1'>&laquo; <?php echo LangUtil::$pageTerms['CMD_BACK_TOCATALOG']; ?></a>
<br><br>
<?php $page_elems->getRejectionReasonInfo($_REQUEST['rr'], true); ?>
<?php include("includes/footer.php"); ?>