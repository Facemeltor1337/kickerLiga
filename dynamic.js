function countSelections(min, max)
{
	var select = document.getElementById( 'player' );
	var count = 0;
	for ( var i = 0, l = select.options.length, o; i < l; i++ )
	{
	  if(l > 0)
	  {
		  o = select.options[i];
		  if (o.selected )
		  {
		  count = count + 1;
		
		  }
	  }
	}

	text = '';
	if(min <= count)
	{
		if(count <= max)
		{
			if (count % 2 == 0)
			{
			text = "<font color='green'>" + count + "/" + max +"</font><br>Mit diesem Spielern kann die Runde gestartet werden!";
			document.getElementById('konfButton').disabled = false;
			}
			else
			{
				text = "<font color='red'>" + count + "/" + max +"</font><br>Ungerade Anzahl an Spielern!";
				document.getElementById('konfButton').disabled = true;
			}
		}
		else
		{
			text = "<font color='red'>" + count + "/" + max +"</font><br>Zuviele Spieler ausgewählt";
			document.getElementById('konfButton').disabled = true;
		}
	}
	else
	{
		text = "<font color='red'>" + count + "/" + min  +"</font><br>Nicht genügend Spieler ausgewählt!";
		document.getElementById('konfButton').disabled = true;
	}
	document.getElementById('correctPlayers').innerHTML = text;
	
}