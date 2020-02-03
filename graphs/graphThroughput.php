<?php

include("include/jpgraph/src/jpgraph.php");
include("include/jpgraph/src/jpgraph_line.php");
include("include/headerscript.php");

$rows = $conn->getRows('SELECT * FROM (SELECT * FROM Events WHERE (node = "powerin" OR node = "powerout") AND DataSetIndex % 2 = 0 ORDER BY EventID DESC LIMIT 401) AS tbl ORDER BY EventID');

$inputArr = array();
$outputArr = array();
$timeArr = array();

foreach($rows as $row) {

	if($row['Node'] == 'powerin') {
		$inputArr[] = ($row['Data'] / 1000);
	} elseif ($row['Node'] == 'powerout') {
		$outputArr[] = ($row['Data'] / 1000);
	}

	if($row['DataSetIndex'] % 30 == 0) {
		$timeArr[] = date_format(date_create($row['EventTime']), 'H:i:s');
	} else {
		$timeArr[] = "";
	}

}

//print_r($outputArr);die();



$graph = new Graph(1000, 250);
$graph->SetScale("textlin");

//$graph->img->SetAntiAliasing(false);
$graph->title->Set('Power Throughput');
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
$p1 = new LinePlot($inputArr);
$graph->Add($p1);
$p1->SetColor("#6495ED");


$p2 = new LinePlot($outputArr);
$graph->Add($p2);
$p2->SetColor("#ED9564");


//$p1->SetLegend('Produced Energy (kRF/t)');
//$graph->legend->SetFrameWeight(1);

// Output line
$graph->Stroke();

?>
