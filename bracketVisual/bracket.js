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