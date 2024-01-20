<?php
require_once('../connection.php');

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sample MySQL query
$query = "SELECT * FROM your_table"; // Replace with your actual table name

// Execute the query
$result = $conn->query($query);

// Check for errors
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Fetch data from result set
$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Free result set
$result->free_result();

// Close the database connection
$conn->close();

// Convert the PHP array to JSON
$json_data = json_encode($data);

// Output the JSON
header('Content-Type: application/json');
echo $json_data;
?>