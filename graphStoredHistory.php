<?php

include("include/jpgraph/src/jpgraph.php");
include("include/jpgraph/src/jpgraph_line.php");
include("include/headerscript.php");

$nodeName = 'powerstored';

$rows = $conn->getRows('SELECT * FROM (SELECT * FROM Events WHERE (node = "' . $nodeName . '") AND EventID % 50 = 0 ORDER BY EventID DESC) AS tbl ORDER BY EventID');

$inputArr = array();
$outputArr = array();
$timeArr = array();

foreach($rows as $row) {

        if($row['Node'] == 'powerstored') {
                $dataArr[] = ($row['Data'] / 1000000);
        } 

        if($row['DataSetIndex'] % 30 == 0) {
                $timeArr[] = date_format(date_create($row['EventTime']), 'H:i:s');
        } else {
                $timeArr[] = "";
        }

}


//print_r($timeArr);



$graph = new Graph(1000, 250);
$graph->SetScale("textlin");

$graph->title->Set('Power Storage');
$graph->SetBox(false);

$graph->yaxis->HideZeroLabel();
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false, false);
$graph->yaxis->scale->SetAutoMin(0);
//$graph->yaxis->scale->SetAutoMax(500);


$graph->xgrid->Show();
$graph->xgrid->SetLineStyle("solid");
$graph->xaxis->SetTickLabels($timeArr);
$graph->xgrid->SetColor('#E3E3E3');

// Create the first line
$p1 = new LinePlot($dataArr);
$graph->Add($p1);
$p1->SetColor("#6495ED");
//$p1->SetLegend('Stored Energy (MRF)');

//$graph->legend->SetFrameWeight(1);

// Output line
$graph->Stroke();

?>
