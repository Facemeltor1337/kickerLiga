<?php
// config files
include ('config.php');
error_reporting(E_ALL);

// header
include ('header.php');
include ('kickerLib.php');
//=========================
//===	rightSide	===
//=========================
echo "<div id='main'>";
echo "<h1>Statistik</h1>";
//we generate a list with playerTopRank by Ratio
$playerList = getAllPlayerID();
$playerRatio = array();
for ($i=0;$i<sizeof($playerList);$i++)
{
	$playerRatio[$playerList[$i]] = getWinRatio($playerList[$i]);
}
arsort($playerRatio);
echo "<h3>TopSpieler bei Siegquote</h3><table><tr class='tablehead'><td width='300'>Player</td><td width='100'>Siegquote</td></tr>";	
foreach ($playerRatio as $key => $val) {
    $nick = getPlayerNick($key);
    echo "<tr><td><a href='player.php?id=$key'>$nick</a></td><td>$val %</td></tr>";
}

echo "</table>";
//we generate a list with playerMostGames
$playerGames = array();
for ($i=0;$i<sizeof($playerList);$i++)
{
	$playerGames[$playerList[$i]] = getNumberOfGamesByPlayer($playerList[$i]);
}
arsort($playerGames);
echo "<h3>Meiste Spiele gesamt</h3><table><tr class='tablehead'><td width='300'>Player</td><td width='100'>Spiele</td></tr>";	
foreach ($playerGames as $key => $val) {
    $nick = getPlayerNick($key);
    echo "<tr><td><a href='player.php?id=$key'>$nick</a></td><td>$val</td></tr>";
}
echo "</table>";
//gimme now the most games as defense and offense
$playerDefense = array();
$playerOfense = array();
for ($i=0;$i<sizeof($playerList);$i++)
{
	$playerDefense[$playerList[$i]] = getAbwehrSturm($playerList[$i])[0];
	$playerOfense[$playerList[$i]] = getAbwehrSturm($playerList[$i])[1];
}
//sort the shit now
arsort($playerDefense);
arsort($playerOfense);

echo "<h3>Meiste Spiele Abwehr</h3><table><tr class='tablehead'><td width='300'>Player</td><td width='100'>Spiele Abwehr</td></tr>";	
foreach ($playerDefense as $key => $val) {
    $nick = getPlayerNick($key);
    echo "<tr><td><a href='player.php?id=$key'>$nick</a></td><td>$val</td></tr>";
}
echo "</table>";
echo "<h3>Meiste Spiele Sturm</h3><table><tr class='tablehead'><td width='300'>Player</td><td width='100'>Spiele Sturm</td></tr>";	
foreach ($playerOfense as $key => $val) {
    $nick = getPlayerNick($key);
    echo "<tr><td><a href='player.php?id=$key'>$nick</a></td><td>$val</td></tr>";
}
echo "</table>";




echo "</div>";
//=========================
//===	rightSide	===
//=========================
echo "<div id='rightSide'>";
$foo = getOverallGames();
echo "<h2>Spiele gesamt:</h2>";
for ($i=0;$i<sizeof($foo);$i++)
{
	echo "<div class='number'>$foo[$i]</div><div class='number'>$foo[$i]</div>";
}
echo "</div>";
// footer
include ('footer.php');
?>
