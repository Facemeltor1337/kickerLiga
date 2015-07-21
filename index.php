<?php
// config files
include ('config.php');
error_reporting(E_ALL);

// header
include ('header.php');

include('kickerLib.php');

//now add the main area
echo "<div id='main'>";
echo "<h1>Letzte 10 Spiele</h1>";
$sql_te = mysql_query("SELECT id FROM games ORDER BY Id DESC LIMIT 10");
$check_name = mysql_num_rows($sql_te);
echo "<table><tr class='tablehead'><td colspan='2' width='300px'>Team 1</td><td width='100px'>Ergebnis</td><td colspan='2' width='300px'>Team 2</td><td width='200px'>Datum</td><td>Details</td></tr>";
 // we need some empty vars
$nickname2_2 = '';
$nickname1_1 = '';

for ($i=0;$i<$check_name;$i++)
{
		$gameID = mysql_result($sql_te,$i, "id");
		$data = getGameDetails($gameID);

		$date = $data[2];
		//load data team 1
		$player1 = $data[3];
		$player1_1 = $data[4];
		$nickname1 = getPlayerNick($player1);
		$ava1 = checkAvatar($nickname1);
		if ($player1_1 != 0)
		{
			$nickname1_1 = getPlayerNick($player1_1);	
			$ava1_1 = checkAvatar($nickname1_1);	
		}
		//load data team 2
		$player2 = $data[5];
		$player2_2 = $data[6];
		
		$nickname2 = getPlayerNick($player2);
		$ava2 = checkAvatar($nickname2);
		if ($player2_2 != 0)
		{
			$nickname2_2 = getPlayerNick($player2_2);	
			$ava2_2 = checkAvatar($nickname2_2);	
		}
	
		
		echo "<tr><td align='center'><img width='30px' src='img/avatare/$ava1'><br><a href='player.php?id=$player1'>$nickname1</a></div>";
		if ($nickname1_1 != '')
		{
			echo " <td align='center'><img width='30px' src='img/avatare/$ava1_1'><br><a href='player.php?id=$player1_1'>$nickname1_1</a></td>";
		}
		echo "</td><td><div class='number'>$data[0]</div> : <div class='number'>$data[1]</div></td><td align='center'><img width='30px' src='img/avatare/$ava2'><br><a href='player.php?id=$player2'>$nickname2</a>";
		if ($nickname2_2 != '')
		{
			echo " <td align='center'><img width='30px' src='img/avatare/$ava2_2'><br><a href='player.php?id=$player2_2'>$nickname2_2</a></td>";
		}
		echo "</td><td>$date</td><td><a href='game.php?id=$gameID'>[ Details ]</a></tr>";

		//reset the nicknames
		$nickname1_1 = '';
		$nickname2_2 = '';
		
}
echo "</table>";

echo "</div>";
//now add the left side panel
echo "<div id='rightSide'>";
echo "<h2>Spieler</h2>";
$playerList = getAllPlayerID();
for ($i=0;$i<sizeof($playerList);$i++)
{
		$v_name_sel = getPlayerNick($playerList[$i]);
		echo "<a href='player.php?id=$playerList[$i]'>$v_name_sel</a><br>";

}
echo "</div>";
// footer
include ('footer.php');
?>
