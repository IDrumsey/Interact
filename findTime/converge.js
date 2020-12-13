//person
function Person(name, availableTimes){
    let obj = {};
    obj.name = name;
    obj.availableTimes = sortAsc(availableTimes, null, 'start');
    obj.setUnavailable = function(){this.unavailableTimes = findUnavailable(this.availableTimes);}
    obj.logAvailable = function(){
        for(let i = 0; i < this.availableTimes.length; i++){
            let startTime = minToTime(this.availableTimes[i].start);
            let endTime = minToTime(this.availableTimes[i].end);
        }
    }
    obj.addAvailable = function(int){
        obj.availableTimes.push(int);
    }
    return obj;
}

let timelineEl = document.getElementById('timeline');
let commonTimes = document.getElementById('times');
const maxTime = 60 * 24;

//Algorithm for finding the best time for multiple peoples' schedules

//Calc the unavailable time intervals from the available time intervals

//for all unavailable times, mark index as false

//left with true values where time available for all

//get match
let prms = new URLSearchParams(window.location.search);
let match = prms.get("match");

//Set people
//get people
let call = new XMLHttpRequest();
//response
let knownInts;
call.onload = function(){
    //raw
    knownInts = JSON.parse(this.responseText);
    go(knownInts);
}

call.open("POST", "getMatchTimes.php");
call.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
call.send("match=" + match);

const timeDiff = 500;
const numPeople = 5;
const numIntervals = 5;
const intDiff = 300;

//initialize main timeline array
let timeline = Array(60 * 24);
timeline.fill(true);

//For each person and all their unavailable intervals mark those slots as false
//runAlg(peopleList);

//helper functions

let peopleActual = [];

function go(raw){
    setPeople(raw);

    //for all people order available
    for(let i = 0; i < peopleActual.length; i++){
        peopleActual[i].availableTimes = sortAsc(peopleActual[i].availableTimes, null, 'start');
    }


    //get all unique dates
    let dates = getDates(peopleActual);
    
    //get min date


    //for each date, assemble array of ints for that date and run alg

    let finalCommon = [];

    for(let i = 0; i < dates.length; i++){
        let thisDateInts = getInts(dates[i], peopleActual);
        //Convert each time to minutes
        let tmpAvail = [];
        for(let j = 0; j < thisDateInts.length; j++){
            let ti = thisDateInts[j];
            let ts = splitDateRaw(ti.start, ':');
            let te = splitDateRaw(ti.end, ':');
            let minStart = timeToMin(parseInt(ts.year),parseInt(ts.month),'pm');
            let minEnd = timeToMin(parseInt(te.year),parseInt(te.month),'pm');
            tmpAvail.push({start: minStart, end: minEnd});
        }
        
        //get unavailable for current date
        let thisDateUnavailable = findUnavailable(tmpAvail);
        //converge intervals for current date

        //run the algorithm
        finalCommon.push(runAlg(dates[i], thisDateUnavailable));
    }

    let rerenderCommon = [];

    //merge all common intervals as one
    for(let i = 0; i < finalCommon.length; i++){
        for(let j = 0; j < finalCommon[i].length; j++){
            rerenderCommon.push(finalCommon[i][j]);
        }
    }


    renderTimeline(rerenderCommon);
    

    //choose max
    let op = getMaxInt(rerenderCommon);

    let up = new XMLHttpRequest();
    //response
    let knownInts;
    up.onload = function(){
        window.location.href = "../bracketVisual/bracket.php";
    }

    up.open("POST", "addCommon.php");
    up.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    up.send("match=" + match + "&start=" + timeObjToFormatted(minToTime(op.start)) + "&end=" + timeObjToFormatted(minToTime(op.end)));
}

function getMaxInt(arr){
    let mxInt = arr[0];
    let mxDist = 0;
    for(let i = 0; i < arr.length; i++){
        let tmpDist = getDistance(arr[i].start, arr[i].end);
        if(tmpDist > mxDist){
            mxDist = tmpDist;
            mxInt = arr[i];
        }
    }
    return mxInt;
}

function formatTimes(arr){
    for(let i = 0; i < arr.length; i++){
        
    }
}

function setPeople(arr){
    let prevID; 
    let currPerson;
    for(let i = 0; i < arr.length; i++){
        if(i != 0){
            //Check if curr player id is different than prev
            if(arr[i].player_ID != prevID){
                //create new person
                //set currPerson to new
                currPerson = createPerson("name " + i);
                //push into existing
                peopleActual.push(currPerson);
            }
        }
        else {
            //create new person
            //set currPerson to new
            currPerson = createPerson("name " + i);
            //push into existing
            peopleActual.push(currPerson);
        }
        //createInterval
        let tDate = splitDateRaw(arr[i].match_date, '-');
        let ti = createIntervalD(tDate.month, tDate.day, tDate.year, arr[i].start_time, arr[i].end_time);
        //push new int to person
        currPerson.addAvailable(ti);
        //set prev id
        prevID = arr[i].player_ID;
    }
}

function createIntervalD(dM, dD, dY, start, end){
    let tmpDate = new Date(dY - 1, dM, dD);
    return {
        date: tmpDate,
        start: start,
        end: end
    };
}

function createInterval(start, end){
    return {
        start: start,
        end: end
    };
}

function splitDateRaw(date, del){
    let tmpChar;
    let ind = 0;
    let month = "";
    let day = "";
    let year = "";

    for(let i = 0; i < date.length; i++){
        tmpChar = date[i];
        if(tmpChar == del){
            ind++;
        }
        else{
            switch(ind){
                case 0:
                    year += tmpChar;
                    break;
                case 1:
                    month += tmpChar;
                    break;
                case 2:
                    day += tmpChar;
                    break;
            }
        }
    }

    return {
        month: month,
        day: day,
        year: year
    };
}

function getDates(people){
    let dates = [];
    let prevDate;
    for(let i = 0; i < people.length; i++){
        for(let j = 0; j < people[i].availableTimes.length; j++){
            let tmpDate = extractDateRaw(people[i].availableTimes[j].date);
            if(i > 0 || j > 0){
                if(tmpDate != prevDate){
                    dates.push(people[i].availableTimes[j].date);
                }
            }
            else{
                dates.push(people[i].availableTimes[j].date);
            }
            prevDate = tmpDate;
        }
    }
    return dates;
}

function extractDateRaw(date){
    let d = date.getDay();
    let m = date.getMonth() - 1;
    if(m == -1){
        m = 12;
    }
    let y = date.getFullYear();
    let td = m + "/" + d + "/" + y;

    return td;
}

function getMinDate(people){
    let minDate;
    for(let i = 0; i < people.length; i++){
        let currP = people[i];
        for(let j = 0; j < currP.availableTimes.length; j++){
            //Check if currDate is less than min
            if(i > 0 || j > 0){
                if(currP.availableTimes[j].date < minDate){
                    minDate = currP.availableTimes[j].date;
                }
            }
            else{
                minDate = currP.availableTimes[j].date;
            }
        }
    }
    return minDate;
}

function getInts(date, people){
    let dateC = extractDateRaw(date);
    let tmpIntervals = [];
    for(let c = 0; c < people.length; c++){
        let tmpPerson = people[c];
        for(let k = 0; k < tmpPerson.availableTimes.length; k++){
            let tmpInterval = tmpPerson.availableTimes[k];
            if(extractDateRaw(tmpInterval.date) == dateC){
                //Problem
                let tmpInt = createInterval(tmpInterval.start, tmpInterval.end);
                tmpIntervals.push(tmpInt);
            }
        }
    }
    return tmpIntervals;
}

function rawTimeToFormat(time){
    let tmpTime = splitRawTime(time);
}

function splitRawTime(time, delimeter){
    let hr = "";
    let min = "";
    let sec = "";
    ind = 0;
    for(let i = 0; i < time.length; i++){
        if(time[i] == delimeter){
            ind++;
        }
        else{
            switch(ind){
                case 0:
                    hr += time[i];
                    break;
                case 1:
                    min += time[i];
                    break;
                case 2:
                    sec += time[i];
                    break;
            }
        }
    }
    return {
        hr: hr,
        min: min,
        sec: sec
    }
}

function printInterval(startHr, startMin, startPer, endHr, endMin, endPer){
    return (startHr + ":" + startMin + startPer + ' - ' + endHr + ":" + endMin + endPer);
}

function getDistance(start, end){
    return end - start;
}

function getMax(arr){
    if(arr.length > 0){
        let tmpMax = arr[0];
        for(let i = 0; i < arr.length; i++){
            if(arr[i] > tmpMax){
                tmpMax = arr[i];
            }
        }
        return tmpMax;
    }
    else{
        return null;
    }
}

function renderTimeline(intervals){
    //remove all intervals
    let intElements = document.getElementsByClassName('interval');
    for(let i = 0; i < intElements.length; i++){
        timelineEl.removeChild(intElements[i]);
    }
    //add new Elements
    for(let i = 0; i < intervals.length; i++){
        //create el
        let tmpElement = document.createElement('div');
        tmpElement.classList.add('interval');
        //set left
        let left = intervals[i].start / maxTime;
        tmpElement.style.left = left * 100 + "%";
        let width = (intervals[i].end - intervals[i].start) / maxTime;
        tmpElement.style.width = width * 100 + '%';
        timelineEl.appendChild(tmpElement);
    }
}

function findUnavailable(availableTimes){
    unavailableTimes = [];
    for(let i = 0; i < availableTimes.length; i++){
        if(i == 0 && i != availableTimes.length - 1){
            unavailableTimes.push(findUnavailableInt(-1, availableTimes[i].start));
        }
        else if(i == 0 && i == availableTimes.length - 1){
            unavailableTimes.push(findUnavailableInt(-1, availableTimes[i].start));
            unavailableTimes.push(findUnavailableInt(availableTimes[i].end, 60 * 24));
        }
        else if(i == availableTimes.length - 1){
            unavailableTimes.push(findUnavailableInt(availableTimes[i-1].end, availableTimes[i].start));
            unavailableTimes.push(findUnavailableInt(availableTimes[i].end, 60 * 24));
        }
        else{
            unavailableTimes.push(findUnavailableInt(availableTimes[i-1].end, availableTimes[i].start));
        }
    }
    return unavailableTimes;
}

function createPerson(name){
    let tmpPerson = Person(name, []);
    return tmpPerson;
}

function runAlg(date, ut){
    for(let j = 0; j < ut.length; j++){
        let tmpInterval = ut[j];
        markUnavailable(timeline, tmpInterval[0], tmpInterval[1]);
        let tmpIntervals = extractCommon(timeline);
        if(j == 0){
            renderTimeline(tmpIntervals);
        }
        else{
            renderTimeline(tmpIntervals);
        }
    }
    //get the common
    let dateCommon = extractCommon(timeline);
    //reset the timeline
    timeline.fill(true);
    renderCommon(dateCommon);
    return dateCommon;
}

function renderCommon(common){
    for(let i = 0; i < common.length; i++){
        let tmpEl = document.createElement('h1');
        tmpEl.classList.add("commonTime");
        tmpEl.innerText = printTimeObj(minToTime(common[i].start)) + " - " + printTimeObj(minToTime(common[i].end));
        commonTimes.appendChild(tmpEl);
    }
}

function timeObjToFormatted(time){
    return time.hr + ":" + time.min;
}

function findUnavailableInt(endPrev, startCurr){
    return [endPrev + 1, startCurr - 1];
}

function markUnavailable(arr, start, end){
    if(start <= arr.length - 1 && end <= arr.length - 1){
        for(g = start; g <= end; g++){
            arr[g] = false;
        }
    }
}

function extractCommon(arr){
    let commonInts = [];
    let lastVal = 0;
    let tmpStart;
    let tmpEnd;
    for(let i = 0; i < arr.length; i++){
        if(arr[i] == true){
            if(lastVal == 0){
                lastVal = 1;
                tmpStart = i;
            }
        }
        else{
            if(lastVal == 1){
                lastVal = 0;
                tmpEnd = i - 1;
                commonInts.push({start: tmpStart, end: tmpEnd});
            }
        }
    }
    if(lastVal == 1){
        tmpStop = arr.length;
        commonInts.push({start: tmpStart, end: tmpStop});
    }
    
    return commonInts;
}

function genBase(intervals){
    let base = [];
    for(let i = 0; i < intervals.length; i++){
        base.push(genBaseInt(intervals[i].start, intervals[i].end));
    }

    return base;
}

function genBaseInt(start, end){
    return [start, end];
}

function getRandom(min, max, num, string, strings){
    if(num == 1){
        return (Math.floor(Math.random() * Math.floor(max + 1 - min)) + min);
    }
    else if(string == 1){
        return (strings[getRandom(0, strings.length - 1, 1, 0, null)]);
    }
}

function timeToMin(hr, min, per){
    let totMin;
    if(hr == 12){
        totMin = 0;
    }
    else{
        totMin = (60 * hr);
    }
    totMin += min;
    if(per == "pm"){
        totMin += 60 * 12;
    }
    return totMin;
}

function combineOverlapping(arr){
    let result = [];
    
}

//Testing Functions

function genTime(){
    let hr = getRandom(1, 12, 1, 0, null);
    let min = getRandom(0, 59, 1, 0, null);
    let per = getRandom(null, null, 0, 1, ['am', 'pm']);

    return {
        hr: hr,
        min: min,
        period: per
    };
}

function formatTime(time){
    strV = time.toString();
    if(time < 10){
        return '0' + strV;
    }
    else{
        return strV;
    }
}

function genIntervals(num, max){
    let intervals = [];
    let tmpInt;
    let count = 0;

    for(let i = 0; i < num; i++){
        let timeA = genTime();
        let timeB = genTime();
        let a = timeToMin(timeA.hr, timeA.min, timeA.period);
        let b = timeToMin(timeB.hr, timeB.min, timeB.period);
        if(a > b){
            let temp = a;
            a = b;
            b = temp;
        }
        if(b - a > max){
            b -= -(a + max) + b;
        }
        tmpInt = {
            start: a,
            end: b
        }
        count++;
        intervals.push(tmpInt);
    }
    return intervals;
}

function minToTime(min){
    let per;
    let hr = Math.floor(min / 60);
    if(min > 60 * 12){
        per = 'pm';
        hr -= 12;
    }
    else{
        per = 'am';
    }
    if(hr == 0){
        hr = 12;
    }
    let minutes = min % 60;
    return {
        hr: hr,
        min: minutes,
        per: per
    }
}

function sortAsc(arr, ind, prop){
    let comp;
    if(ind === undefined){
        comp = 0;
    }
    else{
        comp = prop;
    }
    for(let i = 0; i < arr.length; i++){
        for(let j = 0; j < arr.length; j++){
            if(arr[i][comp] < arr[j][comp]){
                let tmp = arr[i];
                arr[i] = arr[j];
                arr[j] = tmp;
            }
        }
    }
    return arr;
}

function printTimeObj(o){
    return o.hr + ":" + o.min + o.per;
}