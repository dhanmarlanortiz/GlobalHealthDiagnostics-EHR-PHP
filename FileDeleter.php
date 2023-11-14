<?php
class FileDeleter {
    private $uploadDir;
    private $db;
    
    public function __construct($uploadDir, $db) {
        $this->uploadDir = $uploadDir;
        $this->db = $db;
    }

    public function deleteFile($fileName, $medicalExaminationFK) {
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
    }
}

?>
