<?php

/**
 * Reads a file line by line and yields each non-empty line.
 *
 * @param string $filePath Path to the file to be read.
 * @return Generator Yields each non-empty line from the file.
 */
function yieldEntries($filePath) {
    $file = fopen($filePath, 'r') or die("Failed to open file!: $filePath");    
    while (($line = fgets($file)) !== false) {
        $line = trim($line);
        if ($line === '') continue;
        yield $line;
    }
    fclose($file);
}

/**
 * Extracts a substring from the given log entry, starting at a specific position
 * and of a certain length, and trims any surrounding whitespace.
 *
 * @param string $entry The entry (string) from which the substring will be extracted.
 * @param int $position The starting position to extract the substring.
 * @param int $length The length of the substring to extract.
 * @return string The extracted substring, trimmed of any whitespace.
 */
function extractField($entry, $position, $length) {
    return trim(substr($entry, $position, $length));
}

/**
 * Formats a number by adding comma separators for thousands.
 *
 * @param mixed $value The value to format.
 * @return string The formatted number with comma separators.
 */
function formatNumber($value) {
    return number_format($value);
}

/**
 * Converts a date-time string into a specific formatted string.
 * The format is 'Day, dd Month YYYY HH:mm:ss' (e.g., "Tue, 04 March 2025 00:00:00").
 *
 * @param string $dateTime The original date-time string to format.
 * @return string The formatted date-time string.
 */
function formatDateTime($dateTime) {
    $timestamp = strtotime($dateTime);
    if ($timestamp === false) return $dateTime;
    return date('D, d F Y H:i:s', $timestamp);
}
