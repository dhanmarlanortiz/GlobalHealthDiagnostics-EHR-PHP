<?php



$classBtnPrimary = "btn btn-primary btn-sm text-xs rounded normal-case h-9";
global $classBtnPrimary;

$classTblBtnPrimary = "btn btn-primary btn-sm text-xs rounded normal-case font-normal";
global $classTblBtnPrimary;

$classTblBtnSecondary = "btn btn-secondary btn-sm text-xs rounded normal-case font-normal";
global $classTblBtnSecondary;

$classBtnDefault = "btn btn-default btn-sm text-xs rounded normal-case h-9";
global $classBtnDefault;

$classMainContainer = "mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8";
global $classMainContainer;

function main_open() {

}

function main_close() {
    
}

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