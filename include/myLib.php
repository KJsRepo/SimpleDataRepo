<?php

class mysqliPlus extends mysqli {

	function getRows($query) {

		$rs = parent::query($query);

		while($row = $rs->fetch_assoc()) {
			$finalArr[] = $row;
		}

		return $finalArr;

	}


}

?>
