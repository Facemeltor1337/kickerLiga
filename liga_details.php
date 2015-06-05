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
	$dis_orNot = '';
}
else 
{
	$dis_orNot = "disabled";
}

echo "Spielmodus  <select name='modus' $dis_orNot>";


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
	echo "Spieler:";
	
	$sql_player = mysql_query("SELECT * FROM players");
	$n = mysql_num_rows($sql_player);
	echo "<select name='player' multiple $dis_orNot size='$n'>";
	for($i=0;$i<$n;$i++)
	{
		$pName = mysql_result($sql_player,$i, "Nickname");
		$pID = mysql_result($sql_player,$i, "id");
		echo "<option value='$pID'>$pName</option>";
	}
echo "</select><br>";
echo "<input type='submit' value='Konfigurieren'>";
}
echo "</div>";


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
echo "</div>"; //end rightSide
} // end $func = ''
if ($func == 'konf')
{
	$modus = $_POST['modus'];
	//Update now the Konf
	$sql_modus = mysql_query("UPDATE liga SET liga_modus='$modus' where id='$ligaID'")or die(mysql_error());;
	
	echo "<script type=\"text/javascript\">window.location.href = 'liga_details.php?id=$ligaID'</script>";
}//end $func = konf
// footer
include ('footer.php');

?>