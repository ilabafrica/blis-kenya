<?php
#
# Shows confirmation for specimen type updation
#
include("redirect.php");
include("includes/header.php"); 
LangUtil::setPageId("catalog");
?>
<br>
<b><?php echo "Test Category Updated"; ?></b>
 | <a href='catalog.php?show_tc=1'>&laquo; <?php echo LangUtil::$pageTerms['CMD_BACK_TOCATALOG']; ?></a>
<br><br>
<?php 
$test_category = get_test_category_by_id($_REQUEST['tcid']);
$page_elems->getTestCategoryInfo($test_category->name); 
echo $_REQUEST['tcid'];
echo $test_category->name;
include("includes/footer.php");
?>