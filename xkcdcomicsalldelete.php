<?php
require ('utilityProvider.php');
error_reporting(E_ALL);

$allFilesFromBaseDirectory = glob('xkcdImages/'.'*');

//Actually delete all files from specified root folder
deleteAllFilesFromFolder($allFilesFromBaseDirectory); 

echo "All Files in Base Folder are deleted";



?>  