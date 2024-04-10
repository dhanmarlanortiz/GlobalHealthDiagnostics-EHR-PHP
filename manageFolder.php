<?php

class ManageFolder {
    function __construct() {

    }

    function mkdir($path) {
        if (!mkdir($path, 0777, true)) {
            echo 'Create folder failed';
        } else {
            echo 'Create folder success';
        }
    }

    function rmdir($path) {
        
        if (!rmdir($path)) {
            echo 'Delete folder failed';
        } else {
            echo 'Delete folder success';
        }
    }
}


$manageFolder = new ManageFolder;
// $manageFolder->mkdir("uploads/test/1.php");
// $manageFolder->rmdir("uploads/test");
// mkdir("/reports/" . $name . " APE" . date("Y"), 0777);

