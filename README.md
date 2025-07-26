# Solution to the PHP Coding Challenge
My 3-step solution to the coding challenge.


## Step 1: Define field specifications
The field layout and formatting rules are defined in `dataProperties.php` using an associative array.

Each field includes:
- `position`: 1-based start index in the log line
- `length`: number of characters to extract
- `format`: how the value should be processed
                                         
```php
...

"dateTime" => [
    "position" => 35,
    "length"   => 17,
    "format"   => "date",
],
"id" => array(
    "position" => 1,
    "length"   => 12,
    "format"   => "text",
),
```
>[!NOTE]
> The field order in this array determines the order of output. In this case, `id` is intentionally listed last to match the required pipe-delimited format.

## Step 2: Helper functions
The helper functions in `helpers.php` handle extraction and formatting:
- `extractField()`: Extracts a fixed-width substring from a log line.

- `formatNumber()`: Formats numeric values with thousands separators.

- `formatDateTime()`: Converts raw timestamps to a human-readable format.

- `formatValue()`: Centralized dispatcher for formatting values based on their type.

- `formatSortedIDs()`/ `formatUserList()`: Sort and format final outputs.


## Step 3: Parse and format the Log
The main logic is implemented in `parseLogEntries()` in `logParser.php` and `main.php`:

### Stream and parse log entries
```php
foreach ( parseLogEntries (
  $filePath, 
  $dataProperties, 
  $ids, 
  $uniqueUserIds) 
  as $formattedEntry) {
    $outputLines[] = $formattedEntry;
}
```
Each log entry line is:
- Sliced according to the positions in dataProperties
- Formatted according to its type
- Yielded as a pipe-delimited string

The function also updates $uniqueUserIDs and $ids by reference.

### Output generation
After all entries are parsed, and all `ids` and `uniqueUserIds` are sorted, the final output is generated.

#### Section 1: Pipe delimited version of the log.
Pipe-delimited entries are written first:
```yaml
GITB|660,428|424,450|Tue, 10 September 2019 06:05:00|58QV-Q26X
LBCA|476,255|413,615|Tue, 10 September 2019 06:09:00|278NV-Y69K
CHAI|955,937|669,285|Tue, 10 September 2019 06:15:00|665PP-G26P
JOVB|287,303|45,136|Tue, 10 September 2019 06:20:00|455YS-Y87A
```

#### Section 2: Sorted IDs in ascending order
IDs are sorted using `natsort()` for natural order:
```yaml
9WR-L57J
9YY-R97L
10DE-Z54C
10DR-V9C
```

#### Section 3: Sorted Unique User IDs in ascending order
User IDs are sorted and numbered:
```yaml
[1] AALP
[2] ABCM
[3] ABRB
[4] ABYW
```

#### Final output
The output is written in `output.txt` as structured sections: 

```yaml
GITB|660,428|424,450|Tue, 10 September 2019 06:05:00|58QV-Q26X
LBCA|476,255|413,615|Tue, 10 September 2019 06:09:00|278NV-Y69K
CHAI|955,937|669,285|Tue, 10 September 2019 06:15:00|665PP-G26P
JOVB|287,303|45,136|Tue, 10 September 2019 06:20:00|455YS-Y87A
...

9WR-L57J
9YY-R97L
10DE-Z54C
10DR-V9C
...

[1] AALP
[2] ABCM
[3] ABRB
[4] ABYW
...
```
