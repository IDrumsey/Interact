let registerBtn = document.getElementById("registerBtn");

registerBtn.addEventListener('click', register);

function register() {
    document.getElementById("registrationForm").submit();
}

window.addEventListener('load', checkForErrors);

function checkForErrors(){
    let searchParams = new URLSearchParams(window.location.search);
    //Check if on registration page
    if(searchParams.has('registration')){
        let errorCode = "";
        if(searchParams.get('registration') == "fail"){
            errorCode = searchParams.get('errorCode');
            for(let j = 0; j < errorCode.length; j++){
                getErrorMsg(errorCode.charAt(j));
            }
        }
    }
}

const errorCodes = [['a', "Email empty"], ['b', "Username empty"], ['c', "Password Empty"], ['d', "Password Confirmation empty"], ['e', "Passwords don't match"], ['f', "Invalid email"], ['g', "Username not long enough"], ['h', "Password doesn't contain a number"], ['i', "Password not long enough"], ['j', "Password doesn't have special character"]];

function getErrorMsg(errorCode){
    let errorMsg = "";
    for(let i = 0; i < errorCodes.length; i++){
        if(errorCode == errorCodes[i][0]){
            errorMsg = errorCodes[i][1];
            break;
        }
    }
    addErrorMsg(errorMsg);
}

let errorSection = document.getElementById('errors');
function addErrorMsg(errorMsg){
    let wrapper = document.createElement('div');
    let errorText = document.createElement('h2');
    errorText.innerHTML = errorMsg;
    wrapper.appendChild(errorText);
    errorSection.appendChild(wrapper);
}