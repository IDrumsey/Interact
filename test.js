let btnwrap = document.getElementsByClassName('btnWrap');

const timeInt = 150;
const borderW = 4;

for(let i = 0; i < btnwrap.length; i++){
    btnwrap[i].addEventListener('mouseenter', openBorder);
    btnwrap[i].addEventListener('mouseleave', closeBorder);
}

let t;

function openBorder(){
    let bt = this.getElementsByClassName("btnBorderTOP")[0];
    let bb = this.getElementsByClassName("btnBorderBOTTOM")[0];
    let bl = this.getElementsByClassName("btnBorderLEFT")[0];
    let br = this.getElementsByClassName("btnBorderRIGHT")[0];
    console.log("opening");
    console.log(bt, bb, bl, br);
    expandBorder(bt, 1, 0);
    t = setTimeout(expandBorder, timeInt, br, 0, 1);
    t = setTimeout(expandBorder, timeInt * 2, bb, 1, 0);
    t = setTimeout(expandBorder, timeInt * 3, bl, 0, 1);
}

function closeBorder(){
    let bt = this.getElementsByClassName("btnBorderTOP")[0];
    let bb = this.getElementsByClassName("btnBorderBOTTOM")[0];
    let bl = this.getElementsByClassName("btnBorderLEFT")[0];
    let br = this.getElementsByClassName("btnBorderRIGHT")[0];
    console.log("collapsing");
    collapseBorder(bl, 0, 1);
    t = setTimeout(collapseBorder, timeInt, bb, 1, 0);
    t = setTimeout(collapseBorder, timeInt * 2, br, 0, 1);
    t = setTimeout(collapseBorder, timeInt * 3, bt, 1, 0);
}

function expandBorder(borderEl, width, height){
    if(width){
        borderEl.style.width = "100%";
    }
    else {
        borderEl.style.height = "100%";
    }
}

function collapseBorder(borderEl, width, height){
    if(width){
        borderEl.style.width = "0";
    }
    else {
        borderEl.style.height = "0";
    }
}