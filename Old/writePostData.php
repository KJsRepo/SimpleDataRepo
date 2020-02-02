<?php

include("include/headerscript.php");

$rows = $conn->getRows("SELECT * FROM Events");

$inputVars = $_POST;

$stmt = $conn->prepare('INSERT INTO Events (Repo, User, Node, Data, DataSetIndex) VALUES (?,?,?,?,?)');
$stmt->bind_param("ssssi", $inputVars['repo'], $inputVars['user'], $inputVars['node'], $inputVars['data'], $inputVars['dataset']);
$success = $stmt->execute();

if($success) {
	echo('success');
} else {
	echo('lol');
}

?>
