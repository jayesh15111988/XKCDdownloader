<?php

require('StopWordsDatabase.php');

$stri = "My name is to jayesh we all here indiana university";
$tok = explode(" ",$stri);
print_r($tok);



global $stopwords;

$clean = trim("jayesh kawli;", "\x20..\x2F,;,");
var_dump($clean);

foreach ($tok as $val) {

	echo array_key_exists($val, $stopwords);

	if(!isset($stopwords[$val])) {
		echo "Stop Word ".$val;
	}else{
		echo "Non Stop word ".$val;
	}
	echo "<br/>";
	
}
?>