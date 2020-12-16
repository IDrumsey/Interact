function signIn(){
    console.log("attempting to sign in...");
    let signInForm = document.getElementById("signInForm");
    signInForm.submit();
}

let signInBtn = document.getElementById("signInBtn");
signInBtn.addEventListener("click", signIn);

window.addEventListener('load', checkStatus);

function checkStatus(){
    //Check if url has params
    let urlParams = new URLSearchParams(window.location.search);
    
    if(urlParams != ""){
        //check if param is fail
        if(urlParams.get('signIn') == "fail"){
            //Display error msg
            let errorMsg = "Username or password is incorrect.";
            let errorText = document.createElement('h2');
            errorText.innerHTML = errorMsg;
            let registerSection = document.getElementById("register");
            errorText.id = "failedSignIn";
            insertAfter(errorText, registerSection);
        }
    }
}

function insertAfter(newNode, prevNode){
    prevNode.parentNode.insertBefore(newNode, prevNode.nextSibling);
}