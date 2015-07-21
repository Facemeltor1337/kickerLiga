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
$avatar = checkAvatar($nickname);
echo "<div id='main'>";
echo "<div id='ava'><img width='90' src='img/avatare/$avatar'></div>";
echo "<h1>Spieler: $nickname</h1>";
echo "<h4>Spielstatistik</h4><table><tr><td width='100px'></td><td width='100px'>Gesamt</td><td width='100px'>Abwehr</td><td width='100px'>Sturm</td></tr>";
$temp = getAbwehrSturm($playerID);
$ratio = getWinRatioForArray($temp);
$gesamt_niederlagen = $temp[0][2] + $temp[1][2];
$gesamt_siege = $temp[0][1] + $temp[1][1];
$abwehr = $temp[0][0];
$abwehr_niederlage = $temp[0][2];
$abwehr_sieg = $temp[0][1];
$sturm = $temp[1][0];
$sturm_niederlage = $temp[1][2];
$sturm_sieg = $temp[1][1];
echo "<tr><td>Spiele</td><td>$nrGames</td><td>$abwehr</td><td>$sturm</td></tr>";
echo "<tr><td>Spieleverh&auml;ltnis</td><td>$gesamt_siege / $gesamt_niederlagen</td><td> $abwehr_sieg / $abwehr_niederlage </td><td>$sturm_sieg / $sturm_niederlage </td></tr>";
echo "<tr><td>Spiegquote</td><td>$ratio[0] %</td><td>$ratio[1] %</td><td>$ratio[2] %</td></tr>";
echo "</table>";
echo "<h3>Team-Statistiken</h3>";
echo "<h4>Spielt am meisten mit</h4>";
//now lets print the teampartners
$teams = getTeams();
echo "<table><tr><td width='150px'>Spielpartner</td><td width='100px'>Spiele</td></tr>";
$temp_dict = array();
$temp_ratio = array();
for($i=0;$i<sizeof($teams);$i++)
{
	if($teams[$i][0] == $playerID)
	{
		$partner_id = $teams[$i][1];
		$temp_dict[$partner_id] = $teams[$i][2];
		$temp_ratio[$partner_id] = $teams[$i][3] / $teams[$i][2] * 100;
	}
	if ($teams[$i][1] == $playerID)
	{
		$partner_id = $teams[$i][0];
		$temp_dict[$partner_id] = $teams[$i][2];
		$temp_ratio[$partner_id] = $teams[$i][3] / $teams[$i][2] * 100;
	}	
}

arsort($temp_dict);
arsort($temp_ratio);
foreach ($temp_dict as $key => $val) {
	$partner = getPlayerNick($key);
	echo "<tr><td><a href='player.php?id=$key'>$partner</a></td><td>$val</td></tr>";
}
echo "</table>";
echo "<h4>Spielt am besten mit</h4>";
echo "<table><tr><td width='150px'>Spielpartner</td><td width='100px'>Siegquote</td></tr>";
foreach ($temp_ratio as $key => $val) {
	$partner = getPlayerNick($key);
	$ratio = round($val, 2);
	echo "<tr><td><a href='player.php?id=$key'>$partner</a></td><td>$ratio %</td></tr>";
}
echo "</table>";

echo "<h4>Tor-Statistik</h4>";
echo "<table><tr><td width='100px'></td><td width='100px'>Tore</td><td width='100px'>Gegentore</td></tr>";
echo "<tr><td>Anzahl</td><td>$wins_looses[2]</td><td>$wins_looses[3]</td></tr>"; 
$toreGame = $wins_looses[2] / $nrGames;
$toreGame = round($toreGame, 2);
$gegentoreGame = $wins_looses[3] / $nrGames;
$gegentoreGame = round($gegentoreGame, 2);
echo "<tr><td>pro Spiel</td><td>$toreGame</td><td>$gegentoreGame</td></tr>";
echo "</table>";
echo "</div>";
// footer
include ('footer.php');
?>
