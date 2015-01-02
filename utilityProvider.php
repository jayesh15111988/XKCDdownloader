<?php

$defaultServerFolderName = 'Images/xkcdImages';
$tagsCounterCollector = Array();
define("LastImageDownloadedCounterFile", "lastImageDownloadedCounterFile.txt");
define("maximumAllottedTimeout", 5000);

function getCurrentTimeInSeconds() {
	//How much time our script takes to download and store xkcd comics..
   	$mtime = microtime(); 
   	$mtime = explode(" ",$mtime); 
   	$mtime = $mtime[1] + $mtime[0]; 	
   	return $mtime;
}

function deleteAllFilesFromFolder($allFilesFromBaseDirectory) {
	foreach($allFilesFromBaseDirectory as $individualComicImage){ // iterate files
  if(is_file($individualComicImage))
    unlink($individualComicImage); // delete file
}
}

function checkIfDirectoryExists($directoryName) {
	if (!file_exists($directoryName)) {
    	mkdir($directoryName, 0755);
	} 
}

?>