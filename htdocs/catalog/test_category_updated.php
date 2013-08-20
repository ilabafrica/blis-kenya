<?php
#
# Shows confirmation for specimen type updation
#
include("redirect.php");
include("includes/header.php"); 
LangUtil::setPageId("catalog");
?>
<br>
<b><?php echo LangUtil::$pageTerms['TEST_CATEGORY_UPDATED']; ?></b>
 | <a href='catalog.php?show_tc=1'>&laquo; <?php echo LangUtil::$pageTerms['CMD_BACK_TOCATALOG']; ?></a>
<br><br>
<?php 
$test_category = get_test_category_by_id($_REQUEST['tcid']);
$page_elems->getTestCategoryInfo($test_category->name); 
include("includes/footer.php");
?>