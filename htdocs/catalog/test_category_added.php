<?php
#
# Shows confirmation for new test type addition
#
include("redirect.php");
include("includes/header.php"); 
LangUtil::setPageId("catalog");
?>
<br>
<b><?php echo "Laboratory Section Added"; ?></b>
 | <a href='catalog.php?show_tc=1'>&laquo; <?php echo LangUtil::$pageTerms['CMD_BACK_TOCATALOG']; ?></a>
<br><br>
<?php $page_elems->getTestCategoryInfo($_REQUEST['tcn'], true); ?>
<?php include("includes/footer.php"); ?>