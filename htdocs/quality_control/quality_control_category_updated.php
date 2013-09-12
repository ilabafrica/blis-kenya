<?php
#
# Shows confirmation for quality control category updation
#
include("redirect.php");
include("includes/header.php"); 
LangUtil::setPageId("quality");
?>
<br>
<b><?php echo "Quality Control Category Updated."; ?></b>
 | <a href='quality.php?show_qcc=1'>&laquo; <?php echo "Back to Quality Control"; ?></a>
<br><br>
<?php 
$qcc = get_test_category_by_id($_REQUEST['qccid']);
$page_elems->getQualityControlCategoryInfo($qcc->name); 
include("includes/footer.php");
?>