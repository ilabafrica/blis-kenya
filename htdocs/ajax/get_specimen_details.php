<?php
#
#Get Specimen details after received
#
include("../includes/db_lib.php");
$labNo = $_REQUEST['labno'];


$test = Test::getByExternalLabno($labNo);
 echo $test->specimenId."%".$test->testId;

?>