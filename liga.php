<?php
// config files
include ('config.php');
error_reporting(E_ALL);

// header
include ('header.php');
include ('kickerLib.php');

echo "<div id='main'>";
echo "<h1>Letzten 10 Spiel-Ligen</h1>";
$sql_te = mysql_query("SELECT id FROM liga ORDER BY id DESC LIMIT 10");
$check_name = mysql_num_rows($sql_te);
echo "<table><tr class='tablehead'><td width='100px'>Name</td><td width='300px'>Beschreibung</td><td width='100px'>Status</td></tr>";

for ($i=0;$i<$check_name;$i++)
{
	$ligaID = mysql_result($sql_te,$i, "id");
	$temp = getLigaInfo($ligaID);
	echo "<tr><td>$temp[0]</td><td>$temp[1]</td><td>$temp[2]</td></tr>";
}

echo "</table></div>";

// footer
include ('footer.php');

?>
