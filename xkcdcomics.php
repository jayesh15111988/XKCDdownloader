<?php
require('simple_html_dom.php');
require('utilityProvider.php');
require('ComicsDatabaseOperations.php');

//Maximum amount of time until script runs. Specified in the number of second 1500 seconds viz. 25 Minutes max
ini_set('max_execution_time', 1500);
//error_reporting(E_ALL ^ E_WARNING);
$didDownloadAtleastOneImage   = false;
//Keep the track of time how much does it take to download images
$starttime                    = getCurrentTimeInSeconds();
$responseString               = "";
//Get Content of file in HTML form
$minimumImagenumberToDownload = $_GET['miniComicsSequence'];
$maximumImageNumberToDownload = $_GET['maxComicsSequence'];
$defaultServerFolderName      = (strlen($_GET['defaultFolderNameValue']) > 0) ? $_GET['defaultFolderNameValue'] : $defaultServerFolderName;

checkIfDirectoryExists($defaultServerFolderName . "/");

// List of web pages with invalid file name
for ($counter = $minimumImagenumberToDownload; $counter <= $maximumImageNumberToDownload; $counter++) {
    
    //This is an easter egg - 404 is always not found on xkcd
    if ($counter == '404') {
        continue;
    }
    
    //$ret contains all divs with tag class=photo
    $html = file_get_html('http://xkcd.com/' . $counter . '/#');
    if ($html) {
        $ret = $html->find('div[id=comic]');
    }
    //We have reached the end of comics collection
    else {
        break;
    }
    
    //In case you are running it multiple times, truncate table to avoid flooding it with unwanted entries
    foreach ($ret as $div_class_photo) {
        $individualImageElements = $div_class_photo->find("img", 0);
        $imageName               = $individualImageElements->alt;
        $imageDescription        = $individualImageElements->title;

        if (strpos($imageName, ':') !== false) {
            $imageName = str_replace(":", " ", $imageName);
        }
        $comicsFullPath = $defaultServerFolderName . '/' . $counter . "-" . $imageName . ".jpg";
        
        if (!file_exists($comicsFullPath)) {
            
            $didDownloadAtleastOneImage = true;
            $data                       = file_get_contents($individualImageElements->src);
            file_put_contents($comicsFullPath, $data);
            $responseString .= "File <b>" . $comicsFullPath . "</b> did not exist. Downloaded Successfully <br/><br/>";
            storeImageInDatabase($counter, $imageName, $imageDescription);
        } else {
            $didDownloadAtleastOneImage = true;
            $responseString .= "File <b>" . $comicsFullPath . "</b> already exists. Did not download again <br/><br/>";
        }
        
    }
    
    
}
$endtime = getCurrentTimeInSeconds();
storeTagsWithCounterInformation();
if ($didDownloadAtleastOneImage) {
    $responseString .= "This page is processed in <b>" . ($endtime - $starttime) . "</b> Seconds <br/> All Images stored in <b> " . $defaultServerFolderName . " </b>Directory<br/><br/>";
} else {
    $responseString = "Failed to download requested images. Please try again later. <br/> Please note that file_get_contents(fileName)" 
    ." function is disabled on the server. If you are seeing this message plese try to"
    ." setup local server and then run the project on it<br/><br/>"
    ." Please refer to instructions on <a href = 'https://www.udemy.com/blog/xampp-tutorial/'>This</a> page to learn more about"
    ." setting up Xampp on local machine";
}
echo $responseString;
?>