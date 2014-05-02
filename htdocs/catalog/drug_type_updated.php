<?php
#
# Shows confirmation for specimen type updation
#
include("redirect.php");
include("includes/header.php"); 
LangUtil::setPageId("catalog");
?>
<br>
<b><?php echo LangUtil::$pageTerms['DRUG_TYPE_UPDATED']; ?></b>
 | <a href='catalog.php?show_d=1'>&laquo; <?php echo LangUtil::$pageTerms['CMD_BACK_TOCATALOG']; ?></a>
<br><br>
<?php 
$drug_type = get_drug_type_by_id($_REQUEST['did']);
$page_elems->getDrugTypeInfo($drug_type->name); 
include("includes/footer.php");
?>