<?php
// config files
include ('config.php');
error_reporting(E_ALL);

// header
include ('header.php');
include ('kickerLib.php');

$gameID = $_GET['id'];

$data = getGameDetails($gameID);
$date = $data[2];
//load data team 1
$player1 = $data[3];
$player1_1 = $data[4];
$nickname1 = getPlayerNick($player1);
if ($player1_1 != 0)
{
	$nickname1_1 = getPlayerNick($player1_1);	
}
//load data team 2
$player2 = $data[5];
$player2_2 = $data[6];

$nickname2 = getPlayerNick($player2);
if ($player2_2 != 0)
{
	$nickname2_2 = getPlayerNick($player2_2);	
}
$score1 =$data[0];
$score2 = $data[1];

//gimme now the output of this game
echo "<div id='main'>";
echo "<h1>Spielergebnis ($date)</h1><table><tr><td width='200px'><h3>Team1</h3><a href='player.php?id=$player1'>$nickname1</a><br>";
if ($player1_1 != 0)
{
	echo "<a href='player.php?id=$player1_1'>$nickname1_1</a>";
}
echo "</td><td>";
echo "<div class='numberbig'>$score1</div>";
echo "</td><td>";
echo "<div class='numberbig'>$score2</div>";
echo "</td><td width='200px' align='right'><h3>Team2</h3><a href='player.php?id=$player2'>$nickname2</a><br>";
if ($player2_2 != 0)
{
	echo "<a href='player.php?id=$player2_2'>$nickname2_2</a>";
}
echo "</td></tr></table><h2>weitere Duelle</h2>";

//calc a 2nd section with the same games to other times
$detailsGames = getTheSameGames($gameID);
$numberOfGames = $detailsGames[0];
$moreGames = $detailsGames[1];
echo "<table><tr><td colspan='5'>weitere Vergleiche: $numberOfGames</td></tr>";
for ($i=0;$i<sizeof($moreGames);$i++)
{
	$data = getGameDetails($moreGames[$i]);
	$player1 = $data[3];
	$player1_1 = $data[4];
	$nickname1 = getPlayerNick($player1);
	if ($player1_1 != 0)
	{
		$nickname1_1 = getPlayerNick($player1_1);	
	}
	//load data team 2
	$player2 = $data[5];
	$player2_2 = $data[6];

	$nickname2 = getPlayerNick($player2);
	if ($player2_2 != 0)
	{
		$nickname2_2 = getPlayerNick($player2_2);	
	}
	echo "<tr><td>$data[2]</td><td>Team1: <a href='player.php?id=$player1'>$nickname1</a>";
	if ($player1_1 != 0)
	{
		echo "/ <a href='player.php?id=$player1_1'>$nickname1_1</a>";
	}
	echo "</td><td><div class='number'>$data[0]</div></td><td><div class='number'>$data[1]</div></td>";
	echo "<td>Team2: <a href='player.php?id=$player2'>$nickname2</a>";
	if ($player2_2 != 0)
	{ 
		echo " / <a href='player.php?id=$player2_2'>$nickname2_2</a>";
	}
	echo "</td>";
	echo "</tr>";
}
echo "</table></div>";
// footer
include ('footer.php');
?>
