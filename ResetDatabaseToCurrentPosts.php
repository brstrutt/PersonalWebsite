<?php
echo "Include the database functions!";
include_once($_SERVER['DOCUMENT_ROOT'] . "/DatabaseFunctions.php");
echo "Start resetting the doongaloong!";
BS_ResetDatabaseToCurrentPosts();
echo "Done!";
?>
