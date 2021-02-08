<?php
$_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__);
include_once($_SERVER['DOCUMENT_ROOT'] . "/DatabaseFunctions.php");
BS_ResetDatabaseToCurrentPosts();
echo "Done!";
?>
