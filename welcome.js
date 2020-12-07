let continueBtn = document.getElementById("continueBtn");
continueBtn.addEventListener('click', insertGames);

function insertGames(){
    document.getElementById("newPlayerForm").submit();
}

let popup = document.getElementById("popup");

const errorCodes = [['a', "Profile image not selected", false], ['b', "File extension not allowed", false], ['c', "Choose a different profile image", false], ['d', "File size too large", false]];

window.addEventListener('load', checkForErrors);

function checkForErrors(){
    let searchParams = new URLSearchParams(window.location.search);
    let maxMsgs = errorCodes.length;
    let currErrMsg = "";
    if(searchParams.has('errorCodes')){
        let countMsgs = 0;
        //show error section on page
        popup.style.display = "block";
        let errorCode = searchParams.get('errorCodes');
        for(let j = 0; j < errorCode.length; j++){
           //get code position
           let codePos = getErrorPos(errorCode.charAt(j));
           do{
            countMsgs++;
            validCode = false;
            if(codePos != -1){
                currErrMsg += getErrorMsg(codePos);
                validCode = true;
            }
           } while(validCode == true && checkMore(codePos) == true && countMsgs <= maxMsgs);
           //add error msg to page
           createErrorMsg(currErrMsg);
           currErrMsg = "";
        }
    }
}

function getErrorPos(errorCode){
    for(let c = 0; c < errorCodes.length; c++){
        if(errorCodes[c][0] == errorCode){
            return c;
        }
    }
    return -1;
}

function getErrorMsg(pos) {
    return errorCodes[pos][1];
}

function checkMore(pos){
    if(errorCodes[pos][2] == true){
        return true;
    }
    else{
        return false;
    }
}

function createErrorMsg(errorMsg) {
    let wrapper = document.createElement('div');
    let content = document.createElement('h2');
    content.innerHTML = errorMsg;
    wrapper.appendChild(content);
    popup.appendChild(wrapper);
}