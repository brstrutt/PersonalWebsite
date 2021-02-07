<?php
echo "Include the database functions!";
echo "HMMM" . $_SERVER['DOCUMENT_ROOT'] . "YES";
include_once("/home/u296099994/public_html/dev/DatabaseFunctions.php");
echo "Start resetting the doongaloong!";
BS_ResetDatabaseToCurrentPosts();
echo "Done!";
?>
