<?php
// config files
include ('config.php');
error_reporting(E_ALL);

// header
include ('header.php');
include ('kickerLib.php');

//get link vars

if (isset($_GET['status'])) 
{
	$status = $_GET['status'];
}
else
{
	$status = '';
}


if ($status != 'add')
{
	echo "<div id='main'>";
        echo "<h1>Neuen Spieler hinzufügen</h1>";
	//Hole nun die Daten der Spieler
	echo "<form action='add_player.php' method='get'>";
	echo "<input name='status' value='add' type='hidden'>";
	echo "Spielername: <input name='name' required placeholder='Namen eingeben' /><br>";
	echo "Nickname: <input name='nickname' required placeholder='Nickname eingeben' /><br>";
	echo " <input type='submit' value='Hinzufügen'></form>";

	echo "</div>";
}
else
{
$name = $_GET['name'];
$nname = $_GET['nickname'];

$sql123 = mysql_query("INSERT INTO players (Name, Nickname)   VALUES('$name', '$nname')") or die (mysql_error());
echo "<div id='main'>";
if ($sql123)
echo "Spieler $nname wurde hinzugefügt";
else
{
echo "<font color='red'>Fehler:</font> Es gab ein Problem mit der Datenbankanbindung, versuche es erneut oder wende dich bitte an einen der Administratoren.";
echo "<br>Gehe <a href=javascript:history.back()>zurück</a> und versuche es erneut oder gehe zur <a href='index.php'>Startseite</a> zurück.";
}
echo "</div>";
}

// footer
include ('footer.php');
?>
