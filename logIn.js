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

let inp = document.getElementsByClassName('fi');

for(let i = 0; i < inp.length; i++){
    inp[i].addEventListener('click', moveTitle);
}

let lastFocus;
let focus = 0;

function moveTitle(e){
    let title = this.parentNode.childNodes[1];
    if(focus == 1){
        if(document.elementFromPoint(e.clientX, e.clientY) != lastFocus){
            let temp = lastFocus.parentNode.childNodes[1];
            temp.classList.remove('slideRight');
            temp.classList.add('slideLeft');
            temp.style.textShadow = "none";
        }
    }
    if(focus == 0){
        focus = 1;
    }
    window.addEventListener('click', checkNot);
    //move to right
    title.classList.remove('slideLeft');
    title.classList.add('slideRight');
    title.style.textShadow = "0 0 5px #fff";
    lastFocus = this;
}

function checkNot(e){
    let under = document.elementFromPoint(e.clientX, e.clientY);
    if(under != lastFocus){
        //slide back
        let title = lastFocus.parentNode.childNodes[1];
        title.classList.remove('slideRight');
        title.classList.add('slideLeft');
        title.style.textShadow = "none";
        //remove listener
        window.removeEventListener('click', checkNot);
    }
}