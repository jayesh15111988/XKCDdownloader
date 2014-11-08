'use strict';

var minimumComicsNumber = 1;
var maximumComicsNumber = 100;
var remoteComicsFetcherURL = 'xkcdcomics.php';
var remoteSelectedDeleteURL = 'xkcdcomicsselecteddelete.php';
var remoteAllDeleteURL = 'xkcdcomicsalldelete.php';
var defaultServerFolderName = 'xkcdImages';

hideLoadingIndicator("Waiting for user input ..");

$("#getImagesButton").click( function() {

sendRequestToServerWithParameters({loadingText: "Downloading ......... Please Wait <div class='loadingDiv'> <img src='loader_spinner.gif'> </div>",remoteURL: remoteComicsFetcherURL,parameters: getRequestParametersToSendToServer(),requestType: 0});


});

$("#removeSelectedImagesButton").click(function(){

if (confirm('Are you sure you want to remove input range of images from server directory '+defaultServerFolderName+'?')) {
    sendRequestToServerWithParameters({loadingText: "Removing Selected files... Please be patient",remoteURL: remoteSelectedDeleteURL,parameters: getRequestParametersToSendToServer(),requestType: 1});
} 

})

$("#removeAllImagesButton").click(function(){

if (confirm('Are you sure you want to remove all Comics from server directory '+defaultServerFolderName+'?')) {
sendRequestToServerWithParameters({loadingText: "Removing all files. Please be patient",remoteURL: remoteAllDeleteURL,parameters: {defaultFolderNameValue: defaultServerFolderName},requestType: 2})
}


})


function sendRequestToServerWithParameters(parametersDictionary) {



showLoadingIndicator(parametersDictionary.loadingText);
getPromiseWithURLAndParameters(parametersDictionary.remoteURL,parametersDictionary.parameters,'text').then(function(response) {
    hideLoadingIndicator(response);
}, function(error) {
 console.log('Promise rejected With Error '+ JSON.stringify(error));
    });    
}

$("#minimumNumber").on("keyup change", function() {
        updateLiveContentWithcomicsInfo();
    });

$("#maximumNumber").on("keyup change", function() {
        updateLiveContentWithcomicsInfo();
    });

$("input#folderNameInput").on("keyup change",function() {
        defaultServerFolderName = this.value;
});

//Source - http://stackoverflow.com/questions/469357/html-text-input-allow-only-numeric-input
$("input.numberInput").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) || 
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
});




function updateLiveContentWithcomicsInfo() {
	minimumComicsNumber = $("#minimumNumber").val()
	maximumComicsNumber = $("#maximumNumber").val()
	 
	minimumComicsNumber = (minimumComicsNumber.length > 0) ? minimumComicsNumber : 1;
	maximumComicsNumber = (maximumComicsNumber.length > 0) ? maximumComicsNumber : 100;

	//Only for retarded users
	if(maximumComicsNumber <= minimumComicsNumber) {
		maximumComicsNumber = minimumComicsNumber + 1;
	}

    $("div#currentInputStatus").text("Requesting comics sequence ranging from "+ minimumComicsNumber + " to "+ maximumComicsNumber);
}


function showLoadingIndicator(loadingMessage) {
    $("#loadingIndicator").html(loadingMessage);
        
}

function hideLoadingIndicator(displayMessage) {
    $("#loadingIndicator").html(displayMessage);

}

function getRequestParametersToSendToServer() {
    return {miniComicsSequence: minimumComicsNumber,maxComicsSequence: maximumComicsNumber, defaultFolderNameValue: defaultServerFolderName};
}
