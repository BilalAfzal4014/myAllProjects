var defaultStrType = "name"; // is of type string cz initialize with string type value, now cannot change its type
console.log(defaultStrType);
var explicitStrType = "red";
console.log(explicitStrType);
var number1 = 1;
console.log(number1);
var generalObj = {
    name: "Bilal"
};
console.log(generalObj);
//console.log(obj.name); //will be an error cz object type is too generic
console.log(generalObj["name"]);
console.log(generalObj[defaultStrType]);
var specifiedObj = {
    name: "Amna",
    age: 30
};
console.log(specifiedObj);
console.log(specifiedObj.name);
console.log(specifiedObj["name"]);
console.log(specifiedObj[defaultStrType]);
var tuple = ["1", 1]; // will contain two and only two elements and type order will matter as well
console.log(tuple);
var arrOfString = ["1", "2", "3"];
console.log(arrOfString);
var implicitAnyType; //if no type mentioned and no initialization then its any type be default
implicitAnyType = 1;
implicitAnyType = "implicitAnyType";
console.log(implicitAnyType);
var explicitAnyType;
explicitAnyType = 2;
explicitAnyType = "explicitAnyType";
console.log(explicitAnyType);
var unknownType; //difference between any and unknown type is, unknown type will show intellisense of current assigned type
unknownType = 1;
unknownType = "unknownType";
console.log(unknownType);
var unionType; // can only assign string or number
unionType = 1;
unionType = "unionType";
console.log(unionType);
var literalType; //can only assign one of these values
// literalType = "1" //it will produce error
literalType = "can-be-2";
console.log(literalType);
var color;
(function (color) {
    color[color["red"] = 0] = "red";
    color[color["green"] = 1] = "green";
    color[color["blue"] = 2] = "blue";
})(color || (color = {}));
console.log(color.red);
console.log(color["red"]);
console.log(color[explicitStrType]);
var startWithCustomNumber;
(function (startWithCustomNumber) {
    startWithCustomNumber[startWithCustomNumber["red"] = 4] = "red";
    startWithCustomNumber[startWithCustomNumber["green"] = 5] = "green";
    startWithCustomNumber[startWithCustomNumber["blue"] = 6] = "blue";
})(startWithCustomNumber || (startWithCustomNumber = {}));
console.log(startWithCustomNumber.green);
var middleWithCustomNumber;
(function (middleWithCustomNumber) {
    middleWithCustomNumber[middleWithCustomNumber["red"] = 0] = "red";
    middleWithCustomNumber[middleWithCustomNumber["green"] = 4] = "green";
    middleWithCustomNumber[middleWithCustomNumber["blue"] = 5] = "blue";
})(middleWithCustomNumber || (middleWithCustomNumber = {}));
console.log(middleWithCustomNumber.red);
console.log(middleWithCustomNumber.green);
console.log(middleWithCustomNumber.blue);
var assignValueToEveryEnum;
(function (assignValueToEveryEnum) {
    assignValueToEveryEnum[assignValueToEveryEnum["red"] = 4] = "red";
    assignValueToEveryEnum["green"] = "g";
    assignValueToEveryEnum["blue"] = "6";
})(assignValueToEveryEnum || (assignValueToEveryEnum = {}));
console.log(assignValueToEveryEnum.red);
console.log(assignValueToEveryEnum.green);
console.log(assignValueToEveryEnum.blue);
