(function(){
    let settingsBtn = document.getElementById("settingsBtn");
    //let settingsMenuBtn = document.getElementById("settingsMenu");

    settingsBtn.addEventListener('click', toggleSettings);

    function toggleSettings(){
        if(settingsMenu.style.display == "block"){
            settingsMenu.style.display = "none";
        }
        else{
            settingsMenu.style.display = "block";
        }
    }

    let colorPreferences = document.getElementById("pickColorPreferences");

    window.updateColorPreferences = function(){
        colorPreferences.submit();
    }

    window.addEventListener('load', setColorScheme);

    function elementObj(selectorType, id, colorChoice, affectedStyle, attributes){
        let el = {};
        el.selectorType = selectorType;
        el.id = id;
        el.color = colorChoice;
        el.affectedStyle = affectedStyle;
        el.attributes = attributes;
        return el;
    }

    function setColorScheme(){
        let elementList = [];
        for(let i = 0; i < objList.length; i++){
            let tempEl = elementObj(objList[i][0], objList[i][1], objList[i][2], objList[i][3], objList[i][4]);
            elementList.push(tempEl);
        }
        let copies = [];
        for(let i = 0; i < elementList.length; i++){
            let tempRef;
            if(elementList[i].selectorType == "id"){
              tempRef = document.getElementById(elementList[i].id);
              elementList[i].ref = tempRef;
            }
            else if(elementList[i].selectorType == "class"){
              tempRef = document.getElementsByClassName(elementList[i].id);
              let tempAr = Array.from(tempRef);
              //set original
              elementList[i].ref = tempAr[0];
              //make copies
              let cpy;
              for(let j = 1; j < tempAr.length; j++){
                cpy = JSON.parse(JSON.stringify(elementList[i]));
                cpy.ref = tempAr[j];
                copies.push(cpy);
              }
            }
        }
        elementList = elementList.concat(copies);
        for(let i = 0; i < elementList.length; i++){
            let tempRef = elementList[i];
            let tempColor = "";
            if(tempRef.color == "primary"){
              tempColor = primaryColor;
            }
            else if(tempRef.color == "secondary"){
              tempColor = secondaryColor;
            }
            else if(tempRef.color == "tertiary"){
                tempColor = tertiaryColor;
            }
            switch(tempRef.affectedStyle){
                case "background":
                    if(tempRef.attributes['type'] == "linear-gradient"){
                        let gradientDir = tempRef.attributes['direction'];
                        tempRef.ref.style.backgroundImage = "linear-gradient(to bottom, " + tempColor + ", " + tertiaryColor + ")";
                    }
                    else if(tempRef.attributes['type'] == "solid"){
                        tempRef.ref.style.backgroundColor = tempColor;
                    }
                    break;
                case "text-shadow":
                    tempRef.ref.style.textShadow = "0 0 10px " + tempColor;
                    break;
                case "border":
                    let tempWidth = tempRef.attributes['borderWidth'];
                    if(tempRef.attributes['sides'] == "full"){
                        tempRef.ref.style.border = tempWidth + " solid " + tempColor;
                        break;
                    }
                    else if(tempRef.attributes['sides'] == "left"){
                        tempRef.ref.style.borderLeft = tempWidth + " solid " + tempColor;
                        break;
                    }
                    else if(tempRef.attributes['sides'] == "right"){
                        tempRef.ref.style.borderRight = tempWidth + " solid " + tempColor;
                        break;
                    }
                    else if(tempRef.attributes['sides'] == "top"){
                        tempRef.ref.style.borderTop = tempWidth + " solid " + tempColor;
                        break;
                    }
                    else if(tempRef.attributes['sides'] == "bottom"){
                        tempRef.ref.style.borderBottom = tempWidth + " solid " + tempColor;
                        break;
                    }
                case "color":
                    tempRef.ref.style.color = tempColor;
                    break;
              default:
                console.log("Case not found");
            }
        }
    }
})();