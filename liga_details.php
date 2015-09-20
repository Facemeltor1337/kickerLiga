<?php
// config files
include ('config.php');
error_reporting(E_ALL);
//define status
$flags = array();
$flags[0] = "yellow";
$flags[1] = "green";
$flags[2] = "red";
$flags[3] = "green-playoff";

//define altText Status
$flagsT = array();
$flagsT[0] = "Konfigurationsphase";
$flagsT[1] = "Laufender Spielbetrieb";
$flagsT[2] = "Liga bereits beendet";
$flagsT[3] = "Spielbetrieb Playoffs";

//define longText Status
$flagsTL = array();
$flagsTL[0] = "Diese Spielrunde ist noch nicht gestartet. Bevor mit den ersten Spielen begonnen werden kann mussen die Spieler hinzugefügt werden und die Runde aktiviert werden. Es müssen mindestens 8 Spieler hinzugefügt werden und der Modus muss gewählt sein um die Spielrunde starten zu können!";
$flagsTL[1] = "Diese Spielrunde ist bereits gestartet und es werden die Spiele der Hauptrunde durchgeführt.";
$flagsTL[2] = "Diese Spielrunde ist abgeschlossen.";
$flagsTL[3] = "Diese Spielrunde ist gestartet und aktuell werden die Spiele der Playoffs durchgeführt";

// header
include ('header.php');
include ('kickerLib.php');
echo '<script src="dynamic.js"></script>';
echo '<script src="res/tabs_old.js"></script>'; 
//get GET
if (isset($_POST['func'])) {
	$func = $_POST['func'];
	$ligaID = $_POST['ligaID'];
}
else
{
	$func = '';
	$ligaID = $_GET['id'];
}

if ($func == '')
{
//generiere eine InfoBox

$temp = getLigaInfo($ligaID);
$status = $flags[$temp[2]];
$status_text = $flagsT[$temp[2]];
$status_ltext = $flagsTL[$temp[2]];

echo "<div id='main'>";
echo "<h1>$temp[0]</h1>";
echo "Beschreibung: $temp[1]<br>";
if ($temp[2] == 0) //nur wenn Konfigphase
{
echo "<form action='liga_details.php' method='post'>
	 <input name='func' value='konf' type='hidden'>
	 <input name='ligaID' value='$ligaID' type='hidden'>";
}

echo "Spielmodus  <select name='modus' disabled>";


echo "<option value='0'>kein Modus</option>"; //setze wert 0
// Hole alle Moduse :) aus der DB
$sql_modus = mysql_query("SELECT * FROM liga_modus");
$n = mysql_num_rows($sql_modus);

for($i=0;$i<$n;$i++)
{
	$myModus = mysql_result($sql_modus,$i, "name");
	$myID = mysql_result($sql_modus,$i, "id");
		
	if ($myID == $temp[3])
	{
		$selected = 'selected';
		$loadMinTeilnehmer = mysql_result($sql_modus,$i, "min_teilnehmer");
		$loadMaxTeilnehmer = mysql_result($sql_modus,$i, "max_teilnehmer");
	
	}
	else
	{
		$selected = '';
	}
	echo $myID;
	echo "<option value='$myID' $selected>$myModus</option>";
}

echo "</select><br>";
if ($temp[2] == 0) //nur wenn Konfigphase
{
	echo "<div id='correctPlayers' style='left:500px;position:absolute;'>foo</div>";
	echo "Spieler:";
	
	$sql_player = mysql_query("SELECT * FROM players");
	$n = mysql_num_rows($sql_player);
	echo "<select name='player[]' multiple size='$n' onChange='countSelections($loadMinTeilnehmer, $loadMaxTeilnehmer)' id='player'>";

	
	for($i=0;$i<$n;$i++)
	{
		$pName = mysql_result($sql_player,$i, "Nickname");
		$pID = mysql_result($sql_player,$i, "id");

		echo "<option value='$pID'>$pName</option>";

		
	}
echo "</select><br>";
echo "<input type='submit' value='Konfigurieren' disabled id='konfButton'>";
echo '<script type="text/javascript">';
echo "countSelections($loadMinTeilnehmer, $loadMaxTeilnehmer)";
echo '</script>';
}

//zeigen Rundenspiele an
if ($temp[2] == 1 or $temp[2] == 2 or$temp[2] == 3)
{
	//oben the tabs now
	echo '  <div id="tabContainer">
    <div id="tabs">
      <ul>
        <li id="tabHeader_1">Vorrunde</li>
        <li id="tabHeader_2">Tabelle</li>
        <li id="tabHeader_3">Playoffs</li>
      </ul>
    </div><div id="tabscontent">';
	
	//TODO: Reiter für Spiele, Tabelle, Playoffs
	echo '<div class="tabpage" id="tabpage_1">';
	echo "<h1>Paarungen</h1>";
	$sql_ligagames = mysql_query("SELECT * FROM games_liga where ligaID = '$ligaID' and playOff is NULL");
	$n = mysql_num_rows($sql_ligagames);
	echo "<table><tr class='tablehead'><td colspan='2' width='300px'>Team 1</td><td width='130px'>Ergebnis</td><td colspan='2' width='300px'>Team 2</td></tr>";
	for($i=0;$i<$n;$i++)
	{
		$score1 = mysql_result($sql_ligagames,$i, "score1");
		$score2 = mysql_result($sql_ligagames,$i, "score2");
		//wenn kein ergebnis vorhanden
		if(!$score1)
		{
			$gameID = mysql_result($sql_ligagames,$i, "id");		
		}
		$team1 = mysql_result($sql_ligagames,$i, "team1");
		$team2 = mysql_result($sql_ligagames,$i, "team2");
		//get infos for team 1
		echo "<tr>";
		$sql_team1 = mysql_query("SELECT * FROM rel_liga_player where ligaID = '$ligaID' and team = '$team1'");
		$m = mysql_num_rows($sql_team1);
		for($j=0;$j<$m;$j++)
		{
			$playerID = mysql_result($sql_team1,$j, "playerID");
			$nickname = getPlayerNick($playerID);
			$ava = checkAvatar($nickname);
			echo "<td align='center'><img width='30px' src='img/avatare/$ava'><br><a href='player.php?id=$playerID'>$nickname</a></td>";
		}
		if(!$score1)
		{
		?>
			<form action='add_game_liga.php' method='post'>
			<input name='gameID' value='<?php echo $gameID ?>' type='hidden'>
			<td><fieldset>
			<select name = "t1score" required>
			<option value="0">0</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
			<option value="10">10</option>
			</select>
			: 
		
			<select name = "t2score" required>
			<option value="0">0</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
			<option value="10">10</option>
			</select>
			</p>
			<input type='submit' value='Hinzufügen'>
   			</form></fieldset></td>
		<?php 
		}
		else
		{
			echo "<td><div class='number'>$score1</div>  : <div class='number'>$score2</div> </td>";
		}
		$sql_team2 = mysql_query("SELECT * FROM rel_liga_player where ligaID = '$ligaID' and team = '$team2'");
		$m = mysql_num_rows($sql_team2);
		for($j=0;$j<$m;$j++)
		{
			$playerID = mysql_result($sql_team2,$j, "playerID");
			$nickname = getPlayerNick($playerID);
			$ava = checkAvatar($nickname);
			echo "<td align='center'><img width='30px' src='img/avatare/$ava'><br><a href='player.php?id=$playerID'>$nickname</a></td>";
		}
		echo "</tr>";
	}
	echo "</table></div>";
	echo '<div class="tabpage" id="tabpage_3">';
	echo "<h1>PlayOffs</h1>";
	//TODO: eifügen der Playoff-Spiele - zuerst jedoch anlegen in add_game_liga.php
	echo "</div>";
	echo '<div class="tabpage" id="tabpage_2">';
	echo "<h1>Tabelle</h1>";
	echo "</div>";
	
	//TODO: anlegen einer 
	
}
echo "</div></div></div>";


//content for rightSide
echo "<div id='rightSide'>";

echo "<div id='ligaInfo'><img src='img/flag-$status.png'><b>$status_text</b>";
echo "<br>$status_ltext<br>";
echo "</div>";
if ($temp[2] == 0)
{
	echo "<div id='modus_howto'>";
	$sql_modus = mysql_query("SELECT * FROM liga_modus");
	$n = mysql_num_rows($sql_modus);
	echo "<br><b>Welche Runden gibt es?</b><br><br><table width='100%'><tr><td>Name</td><td>Runden</td><td>Spieler(Min/Max/PlayOffs)</td><td>Playoffs BestOf</td><td>Beschreibung</td></tr>";
	for($i=0;$i<$n;$i++)
	{
		$myModus = mysql_result($sql_modus,$i, "name");
		$runden = mysql_result($sql_modus,$i, "anzahl_runden");
		$min_teilnehmer = mysql_result($sql_modus,$i, "min_teilnehmer");
		$max_teilnehmer = mysql_result($sql_modus,$i, "max_teilnehmer");
		$teilnehmer_playoffs = mysql_result($sql_modus,$i, "teilnehmer_playoff");
		$bestof_playoffs = mysql_result($sql_modus,$i, "bestOf_PlayOffs");
		$text = mysql_result($sql_modus,$i, "text");
		echo "<tr><td>$myModus</td><td>$runden</td><td>$min_teilnehmer/$max_teilnehmer/$teilnehmer_playoffs</td><td>$bestof_playoffs</td><td>$text</td></tr>";
	}
	echo "</table></div>"; // end modus_howto
}
if ($temp[2] == 1 || $temp[2] == 2 || $temp[2] == 3)
{
	echo "<div id='modus_howto'>";
	echo "<h2>Teams in dieser Liga</h2>";
	$sql_modus = mysql_query("SELECT * FROM rel_liga_player where ligaID='$ligaID' order by team");
	$n = mysql_num_rows($sql_modus);
	echo "<table><tr><td>Spieler1</td><td>Spieler2</td></tr>";
	for($i=0;$i<$n;$i++)
	{
		if((($n-$i)%2) == 0)
		{
			echo "<tr>";
		}
		$idPlayer= mysql_result($sql_modus,$i, "playerID");
		$tempPlayer = getPlayerNick($idPlayer);
		echo "<td><a href='player.php?id=$idPlayer'>$tempPlayer</a></td>";
		if((($n-$i)%2) == 1)
		{
			echo "</tr>";
		}
	}
	echo "</table></div>";
}
echo "</div>"; //end rightSide
} // end $func = ''
if ($func == 'konf')
{
	$players = $_POST['player'];
	$teamNr = count($players) / 2;
	echo "$teamNr = Anzahl Teams<br>";
	
	for($i=0;$i<$teamNr;$i++)
	{
		shuffle($players);
		$player1 = array_pop($players);
		shuffle($players);
		$player2 = array_pop($players);	
		$sql123 = mysql_query("INSERT INTO rel_liga_player (playerID, ligaID, team) VALUES('$player1', '$ligaID', '$i')") or die (mysql_error());
		$sql123 = mysql_query("INSERT INTO rel_liga_player (playerID, ligaID, team) VALUES('$player2', '$ligaID', '$i')") or die (mysql_error());
	}
	
	//lets set the status to 1 (laufend)
	$sql123 = mysql_query("UPDATE liga SET status='1' WHERE id='$ligaID'");
		//hole mir mal die Anzahl der Runden
	$sql_modus = mysql_query("SELECT * FROM liga_modus");
	$runden = mysql_result($sql_modus,0, "anzahl_runden");
	//Alle Spiele der Vorrunde anlegen
	for($i=0;$i<$teamNr;$i++)
	{
		for($j=$i+1;$j<$teamNr;$j++)
		{
			for($k=0;$k<$runden;$k++)
			{
			$sql123 = mysql_query("INSERT INTO games_liga (ligaID, team1, team2) VALUES('$ligaID', '$i', '$j')");
			}
		}
	}
	echo "<script type=\"text/javascript\">window.location.href = 'liga_details.php?id=$ligaID'</script>";
	
}//end $func = konf
// footer
include ('footer.php');

?>