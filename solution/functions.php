<?php

function yieldEntries($filePath) {
    $file = fopen($filePath, 'r') or die("Failed to open file!: $filePath");    
    while (($line = fgets($file)) !== false) {
        $line = trim($line);
        if ($line === '') continue;
        yield $line;
    }
    fclose($file);
}

function extractField($entry, $position, $length) {
    return trim(substr($entry, $position, $length));
}

function formatNumber($value) {
    return number_format($value);
}

function formatDateTime($dateTime) {
    $timestamp = strtotime($dateTime);
    if ($timestamp === false) return $dateTime;
    return date('D, d F Y H:i:s', $timestamp);
}
