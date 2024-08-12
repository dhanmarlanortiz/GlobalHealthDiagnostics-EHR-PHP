<?php
class CSVImporter
{
    private $conn;
    private $successfulInserts;
    private $failedInserts;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->successfulInserts = [];
        $this->failedInserts = [];
    }

    public function importCSV($file, $o)
    {
        if (!empty($file['name']) && pathinfo($file['name'], PATHINFO_EXTENSION) === 'csv') {

            if (is_uploaded_file($file['tmp_name'])) {
                $csvFile = fopen($file['tmp_name'], 'r');

                fgetcsv($csvFile);

                while (($line = fgetcsv($csvFile)) !== FALSE) {
                    // Use the processCSVLine result to update successful and failed inserts lists
                    $result = $this->processCSVLine($line, $o);
                    if ($result) {
                        $this->successfulInserts[] = $line;
                    } else {
                        $this->failedInserts[] = $line;
                    }
                }

                fclose($csvFile);
                return true; // Return true only if all lines are successfully processed
            }
        }

        return false;
    }

    private function processCSVLine($line, $o)
    {
        $firstName = $line[0];
        $middleName = $line[1];
        $lastName = $line[2];
        $age = $line[3];
        $sex = $line[4];
        $civilStatus = $line[5];
        $homeAddress = $line[6];
        $employeeNumber = $line[7];
        $remarks = $line[8];

        // Check if dateRegistered is null, and set it to the current date
        // if ($dateRegistered === null) {
            $dateRegistered = date("Y-m-d");
        // }

        $prevQuery = "SELECT id FROM APE WHERE 
                        firstName = ? AND 
                        middleName = ? AND 
                        lastName = ? AND
                        organizationId = ? AND
                        dateRegistered = ? ";

        $prevStmt = $this->conn->prepare($prevQuery);
        $prevStmt->bind_param("sssis", $firstName, $middleName, $lastName, $o, $dateRegistered);
        $prevStmt->execute();
        $prevResult = $prevStmt->get_result();

        $PrevId = 0;
        if ($prevResult->num_rows > 0) {
            try {
                $prevData = $prevResult->fetch_assoc();
                $PrevId = $prevData['id'];

                $updateQuery = "UPDATE APE SET
                        age = ?,
                        sex = ?,
                        civilStatus = ?,
                        homeAddress = ?,
                        employeeNumber = ?,
                        remarks = ?
                        WHERE id = ?";

                $updateStmt = $this->conn->prepare($updateQuery);
                $updateStmt->bind_param("ssssssi", $age, $sex, $civilStatus, $homeAddress, $employeeNumber, $remarks, $PrevId);
                $result = $updateStmt->execute();
                $updateStmt->close();
            } catch (mysqli_sql_exception $e) {
                create_flash_message('import-failed', '<strong>Import Failed!</strong> Please review and try again.', FLASH_ERROR);
            }
        } else {
            try {
                $insertQuery = "INSERT INTO APE (
                    headCount,
                    firstName,
                    middleName,
                    lastName,
                    age,
                    sex,
                    civilStatus,
                    homeAddress,
                    employeeNumber,
                    remarks,
                    dateRegistered,
                    organizationId,
                    userId
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                )";
                
                $headCount = getHeadCountAPE($o, date("Y", strtotime($dateRegistered)));
                $insertStmt = $this->conn->prepare($insertQuery);
                $insertStmt->bind_param("issssssssssii", $headCount, $firstName, $middleName, $lastName, $age, $sex, $civilStatus, $homeAddress, $employeeNumber, $remarks, $dateRegistered, $o, $_SESSION['userId']);
                $result = $insertStmt->execute();
                $insertStmt->close();
            } catch (mysqli_sql_exception $e) {
                create_flash_message('import-failed', '<strong>Import Failed!</strong> Please review and try again.', FLASH_ERROR);                
            }
        }

        // Return true if the query is successful, otherwise false
        return $result;
    }

    public function getSuccessfulInserts()
    {
        return $this->successfulInserts;
    }

    public function getFailedInserts()
    {
        return $this->failedInserts;
    }
}