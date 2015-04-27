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

function getWinRatio($id)
{
	$wins_looses = getWinsAndLoose($id);
	$overall = getNumberOfGamesByPlayer($id);
	$a_s_nr = getAbwehrSturm($id);
	$ratio = $wins_looses[0] / $overall * 100;
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
	for ($i=0;$i<$n;$i++)
	{
	$play1 = mysql_result($sql_player1,$i, "player_1");
	$play1_1 = mysql_result($sql_player1,$i, "player1_1");
	$play2 = mysql_result($sql_player1,$i, "player_2");
	$play2_2 = mysql_result($sql_player1,$i, "player2_2");
	$score1 = mysql_result($sql_player1,$i, "score1");
	$score2 = mysql_result($sql_player1,$i, "score2");
	if ($play1 == $id or $play1_1 == $id)
	{
		//if the player is in team 1, than add score 1 good and score2 badgoals
		$goodGoals += $score1; 		
		$badGoals += $score2;
		if ($score1 > $score2)
		{
			$win += 1;
		}		
		else
		{
			$loose +=1;
		}
	}
	else
	{
		$goodGoals += $score2; 		
		$badGoals += $score1;
		if ($score1 < $score2)
		{
			$win += 1;
		}		
		else
		{
			$loose +=1;
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

function getLast10($id)
{
	$foo = array();
	$sql_player1 = mysql_query("SELECT * FROM games where player_1='$id' or player_2='$id' or player1_1='$id' or player2_2='$id' order by date DESC");
	$n = mysql_num_rows($sql_player1);
	for ($i=0;$i<$n;$i++)
	{
	$play1 = mysql_result($sql_player1,$i, "player_1");
	$play1_1 = mysql_result($sql_player1,$i, "player1_1");
	$play2 = mysql_result($sql_player1,$i, "player_2");
	$play2_2 = mysql_result($sql_player1,$i, "player2_2");
	$score1 = mysql_result($sql_player1,$i, "score1");
	$score2 = mysql_result($sql_player1,$i, "score2");
	$temp = '';
	if ($play1 == $id or $play1_1 == $id)
	{
		if ($score1 > $score2)
		{
			$temp = 1;
		}		
		else
		{
			$temp =-1;
		}
	}
	else
	{
		if ($score1 < $score2)
		{
			$temp = 1;
		}		
		else
		{
			$temp =-1;
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
	$sql_team1 = mysql_query("SELECT * FROM games where 
				(player_1='$player1' and player1_1='$player1_1')
				or 
				(player_1='$player1_1' and player1_1='$player1')
				or
				(player_2='$player1' and player2_2='$player1_1')
				or				
				(player_2='$player1_1' and player2_2='$player1')
				Order by date DESC");
	$sql_team2 = mysql_query("SELECT * FROM games where 
				(player_1='$player2' and player1_1='$player2_2')
				or 
				(player_1='$player2_2' and player1_1='$player2')
				or
				(player_2='$player2' and player2_2='$player2_2')
				or				
				(player_2='$player2_2' and player2_2='$player2')
				Order by date DESC");
	$gamesTeam1 = mysql_num_rows($sql_team1);
	$gamesTeam2 = mysql_num_rows($sql_team2);
	$countGames = 0;
	$ids = array();
	for($i=0;$i<$gamesTeam1;$i++)
	{
		for($j=0;$j<$gamesTeam2;$j++)
		{
			$id1 = mysql_result($sql_team1,$i, "id");
			$id2 = mysql_result($sql_team2,$j, "id"); 
			if (($id1 == $id2) and ($id1 != $gameID or $id2 != $gameID))
			{
				$countGames += 1;
				$ids[] = $id1;
			}
		}
	}
	return array($countGames, $ids);
}



function getAbwehrSturm($id)
{
	$sql_player1 = mysql_query("SELECT * FROM games where player_1='$id' or player_2='$id' or player1_1='$id' or player2_2='$id'");
	$n = mysql_num_rows($sql_player1);
	$sturm = 0;
	$abwehr = 0;
	$awins = 0;
	$swins = 0;
	$aloose = 0;
	$sloose = 0;
	for ($i=0;$i<$n;$i++)
	{
	$play1 = mysql_result($sql_player1,$i, "player_1");
	$play1_1 = mysql_result($sql_player1,$i, "player1_1");
	$play2 = mysql_result($sql_player1,$i, "player_2");
	$play2_2 = mysql_result($sql_player1,$i, "player2_2");
	$score1 = mysql_result($sql_player1,$i, "score1");
	$score2 = mysql_result($sql_player1,$i, "score2");
	//plays allone
	if ($id == $play1 and $play1_1 == 0)
	{
		//do nothing
		continue;
	}
	if ($id == $play2 and $play2_2 == 0)
	{
		//do nothing
		continue;
	}
	//plays Defense
	if ($id == $play1)
	{
		$abwehr += 1;
		if ($score1 > $score2)
		{
			$awins += 1;
		}		
		else
		{
			$aloose +=1;
		}
		continue;
	}
	if ($id == $play2)
	{
		$abwehr += 1;
		if ($score2 > $score1)
		{
			$awins += 1;
		}		
		else
		{
			$aloose +=1;
		}
		continue;
	}
	//plays Forward
	if ($id == $play1_1)
	{
		//do nothing
		$sturm += 1;
		if ($score1 > $score2)
		{
			$swins += 1;
		}		
		else
		{
			$sloose +=1;
		}
		continue;
	}
	if ($id = $play2_2)
	{
		//do nothing
		$sturm += 1;
		if ($score2 > $score1)
		{
			$swins += 1;
		}		
		else
		{
			$sloose +=1;
		}
		continue;
	}
	}
	return array(array($abwehr, $awins, $aloose), array($sturm, $swins, $sloose));
}

function getLigaInfo($id)
{
	$sql_player1 = mysql_query("SELECT * FROM liga where id='$id'");
	$name = mysql_result($sql_player1,0, "name");
	$text = mysql_result($sql_player1,0, "text");
	$status = mysql_result($sql_player1,0, "status");
	
	return array($name, $text, $status);
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
				if ($foo[$j][0] == $play1 and $foo[$j][1] == $play1_1)
				{
					//add one to games
					$games =  $foo[$j];
					$games = $games[2];
					$games = $games + 1;
					//add wins now
					$wins = $foo[$j];
					$wins = $wins[3];
					if ($score1 > $score2)
					{
						$wins = $wins +1 ;
					}

					$foo[$j] = array($play1, $play1_1, $games, $wins);
					$foundTeam = 1;
					break;
				}
				if ($foo[$j][0] == $play1_1 and $foo[$j][1] == $play1)
				{
					$games =  $foo[$j];
					$games = $games[2];
					$games = $games + 1;
					//add wins now
					$wins = $foo[$j];
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
				if ($foo[$j][0] == $play2 and $foo[$j][1] == $play2_2)
				{
					$games =  $foo[$j];
					$games = $games[2];
					$games = $games + 1;
					//add wins now
					$wins = $foo[$j];
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
				if ($foo[$j][0] == $play2_2 and $foo[$j][1] == $play2)
				{
					$games =  $foo[$j];
					$games = $games[2];
					$games = $games + 1;
					//add wins now
					$wins = $foo[$j];
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




?>

