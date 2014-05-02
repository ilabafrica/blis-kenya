<?php
#
# Shows confirmation for new test type addition
#
include("redirect.php");
include("includes/header.php"); 
LangUtil::setPageId("catalog");
?>
<br>
<b><?php echo "Drug Type Added"; ?></b>
 | <a href='catalog.php?show_d=1'>&laquo; <?php echo LangUtil::$pageTerms['CMD_BACK_TOCATALOG']; ?></a>
<br><br>
<?php $page_elems->getDrugTypeInfo($_REQUEST['d'], true); ?>
<?php include("includes/footer.php"); ?>