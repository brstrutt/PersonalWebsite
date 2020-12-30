<?php
function ResetDatabase()
{
  include_once($_SERVER['DOCUMENT_ROOT'] . "/DatabaseFunctions.php");
  // Wipe database data (Leave the structure)
  BS_QueryDatabase("TRUNCATE TABLE `Posts`");
  BS_QueryDatabase("TRUNCATE TABLE `PostTags`");
  BS_QueryDatabase("TRUNCATE TABLE `Tags`");

  // Grab all Post Folders
  BS_QueryDatabase("INSERT INTO `Posts` (`ID`, `Name`, `CreationDate`) VALUES (NULL, 'TESTIG', '2020-12-30')");
  BS_QueryDatabase("INSERT INTO `Tags` (`ID`, `Name`, `IsCategory`) VALUES (NULL, 'TestCategory', 1)");
  BS_QueryDatabase("INSERT INTO `Tags` (`ID`, `Name`, `IsCategory`) VALUES (NULL, 'TestTag', 0)");
  BS_QueryDatabase("INSERT INTO `PostTags` (`ID`, `PostId`, `TagId`) VALUES (NULL, 1, 1)");
  // For each one add the post then add its tags and other data from the csv
}
?>
