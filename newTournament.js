let createNewTournament = document.getElementById("create");

createNewTournament.addEventListener('click', createTournament);

function createTournament() {
    document.getElementById("newTournament").submit();
}

function setOutlineColor(element){
    element.style.outlineColor = secondaryColor;
}