<?php

require 'helpers.php';
require 'logParser.php';
require 'dataProperties.php';

$dataProperties = require 'dataProperties.php';

$filePath = 'sample-log.txt';
$outputPath = 'output.txt';

$ids = [];
$uniqueUserIds = [];
$outputLines = [];

foreach ( parseLogEntries (
  $filePath, 
  $dataProperties, 
  $ids, 
  $uniqueUserIds
  ) 
  as $formattedEntry) {
    $outputLines[] = $formattedEntry;
}
$outputLines[] = "";

$sortedIds = formatSortedIds($ids);
$outputLines = array_merge($outputLines, $sortedIds);
$outputLines[] = "";

$userIdList = formatUserList(array_keys($uniqueUserIds));
$outputLines = array_merge($outputLines, $userIdList);
$outputLines[] = "";

file_put_contents(
  $outputPath,
  implode(PHP_EOL, $outputLines)
);

echo "Output written to: $outputPath\n";