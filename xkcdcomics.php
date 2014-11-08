<?php
require ('simple_html_dom.php');
require ('utilityProvider.php');
//Maximum amount of time until script runs. Specified in the number of second 1500 seconds viz. 25 Minutes max
ini_set('max_execution_time', 1500);
error_reporting(E_ALL);
$baseFolderName = 'xkcdImages/';
  //Keep the track of time how much does it take to download images
   $starttime = getCurrentTimeInSeconds(); 
   $responseString = "";
//Get Content of file in HTML form
$minimumImagenumberToDownload = $_GET['miniComicsSequence']; 
$maximumImageNumberToDownload = $_GET['maxComicsSequence'];

// List of web pages with invalid file name
for ($counter = $minimumImagenumberToDownload; $counter <= $maximumImageNumberToDownload; $counter++) { 

//This is an easter egg - 404 is always not found on xkcd
if($counter == '404'){
	continue;
}

//$ret contains all divs with tag class=photo
$html = file_get_html('http://xkcd.com/'.$counter.'/#');
if($html) {
	$ret = $html->find('div[id=comic]'); 
}
//We have reached the end of comics collection
else {
	break;
}

//In case you are running it multiple times, truncate table to avoid flooding it with unwanted entries

foreach ($ret as  $div_class_photo) {	
$individualImageElements = $div_class_photo->find("img",0);
$imageName = $individualImageElements->alt;

 if (strpos($imageName,':') !== false) {
   $imageName = str_replace(":"," ",$imageName);
   }
$comicsFullPath = $baseFolderName.$counter."-".$imageName.".jpg";

if(!file_exists($comicsFullPath)) {
	$data = file_get_contents($individualImageElements->src);
	file_put_contents($comicsFullPath, $data);
  $responseString .= "File <b>".$comicsFullPath."</b> did not exist. Downloaded Successfully <br/><br/>";
}
else {
  $responseString .= "File <b>".$comicsFullPath."</b> already exists. Did not download again <br/><br/>";
}

}

}
   $endtime = getCurrentTimeInSeconds(); 
   $responseString .= "<br/><br/> This page is processed in <b>".($endtime - $starttime)."</b> Seconds <br/> All Images stored in <b>".$baseFolderName."</b>Directory<br/><br/>"; 
   echo $responseString;
?>