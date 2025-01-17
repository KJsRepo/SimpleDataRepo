<?php

include("include/headerscript.php");

if(!empty($_POST)) {
	$inputVars = $_POST;
} else {
	$inputVars = $_GET;
}

if(@$inputVars['process_name'] == NULL) {
	$inputVars['process_name'] = '';
}


$inputVars['allow_duplicates'] = 1;

#  allow_duplicates: Allow duplicates even if they haven't been cleared.  Pretty much just means don't bother checking.

# allow_cleared_duplicates:  Allow duplicates only if they have been cleared.  If the light was turned off by Anna at 8pm, and we wanted to tell Anna to do this again at 9pm.  DON'T PUT the same job in the list twice if it wasn't done the first time.  Check the last row for that repo and node, if the data matches, don't write the file.

# If we aren't going to allow duplicates, we must check for duplicates
if(@$inputVars['allow_duplicates'] != 1) {

		
	# If we're allowed to write cleared duplicates, we look at the last instance of this repo-node,
	# and if it has the same data AND has been cleared, then it's not considered a duplicate.  If the data matches and it hasn't been cleared

	# Get the status anyway

	$sql = 'SELECT * FROM Events WHERE Repo = ?
				AND Node = ?
				ORDER BY EventID DESC LIMIT 1';


	$stmt = $conn->prepare($sql);
	$stmt->bind_param("ss", $inputVars['repo'], $inputVars['node']);
	$stmt->execute();
	$result = $stmt->get_result();

	$row = $result->fetch_array(MYSQLI_ASSOC);

	if(@$inputVars['allow_cleared_duplicates'] == 1) {

		# If we're allowed to write cleared duplicates, then we only block where the data is the same 
		# AND it hasn't been cleared
		if($row['Data'] == @$inputVars['data']
			&& $row['Cleared_By'] == '') {
			
			$is_duplicate = 1;
		}	

	} else {
	
		echo($row['Data'] . '###' . @$inputVars['data']);

		if($row['Data'] == @$inputVars['data']) {
			$is_duplicate = 1;
		}	

	}

}

//  Adjust strings for Google Assistant input (which puts spaces before and after some punctuation)
if($inputVars['process_name'] == 'google_assistant') {
	$inputVars['data'] = str_replace(" ' ", "'", $inputVars['data']);
	$inputVars['data'] = str_replace(" - ", "-", $inputVars['data']);
}

if(!$is_duplicate || @$inputVars['allow_duplicates']) {
	
	$stmt = $conn->prepare('INSERT INTO Events (Repo, User, Node, Data, DataSetIndex, Process_Name) VALUES (?,?,?,?,?,?)');
	$stmt->bind_param("ssssis", $inputVars['repo'], $inputVars['user'], $inputVars['node'], $inputVars['data'], $inputVars['dataset'], $inputVars['process_name']);
	$success = $stmt->execute();

	if($success) {
		echo('success');
	} else {
		echo('failure: ' . $stmt->error);
	}
} else {
	echo('failure: is duplicate');
}

?>
