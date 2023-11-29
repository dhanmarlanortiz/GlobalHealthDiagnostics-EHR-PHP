<?php
ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../connection.php');
require ('../functions/flash.php');

function base_url($print = true) {
    $url = ($_SERVER['HTTP_HOST'] == 'app.globalhealth-diagnostics.com') ? "https://app.globalhealth-diagnostics.com" : "http://localhost/globalhealth-php";
    if($print === true) {
        echo $url;
    } else {
        return $url;
    }
}

function getControlNumber() {
    require('../connection.php');
    $controlNumber = 1;

    $sql = "SELECT MAX(controlNumber) FROM APE WHERE controlDate = '" . date('Y-m-d') . "'";
    $result = $conn->query($sql);

    if ($result !== false && $result->num_rows > 0) {
        while($hc = $result->fetch_assoc()) {
            $controlNumber = $hc['MAX(controlNumber)'] + 1;
        }
    }
    
    $conn->close();
    return $controlNumber;
}

function checkControlNumber($id) {
    require('../connection.php');
    $controlNumber = 0;

    $sql = "SELECT controlNumber FROM APE WHERE id = $id";
    $result = $conn->query($sql);

    if ($result !== false && $result->num_rows > 0) {
        while($hc = $result->fetch_assoc()) {
            $controlNumber = $hc['controlNumber'];
        }
    }

    $conn->close();
    return $controlNumber;
}

function setControlNumber($id, $controlNumber) {
    require('../connection.php');

    $sql = "UPDATE APE SET controlNumber = $controlNumber, controlDate = '" . date('Y-m-d') . "' WHERE id = $id";
    $result = $conn->query($sql);
    
    if ($conn->query($result) === TRUE) {
        create_flash_message('generate-control-number-success', '<strong>Success!</strong> Control number has been generated.', FLASH_SUCCESS);
    } else {
        create_flash_message('generate-control-number-error', '<strong>FAILED!</strong> An error occurred while generating control number.', FLASH_ERROR);
    }

    $conn->close();
}

if( null !== $_GET['id'] ) {
    $id = $_GET['id'];

    $controlNumber = (null !== checkControlNumber($id) ) ? checkControlNumber($id) : getControlNumber();
    setControlNumber($id, $controlNumber);

    $url = base_url(false) . "/employee-APE.php?id=" . $id;
    header("Location: " . $url ."");
} else {

    $url = base_url(false) . "/employees-APE.php";    
    header("Location: " . $url ."");
}



?>