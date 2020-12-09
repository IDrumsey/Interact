//person
function Person(name, availableTimes){
    let obj = {};
    obj.name = name;
    obj.availableTimes = availableTimes;
    obj.unavailableTimes = findUnavailable(availableTimes);
    return obj;
}

//Algorithm for finding the best time for multiple peoples' schedules

//Calc the unavailable time intervals from the available time intervals

//for all unavailable times, mark index as false

//left with true values where time available for all

//Set people
let peopleList = [];
const numPeople = 2;
//create people
for(let i = 0; i < numPeople; i++){
    let tmpName = "name " + i;
    let tmpPerson = createPerson(tmpName);
    peopleList.push(tmpPerson);
}

console.log("people: ", peopleList);

//initialize main timeline array
let timeline = Array(60 * 24);
timeline.fill(true);

//For each person and all their unavailable intervals mark those slots as false
//runAlg(peopleList);


runAlg(peopleList);


console.log("Done");
console.log(timeline);

let commonTimes = extractCommon();
let commonTimesConvert = [];

//replace minutes versions
for(let i = 0; i < commonTimes.length; i++){
    console.log(commonTimes[i]);
    commonTimesConvert.push({
        start: minToTime(commonTimes[i].start),
        end: minToTime(commonTimes[i].end)
    });
}

console.log("Common : ", commonTimesConvert);




//helper functions

function findUnavailable(availableTimes){
    unavailableTimes = [];
    for(let i = 0; i < availableTimes.length; i++){
        if(i == 0){
            unavailableTimes.push(findUnavailableInt(0, availableTimes[i].start));
        }
        else if(i == availableTimes.length - 1){
            unavailableTimes.push(findUnavailableInt(availableTimes[i-1].end, availableTimes[i].start));
            unavailableTimes.push(findUnavailableInt(availableTimes[i].start, 60 * 24));
        }
        else{
            unavailableTimes.push(findUnavailableInt(availableTimes[i-1].end, availableTimes[i].start));
        }
    }
    return unavailableTimes;
}

function createPerson(name){
    let tmpPerson = Person(name, genIntervals(20, 120));
    return tmpPerson;
}

function runAlg(people){
    for(let i = 0; i < people.length; i++){
        let tmpPerson = people[i];
        for(let j = 0; j < tmpPerson.unavailableTimes.length; j++){
            let tmpInterval = tmpPerson.unavailableTimes[j];
            markUnavailable(tmpInterval[0], tmpInterval[1]);
        }
    }
}

function findUnavailableInt(endPrev, startCurr){
    return [endPrev + 1, startCurr - 1];
}

function markUnavailable(start, end){
    console.log("marking unavailable : ", start, " - ", end);
    for(g = start; g <= end; g++){
        timeline[g] = false;
    }
}

function extractCommon(){
    let commonInts = [];
    let lastVal = 0;
    let tmpStart;
    let tmpEnd;
    for(let i = 0; i < timeline.length; i++){
        if(timeline[i] == true){
            if(lastVal == 0){
                lastVal = 1;
                tmpStart = i;
            }
        }
        else{
            if(lastVal == 1){
                lastVal = 0;
                tmpEnd = i;
                commonInts.push({start: tmpStart, end: tmpEnd});
            }
        }
    }
    if(lastVal == 1){
        tmpStop = timeline.length;
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
        console.log("max : ", max);
        let timeA = genTime();
        let timeB = genTime();
        let a = timeToMin(timeA.hr, timeA.min, timeA.period);
        let b = timeToMin(timeB.hr, timeB.min, timeB.period);
        if(a > b){
            let temp = a;
            a = b;
            b = temp;
        }
        console.log("prev int : ", a, b);
        if(b - a > max){
            b -= -(a + max) + b;
            console.log("new int : ", a, b);
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