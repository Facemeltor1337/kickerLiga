<?php
// config files
include ('config.php');
error_reporting(E_ALL);

// header
include ('header.php');
include ('kickerLib.php');
//get link vars

$gameID = $_POST['gameID'];
$t1score = $_POST['t1score'];
$t2score = $_POST['t2score'];



$sql123 = mysql_query("UPDATE games_liga SET score1='$t1score', score2='$t2score', date=now() WHERE id='$gameID'");

if ($sql123)
{
	
}
else
{
echo "<font color='red'>Fehler:</font> Es gab ein Problem mit der Datenbankanbindung, versuche es erneut oder wende dich bitte an einen der Administratoren.";
echo "<br>Gehe <a href=javascript:history.back()>zurück</a> und versuche es erneut oder gehe zur <a href='index.php'>Startseite</a> zurück.";
}

$sql_ligaID = mysql_query("SELECT ligaID FROM games_liga where id='$gameID'");
$ligaID = mysql_result($sql_ligaID,0, "ligaID");
// lets check if this was the lastgame
$sql_lastGame = mysql_query("SELECT id FROM games_liga where ligaID='$ligaID' and score1 is NULL");
$n = mysql_num_rows($sql_lastGame);

if(!$n)
{
	//lets set the status to 1 (laufend)
	$sql123 = mysql_query("UPDATE liga SET status='3' WHERE id='$ligaID'");
	//TODO: hinzufügen der spiele für die playoffs
	//zuvor jedoch die Tabelle erstellen und eine Methode in der Kickerlib, die die Tabelle anzeigt
}

//return to liga
echo "<script type=\"text/javascript\">window.location.href = 'liga_details.php?id=$ligaID'</script>";

// footer
include ('footer.php');
?>
