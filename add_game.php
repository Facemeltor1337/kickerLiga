<script type="text/javascript" src="validation.js">
</script>
<?php
// config files
include ('config.php');
error_reporting(E_ALL);

// header
include ('header.php');
include ('kickerLib.php');
//get link vars

if (isset($_POST['status'])) 
{
	$status = $_POST['status'];
}
else
{
	$status = '';
}


if ($status != 'add')
{
echo "<div id='main'>";
echo "<div id='errors' style='font-size: 16px;background-color: #ED8B00;padding-left: 2em'></div>";
echo "<h1>Ein Spiel hinzufügen:</h1>";
//Hole nun die Daten der Spieler
$nrPlayer = getNumberOfPlayer();
?>
<script type="text/javascript">
// pass PHP variable declared above to JavaScript variable
var possiblePlayers = <?php echo json_encode(listPlayerAsPureList()) ?>;
</script>
	<datalist id="PlayerIDs">
		<?php 
		listPlayerAsList();
		?>
	</datalist>
    <form name='spielEintrag' action='add_game.php' onsubmit="return validateInput(possiblePlayers)" method='post'>
       <input name='status' value='add' type='hidden'>
       <fieldset>
          <legend>Team1</legend>
          <p>
             <label>Abwehr</label>
             <input type="text" name="t1s1" list="PlayerIDs" size='20' required>
             <label>Sturm</label>
             <input type="text" name="t1s2" list="PlayerIDs" size='20' required>
	     <label>Tore</label>
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
          </p>
       </fieldset>
       <fieldset>
          <legend>Team2</legend>
          <p>
             <label>Abwehr</label>
             <input type="text" name="t2s1" list="PlayerIDs" size='20' required>
             <label>Sturm</label>
             <input type="text" name="t2s2" list="PlayerIDs" size='20' required>
	     <label>Tore</label>
             <select name = "t2score"required>
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
       </fieldset>

	<input type='submit' value='Hinzufügen'>
    </form>
<?php
echo "</div>";
}
else
{
$t1s1 = getPlayerID($_POST['t1s1']);
$t1s2 = getPlayerID($_POST['t1s2']);
$t1score = $_POST['t1score'];
$t2s1 = getPlayerID($_POST['t2s1']);
$t2s2 = getPlayerID($_POST['t2s2']);
$t2score = $_POST['t2score'];

$sql123 = mysql_query("INSERT INTO games (date, player_1, player1_1, score1, player_2, player2_2, score2)   VALUES(now(), '$t1s1', '$t1s2', '$t1score', '$t2s1', '$t2s2', '$t2score')") or die (mysql_error());
echo "<div id='main'>";
if ($sql123)
{
$sql_te = mysql_query("SELECT * FROM games order by date DESC Limit 1");
$newID = mysql_result($sql_te,0, "id");
echo "Spiel wurde hinzugefügt. Details zum Verglich findest du <a href='game.php?id=$newID'>hier</a>!";
}
else
{
echo "<font color='red'>Fehler:</font> Es gab ein Problem mit der Datenbankanbindung, versuche es erneut oder wende dich bitte an einen der Administratoren.";
echo "<br>Gehe <a href=javascript:history.back()>zurück</a> und versuche es erneut oder gehe zur <a href='index.php'>Startseite</a> zurück.";
}
echo "</div>";

}
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
