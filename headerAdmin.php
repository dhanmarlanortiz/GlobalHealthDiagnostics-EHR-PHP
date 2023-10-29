<?php 

if(! isset($_SESSION["valid"])){
    header("location:login.php");
    exit();
}