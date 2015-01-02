<?php
require ('db_info.php'); 
require('StopWordsDatabase.php');

error_reporting(E_ALL);

function storeImageInDatabase($sequenceIdentifier, $imageName, $imageDescription) {
	global $dbh;
	storeTagsWithImageInformation($sequenceIdentifier, $imageDescription);
	$queryToInsertImageData = $dbh->prepare("INSERT INTO xkcdimagemetadata (sequenceIdentifier, title, imageDescription) VALUES (:sequenceIdentifier, :title, :imageDescription) ON DUPLICATE KEY UPDATE title = :updateTitle AND imageDescription = :updatedImageDescription");
	$queryToInsertImageData->bindParam(':sequenceIdentifier', $sequenceIdentifier,PDO::PARAM_INT);
	$queryToInsertImageData->bindParam(':title', $imageName,PDO::PARAM_STR);
	$queryToInsertImageData->bindParam(':imageDescription', $imageDescription,PDO::PARAM_STR);
	$queryToInsertImageData->bindParam(':updateTitle', $imageName,PDO::PARAM_STR);
	$queryToInsertImageData->bindParam(':updatedImageDescription', $imageDescription,PDO::PARAM_STR);
	$queryToInsertImageData->execute();
}

function storeTagsWithImageInformation($imageIdentifier, $imageDescription) {
	global $dbh;

	$descriptionWordsArray = explode(" ", $imageDescription);

	$filteredArray = array_filter($descriptionWordsArray, "isNonStopWord");
	//echo "<br/>";
	//print_r($filteredArray);
	//echo "<br/>";
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
truncate table tagsdatabase;
*/


function storeTagsWithCounterInformation() {
	//Now show tags info
    global $tagsCounterCollector;
    global $dbh;
    //Now store these tags in the database for later use

    foreach ($tagsCounterCollector as $tag => $counter) {
        //Get each tag from already registered array and store it in tags counter

////////////


 $statement = $dbh->prepare("select numberOfOccurrence from tagsdatabase where tagName = :tagToSearch");
		
		$statement->execute(array(':tagToSearch' => $tag));
		$result = $statement->fetchAll();
		$numberOfRows = sizeof($result);
		
		//echo "number of rows retrieved ".$numberOfRows.'<br/>';
		
		if($numberOfRows == 0){
 			$insertNewTagInDatabase=$dbh->prepare("INSERT INTO tagsdatabase(tagName,numberOfOccurrence) 
	value (:tagName,:numberOfOccurrence)");


 			$numRows = 1;
			$insertNewTagInDatabase->bindParam(':tagName', $tag, PDO::PARAM_STR);
			$insertNewTagInDatabase->bindParam(':numberOfOccurrence', $numRows,PDO::PARAM_INT);
			$insertNewTagInDatabase->execute();


   		}

   		else if($numberOfRows == 1) {
   			$countForExistingTagInDatabase = $result[0]['numberOfOccurrence'];
   			$updatedCount = $countForExistingTagInDatabase + $counter;
   			$updatenumberofTagOccurrences=$dbh->prepare("update tagsdatabase set numberOfOccurrence = :updatedCount where tagName=:tagName");
			$updatenumberofTagOccurrences->bindParam(':updatedCount',$updatedCount,PDO::PARAM_INT);
			$updatenumberofTagOccurrences->bindParam(':tagName',$tag,PDO::PARAM_STR);
			$updatenumberofTagOccurrences->execute();
   		}
   		else if($numberOfRows > 1){
   			echo "Terrible Bug spotted. Please pay attention to rectify it";
   		}
	}
}

function isNonStopWord($inputWord) {
	global $stopwords;
	$wordWithSpecialCharactersRemoved = trimInputWord($inputWord);
	if ((array_key_exists($wordWithSpecialCharactersRemoved, $stopwords) == TRUE) && ($stopwords[$wordWithSpecialCharactersRemoved] == TRUE)) {
		return FALSE;
	}
	return TRUE;
}

function trimInputWord($string) {
   $string = strtolower($string);
   $string = str_replace(' ', '', $string); // Replaces all spaces with hyphens.
   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}


?>