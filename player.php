<?php
// config files
include ('config.php');
error_reporting(E_ALL);

// header
include ('header.php');
include ('kickerLib.php');
echo '<script src="res/Chart.js"></script>';

echo '<div id="rightSide"><div id="canvas-holder" align="center"><h3>Gewonnen/Verloren</h3><canvas id="chart-area" width="150" height="150"/></div>
<div id="canvas-holder2" align="center"><h3>Letzten 10 Spiele</h3><canvas id="chart-area2" width="300" height="150"/></div>
</div>';

$playerID = $_GET['id'];
$nickname = getPlayerNick($playerID);
$nrGames = getNumberOfGamesByPlayer($playerID);
$wins_looses = getWinsAndLoose($playerID);

//generate a string for last 10
$data_last10 = getLast10($playerID);
$last10 = '[';
for ($i=9;$i>-1;$i--)
{
	if ($i > 0)
	{
		if ($i < sizeof($data_last10))
		{
		$last10 = "$last10 $data_last10[$i],";
		}
		else
		{
		$last10 = "$last10 0,";
		}
	}
	else
	{
		if ($i < sizeof($data_last10))
		{
		$last10 = "$last10 $data_last10[$i]";
		}
		else
		{
		$last10 = "$last10 0";
		}
	}
}
$last10 = "$last10 ]";

//generate the plot for win and Looses now
?>
<script>window.onload = function(){var ctx = document.getElementById("chart-area").getContext("2d");window.myPie = new Chart(ctx).Pie
	([
{
	value: <?php echo $wins_looses[1]; ?>,
	color:"#F7464A",
	highlight: "#FF5A5E",
	label: "Verloren"},
{
	value: <?php echo $wins_looses[0]; ?>,
	color: "#46BFBD",
	highlight: "#5AD3D1",	
	label: "Gewonnen"}]
)
var ctx2 = document.getElementById("chart-area2").getContext("2d");
var myLineChart = new Chart(ctx2).Line(
	{
	labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"], 
	datasets: [
	    {
	    label: "My First dataset",
            fillColor: "#005f6a",
            strokeColor: "#005f6a",
            pointColor: "#005f6a",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "#005f6a",
            data: <?php echo $last10; ?>
	    }
	]
	}
);

};</script>
<?php
echo "<div id='main'>";
echo "<h2>$nickname</h2>";
echo "<h4>Spiele insgesamt: $nrGames</h4>";
$temp = getAbwehrSturm($playerID);
$abwehr = $temp[0];
$sturm = $temp[1];
echo "<h4>Abwehr/Sturm: $abwehr / $sturm</h4>";
$fav = getFavPlayer($playerID);
echo "<h4>Spielt am häufigsten in einem Team mit: $fav</h4>";
echo "</div>";
?>
