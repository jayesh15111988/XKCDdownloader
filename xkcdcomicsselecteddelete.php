<?php
require ('utilityProvider.php');
error_reporting(E_ALL);

$allFilesFromBaseDirectory = glob('xkcdImages/'.'*');
$minimumComicsNumberToDelete = $_GET['miniComicsSequence']; 
$maximumComicsNumberToDelete = $_GET['maxComicsSequence'];
$defaultServerFolderName = (strlen($_GET['defaultFolderNameValue']) > 0) ? $_GET['defaultFolderNameValue'] : $defaultServerFolderName;

function filterFileName ($individulaFileName) {
	global $minimumComicsNumberToDelete;
	global $maximumComicsNumberToDelete;
	global $defaultServerFolderName;

	$regularExpressionToMatchFileName = '@^'.$defaultServerFolderName.'/(\d+)?@i';
	 preg_match($regularExpressionToMatchFileName,
    $individulaFileName, $matches);
	//Check if file name prefix falls between the input range of files to be deleted
	return ($matches[1] >= $minimumComicsNumberToDelete && $matches[1] <= $maximumComicsNumberToDelete);
}


$filteredArray = array_filter($allFilesFromBaseDirectory,"filterFileName");

deleteAllFilesFromFolder($filteredArray);

echo "All Selected Files removed from folder";

?>