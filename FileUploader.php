<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);

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

                        create_flash_message('upload-success', '<strong>Success!</strong> File has been successfully uploaded.', FLASH_SUCCESS);
                        
                        return;
                    } else {
                        create_flash_message('upload-error', '<strong>Failed!</strong> Error uploading the file.', FLASH_ERROR);
                        
                        return;
                    }
                } else {
                    create_flash_message('upload-error', '<strong>Failed!</strong> Invalid file type. Allowed types are: ' . implode(', ', $allowedTypes), FLASH_ERROR);

                    return;
                }
            } else {
                create_flash_message('upload-error', '<strong>Failed!</strong> File size exceeds the limit.', FLASH_ERROR);

                return;
            }
        }
        create_flash_message('upload-error', '<strong>Failed!</strong> No file uploaded.', FLASH_ERROR);

        return;
    }

    public function uploadSignature($fileInputName) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES[$fileInputName])) {
            $allowedTypes = array('PNG', 'png');
            $id = $_POST['prof_id'];
            $fileName = $_FILES[$fileInputName]['name'];
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $uploadFile = $this->uploadDir . "healthcare-professional-" . $id . "-signature." . $fileExtension;

            if ($_FILES[$fileInputName]['size'] <= 5000000) {
                $fileExtension = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

                // if (in_array($fileExtension, $allowedTypes)) {
                if ($fileExtension == 'png') {
                    if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $uploadFile)) {
                        create_flash_message('upload-success', '<strong>Success!</strong> File has been successfully uploaded.', FLASH_SUCCESS);
                        return;
                    } else {
                        create_flash_message('upload-error', '<strong>Failed!</strong> Error uploading the file.', FLASH_ERROR);
                        return;
                    }
                } else {
                    create_flash_message('upload-error', '<strong>Failed!</strong> Invalid file type. Allowed type is .png only.', FLASH_ERROR);
                    // create_flash_message('upload-error', '<strong>Failed!</strong> Invalid file type. Allowed types are: ' . implode(', ', $allowedTypes), FLASH_ERROR);
                    return;
                }
            } else {
                create_flash_message('upload-error', '<strong>Failed!</strong> File size exceeds the limit.', FLASH_ERROR);
                return;
            }
            
        }
    
        create_flash_message('upload-error', '<strong>Failed!</strong> No file uploaded.', FLASH_ERROR);
        return;
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
