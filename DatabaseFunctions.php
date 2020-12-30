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
function BS_QueryDatabaseParameterised($query, $parameter)
{
	$privateDir = BS_GetPrivateDirectory();
	$dbParams = BS_LoadDatabaseCredentials($privateDir);
	$dbConnection = BS_ConnectToDatabase($dbParams);
	$queryResults = BS_ExecuteDatabaseQueryParameterised($dbConnection, $query, $parameter);
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
function BS_ExecuteDatabaseQueryParameterised($dbConnection, $query, $parameter)
{
	$preparedStatement = $dbConnection->prepare($query);
	$preparedStatement->bind_param("s", $parameter);
	$preparedStatement->execute();
	$query_result = $preparedStatement->get_result();
	$preparedStatement->close();
	return $query_result;
}
?>
