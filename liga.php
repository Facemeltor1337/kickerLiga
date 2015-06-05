<?php
// config files
include ('config.php');
error_reporting(E_ALL);

// header
include ('header.php');
include ('kickerLib.php');

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


echo "<div id='main'>";
echo "<h1>Spiel-Ligen</h1>";
$sql_te = mysql_query("SELECT id FROM liga ORDER BY id DESC");
$check_name = mysql_num_rows($sql_te);
echo "<table><tr class='tablehead'><td width='100px'>Name</td><td width='300px'>Beschreibung</td><td width='100px'>Status</td></tr>";
for ($i=0;$i<$check_name;$i++)
{
	$ligaID = mysql_result($sql_te,$i, "id");
	$temp = getLigaInfo($ligaID);
	$status = $flags[$temp[2]];
	$status_text = $flagsT[$temp[2]];
	
	echo "<tr><td><a href='liga_details.php?id=$ligaID'>$temp[0]</a></td><td>$temp[1]</td><td><img title='$status_text' src='img/flag-$status.png'></td></tr>";
}
echo "</table></div>";

// footer
include ('footer.php');

?>
