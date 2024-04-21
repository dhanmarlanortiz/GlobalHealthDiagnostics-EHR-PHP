<?php
require_once('connection.php');

// Query to retrieve data from your table
$query = "SELECT * FROM your_table";
$result = mysqli_query($connection, $query);

// Check if query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}

// File to export data
$filename = 'data.csv';

// Open file pointer
$file = fopen($filename, 'w');

// Write column headers to CSV file
$fields = mysqli_fetch_fields($result);
$column_headers = [];
foreach ($fields as $field) {
    $column_headers[] = $field->name;
}
fputcsv($file, $column_headers);

// Write data rows to CSV file
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($file, $row);
}

// Close file pointer
fclose($file);

// Close MySQL connection
mysqli_close($connection);

echo "Data exported to $filename successfully!";
?>
