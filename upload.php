<?php
if (isset($_POST['submit'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;
    $fileType = pathinfo($target_file, PATHINFO_EXTENSION);

    // Check if the file is a CSV file
    if ($fileType != "csv") {
        echo "Only CSV files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            // Connection to MySQL database
            $conn = new mysqli("localhost", "root", "", "globalhealthdb");

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Process the CSV file
            $file = fopen($target_file, "r");
            while (($data = fgetcsv($file)) !== FALSE) {
                $name = $data[0];
                $email = $data[1];

                // Insert data into the database
                $sql = "INSERT INTO mytable (name, email) VALUES ('$name', '$email')";
                if ($conn->query($sql) === TRUE) {
                    echo "Record added successfully<br>";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }
            fclose($file);

            // Close the database connection
            $conn->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>CSV Upload</title>
</head>
<body>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        Select CSV file to upload:
        <input type="file" name="file" id="file">
        <input type="submit" value="Upload CSV" name="submit">
    </form>
</body>
</html>