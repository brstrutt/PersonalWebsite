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
// Expects $parameters to be an array with the first arg specifying the
// param types and the second arg being an array of the parameters
// E.G ["s", [$name]] would be a single parameter of type "s" with value $name
// E.G ["si", [$name, $number]] would be two params, one string and one int
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
	$preparedStatement->bind_param($parameters[0], ...$parameters[1]);
	$preparedStatement->execute();
	$query_result = $preparedStatement->get_result();
	$preparedStatement->close();
	return $query_result;
}

// Delete all data in the current database, then insert each post and its tags
function BS_ResetDatabaseToCurrentPosts()
{
	BS_WipeDatabaseData();
	BS_InsertAllPostsToDatabase();
}

// Wipe database data leaving the structure untouched
function BS_WipeDatabaseData()
{
	BS_QueryDatabase("DELETE FROM `Posts`");
	BS_QueryDatabase("ALTER TABLE `Posts` AUTO_INCREMENT = 1");
	BS_QueryDatabase("DELETE FROM `Tags`");
	BS_QueryDatabase("ALTER TABLE `Tags` AUTO_INCREMENT = 1");
	BS_QueryDatabase("TRUNCATE TABLE `PostTags`");
}

// Scan through the folder structure to find all Posts
// then insert said posts and their tags into the database
function BS_InsertAllPostsToDatabase()
{
  // Grab all post names
	$postsDir = $_SERVER['DOCUMENT_ROOT'] . "/posts/";
	$dirs = array_filter(glob($postsDir . '*'), 'is_dir');

	// Add each Post to the Database
	foreach($dirs as $dir)
	{
		$dirName =  str_replace($postsDir, "", $dir);
		BS_InsertPostToDatabase($postsDir, $dirName);
	}
}

// Insert a specified post into the database
// Directory that contains the post must be specified
// the Post itself is expected to be a directory containing at LEAST a data.csv
// TODO: Stop Duplication of Posts
function BS_InsertPostToDatabase($postsDir, $post)
{
	// Read the csv
	$postDataFile = fopen($postsDir . $post . "/data.csv", "r");
	$category = fgetcsv($postDataFile);
	$tags = fgetcsv($postDataFile);
	$tags = array_merge($category, $tags);
	$creationUpdateDates = fgetcsv($postDataFile);
	fclose($postDataFile);

	// Insert the Post into the Database
	BS_QueryDatabaseParameterised("INSERT INTO `Posts` (`ID`, `Name`, `CreationDate`) VALUES (NULL, ?, ?)", ["ss",[$post, '2020-12-12']]);

	$insertedPost = BS_QueryDatabaseParameterised("SELECT * FROM Posts WHERE Name LIKE ?", ["s", [$post]]);
	$postData = $insertedPost->fetch_assoc();

	// Insert each Tag that isn't yet inserted
	foreach($tags as $tag)
	{
		$existingTag = BS_QueryDatabaseParameterised("SELECT * FROM Tags WHERE Name LIKE ?", ["s", [$tag]]);
		if($existingTag->num_rows < 1)
		{
			$isCategory = 0;
			if(strcmp($tag, $category[0]) == 0) $isCategory = 1;
			BS_QueryDatabaseParameterised("INSERT INTO `Tags` (`ID`, `Name`, `IsCategory`) VALUES (NULL, ?, ?)", ["si",[$tag, $isCategory]]);

			$existingTag = BS_QueryDatabaseParameterised("SELECT * FROM Tags WHERE Name LIKE ?", ["s", [$tag]]);
		}
		$tagData = $existingTag->fetch_assoc();

		$postId = $postData["ID"];
		$tagId = $tagData["ID"];
		BS_QueryDatabaseParameterised("INSERT INTO `PostTags` (`ID`, `PostId`, `TagId`) VALUES (NULL, ?, ?)", ["ii",[$postId, $tagId]]);
	}
}
?>
