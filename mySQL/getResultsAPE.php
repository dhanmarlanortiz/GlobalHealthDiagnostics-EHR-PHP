<?php
$sql = "SELECT * FROM ResultsAPE WHERE medicalExaminationFK = '$medicalExaminationFK'";
$result = $conn->query($orgQuery);