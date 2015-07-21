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
	if (isset($_POST['status']))
	{
		$status = $_POST['status'];
		$avatar = $_POST['avatar'];
	}
	else
	{
		$status = '';
	}
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

    echo "<h1>Avatar hochladen</h1>";
	//Hole nun die Daten der Spieler
	echo "<form action='add_player.php' method='post' enctype='multipart/form-data'>";
	echo "<input name='status' value='add' type='hidden'>";
	echo "<input name='avatar' value='true' type='hidden'>";
	echo "<input type='file' name='picture'/>";
	echo " <input type='submit' value='Hochladen'></form>";

	echo "</div>";
}
else
{
	if (!$avatar)
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
	else
	{
		$uploaddir = 'img/avatare/';
		$uploadfile = $uploaddir . basename($_FILES['picture']['name']);
		$uploadOK = 1;
		//check file height, width
		$_imagesize = getimagesize($_FILES['picture']['tmp_name']);
		$breite = $_imagesize[0];
		$hoehe = $_imagesize[1];
		if ($breite != $hoehe or $breite > 100)
		{
			echo "Datei ist größer als 100px oder nicht quadratisch<br>";
			$uploadOK = 0;
		}

		 // Check file size
		if ($_FILES["picture"]["size"] > 50000) {
			echo "Sorry, Datei ist zu groß.";
			$uploadOK = 0;
		} 
		if ($uploadOK != 0)
		{
			if (move_uploaded_file($_FILES['picture']['tmp_name'], $uploadfile)) {
				echo "Datei ist valide und wurde erfolgreich hochgeladen.\n";
			} else {
				echo "Möglicherweise eine Dateiupload-Attacke!\n";
			}
		}
	}
}

// footer
echo '<div id="rightSide"><h3>Info zum Avatarupload</h3>Bitte beachten, dass die Datei quadratisch ist und nicht breiter/höher als 100px.<br>Weiterin muss das Bild den exakten Namen des Nicknames haben. Die Dateiendung wird nicht geprüft, jedoch darauf achten nur Bilder als jpg, gif, png hochzuladen. <br>Zusätzlich darf die Datei nicht größer als 50 kb sein.</div>';
include ('footer.php');
?>
