let defaultConnectorWidth = 50;
let pointRadius = 20;

function Day(date, ref){
    //console.log("Creating Day - Date = ", date);
    let obj = {};
    obj.ref = ref;
    obj.dateRaw = date;
    //console.log("just set date = ", obj.dateRaw);
    obj.dateHR = formatDate(date);
    obj.setDay = function(){
        //console.log("raw : ", this.dateRaw);
        obj.day = dayNames[this.dateRaw.getDay()];
    }
    obj.setTimelineRef = function(timelineRef){
        obj.timelineRef = timelineRef;
    }
    obj.setTimeline = function(timeline){
        obj.timeline = timeline;
    }
    return obj;
}

function Timeline(x, y, ref){
    let obj = {};
    obj.ref = ref;
    obj.x = x;
    obj.y = y;
    obj.height = obj.ref.offsetHeight;
    obj.width = ref.offsetWidth;
    obj.points = [];
    obj.addPoint = function(point, mouseX, mouseY){
        obj.points.push(point);
        this.ref.appendChild(point.ref);
        point.init(mouseX, mouseY, this.y, this.height);
        //add time
        this.ref.appendChild(point.time.ref);
    }
    obj.checkPointPosAvailable = function(mouseX){
        for(let i = 0; i < this.points.length; i++){
            let tmpPoint = this.points[i];
            //get bounds
            let existingLeftBound = tmpPoint.x;
            let existingRightBound = tmpPoint.x + tmpPoint.width;
            let newLeftBound = mouseX;
            let newRightBound = mouseX + tmpPoint.width;
            //Check if bounds overlap
            if((newLeftBound >= existingLeftBound && newLeftBound <= existingRightBound) || (newRightBound <= existingRightBound && newRightBound >= existingLeftBound)){
                return false;
            }
        }
        return true;
    }
    obj.findPoint = function(pointRef){
        for(let i = 0; i < this.points.length; i++){
            if(this.points[i].ref == pointRef){
                return this.points[i];
            }
        }
    }
    obj.changePos = function(){
        let coords = getElementPosition(this.ref);
        this.y = coords.top;
    }
    obj.rerender = function(){
        //update timeline pos
        this.changePos();
        //check if in bounds
        if(this.y > bodyStart - pointRadius && this.y + this.height < bodyEnd + pointRadius){
            //rerender points
            for(let i = 0; i < this.points.length; i++){
                //Check if point is showing
                if(this.points[i].show == false){
                    //toggle show on
                    this.points[i].toggleShow(true);
                }
                this.points[i].rerender();
            }
        }
        else{
            //togglePoints off
            if(this.points.length > 0 && this.points[0].show == true){
                for(let i = 0; i < this.points.length; i++){
                    //Wait for the points transition time
                    //REVIEW
                    let wait = setTimeout(()=>{
                        this.points[i].toggleShow(false);
                    }, this.points[i].tSpeed);
                }
            }
        }
    }
    obj.wrapUp = function(){
        let times = [];
        for(let i = 0; i < this.points.length; i++){
            //set start time and end time
            let tmpPointA = this.points[i];
            if(tmpPointA.anchor == true){
                //get second point
                let tmpPointB = tmpPointA.connected;
                //check left and right
                let leftPoint;
                let rightPoint;
                if(tmpPointA.x <= tmpPointB.x){
                    leftPoint = tmpPointA;
                    rightPoint = tmpPointB;
                }
                else{
                    leftPoint = tmpPointB;
                    rightPoint = tmpPointA;
                }
                //Get times
                let leftTime = leftPoint.time.timeActual;
                let rightTime = rightPoint.time.timeActual;
                let timeInterval = {
                    start: leftTime,
                    end: rightTime
                }
                times.push(timeInterval);
            }
        }
        return times;
    }
    return obj;
}

function Point(x, y, ref, timeline, anchor) {
    let obj = {};
    obj.ref = ref;
    obj.x = x;
    obj.y = y;
    obj.show = true;
    obj.timeline = timeline;
    obj.anchor = anchor;
    obj.init = function(x, y, timelineY, timelineHeight){
        this.x = x;
        this.width = this.ref.offsetWidth;
        this.ref.style.width = pointRadius;
        this.ref.style.height = this.width;
        this.y = timelineY - ((this.ref.offsetHeight - timelineHeight) / 2);
        this.render();
        this.setMoveRate();
    }
    obj.render = function(){
        obj.ref.style.left = this.x - (this.width / 2) + "px";
        obj.ref.style.top = this.y + "px";
    }
    obj.changePos = function(e){
        let mouseCoords = getMousePosition(e);
        if(mouseCoords.x >= obj.timeline.x && mouseCoords.x <= obj.timeline.x + obj.timeline.width){
            //change pos
            obj.x = mouseCoords.x;
            obj.render();
            obj.connector.connectPoints();
        }
        //change time pos
        obj.time.changePos(obj.x);
    }
    obj.setConnector = function(connector, next){
        obj.connector = connector;
    }
    obj.setTime = function(timeDisplay){
        obj.time = timeDisplay;
        obj.time.createEl();
    }
    obj.rerender = function(){
        //get timeline y
        let timelineBottom = this.timeline.y;
        this.y = timelineBottom - this.timeline.height / 2;
        this.render();
    }
    obj.toggleShow = function(show){
        if(show == false){
            //Check if point is halfway over border
            this.ref.style.display = "none";
            this.show = false;
        }
        else{
            this.ref.style.display = "block";
            this.show = true;
        }
    }
    obj.setConnected = function(point){
        this.connected = point;
    }
    obj.setMoveRate = function(){
        let speed = getRandomNum(1000);
        this.tSpeed = speed;
        this.ref.style.transition = "top " + speed + "ms";
    }
    return obj;
}

function Connector(pointA, pointB){
    obj = {};
    obj.pointA = pointA;
    obj.pointB = pointB;
    obj.createEl = function(){
        let tmpEl = document.createElement('div');
        tmpEl.classList.add('connector');
        this.ref = tmpEl;
    }
    obj.setColor = function(){
        let randColor = getRandomColor();
        this.ref.style.backgroundColor = randColor;
    }
    obj.connectPoints = function(){
        //find which point is on left
        let leftPoint = this.findLeft();
        let timelineOffset = leftPoint.timeline.x;
        let leftPointleft = leftPoint.x - timelineOffset;
        this.moveLeft(leftPointleft);
        //set width to right point left - left point left
        this.setWidth(this.getWidth());
    }
    obj.moveLeft = function(left){
        this.ref.style.left = left + "px";
    }
    obj.setWidth = function(width){
        this.ref.style.width = width + "px";
    }
    obj.findLeft = function(){
        if(this.pointA.x < this.pointB.x){
            return this.pointA;
        }
        else{
            return this.pointB;
        }
    }
    obj.getWidth = function(){
        return (Math.abs(this.pointA.x - this.pointB.x));
    }
    return obj;
}

function TimeDisplay(x, y, timeline){
    let obj = {};
    obj.x = x;
    obj.y = y;
    obj.timeline = timeline;
    obj.createEl = function(){
        let wrapper = document.createElement('div');
        wrapper.classList.add("timeDisplay");
        let timeDisplay = document.createElement('h4');
        wrapper.appendChild(timeDisplay);
        obj.ref = wrapper;
        obj.timeText = obj.ref.childNodes[0];
        this.setTime();
        this.setPos();
    }
    obj.setPos = function(){
        this.ref.style.left = x - this.timeline.x - 20 + "px";
        this.ref.style.top = 15 + "px";
    }
    obj.changePos = function(x){
        this.x = x;
        this.ref.style.left = x - this.timeline.x - 20 + "px";
        this.setTime();
    }
    obj.toggleShow = function(show){
        if(show == 1){
            this.ref.style.display = "block";
        }
        else{
            this.ref.style.display = "none";
        }
    }
    obj.calcTime = function(){
        return (this.x - this.timeline.x) / this.timeline.width;
    }
    obj.setTime = function(){
        let ratio = this.calcTime();
        let tmpTime = getTime(ratio);
        let settmpTime = this.formatTime(tmpTime.hour) + ":" + this.formatTime(tmpTime.minutes);
        this.timeText.innerText = settmpTime;
        this.timeActual = settmpTime;
    }
    obj.formatTime = function(num){
        if(num < 10){
            return "0" + num.toString();
        }
        else{
            return num.toString();
        }
    }
    return obj;
}

//get body el
let bodyMain = document.getElementById('mainBody');
//get body start and stop
let bodyDims = bodyMain.getBoundingClientRect();
let bodyStart = bodyDims.top;
let bodyEnd = bodyStart + bodyMain.offsetHeight;

let dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
let startDate = new Date();
let endDate = new Date("2020/12/12");
let days = [];

for(let currDate = startDate; currDate < endDate; currDate.setDate(currDate.getDate() + 1)){
    //Create day markup
    let tmpRawDate = new Date(currDate);
    let dayWrapper = document.createElement('div');
    dayWrapper.classList.add('day');
    let dayText = document.createElement('h2');
    dayText.classList.add('dayTitle');
    dayWrapper.appendChild(dayText);
    //create new day and set day
    //console.log("TMP : ", currDate);
    let tmpDate = Day(tmpRawDate, dayWrapper);
    tmpDate.setDay();
    dayText.innerText = tmpDate.day;
    //console.log("tmpdate : ", tmpDate);
    days.push(tmpDate);
    //console.log("tmpDate2 : ", tmpDate);
    //console.log("days : ", days);
    //Add labels
    let labelWrapper = document.createElement('div');
    labelWrapper.classList.add('labels');
    let dayStart = document.createElement('h4');
    let dayMiddle = document.createElement('h4');
    let dayEnd = document.createElement('h4');
    dayStart.innerText = "12:00am";
    dayMiddle.innerText = "Noon";
    dayEnd.innerText = "11:59pm";
    labelWrapper.appendChild(dayStart);
    labelWrapper.appendChild(dayMiddle);
    labelWrapper.appendChild(dayEnd);
    dayWrapper.appendChild(labelWrapper);
    //create slider
    let slider = document.createElement('div');
    slider.classList.add('times');
    dayWrapper.appendChild(slider);
    tmpDate.setTimelineRef(slider);

    //Push onto page
    bodyMain.appendChild(tmpDate.ref);
}

//scroll event listener
bodyMain.addEventListener('scroll', ()=>{
    //go through each day and rerender
    for(let i = 0; i < days.length; i++){
        //run rerender
        days[i].timeline.rerender();
    }
})

for(let i = 0; i < days.length; i++){
    //Get position of element
    let pos = getElementPosition(days[i].timelineRef);
    //Create obj
    let tmpTimeline = Timeline(pos.left, pos.top, days[i].timelineRef);
    //addTimeline to day
    days[i].setTimeline(tmpTimeline);
    //add event listener
    days[i].timelineRef.addEventListener('click', function(e){
        let currTimelineRef = this;
        let currTimelineObj = findTimeline(tmpTimeline.ref);
        //Check if point already there
        let validPos = currTimelineObj.checkPointPosAvailable(e.pageX);
        if(validPos == false){
        }
        else{
            //create first point
            let pointElA = document.createElement('div');
            pointElA.classList.add('point');
            let tmpPointA = Point(e.pageX, e.pageY, pointElA, currTimelineObj, true);
            let tmpTimeA = TimeDisplay(tmpPointA.x, tmpPointA.y, currTimelineObj);
            tmpPointA.setTime(tmpTimeA);
            //add point to timeline
            currTimelineObj.addPoint(tmpPointA, e.pageX, e.pageY);
            let currPointA;
            tmpPointA.ref.addEventListener("mousedown", ()=>{
                let currTimeLine = findTimeline(this);
                currPointA = currTimeLine.findPoint(tmpPointA.ref);
                //show point time
                currPointA.time.toggleShow(true);
                window.addEventListener("mousemove", currPointA.changePos);
                window.addEventListener("mouseup", ()=>{
                    window.removeEventListener("mousemove", currPointA.changePos);
                    //hide point time
                    currPointA.time.toggleShow(false);
                })
            })
            //create second point
            let pointElB = document.createElement('div');
            pointElB.classList.add('point');
            //Check if room on right
            let pointOffset = defaultConnectorWidth;
            if(tmpPointA.x + defaultConnectorWidth >= currTimelineObj.x + currTimelineObj.width){
                pointOffset *= -1;
            }
            let tmpPointB = Point(e.pageX + pointOffset, e.pageY, pointElB, currTimelineObj, false);
            let tmpTimeB = TimeDisplay(tmpPointB.x, tmpPointB.y, currTimelineObj);
            tmpPointB.setTime(tmpTimeB);
            //add point to timeline
            currTimelineObj.addPoint(tmpPointB, e.pageX + pointOffset, e.pageY);
            let currPointB;
            tmpPointB.ref.addEventListener("mousedown", ()=>{
                let currTimeLine = findTimeline(this);
                currPointB = currTimeLine.findPoint(tmpPointB.ref);
                //show point time
                currPointB.time.toggleShow(true);
                window.addEventListener("mousemove", currPointB.changePos);
                window.addEventListener("mouseup", ()=>{
                    window.removeEventListener("mousemove", currPointB.changePos);
                    //hide point time
                    currPointB.time.toggleShow(false);
                })
            })
            //link points
            tmpPointA.setConnected(tmpPointB);
            tmpPointB.setConnected(tmpPointA);
            //Create connector
            let tmpConnector = Connector(tmpPointA, tmpPointB);
            tmpConnector.createEl();
            tmpConnector.setColor();
            //Add connector to points
            tmpPointA.setConnector(tmpConnector);
            tmpPointB.setConnector(tmpConnector);
            //Add to timeline
            currTimelineObj.ref.appendChild(tmpConnector.ref);
            tmpConnector.connectPoints();
        }
    });
}

//wrap up
let continueBtn = document.getElementById('continue');
continueBtn.addEventListener('click', ()=>{
    let timeIntervals = [];
    for(let c = 0; c < days.length; c++){
        //console.log("Day : ", days[c]);
        if(days[c].timeline.points.length > 0){
            let dayTimes = days[c].timeline.wrapUp();
            let fin = {
                date: days[c].dateHR,
                day: days[c].day,
                times: dayTimes
            }
            timeIntervals.push(fin);
        }
    }
    process(timeIntervals);
})

//console.log("Days = ", days);

//Functions

function formatDate(date){
    let tmpMonth = date.getMonth() + 1;
    return date.getFullYear() + "/" + tmpMonth + "/" + date.getDate();
}

function getTime(timeRatio){
    let minutesTotal = 1440 * timeRatio;
    let hours = Math.floor((minutesTotal / 60) % 12)
    if(hours == 0){
        hours = 12;
    }
    let minutes = Math.round(minutesTotal % 60);
    return {
        hour: hours,
        minutes: minutes
    }
}

function getRandomNum(max){
    return (Math.floor(Math.random() * (max + 1)));
}

function getRandomColor(){
    //get rgb
    let r = getRandomNum(255);
    let g = getRandomNum(255);
    let b = getRandomNum(255);
    return ("rgb(" + r + ", " + g + ", " + b + ")");
}

//Get window length
function getWindowDims(){
    return {
        width: window.innerWidth,
        height: window.innerHeight
    }
}

function findTimeline(timeLineRef) {
    for(let i = 0; i < days.length; i++){
        if(days[i].timelineRef == timeLineRef){
            return days[i].timeline;
        }
    }
}

let getMousePosition = function (e){
    //Check if mouse down
    let x = e.clientX;
    let y = e.clientY;
    return {
        x: x,
        y: y
    }
}

function getElementPosition(element){
    let position = element.getBoundingClientRect();
    return {
        left: position.left,
        top: position.top
    }
}

let popupBox = document.getElementById("popup");

function process(timeInts){
    //prep times
    let intervals = JSON.stringify(timeInts);
    let call = new XMLHttpRequest();
    //response
    call.onload = function(){
        console.log("Response : ", this.responseText);
        if(this.responseText == "Success"){
            window.location.href = "../index.php";
        }
        else{
            let msgBox = document.getElementById("msg");
            let msgText = msgBox.childNodes[0];
            msgText.innerText = "Error Occured";
            popupBox.style.display = "block";
        }
    }

    let pms = window.location.search;
    call.open("POST", "addUserAvailability.php" + pms);
    call.setRequestHeader("Content-type", "application/json");
    call.send(intervals);
}

let retryBtn = document.getElementById('acknowledge');

retryBtn.addEventListener('click', ()=>{
    popupBox.style.display = "none";
})