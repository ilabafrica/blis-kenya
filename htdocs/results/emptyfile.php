<?php
$thefile = "http://192.168.1.88/celtac/celtac-results.txt";

$f = @fopen($thefile, "r+");
if ($f !== false) {
    ftruncate($f, 0);
    fclose($f);
	echo "file emptied";
}
?>