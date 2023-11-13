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
            $uploadFile = $this->uploadDir . basename($_FILES[$fileInputName]['name']);
            
            if ($_FILES[$fileInputName]['size'] <= 5000000) { 

                $fileExtension = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

                if (in_array($fileExtension, $allowedTypes)) {
                    if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $uploadFile)) {
            
                        $this->storeInDatabase($_FILES[$fileInputName]['name'], $medicalExaminationFK);

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
