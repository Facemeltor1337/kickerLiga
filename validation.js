/**
 * 
 */

var indexOf = function(needle) {
    if(typeof Array.prototype.indexOf === 'function') {
        indexOf = Array.prototype.indexOf;
    } else {
        indexOf = function(needle) {
            var i = -1, index = -1;

            for(i = 0; i < this.length; i++) {
                if(this[i] === needle) {
                    index = i;
                    break;
                }
            }

            return index;
        };
    }

    return indexOf.call(this, needle);
};

function checkName(name, lAvailableNames) {
	if(indexOf.call(lAvailableNames, name) < 0) {
		document.getElementById("errors").innerHTML += (name + " existiert nicht in der Datenbank!<br/>");
		return false;
	}
	return true;
}

function checkScore() {
	var scoreTeam1 = document.forms['spielEintrag']['t1score'].value;
	var scoreTeam2 = document.forms['spielEintrag']['t2score'].value;
	var totalScore = 0;
	var goalDifference = Math.abs(scoreTeam1-scoreTeam2);
	
	if(scoreTeam1 != scoreTeam2){
		totalScore = scoreTeam1 + scoreTeam2; 
		if(scoreTeam1==10 || scoreTeam2==10){
			return (totalScore == 19) || (totalScore==18);
		}else{
			if(scoreTeam1>6 || scoreTeam2>6){
				return (totalScore>=10 && goalDifference==2);
			}else{
				return ((scoreTeam1==6||scoreTeam2==6)&&goalDifference>=2);
			}
			
		}
		
	}else{
		return false;
	}
	
}

function namesUnique() {
	var nameSet = new Set();
	
	nameSet.add(document.forms['spielEintrag']['t1s1'].value);
	nameSet.add(document.forms['spielEintrag']['t1s2'].value);
	nameSet.add(document.forms['spielEintrag']['t2s1'].value);
	nameSet.add(document.forms['spielEintrag']['t2s2'].value);
	
	return nameSet.size == 4;
}

function validateInput(listOfNames) {

	var valid = true;
	document.getElementById("errors").innerHTML = "";
	
	if (!namesUnique()) {
		valid = false;
		document.getElementById("errors").innerHTML += ("Spieler doppelt vorhanden!<br/>");
	}
	
	var currentName = document.forms['spielEintrag']['t1s1'].value;
    if (!checkName(currentName, listOfNames)){
    	valid = false;
    }
    currentName = document.forms['spielEintrag']['t1s2'].value;
    if (!checkName(currentName, listOfNames)){
    	valid = false;
    }
    currentName = document.forms['spielEintrag']['t2s1'].value;
    if (!checkName(currentName, listOfNames)){
    	valid = false;
    }
    currentName = document.forms['spielEintrag']['t2s2'].value;
    if (!checkName(currentName, listOfNames)){
    	valid = false;
    }
    
    if (!checkScore()) {
    	valid = false;
    	document.getElementById("errors").innerHTML += ("Spielstand ist nicht korrekt!<br/>");
    }

    return valid;
    
}