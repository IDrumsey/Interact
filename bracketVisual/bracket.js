function displayDetails(element) {
    let match = element;
    for(let i = 0; i < match.childNodes.length; i++){
        if(match.childNodes[i].className == "matchDetails"){
            currDetails = match.childNodes[i];
            break;
        }
    }
    currDetails.style.display = "block";
}

function closeDetails(element, event){
    let currDetails = element.parentNode.parentNode.parentNode;
    currDetails.style.display = "none";
    event.stopPropagation();
}

function displaySection(){
    //Get correct section
    let sectionToShow = document.getElementsByClassName("sectionVisible")[0];
    sectionToShow.style.display = "block";
}

//get all option openers
let optionSelect = document.getElementsByClassName('matchWrapper');
for(let i = 0; i < optionSelect.length; i++){
    let tmpMatch = optionSelect[i].childNodes[0];
    tmpMatch.addEventListener('mouseenter', toggleOptionsOn);
    optionSelect[i].addEventListener('mouseleave', toggleOptionsOff);
}

let currOpenListener;

function toggleOptionsOn(){
    let optionTag = this.nextElementSibling;
    let optionOpener = optionTag.childNodes[0];

    optionTag.style.top = "100%";
    optionTag.style.opacity = "1";
    optionOpener.addEventListener('click', openMenu);
}

function openMenu(){
    let optionOpener = this;
    optionOpener.parentNode.style.width = "100%";
    //add options
    if(optionOpener.parentNode.childNodes.length < 2){
        let optionsWrapper = document.createElement('div');
        optionsWrapper.classList.add('optionsMenu');
        optionOpener.parentNode.appendChild(optionsWrapper);
        //option for declaring winner
        let setWinnerBtn = buildIcon(['fas', 'fa-crown', 'setChampion']);
        optionsWrapper.appendChild(setWinnerBtn);
        //hoverIcon(tmpIcon);
        //option for resetting match time
        tmpIcon = buildIcon(['fas', 'fa-calendar-alt', 'resetTime']);
        optionsWrapper.appendChild(tmpIcon);

        //listen for set winner btn
        setWinnerBtn.addEventListener('click', setWinner)
    }
    optionOpener.style.width = "15%";
}

function hoverIcon(icon){
    let styles = [['text-shadow', '0 0 10px #fff']];
    icon.addEventListener('mouseover', () => {
        for(let j = 0; j < styles.length; j++){
            setStyle(icon, styles[i][0], styles[i][1]);
        }
    });
}

function setStyle(element, styleProp, styleVal){
    element.style.styleProp = styleVal;
}

function buildIcon(classes){
    let iconWrap = document.createElement('div');
    let icon = document.createElement('i');
    for(let j = 0; j < classes.length; j++){
        icon.classList.add(classes[j]);
    }
    iconWrap.appendChild(icon);
    return iconWrap;
}

function toggleOptionsOff(){
    let optionTag = this.childNodes[1];
    let optionOpener = optionTag.childNodes[0];
    optionOpener.removeEventListener('click', openMenu);
    optionTag.style.top = "0";
    optionTag.style.opacity = "0";
    optionOpener.style.width = "100%";
    optionTag.style.width = "15%";
    //remove menu
    if(optionTag.childNodes.length == 2){
        optionTag.removeChild(optionTag.childNodes[1]);
    }
}

function setWinner(){
    //find select winner form
    let selectWinner = this.parentNode.parentNode.parentNode.childNodes[3];
    selectWinner.style.display = "block";
    //listen for click on team
        //get teams
        let tA = selectWinner.childNodes[5].childNodes[1];
        let tB = selectWinner.childNodes[5].childNodes[3];
        tA.addEventListener('click', setWinningTeam);
        tB.addEventListener('click', setWinningTeam);
}

function setWinningTeam(){
    //undo all previous style changes
        //get alt
        let teams = this.parentNode;
        let teamA = teams.childNodes[1];
        let teamB = teams.childNodes[3];

        let tA_icon = teamA.getElementsByTagName('i')[0];
        let tA_name = teamA.getElementsByTagName('h2')[0];
        let tB_icon = teamB.getElementsByTagName('i')[0];
        let tB_name = teamB.getElementsByTagName('h2')[0];

        tA_icon.style.color = "#fff";
        tB_icon.style.color = "#fff";
        tA_name.classList.remove('winner');
        tB_name.classList.remove('winner');


        
        teamA.style.color = "#fff";
        teamB.style.color = "#fff";
        teamA.style.textShadow = "none";
        teamB.style.textShadow = "none";


    let winnerIcon = this.getElementsByTagName('i')[0];
    let winnerName = this.getElementsByTagName('h2')[0];
    //change the icon color permanently
    winnerIcon.style.color = "rgb(172, 126, 1)";
    winnerName.classList.add('winner');

    //Listen for submit click

        //get btn
        let subBtn = this.parentElement.parentElement.getElementsByClassName('subWinner')[0];

        //listen
        subBtn.addEventListener('click', submitMatchWinner);
}

function submitMatchWinner(){
    //get winner team name
    let winner = this.parentNode.getElementsByClassName('winningTeam')[0].getElementsByClassName('winner')[0].innerText;

    //check that winner was set
    if(winner = ""){
        //display error msg
    }
    else{
        //send team name to submit winner
        let prms = new URLSearchParams(window.location.search);
        let tournament = prms.get("tournamentName");
        let roundNum = parseInt(this.parentNode.parentNode.parentNode.parentNode.getElementsByClassName('roundNumber')[0].childNodes[0].innerText.slice(-1), 10) - 1;
        let teams = this.parentNode.getElementsByClassName('winningTeam')[0];
        let teamA = teams.getElementsByClassName('teamA')[0].getElementsByTagName('h2')[0];
        let teamB = teams.getElementsByClassName('teamB')[0].getElementsByTagName('h2')[0];
        let teamAName = teamA.innerText;
        let teamBName = teamB.innerText;
        let winningTeamName = "";
        if(teamA.classList.length > 0){
            if(teamA.classList[0] == "winner"){
                winningTeamName = teamAName;
            }
        }
        else{
            if(teamB.classList[0] == "winner"){
                winningTeamName = teamBName;
            }
        }


        //Set people
        //get people
        let call = new XMLHttpRequest();
        //response
        let knownInts;
        call.onload = function(){
            //raw
            console.log("Response : ", this.responseText);
        }

        call.open("POST", "setMatchWinner.php");
        call.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        call.send("tournament=" + tournament + "&round=" + roundNum + "&teamA=" + teamAName + "&teamB=" + teamBName + "&winningTeam=" + winningTeamName);
    }
}