<?php
session_start();  // Start Session
header('Content-type: text/html; charset=utf-8');
?>
<?php

echo "<html><title>TT-KickerLiga</title><link rel='stylesheet' type='text/css' href='styleMe.css'>";
echo "<div id='header'><div id='title'>TraceTronic Kicker-Liga Ingolstadt</div><div id='logo'><img src='img/logo.png' width='100px'></div><div id='menu'>
<a href='index.php'><img title='Übersicht' src='img/home.png'></a> 
<a href='add_game.php'><img width='40px' title='Spiel eintragen' src='img/add_game.png'></a>
<a href='add_player.php'><img width='40px' title='Spieler hinzufügen' src='img/add_player.png'></a>
<a href='stats.php'><img width='40px' title='Statistiken' src='img/statistics.png'></a>
<a href='liga.php'><img width='40px' title='Liga-Modus' src='img/liga.png'></a>
</div></div>";

?>
