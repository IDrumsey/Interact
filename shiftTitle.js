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