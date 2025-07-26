<?php

/**
 * Extracts a substring(field) from the given entry based on position and length.
 *
 * @param string $entry The raw log line from which the field will be extracted.
 * @param int $position The zero-based starting position to extract the field.
 * @param int $length The length of the field to extract.
 * @return string The extracted substring, trimmed of any whitespace.
 */
function extractField (
    string $entry, 
    int $position, 
    int $length) : string {
    return trim(substr(
        $entry, 
        $position, 
        $length)
    );
}

/**
 * Formats a numeric value by inserting comma separators for thousands.
 *
 * @param string $value Numeric string (e.g., '1000').
 * @return string The formatted number with comma separators (e.g., '1,000').
 */
function formatNumber(string $value): string {
    return number_format((int) $value);
}

/**
 * Converts a date-time string into a specific formatted string.
 * The format is 'Day, dd Month YYYY HH:mm:ss' (e.g., "Tue, 04 March 2025 00:00:00").
 *
 * @param string $dateTime Raw datetime string (e.g., '2025-03-04 00:00:00').
 * @return string Formatted datetime (e.g., 'Tue, 04 March 2025 00:00:00').
 */
function formatDateTime(string $dateTime): string {
    $timestamp = strtotime($dateTime);
    if ($timestamp === false) return $dateTime;
    return date(
        'D, d F Y H:i:s', 
        $timestamp
    );
}

/**
 * Applies formatting to a value based on its declared type.
 * 
 * @param string $value The raw extracted value.
 * @param string $format The format of the value (eg., 'number', 'date').
 * @return string Formatted value.
 */
function formatValue(string $value, string $format) {
    switch ($format) {
        case 'number':  return formatNumber($value);
        case 'date':    return formatDateTime($value);
        default:        return $value;
    }
}

/**
 * Sorts and returns a natural-order sorted list of IDs.
 *
 * @param array $ids List of IDs to sort.
 * @return array Sorted list of IDs.
 */
function formatSortedIds(array $ids): array {
    natsort($ids);
    return array_values($ids);
}

/**
 * Returns a formatted list of user IDs with numbering.
 *
 * @param array $userIds List of user IDs.
 * @return array Formatted list (eg., ["[1] ABC123", "[2] DEF456"]). 
 */
function formatUserList(array $userIds): array {
    sort($userIds);
    $output = [];
    foreach ($userIds as $index => $userId) {
        $output[] = "[" . ($index + 1) . "] " . $userId;
    }
    return $output;
}
