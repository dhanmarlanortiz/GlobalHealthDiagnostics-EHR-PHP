<?php



$classBtnPrimary = "btn btn-primary btn-sm text-xs rounded normal-case h-9";
global $classBtnPrimary;
$classBtnDefault = "btn btn-default btn-sm text-xs rounded normal-case h-9";
global $classBtnDefault;


function getOrganization($id = null) {
    require("connection.php");
    
    $orgQuery = "SELECT * FROM Organization";
    $orgArray = array();

    if(null !== $id) {
        $orgQuery = "SELECT * FROM Organization WHERE id = $id";
        $orgResult = $conn->query($orgQuery);
        
        if ($orgResult !== false && $orgResult->num_rows > 0) {
            return $orgResult->fetch_assoc();
        }
    }
}

function print_pre($data = null) {
    echo "<pre>";   
    print_r($data);
    echo "</pre>";
}