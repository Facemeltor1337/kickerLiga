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
echo "<h1>Statistik Teams</h1>";
//oben the tabs now
echo '  <div id="tabContainer">
    <div id="tabs">
      <ul>
        <li id="tabHeader_1">Gesamt</li>      
      </ul>
    </div><div id="tabscontent">';

echo '<div class="tabpage" id="tabpage_4">';
$teams = getTeams();
$temp_teams = $teams;
//store for sidebar
$maxTeams = sizeof($teams);

$counter = array();
$counter2 = array();
foreach ($teams as $key => $row)
{
	$temp = $row[3] / $row[2] * 100;
	$temp = round($temp, 2);		
    $counter[$key] = $temp;
	$counter2[$key] = $row[2];
}
array_multisort($counter, SORT_DESC, $teams);
echo "<h3>TopTeams bei Siegquote</h3><table><tr class='tablehead'><td width='300'>Team</td><td width='100'>Gewonnen im Team</td></tr>";
for($i=0;$i<sizeof($teams);$i++)
{
	$player1 = getPlayerNick($teams[$i][0]);
	$player1_id = $teams[$i][0];
	$player2 = getPlayerNick($teams[$i][1]);
	$player2_id = $teams[$i][1];
	$games = $teams[$i][2];
	$wins = $teams[$i][3];
	$ratio = $wins / $games * 100;
	$ratio = round($ratio, 2);
	echo "<tr><td><a href='player.php?id=$player1_id'>$player1</a> / <a href='player.php?id=$player2_id'>$player2</a></td><td>$ratio %</td></tr>";
}
echo "</table>";

echo "<h3>Meiste Spiele Teams</h3><table><tr class='tablehead'><td width='300'>Team</td><td width='100'>Spiele im Team</td></tr>";	
$counter = array();

array_multisort($counter2, SORT_DESC, $temp_teams);
for($i=0;$i<sizeof($temp_teams);$i++)
{
	$player1 = getPlayerNick($temp_teams[$i][0]);
	$player1_id = $temp_teams[$i][0];
	$player2 = getPlayerNick($temp_teams[$i][1]);
	$player2_id = $temp_teams[$i][1];
	$games = $temp_teams[$i][2];
	echo "<tr><td><a href='player.php?id=$player1_id'>$player1</a> / <a href='player.php?id=$player2_id'>$player2</a></td><td>$games</td></tr>";
}
echo "</table>";



echo "</div>";
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
echo "<h2>Verschiedene Teams:</h2>";
$temp = (string)$maxTeams;
$maxTeams = str_split($temp);
for ($i=0;$i<sizeof($maxTeams);$i++)
{
	echo "<div class='number'>$maxTeams[$i]</div>";
}

echo "</div>";
// footer
include ('footer.php');
?>
