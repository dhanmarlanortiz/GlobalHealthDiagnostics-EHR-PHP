<?php

class FileUploader {
    private $uploadDir;
    private $db;

    public function __construct($uploadDir, $db) {
        $this->uploadDir = $uploadDir;
        $this->db = $db;
    }

    public function uploadFile($fileInputName, $medicalExaminationFK, $allowedTypes) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES[$fileInputName])) {
            $originalFileName = $_FILES[$fileInputName]['name'];
            $sanitizedFileName = $this->sanitizeFileName($originalFileName);
    
            // Generate a unique filename
            $uniqueFileName = $this->generateUniqueFileName($sanitizedFileName);
            $uploadFile = $this->uploadDir . $uniqueFileName;
    
            if ($_FILES[$fileInputName]['size'] <= 5000000) {
                $fileExtension = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
    
                if (in_array($fileExtension, $allowedTypes)) {
                    if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $uploadFile)) {
    
                        $this->storeInDatabase($uniqueFileName, $medicalExaminationFK);
    
                        return 'File is valid, and was successfully uploaded and stored in the database.';
                    } else {
                        return 'Error uploading the file.';
                    }
                } else {
                    return 'Invalid file type. Allowed types are: ' . implode(', ', $allowedTypes);
                }
            } else {
                return 'File size exceeds the limit.';
            }
        }
    
        return 'No file uploaded.';
    }
    
    private function generateUniqueFileName($fileName) {
        $uniqueSuffix = '_' . time(); // You can use other methods to generate a unique suffix
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $baseName = pathinfo($fileName, PATHINFO_FILENAME);
        $uniqueFileName = $baseName . $uniqueSuffix . '.' . $fileExtension;
    
        return $uniqueFileName;
    }
    

    private function sanitizeFileName($fileName) {
        $fileName = str_replace(' ', '-', $fileName); // Replace spaces with "-"
        $fileName = preg_replace('/[^A-Za-z0-9\-\.]/', '', $fileName); // Remove special characters
        return $fileName;
    }

    private function storeInDatabase($fileName, $medicalExaminationFK) {
        if (!$this->db) {
            return; 
        }

        $medicalExaminationFK = $this->db->real_escape_string($medicalExaminationFK);
        $APEFK = $this->db->real_escape_string($_GET['id']);
        $userFK = $this->db->real_escape_string($_SESSION['userId']);
        $fileName = $this->db->real_escape_string($fileName);

        $sql = "INSERT INTO ResultsAPE (medicalExaminationFK, APEFK, userFK, fileName) VALUES ('$medicalExaminationFK', '$APEFK', '$userFK', '$fileName')";
        $this->db->query($sql);
    }
}

?>
