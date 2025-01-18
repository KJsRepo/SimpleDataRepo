<?php

include("include/headerscript.php");

if(!empty($_POST)) {
	$inputVars = $_POST;
} else {
	$inputVars = $_GET;
}

$stmt = $conn->prepare('UPDATE Events SET Cleared_By = CONCAT(Cleared_By, ",", ?) WHERE EventID = ?');
$stmt->bind_param("si", $inputVars['process_name'], $inputVars['id']);
$success = $stmt->execute();

if($success) {
	echo('success');
} else {
	echo('failure: ' . $stmt->error);
}

?>
