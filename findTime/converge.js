//person
function Person(name, availableTimes){
    let obj = {};
    obj.name = name;
    obj.availableTimes = availableTimes;
}

//Algorithm for finding the best time for multiple peoples' schedules

//setIntervals
let intervals = genIntervals(3);

//Convert intervals to minutes
for(let i = 0; i < intervals.length; i++){
    intervals[i].start = timeToMin(intervals[i].start.hr, intervals[i].start.min, intervals[i].start.period);
    intervals[i].end = timeToMin(intervals[i].end.hr, intervals[i].end.min, intervals[i].end.period);
    if(intervals[i].start > intervals[i].end){
        let temp = intervals[i].start;
        intervals[i].start = intervals[i].end;
        intervals[i].end = temp;
    }
}


console.log("Intervals : ", intervals);

let base = genBase(intervals);

//sort by starting time
sortAsc(base);

console.log(base);

console.log("Converging");

let fin = converge(base, 0);
console.log("Final : ", fin == [] ? "No Common Interval" : fin);

//helper functions

function createPerson(name){
    let tmpPerson = Person(name, genIntervals());
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

function splitTime(time, delimeter){
    let min = "";
    let hr = "";
    let split = 0;
    for(let i = 0; i < time.length; i++){
        if(time[i] == delimeter){
            split = 1;
        }
        else{
            if(split == 0){
                hr += time[i];
            }
            else{
                min += time[i];
            }
        }
    }
    return {
        hr: hr,
        min: min
    }
}

function compare(parentA, propA, parentB, propB, op){
    if(op == '<'){
        if(propA < propB){
            return parentA;
        }
        else{
            return parentB;
        }
    }
    else if(op == '>'){
        if(propA > propB){
            return parentA;
        }
        else{
            return parentB;
        }
    }
    else if(op == '='){
        if(propA == propB){
            return true;
        }
        else{
            return false;
        }
    }
}

function timeToMin(hr, min, per){
    let totMin = (60 * hr) + min;
    if(per == "pm"){
        totMin += 60 * 12;
    }
    return totMin;
}

function sortAsc(base){
    for(let i = 0; i < base.length; i++){
        for(let j = 0; j < base.length; j++){
            let a = base[i];
            let b = base[j];
            if(a[0] < b[0]){
                base[i] = b;
                base[j] = a
            }
        }
    }
}

function converge(base, count){
    console.log("Round ", count , " base : ", base);
    console.log("converging");
    let overlapped = [];
    let nonOverlappedInts = JSON.parse(JSON.stringify(base));
    let newBase = [];
    let inCount = 0;
    if(inCount < 100){
        for(let i = 0; i < base.length; i++){
            for(let j = i + 1; j < base.length; j++){
                if(base[i][1] > base[j][0] && base[i][0] < base[j][1] && inCount < 100){
                    console.log("Found Overlap : ", getOverlap(base[i], base[j]));
                    overlapped.push(i, i + 1);
                    newBase.push(getOverlap(base[i], base[j]));
                }
                inCount++;
            }
        }
    }
    //Remove overlapped values: Non-overlapped values remain
    /*for(let i = 0; i < overlapped.length; i++){
        nonOverlappedInts.splice(overlapped[i], 1);
    }*/

    //console.log("Adding : ", nonOverlappedInts);

    count++
    console.log("Before removing redundancies : ", newBase);
    let revisedBase = removeRedundant(newBase)//.concat(nonOverlappedInts);
    console.log("After removing redundancies and adding nonOverlapped : ", revisedBase);
    console.log("newBase Final : ", revisedBase);
    if(revisedBase.length > 1 && cmpArr(base, revisedBase) == 0){
        console.log("Calling from round ", count);
        return converge(revisedBase, count);
    }
    else if(revisedBase.length == 0){
        return [];
    }
    else{
        return revisedBase;
    }
}

function getOverlap(a, b){
    let start;
    let stop;
    //get difference between a end and b start
    let diff = Math.abs(a[1] - b[0]);
    //get b distance
    let bdiff = b[1] - b[0];
    if(bdiff < diff){
        return Array.from(b);
    }
    else{
        let tmp = Array(b[0], a[1]);
        return tmp;
    }
}

//remove redundancies
function removeRedundant(a){
    console.log("removing redundancies");
    let vals = [];
    let found = 0;
    for(let i = 0; i < a.length; i++){
        for(let j = 0; j < vals.length; j++){
            if(cmpArr(vals[j], a[i])){
                found = 1;
                break;
            }
        }
        if(found == 0){
            vals.push(Array.from(a[i]));
        }
        found = 0;
    }
    return vals;
}

function cmpArr(a, b){
    let eq = 1;
    if(a.length != b.length){
        return 0;
    }
    else{
        if(a[0].length > 1 || b[0].length > 1){
            for(let k = 0; k < a.length; k++){
               eq = cmpArr(a[k], b[k]);
            }
    
            return eq;
        }
        else{
            for(let c = 0; c < a.length; c++){
                if(a[c] != b[c]){
                    eq = 0;
                }
            }
            return eq;
        }
    }
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

function genIntervals(num){
    let intervals = [];
    let tmpInt;
    let count = 0;

    for(let i = 0; i < num; i++){
        let timeA = genTime();
        let timeB = genTime();
        tmpInt = {
            id: count,
            start: timeA,
            end: timeB
        }
        count++;
        intervals.push(tmpInt);
    }
    return intervals;
}