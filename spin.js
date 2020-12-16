function setListeners(className){
    let elements = Array.from(document.getElementsByClassName(className));

    for(let i of elements){
        i.addEventListener('click', runSpin);
    }
}

function runSpin(){
    this.classList.add("spin");
}

function redir(op){
    let red;
    switch(op) {
        case 1:
            red = "./player.php?player=" + userName;
            break;
    }

    setTimeout(() => {
        window.location.href = red;
    }, 2000);
}