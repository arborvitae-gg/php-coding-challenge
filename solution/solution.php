<?php

require 'dataProperties.php';
require 'functions.php';

$filePath = 'sample-log.txt';
$outputPath = 'output.txt';

$logEntries = [];
$uniqueUserIDs = [];
$outputLines = [];

foreach (yieldEntries($filePath) as $entry) {
  if ($entry === false) break; 
  $parsedData = [];

  foreach ($dataProperties as $key => $property) {
    $value = extractField($entry, $property['position']-1, $property['length']);

    switch($property['format']){
      case 'number': 
        $value = formatNumber($value); 
        break;

      case 'date': 
        $value = formatDateTime($value); 
        break;

      default: $value;
    }
    $parsedData[$key] = $value;
  }
  $logEntries[] = $parsedData;
  $uniqueUserIDs[$parsedData['userId']] = true; 
}

$ids = array_column($logEntries, 'id');
natsort($ids);
$ids = array_values($ids);

$userIDs = array_keys($uniqueUserIDs);
sort($userIDs);
$userIDs = array_values($userIDs); 

$outputLines[] = "SECTION 1: Pipe delimited version of the log.";
foreach ($logEntries as $entry) {
  $values = [];
  foreach (array_keys($dataProperties) as $key) $values[] = $entry[$key];
  $outputLines[] = implode('|', $values);
}

$outputLines[] = "\nSECTION 2: IDs sorted in ascending order.";
foreach ($ids as $id) $outputLines[] = $id;

$outputLines[] = "\nSECTION 3: Unique User IDs sorted in ascending order, numbers are enclosed in [ ].";
foreach ($userIDs as $index => $userID) $outputLines[] = "[" . ($index + 1) . "] " . $userID;

file_put_contents($outputPath, implode(PHP_EOL, $outputLines));

echo "Output written to: $outputPath\n";