<?php
function BS_GetDatabaseConfig()
{
	// Locate the private directory on the webhost
	$publicDirName = "public_html";
	$privateDirName = "private";
	$domainRoot = $_SERVER['DOCUMENT_ROOT'];
	$privateDirectory = str_replace($publicDirName, $privateDirName, $domainRoot);

	// Load the database info
	$db_params = parse_ini_file( $privateDirectory . "/database_credentials.ini", false );
	return $db_params;
}
?>
