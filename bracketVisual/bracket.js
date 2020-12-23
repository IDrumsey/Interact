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
        let tmpIcon = buildIcon(['fas', 'fa-crown', 'setChampion']);
        optionsWrapper.appendChild(tmpIcon);
        //hoverIcon(tmpIcon);
        //option for resetting match time
        tmpIcon = buildIcon(['fas', 'fa-calendar-alt', 'resetTime']);
        optionsWrapper.appendChild(tmpIcon);
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