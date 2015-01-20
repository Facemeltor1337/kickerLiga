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
echo "<h1>Ein Spiel hinzufügen:</h1>";
//Hole nun die Daten der Spieler
?>
    <form action='add_game.php' method='post'>
       <input name='status' value='add' type='hidden'>
       <fieldset>
          <legend>Team1</legend>
          <p>
             <label>Abwehr</label>
             <select name = "t1s1" required size='10' autofocus>
		<?php 
		$foo = listPlayerAsList();
		?>
             </select>
             <label>Sturm</label>
             <select name = "t1s2" size='10'>
		<?php 
		$foo = listPlayerAsList();
		?>
             </select>
	     <label>Tore</label>
             <select name = "t1score" size='11' required>
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
             <select name = "t2s1" required size='10'>
		<?php 
		$foo = listPlayerAsList();
		?>
             </select>
             <label>Sturm</label>
             <select name = "t2s2" size='10'>
		<?php 
		$foo = listPlayerAsList();
		?>
             </select>
	     <label>Tore</label>
             <select name = "t2score" size='11' required>
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
$t1s1 = $_POST['t1s1'];
$t1s2 = $_POST['t1s2'];
$t1score = $_POST['t1score'];
$t2s1 = $_POST['t2s1'];
$t2s2 = $_POST['t2s2'];
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

// footer
include ('footer.php');
?>
