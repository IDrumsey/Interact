//person
function Person(name, availableTimes){
    let obj = {};
    obj.name = name;
    obj.availableTimes = sortAsc(availableTimes, null, 'start');
    obj.setUnavailable = function(){this.unavailableTimes = findUnavailable(this.availableTimes);}
    obj.logAvailable = function(){
        //console.log("Available times for Person - ", this.name);
        for(let i = 0; i < this.availableTimes.length; i++){
            let startTime = minToTime(this.availableTimes[i].start);
            let endTime = minToTime(this.availableTimes[i].end);
            //console.log(startTime.hr, ":", startTime.min, startTime.per , " - ", endTime.hr, ":", endTime.min, endTime.per)
        }
    }
    return obj;
}

let timelineEl = document.getElementById('timeline');
const maxTime = 60 * 24;

//Algorithm for finding the best time for multiple peoples' schedules

//Calc the unavailable time intervals from the available time intervals

//for all unavailable times, mark index as false

//left with true values where time available for all

//get match
let prms = new URLSearchParams(window.location.search);
let match = prms.get("match");
console.log("Match : ", match);

//Set people
//get people
    let ps = JSON.stringify({
        match: match
    });
    console.log(ps);
    let call = new XMLHttpRequest();
    //response
    call.onload = function(){
        console.log("Response : ", this.responseText);
    }

    call.open("POST", "getMatchTimes.php");
    call.setRequestHeader("Content-type", "application/json");
    call.send(ps);

let peopleList = [];
const timeDiff = 100;
const numPeople = 5;
const numIntervals = 5;
const intDiff = 300;
//create people
for(let i = 0; i < numPeople; i++){
    let tmpName = "name " + i;
    let tmpPerson = createPerson(tmpName);
    tmpPerson.setUnavailable();
    peopleList.push(tmpPerson);
}

//initialize main timeline array
let timeline = Array(60 * 24);
timeline.fill(true);

//For each person and all their unavailable intervals mark those slots as false
//runAlg(peopleList);


runAlg(peopleList);

let commonTimes = extractCommon(timeline);
let commonTimesConvert = [];

//replace minutes versions
for(let i = 0; i < commonTimes.length; i++){
    commonTimesConvert.push({
        start: minToTime(commonTimes[i].start),
        end: minToTime(commonTimes[i].end)
    });
}

console.log("People : ", peopleList);
for(let i = 0; i < peopleList.length; i++){
    peopleList[i].logAvailable();
}
console.log("Common : ", commonTimesConvert);

//get distances of interval
if(commonTimes.length > 0){
    let maxInt = 0;
    let maxDist = 0;
    for(let i = 0; i < commonTimes.length; i++){
        let tmpDist = getDistance(commonTimes[i].start, commonTimes[i].end);
        if(tmpDist > maxDist){
            maxDist = tmpDist;
            maxInt = i;
        };
    }
    let bt = commonTimesConvert[maxInt]
    console.log("Best interval for activity : ", printInterval(bt.start.hr, bt.start.min, bt.start.per, bt.end.hr, bt.end.min, bt.end.per));
}
else{
    console.log("no common time");
}





//helper functions

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
        if(i == 0){
            unavailableTimes.push(findUnavailableInt(-1, availableTimes[i].start));
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
    let tmpPerson = Person(name, genIntervals(numIntervals, intDiff));
    return tmpPerson;
}

function runAlg(people){
    for(let i = 0; i < people.length; i++){
        let tmpPerson = people[i];
        for(let j = 0; j < tmpPerson.unavailableTimes.length; j++){
            //console.log("rendering");
            let tmpInterval = tmpPerson.unavailableTimes[j];
            //console.log("rendering");
            markUnavailable(timeline, tmpInterval[0], tmpInterval[1]);
            let tmpIntervals = extractCommon(timeline);
            if(i == 0 && j == 0){
                renderTimeline(tmpIntervals);
            }
            else{
                let t = setTimeout(renderTimeline, ((tmpPerson.unavailableTimes.length * i) + j) * timeDiff, tmpIntervals);
            }
        }
    }
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