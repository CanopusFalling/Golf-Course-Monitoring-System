<?php

if(!empty($_POST['Scores'])){
	$ScoreFile = fopen("Scores.txt", "r") or die("Unable to open file!");
	$StoredData = fread($myfile,filesize("Scores.txt"));
	
	$DeserialisedData = json_decode($_POST['Scores']));
	$DeserialiseStoredData = json_decode($StoredData);
	
	$ConcatinatedDeserialisedData = (object) array_merge((array) $DeserialiseStoredData, (array) $DeserialisedData);
	
}
?>