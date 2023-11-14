<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);

// Include the FileDeleter class
require_once 'connection.php'; // Adjust the file path based on your project structure
require_once 'globals.php'; // Adjust the file path based on your project structure
require_once 'functions/flash.php'; // Adjust the file path based on your project structure

class FileDeleter {
    private $uploadDir;
    private $db;
    
    public function __construct($uploadDir, $db) {
        $this->uploadDir = $uploadDir;
        $this->db = $db;
    }

    public function deleteFile($fileName, $medicalExaminationFK, $APEFK) {
        $fileName = $this->db->real_escape_string($fileName);
        $medicalExaminationFK = $this->db->real_escape_string($medicalExaminationFK);

        // Delete file from folder
        $filePath = $this->uploadDir . $fileName;
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Delete record from database
        $sql = "DELETE FROM ResultsAPE WHERE fileName = '$fileName' AND medicalExaminationFK = '$medicalExaminationFK'";
        $this->db->query($sql);

        create_flash_message('delete-success', "<strong>Success!</strong> File has been successfully deleted. ", FLASH_SUCCESS);

        // Optionally, redirect back to the page or display a success message
        $url = base_url(false) . "/employee-APE.php?id=" . $APEFK;
        header("Location: " . $url);
        exit();
    }
}

// Assuming $db is your database connection and $uploadDir is your upload directory
$uploadDir = 'uploads/';
$fileDeleter = new FileDeleter($uploadDir, $conn);

// Check if required parameters are present in the URL
if (isset($_GET['fileName']) && isset($_GET['medicalExaminationFK']) && isset($_GET['APEFK'])) {
    // Get the file name, medicalExaminationFK, and APEFK from the URL
    $fileName = $_GET['fileName'];
    $medicalExaminationFK = $_GET['medicalExaminationFK'];
    $APEFK = $_GET['APEFK'];

    // Delete the file
    $fileDeleter->deleteFile($fileName, $medicalExaminationFK, $APEFK);
} else {
    create_flash_message('delete-error', "<strong>Failed!</strong> An error occurred while deleting the file. ", FLASH_ERROR);

    $url = base_url(false) . "/employee-APE.php?id=" . $APEFK;
    header("Location: " . $url);
    exit();
}
?>
