<?php

include("include/jpgraph/src/jpgraph.php");
include("include/jpgraph/src/jpgraph_line.php");
include("include/headerscript.php");

$nodeName = 'powerin';

$rows = $conn->getRows('SELECT * FROM (SELECT * FROM Events WHERE node = "' . $nodeName . '" ORDER BY EventID DESC LIMIT 250) AS tbl ORDER BY EventID');

$dataArr = array();
$timeArr = array();

$i = 0;

foreach($rows as $row) {

	if($i % 2 == 0) {

		$dataArr[] = ($row['Data'] / 1000);

		if($i % 30 == 0) {
			$timeArr[] = date_format(date_create($row['EventTime']), 'H:i:s');
		} else {
			$timeArr[] = "";
		}

	}	

	$i++;
}

//print_r($timeArr);



$graph = new Graph(1000, 250);
$graph->SetScale("textlin");

//$graph->img->SetAntiAliasing(false);
$graph->title->Set('Power Input');
$graph->SetBox(false);

//$graph->img->SetAntiAliasing();

$graph->yaxis->HideZeroLabel();
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false, false);

$graph->xgrid->Show();
$graph->xgrid->SetLineStyle("solid");
$graph->xaxis->SetTickLabels($timeArr);
$graph->xgrid->SetColor('#E3E3E3');

// Create the first line
$p1 = new LinePlot($dataArr);
$graph->Add($p1);
$p1->SetColor("#6495ED");
$p1->SetLegend('Produced Energy (kRF/t)');

$graph->legend->SetFrameWeight(1);

// Output line
$graph->Stroke();

?>
