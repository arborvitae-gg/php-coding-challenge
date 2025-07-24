# Solution to the PHP Coding Challenge
My 3-step solution to the coding challenge.


## Step 1: Define field specifications
In `dataProperties.php`, we first define the structure of each field as a multi-dimensional associative array. 

Each field includes:
- `position`: TStarting index in the log line (1-based)
- `length`: Number of characters to extract
- `format`: How the value should be processed
                                         
```php
$dataProperties = array(

    "userId" => array(
        "position" => 13,
        "length"   => 6,
        "format"   => "text",
    ),

    "bytesTX" => array(
        "position" => 19,
        "length"   => 8,
        "format"   => "number",
    ),

    "bytesRX" => array(
        "position" => 27,
        "length"   => 8,
        "format"   => "number",
    ),

    "dateTime" => array(
        "position" => 35,
        "length"   => 17,
        "format"   => "date",
    ),

    "id" => array(
        "position" => 1,
        "length"   => 12,
        "format"   => "text",
    ),
);
```
>!NOTE
> The `id` field is placed last in the array to match the required pipe-delimited output order: `<UserID>|<BytesTX|<BytesRX|<DateTime>|<ID>`

## Step 2: Helper functions
These helper functions are used to extract and format data from the log file.
>!NOTE
> The code provided here is a simplified version of the actual code used in the challenge.
#

`yieldEntries($filepath)` 
Efficiently reads the input file line by line using a generator.

```php
function yieldEntries($filePath) {
    $file = fopen($filePath, 'r');    
    while (($line = fgets($file)) !== false) {
        yield $line;
    }
    fclose($file);
}
```

> [!TIP]
> Using a generator allows the file to be processed line by line without loading the entire file into memory, reducing memory usage and improving performance.

##

`extractField()` 
Extracts a field entry by position and length, then trims whitespace.
```php
function extractField($entry, $position, $length) {
    return trim(substr($entry, $position, $length));
}
```

##

`formatNumber()` 
formats a number by adding comma separators for thousands.
```php
function formatNumber($value) {
    return number_format($value);
}
```

##

`formatDateTime()` converts a date-time string into the following format: `Day, dd Month YYYY HH:mm:ss`
```php
function formatDateTime($dateTime) {
    $timestamp = strtotime($dateTime);
    if ($timestamp === false) return $dateTime;
    return date('D, d F Y H:i:s', $timestamp);
}
```

## Step 3: Solution Program Flow

### Initialization and setup
```php
require 'dataProperties.php';
require 'helpers.php';

$filePath = 'sample-log.txt';
$outputPath = 'output.txt';

$logEntries = [];
$uniqueUserIDs = [];
$outputLines = [];
```

### Parse and format log entries
```php
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
```

###  Sort and extract IDs and Unique User IDs
```php
$ids = array_column($logEntries, 'id');
natsort($ids);
$ids = array_values($ids);

$userIDs = array_keys($uniqueUserIDs);
sort($userIDs);
$userIDs = array_values($userIDs); 
```

### Generate output
```php
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
```

### Write output to file
```php
file_put_contents($outputPath, implode(PHP_EOL, $outputLines));
echo "Output written to: $outputPath\n";
```