<?php
echo "Include the database functions!";
$_SERVER['DOCUMENT_ROOT'] = "/home/u296099994/public_html/dev";
echo "HMMM" . $_SERVER['DOCUMENT_ROOT'] . "YES";
include_once($_SERVER['DOCUMENT_ROOT'] . "/DatabaseFunctions.php");
echo "Start resetting the doongaloong!";
BS_ResetDatabaseToCurrentPosts();
echo "Done!";
?>
