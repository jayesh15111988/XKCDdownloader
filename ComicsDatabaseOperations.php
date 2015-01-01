<?php
require ('db_info.php'); 
require('StopWordsDatabase.php');

error_reporting(E_ALL);

function storeImageInDatabase($sequenceIdentifier, $imageName, $imageDescription) {
	global $dbh;
	storeTagsWithImageInformation($sequenceIdentifier, $imageDescription);
	$queryToInsertImageData = $dbh->prepare("INSERT INTO xkcdimagemetadata (sequenceIdentifier, title, imageDescription) VALUES (:sequenceIdentifier, :title, :imageDescription)");
	$queryToInsertImageData->bindParam(':sequenceIdentifier', $sequenceIdentifier,PDO::PARAM_INT);
	$queryToInsertImageData->bindParam(':title', $imageName,PDO::PARAM_STR);
	$queryToInsertImageData->bindParam(':imageDescription', $imageDescription,PDO::PARAM_STR);
	$queryToInsertImageData->execute();
}

function storeTagsWithImageInformation($imageIdentifier, $imageDescription) {
	global $dbh;

	$descriptionWordsArray = explode(" ", $imageDescription);

	$filteredArray = array_filter($descriptionWordsArray, "isNonStopWord");
	foreach ($filteredArray as $individualWord) {
		
			storeTagInCounterArray($individualWord);	
			//Store that tag in database along with image identifier. This is not a stopword
			$queryToInsertImageTag = $dbh->prepare("INSERT INTO imagetags (imageIdentifier, tag) VALUES (:imageIdentifier, :tagValue)");
			$queryToInsertImageTag->bindParam(':imageIdentifier', $imageIdentifier,PDO::PARAM_INT);
			$queryToInsertImageTag->bindParam(':tagValue', $individualWord,PDO::PARAM_STR);
			$queryToInsertImageTag->execute();
		
		
	}
}

function storeTagInCounterArray($tagValue) {
	global $tagsCounterCollector;
	if(array_key_exists($tagValue, $tagsCounterCollector)){
		$tagsCounterCollector[$tagValue] = $tagsCounterCollector[$tagValue] + 1;
	}
	else {
		$tagsCounterCollector[$tagValue] = 1;
	}
}

/*
truncate table xkcdimagemetadata;
truncate table imagetags;
*/

function isNonStopWord($inputWord) {
	global $stopwords;
	return (array_key_exists(trimInputWord($inputWord), $stopwords) == FALSE);
}

function trimInputWord($inputWord) {
	return trim(strtolower($inputWord), "\x20..\x2F,;,");
}

?>