<?php
// config files
include ('config.php');
error_reporting(E_ALL);

function getAllPlayerID()
{
	$sql_te = mysql_query("SELECT * FROM players");
	$check_name = mysql_num_rows($sql_te);
	$listPlayer = array();
	for ($i=0;$i<$check_name;$i++)
	{
			$listPlayer[$i] = mysql_result($sql_te,$i, "id");
	}
	return $listPlayer;
}

function getNumberOfPlayer()
{
	$sql_te = mysql_query("SELECT * FROM players");
	$check_name = mysql_num_rows($sql_te);

	return $check_name;
}

function getPlayerNick($id)
{
	$sql_player1 = mysql_query("SELECT * FROM players where id='$id'");
	$nick = mysql_result($sql_player1,0, "Nickname");
	return $nick;
}

function checkAvatar($name)
{
	$default = 'default.png';
	$files = scandir('img/avatare');
	for ($i=0; $i<sizeof($files); $i++) {
		$temp = pathinfo($files[$i])['filename'];
		if ($temp == $name)
		{
			return $files[$i];
		}
	}
	return $default;
}

function getPlayerID($name)
{
	$sql_player1 = mysql_query("SELECT * FROM players where Nickname='$name'");
	$id = mysql_result($sql_player1,0, "id");
	return $id;	
}

function getNumberOfGamesByPlayer($id)
{
	$sql = mysql_query("SELECT * FROM games where player_1='$id' or player_2='$id' or player1_1='$id' or player2_2='$id'");
	$n = mysql_num_rows($sql);
	return $n;
}

function getWinRatio($id) {
	$win_loss_arrays = getAbwehrSturm($id);
	return getWinRatioForArray($win_loss_arrays);
}

function getWinRatioForArray($a_s_nr)
{
	$overall = $a_s_nr[0][0] + $a_s_nr[1][0];
	$ratio = ($a_s_nr[0][1] + $a_s_nr[1][1]) / $overall * 100;
	$aratio = $a_s_nr[0][1] / $a_s_nr[0][0] * 100; 	
	$sratio = $a_s_nr[1][1] / $a_s_nr[1][0] * 100;
	return array(round($ratio, 2), round($aratio, 2), round($sratio, 2));
}

function getOverallGames()
{
	$sql = mysql_query("SELECT * FROM games");
	$n = mysql_num_rows($sql);
	return str_split($n);
}


function getWinsAndLoose($id)
{
	$sql_player1 = mysql_query("SELECT * FROM games where player_1='$id' or player_2='$id' or player1_1='$id' or player2_2='$id'");
	$n = mysql_num_rows($sql_player1);
	$win = 0;
	$loose = 0;
	$goodGoals = 0;
	$badGoals = 0;
	for ($i=0; $i<$n; $i++) {
		$score1 = mysql_result($sql_player1,$i, "score1");
		$score2 = mysql_result($sql_player1,$i, "score2");
		if ($score1 > $score2)
		{
			//team 1 won check if player was on team 1
			$play1 = mysql_result($sql_player1,$i, "player_1");
			$play1_1 = mysql_result($sql_player1,$i, "player1_1");
			
			if ($play1 == $id || $play1_1 == $id) {
				$goodGoals += $score1;
				$badGoals += $score2;
				$win += 1;
			} else {
				$goodGoals += $score2;
				$badGoals += $score1;
				$loose +=1;
			}		
		} else {
			//team 2 won
			$play2 = mysql_result($sql_player1,$i, "player_2");
			$play2_2 = mysql_result($sql_player1,$i, "player2_2");
			
			if ($play2 == $id || $play2_2 == $id)	{
				$goodGoals += $score2;
				$badGoals += $score1;
				$win +=1;
			} else {
				$goodGoals += $score1;
				$badGoals += $score2;
				$loose += 1;
			}
		}	
	}
	return array($win, $loose, $goodGoals, $badGoals);
}

function getGameDetails($id)
{
	$sql_te = mysql_query("SELECT * FROM games where id = '$id'");
	$date = mysql_result($sql_te,0, "date");
	//load data team 1
	$player1 = mysql_result($sql_te,0, "player_1");
	$player1_1 = mysql_result($sql_te,0, "player1_1");
	$player2 = mysql_result($sql_te,0, "player_2");
	$player2_2 = mysql_result($sql_te,0, "player2_2");
	$score1 = mysql_result($sql_te,0, "score1");
	$score2 = mysql_result($sql_te,0, "score2");

	return array($score1, $score2, $date, $player1, $player1_1, $player2, $player2_2);
}

function listPlayerAsList()
{
	$sql_te = mysql_query("SELECT id FROM players");
	$check_name = mysql_num_rows($sql_te);

	for ($i=0;$i<$check_name;$i++)
	{
			$v_id_sel = mysql_result($sql_te,$i, "id");
			$v_name_sel = getPlayerNick($v_id_sel);
			echo "<option>$v_name_sel</option>";
	}
}

function listPlayerAsPureList()
{
	$sql_te = mysql_query("SELECT id FROM players");
	$check_name = mysql_num_rows($sql_te);

	for ($i=0;$i<$check_name;$i++)
	{
		$v_id_sel = mysql_result($sql_te,$i, "id");
		$v_name_sel = getPlayerNick($v_id_sel);
		$output[] = $v_name_sel;
	}
	return $output;
}

function getLast10($id)
{
	$foo = array();
	$sql_player1 = mysql_query("SELECT * FROM games where player_1='$id' or player_2='$id' or player1_1='$id' or player2_2='$id' order by date DESC");
	$n = mysql_num_rows($sql_player1);
	for ($i=0; $i < $n && $i < 10; $i++)
	{
		$score1 = mysql_result($sql_player1,$i, "score1");
		$score2 = mysql_result($sql_player1,$i, "score2");
		$temp = '';
		if ($score1 > $score2)
		{
			$play1 = mysql_result($sql_player1,$i, "player_1");
			$play1_1 = mysql_result($sql_player1,$i, "player1_1");
			if ($play1 == $id or $play1_1 == $id) {
				$temp = 1;
			} else {
				$temp = -1;
			}
		} else {
			$play2 = mysql_result($sql_player1,$i, "player_2");
			$play2_2 = mysql_result($sql_player1,$i, "player2_2");
			if ($play2 == $id or $play2_2 == $id) {
				$temp = 1;
			} else {
				$temp = -1;
			}
		}
	
		$foo[] = $temp;
	}
	return $foo;
}

function getTheSameGames($gameID)
{
	$data = getGameDetails($gameID);
	$player1 = $data[3];
	$player1_1 = $data[4];
	$player2 = $data[5];
	$player2_2 = $data[6];
	//look for games with team1
	$sql_team1 = mysql_query("SELECT id FROM games where(
				(player_1='$player1' and player1_1='$player1_1')
				or 
				(player_1='$player1_1' and player1_1='$player1')
				or
				(player_2='$player1' and player2_2='$player1_1')
				or				
				(player_2='$player1_1' and player2_2='$player1')
			)
			AND id IN
				(SELECT id FROM games where 
					(player_1='$player2' and player1_1='$player2_2')
					or 
					(player_1='$player2_2' and player1_1='$player2')
					or
					(player_2='$player2' and player2_2='$player2_2')
					or				
					(player_2='$player2_2' and player2_2='$player2')
				)

			Order by date DESC");
	$countGames = mysql_num_rows($sql_team1);
	
	$ids = array();
	for($i=0; $i < $countGames; $i++) {
		$id1 = mysql_result($sql_team1,$i, "id");
		if ($id1 != $gameID) {
			$ids[] = $id1;
		}
	}
	return array($countGames, $ids);
}


/**
 * 
 * @param unknown $id Id des Spielers
 * @return [[abwehrspiele, abwehrsiege, abwehrniederlagen],[sturmspiele, sturmsiege, sturmniederlagen]]
 */
function getAbwehrSturm($id)
{
	$sql_defense_wins = mysql_query("SELECT id FROM games where (player_1='$id' AND player1_1!='0' AND score1 > score2)
			OR (player_2='$id' AND player2_2!='0' AND score2 > score1)");
	$sql_defense_losses = mysql_query("SELECT id FROM games where (player_1='$id' AND player1_1!='0' AND score1 < score2)
			OR (player_2='$id' AND player2_2!='0' AND score2 < score1)");
	$sql_forward_wins = mysql_query("SELECT id FROM games where (player1_1='$id' AND player_1!='0' AND score1 > score2)
			OR (player2_2='$id' AND player_2!='0' AND score2 > score1)");
	$sql_forward_losses = mysql_query("SELECT id FROM games where (player1_1='$id' AND player_1!='0' AND score1 < score2)
			OR (player2_2='$id' AND player_2!='0' AND score2 < score1)");

	$aloose = mysql_num_rows($sql_defense_losses);
	$sloose = mysql_num_rows($sql_forward_losses);
	$awins = mysql_num_rows($sql_defense_wins);
	$swins = mysql_num_rows($sql_forward_wins);
	$sturm = $sloose + $swins;
	$abwehr = $aloose + $awins;

	return array(array($abwehr, $awins, $aloose), array($sturm, $swins, $sloose));
}

function getLigaInfo($id)
{
	$sql_player1 = mysql_query("SELECT * FROM liga where id='$id'");
	$name = mysql_result($sql_player1,0, "name");
	$text = mysql_result($sql_player1,0, "text");
	$status = mysql_result($sql_player1,0, "status");
	$liga_modus = mysql_result($sql_player1, 0, "liga_modus");
	return array($name, $text, $status, $liga_modus);
}

function getPlayerInLiga($ligaID)
{
	$sql_players = mysql_query("SELECT playerID FROM rel_liga_player where ligaID='$ligaID'");
	$player = array();
	$playerCounter = mysql_num_rows($sql_players);
	for($i=0;$i<$playaerCounter;$i++)
	{
		$id = mysql_result($sql_players,$i, "playerID");
		$player[] = $id;
	}
	return $player;
}


//get all teams now
function getTeams()
{
	//try to get all teams now and ofc we name the array foo
	$foo = array();
	
	$sql_player1 = mysql_query("SELECT * FROM games");
	$n = mysql_num_rows($sql_player1);
	for($i=0;$i<$n;$i++)
	{
			$play1 = mysql_result($sql_player1,$i, "player_1");
			$play1_1 = mysql_result($sql_player1,$i, "player1_1");
            $play2 = mysql_result($sql_player1,$i, "player_2");
			$play2_2 = mysql_result($sql_player1,$i, "player2_2");
			$score1 = mysql_result($sql_player1,$i, "score1");
			$score2 = mysql_result($sql_player1,$i, "score2");
			if (sizeof($foo) == 0)
			{
				//player1, player2, games, won
				if($score1 > $score2)
				{
					$foo[] = array($play1, $play1_1, 1, 1);
					$foo[] = array($play2, $play2_2, 1, 0);
				}
				else
				{
					$foo[] = array($play1, $play1_1, 1, 0);
					$foo[] = array($play2, $play2_2, 1, 1);
				}
				continue;
			}		
			$foundTeam = 0;
			//check if team1 exists
			for($j=0;$j<sizeof($foo);$j++)
			{
				$currentRow = $foo[$j];
				if ($currentRow[0] == $play1 and $currentRow[1] == $play1_1)
				{
					//add one to games
					$games =  $currentRow;
					$games = $games[2];
					$games = $games + 1;
					//add wins now
					$wins = $currentRow;
					$wins = $wins[3];
					if ($score1 > $score2)
					{
						$wins = $wins +1 ;
					}

					$foo[$j] = array($play1, $play1_1, $games, $wins);
					$foundTeam = 1;
					break;
				}
				if ($currentRow[0] == $play1_1 and $currentRow[1] == $play1)
				{
					$games =  $currentRow;
					$games = $games[2];
					$games = $games + 1;
					//add wins now
					$wins = $currentRow;
					$wins = $wins[3];
					if ($score1 > $score2)
					{
						$wins = $wins +1 ;
					}

					$foo[$j] = array($play1_1, $play1, $games, $wins);
					$foundTeam = 1;
					break;
				}
			}//end team 1
			if ($foundTeam == 0)
			{

					if ($score1 > $score2)
					{
						$wins = 1 ;
					}
					else
					{
						$wins = 0;
					}
					$foo[] = array($play1, $play1_1, 1, $wins);	

			}
			$foundTeam = 0;		
			for($j=0;$j<sizeof($foo);$j++)
			{					
				$currentRow = $foo[$j];
				if ($currentRow[0] == $play2 and $currentRow[1] == $play2_2)
				{
					$games =  $currentRow;
					$games = $games[2];
					$games = $games + 1;
					//add wins now
					$wins = $currentRow;
					$wins = $wins[3];
					if ($score1 < $score2)
					{
						$wins = $wins +1 ;
					}
					$foo[$j] = array($play2, $play2_2, $games, $wins);
					$foundTeam = 1;
					$whichTeam = 2;
					break;
				}
				if ($currentRow[0] == $play2_2 and $currentRow[1] == $play2)
				{
					$games =  $currentRow;
					$games = $games[2];
					$games = $games + 1;
					//add wins now
					$wins = $currentRow;
					$wins = $wins[3];
					if ($score1 < $score2)
					{
						$wins = $wins +1 ;
					}
					$foo[$j] = array($play2_2, $play2, $games, $wins);
					$foundTeam = 1;
					$whichTeam = 2;
					break;
				}	
			}//end team2
			if ($foundTeam == 0)
			{
					if ($score1 < $score2)
					{
						$wins = 1 ;
					}
					else
					{
						$wins = 0;
					}
				$foo[] = array($play2, $play2_2, 1, $wins);
			}			
	
	} //end games loop
	
	return $foo;
}

//do not use yet --> function will be interesting upon reaching a higher game count
function getTeamsSQL()
{
	$foo = array();

	$sql_num_players = mysql_query("SELECT * FROM players");
	$n = mysql_num_rows($sql_num_players);
	for($i=0;$i<$n;$i++)
	{
		for($j=$i+1;$j<=$n;$j++)
		{
			$wins_team1_sql = mysql_query("SELECT count(id) as totGames, SUM(score1) as posScore, SUM(score2) as negScore FROM games WHERE ((player_1='+$i+' AND player1_1='+$j+') OR (player_1='+$j+' AND player1_1='+$i+')) AND score1 > score2");
			$losses_team1_sql =	mysql_query("SELECT count(id) as totGames, SUM(score1) as posScore, SUM(score2) as negScore FROM games WHERE ((player_1='+$i+' AND player1_1='+$j+') OR (player_1='+$j+' AND player1_1='+$i+')) AND score1 < score2");
			$wins_team2_sql = mysql_query("SELECT count(id) as totGames, SUM(score2) as posScore, SUM(score1) as negScore FROM games WHERE ((player_2='+$i+' AND player2_2='+$j+') OR (player_2='+$j+' AND player2_2='+$i+')) AND score1 < score2");
			$losses_team2_sql =	mysql_query("SELECT count(id) as totGames, SUM(score2) as posScore, SUM(score1) as negScore FROM games WHERE ((player_2='+$i+' AND player2_2='+$j+') OR (player_2='+$j+' AND player2_2='+$i+')) AND score1 > score2");
		
			$games_won = mysql_result($wins_team1_sql,0, "totGames") + mysql_result($wins_team2_sql,0, "totGames");
			$games_lost = mysql_result($losses_team1_sql,0, "totGames") + mysql_result($losses_team2_sql,0, "totGames");
			$goals_shot = mysql_result($wins_team1_sql,0, "posScore") + mysql_result($wins_team2_sql,0, "posScore") + 
								mysql_result($losses_team1_sql,0, "posScore") + mysql_result($losses_team2_sql,0, "posScore");
			$goals_received = mysql_result($wins_team1_sql,0, "negScore") + mysql_result($wins_team2_sql,0, "negScore") +
								mysql_result($losses_team1_sql,0, "negScore") + mysql_result($losses_team2_sql,0, "negScore");

			if (($games_lost + $games_won) > 0)
			{
				$foo[sizeof($foo)] = array($i, $j, $games_lost + $games_won, $games_won);
			}
		}
	}
	return $foo;
}


?>

