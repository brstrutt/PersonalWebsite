<?php
// Query the website database and return the results
function BS_QueryDatabase($query)
{
	$privateDir = BS_GetPrivateDirectory();
	$dbParams = BS_LoadDatabaseCredentials($privateDir);
	$dbConnection = BS_ConnectToDatabase($dbParams);
	$queryResults = BS_ExecuteDatabaseQuery($dbConnection, $query);
	$dbConnection->close();
	return $queryResults;
}

// Query the website database using parameterised queries to avoid pesky sql injection
// Expects $parameters to be an array of arrays, with each sub array being the Type and vaue of that parameter
// E.G [["s", $name]] would be a single parameter of type "s" with value $name
function BS_QueryDatabaseParameterised($query, $parameters)
{
	$privateDir = BS_GetPrivateDirectory();
	$dbParams = BS_LoadDatabaseCredentials($privateDir);
	$dbConnection = BS_ConnectToDatabase($dbParams);
	$queryResults = BS_ExecuteDatabaseQueryParameterised($dbConnection, $query, $parameters);
	$dbConnection->close();
	return $queryResults;
}

// Return the directory path for this subdomain's private directory
function BS_GetPrivateDirectory()
{
	$publicDirName = "public_html";
	$privateDirName = "private";
	$domainRoot = $_SERVER['DOCUMENT_ROOT'];
	$privateDirectory = str_replace($publicDirName, $privateDirName, $domainRoot);
	return $privateDirectory;
}

// Load the database credentials from the config file in a given directory. Returns the configuration data.
function BS_LoadDatabaseCredentials($directory)
{
	$db_params = parse_ini_file( $directory . "/database_credentials.ini", false );
	return $db_params;
}

// Connect to a database described by the db_params object. Returns the connection object.
function BS_ConnectToDatabase($db_params)
{
	$db_connection = new mysqli($db_params['host'], $db_params['user'], $db_params['password'], $db_params['dbname']);
	if ($db_connection->connect_error)
	{
		echo "<p>Connection failed: " . $db_connection->connect_error . "</p>";
	}
	return $db_connection;
}

// Execute a given sql query on a given database connection, returning the result.
function BS_ExecuteDatabaseQuery($dbConnection, $query)
{
	$query_result = $dbConnection->query($query);
	return $query_result;
}

// Execute a given parameterised sql query on a given database connection, returning the result.
function BS_ExecuteDatabaseQueryParameterised($dbConnection, $query, $parameters)
{
	$preparedStatement = $dbConnection->prepare($query);
	foreach($parameters as $parameter)
	{
		$preparedStatement->bind_param($parameter[0], $parameter[1]);
	}
	$preparedStatement->execute();
	$query_result = $preparedStatement->get_result();
	$preparedStatement->close();
	return $query_result;
}

function BS_ResetDatabaseToCurrentPosts()
{
	BS_WipeDatabaseData();
	//BS_InsertAllPostsToDatabase();
}

function BS_WipeDatabaseData()
{
  // Wipe database data leaving the structure untouched
	$ignoreForeignKeys = "SET FOREIGN_KEY_CHECKS = 0;";
	$wipePosts = "TRUNCATE TABLE `Posts`;";
	$wipeTags = "TRUNCATE TABLE `Tags`;";
	$wipeLinks = "TRUNCATE TABLE `PostTags`;";

	BS_QueryDatabase("DELETE FROM `Posts`; ALTER TABLE `Posts` AUTO_INCREMENT = 1;");
}

function BS_InsertAllPostsToDatabase()
{
  // Grab all Post Folders
  BS_QueryDatabase("INSERT INTO `Posts` (`ID`, `Name`, `CreationDate`) VALUES (NULL, 'TESTIG', '2020-12-30')");
  BS_QueryDatabase("INSERT INTO `Tags` (`ID`, `Name`, `IsCategory`) VALUES (NULL, 'TestCategory', 1)");
  BS_QueryDatabase("INSERT INTO `Tags` (`ID`, `Name`, `IsCategory`) VALUES (NULL, 'TestTag', 0)");
  BS_QueryDatabase("INSERT INTO `PostTags` (`ID`, `PostId`, `TagId`) VALUES (NULL, 1, 1)");
  // For each one add the post then add its tags and other data from the csv
}
?>
