<?php

//  Expects at least one condition in user, repo, node, or data.
//  Can also match data against a condition with comparison operator
//  Order can be passed as "DESC" to retrieve last row
//  'since' can be passed as anything PHP's DateTime class recognizes

include("include/headerscript.php");

if(!empty($_POST)) {
	$inputVars = $_POST;
} else {
	$inputVars = $_GET;
}

$conditions = array();

if(@$inputVars['user'] != '') $conditions[] = ' User = "' . $inputVars['user'] . '"';
if(@$inputVars['repo'] != '') $conditions[] = ' Repo = "' . $inputVars['repo'] . '"';
if(@$inputVars['node'] != '') $conditions[] = ' Node = "' . $inputVars['node'] . '"';
if(@$inputVars['process_name'] != '') $conditions[] = ' Process_Name = "' . $inputVars['process_name'] . '"';

if(@$inputVars['date'] != '') $conditions[] = ' EventTime LIKE "' . $inputVars['date'] . '%"';

if(@$inputVars['since'] != '') {
	$sinceDate = new DateTime($inputVars['since']);
	$dateStr = $sinceDate->format('Y-m-d H:i:s');
	$conditions[] = ' EventTime > "' . $dateStr . '" ';
}

if(@$inputVars['data'] != '') {

	if(@$inputVars['comparison'] == '') $inputVars['comparison'] = '=';

	if($inputVars['comparison'] != '<' &&
			$inputVars['comparison'] != '>' &&
			$inputVars['comparison'] != '=' &&
			$inputVars['comparison'] != 'LIKE') {

		echo 'illegal operator';
		die();
	}

	if(is_numeric($inputVars['data'])) {
		$conditions[] = ' Data ' . $inputVars['comparison'] . $inputVars['data'];
	} else {
		$conditions[] = ' data ' . $inputVars['comparison'] . '"' . $inputVars['data'] . '"';
	}
}


// Add the Cleared_By, unless it's a status call
if(@$inputVars['mode'] != 'status') {
	if(@$inputVars['ignore_cleared_by'] != '') {

		switch ($inputVars['ignore_cleared_by']) {

			case 'anyone':
				$conditions[] = ' LEN(Cleared_By) = 0 ';
				break;

			default:
				$conditions[] = ' (Cleared_By NOT LIKE "%' . $inputVars['ignore_cleared_by'] . '%") ';
				break;

		} 
	}
}


$conditionStr = implode(' AND ', $conditions);
$qry = 'SELECT * FROM Events WHERE ' . $conditionStr;

if(@$inputVars['mode'] == 'status') {
	$inputVars['order'] = 'DESC';
}

if(@$inputVars['order'] != '') $qry .= ' ORDER BY EventID ' . $inputVars['order'];

$qry .= ' LIMIT 1 ';

$result = $conn->query($qry);
$row = mysqli_fetch_assoc($result);

if(stripos($row['Cleared_By'], @$inputVars['ignore_cleared_by']) !== false) {
	$row = false;
}

if($row != false) {
	echo(json_encode($row));
} else {
	echo(json_encode(array('Data' => '')));
}

?>
