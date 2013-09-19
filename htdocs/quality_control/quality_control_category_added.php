<?php
#
# Shows confirmation for new test type addition
#
include("redirect.php");
include("includes/header.php"); 
LangUtil::setPageId("quality");
?>
<br>
<b><?php echo "Quality Control Category Added"; ?></b>
 | <a href='quality.php?show_qcc=1'>&laquo; <?php echo "Back to Quality Control"; ?></a>
<br><br>
<?php $page_elems->getQualityControlCategoryInfo($_REQUEST['qcc'], true); ?>
<?php include("includes/footer.php"); ?>