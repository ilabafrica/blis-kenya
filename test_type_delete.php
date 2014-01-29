<?php
#
# Deletes a test type from DB
# Sets disabled flag to true instead of deleting the record
# This maintains info for samples that were linked to this test type previously
#

include("../includes/db_lib.php");


$test_type_id = $_REQUEST['id'];
TestType::deleteById($test_type_id);

DbUtil::switchRestore($saved_db);
SessionUtil::restore($saved_session);

header("Location: catalog.php?tdel");
?>
