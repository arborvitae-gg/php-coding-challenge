<?php

require_once 'helpers.php';

/**
 * Generator that parses and yields formatted log entries.
 *
 * @param string $filePath
 * @param array $dataProperties
 * @param array &$ids
 * @param array &$uniqueUserIds
 * @return Generator
 */
function parseLogEntries( 
    string $filePath, 
    array $dataProperties, 
    array &$ids, 
    array &$uniqueUserIds 
    ) : Generator {
    $file = new SplFileObject($filePath);

    while (!$file->eof()) {
        $line = trim($file->fgets());
        if ($line === '') continue;

        $parsedData = [];

        foreach ($dataProperties as $key => $property) {
            $raw = extractField (
                $line, 
                $property['position'] - 1, // -1 since position given in the specifications is not zero-based
                $property['length']
            );
            $parsedData[$key] = formatValue (
                $raw, 
                $property['format']
            );
        }

        if (!empty($parsedData['id'])) {
            $ids[] = $parsedData['id'];
        }

        if (!empty($parsedData['userId'])) {
            $uniqueUserIds[$parsedData['userId']] = true;
        }

        $orderedValues = [];
        foreach (array_keys($dataProperties) as $key) {
            $orderedValues[] = $parsedData[$key] ?? '';
        }

        yield implode('|', $orderedValues);
    }

}