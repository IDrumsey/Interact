let searchBtn = document.getElementById("searchBtnActual");

searchBtn.addEventListener('click', findPlayers);

function findPlayers(){
    document.getElementById("newPlayerSearch").submit();
}