<?php
require ('utilityProvider.php');
error_reporting(E_ALL);
$defaultServerFolderName = (strlen($_GET['defaultFolderNameValue']) > 0) ? $_GET['defaultFolderNameValue'] : $defaultServerFolderName;

$allFilesFromBaseDirectory = glob($defaultServerFolderName.'/*');

//Actually delete all files from specified root folder
deleteAllFilesFromFolder($allFilesFromBaseDirectory); 

echo "All Files in Base Folder are deleted";

?>  