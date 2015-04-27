<?php
// config files
include ('config.php');
error_reporting(E_ALL);

// header
include ('header.php');
include ('kickerLib.php');
?>
<script src="res/tabs_old.js"></script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-1332079-8']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<?php
//=========================
//===	rightSide	===
//=========================
echo "<div id='main'>";
echo "<h1>Statistik</h1>";
//oben the tabs now
echo '  <div id="tabContainer">
    <div id="tabs">
      <ul>
        <li id="tabHeader_1">Gesamt</li>
        <li id="tabHeader_2">Abwehr</li>
        <li id="tabHeader_3">Sturm</li>     
      </ul>
    </div><div id="tabscontent">';

//we generate a list with playerTopRank by Ratio
$playerList = getAllPlayerID();
$playerRatio = array();
$playerRatioA = array();
$playerRatioS = array();
for ($i=0;$i<sizeof($playerList);$i++)
{
	$playerRatio[$playerList[$i]] = getWinRatio($playerList[$i])[0];
	$playerRatioA[$playerList[$i]] = getWinRatio($playerList[$i])[1];
	$playerRatioS[$playerList[$i]] = getWinRatio($playerList[$i])[2];
}
arsort($playerRatio);
echo '<div class="tabpage" id="tabpage_1">';
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
echo "</table></div>";
//gimme now the most games as defense and offense
$playerDefense = array();
$playerOfense = array();
for ($i=0;$i<sizeof($playerList);$i++)
{
	$playerDefense[$playerList[$i]] = getAbwehrSturm($playerList[$i])[0][0];
	$playerOfense[$playerList[$i]] = getAbwehrSturm($playerList[$i])[1][0];
}
//sort the shit now
arsort($playerDefense);
arsort($playerOfense);
arsort($playerRatioA);
arsort($playerRatioS);
echo '<div class="tabpage" id="tabpage_2">';
echo "<h3>TopSpieler bei Siegquote</h3><table><tr class='tablehead'><td width='300'>Player</td><td width='100'>Siegquote</td></tr>";	
foreach ($playerRatioA as $key => $val) {
    $nick = getPlayerNick($key);
    echo "<tr><td><a href='player.php?id=$key'>$nick</a></td><td>$val %</td></tr>";
}
echo "</table>";
echo "<h3>Meiste Spiele Abwehr</h3><table><tr class='tablehead'><td width='300'>Player</td><td width='100'>Spiele Abwehr</td></tr>";	
foreach ($playerDefense as $key => $val) {
    $nick = getPlayerNick($key);
    echo "<tr><td><a href='player.php?id=$key'>$nick</a></td><td>$val</td></tr>";
}
echo "</table>";



echo "</div>";
echo '<div class="tabpage" id="tabpage_3">';
echo "<h3>TopSpieler bei Siegquote</h3><table><tr class='tablehead'><td width='300'>Player</td><td width='100'>Siegquote</td></tr>";	
foreach ($playerRatioS as $key => $val) {
    $nick = getPlayerNick($key);
    echo "<tr><td><a href='player.php?id=$key'>$nick</a></td><td>$val %</td></tr>";
}
echo "</table>";
echo "<h3>Meiste Spiele Sturm</h3><table><tr class='tablehead'><td width='300'>Player</td><td width='100'>Spiele Sturm</td></tr>";	
foreach ($playerOfense as $key => $val) {
    $nick = getPlayerNick($key);
    echo "<tr><td><a href='player.php?id=$key'>$nick</a></td><td>$val</td></tr>";
}
echo "</table></div>";
echo "</div></div>"; //end tabContainer

echo "</div>"; //end main
//=========================
//===	rightSide	===
//=========================
echo "<div id='rightSide'>";
$foo = getOverallGames();
echo "<h2>Spiele gesamt:</h2>";
for ($i=0;$i<sizeof($foo);$i++)
{
	echo "<div class='number'>$foo[$i]</div>";
}
echo "</div>";
// footer
include ('footer.php');
?>
