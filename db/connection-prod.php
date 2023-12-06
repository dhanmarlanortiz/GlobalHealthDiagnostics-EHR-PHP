<?php
$servername = "localhost";
$username = "admghd";
$password = "P@u8rkpI@PAgCrAz";
$dbname = "globalhealthdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>