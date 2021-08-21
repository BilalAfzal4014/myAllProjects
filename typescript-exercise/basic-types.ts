let defaultStrType = "name"; // is of type string cz initialize with string type value, now cannot change its type
console.log(defaultStrType);

let explicitStrType : string = "red";
console.log(explicitStrType);

let number1 : number = 1;
console.log(number1);

let generalObj : object = {
    name: "Bilal"
}
console.log(generalObj);
//console.log(obj.name); //will be an error cz object type is too generic
console.log(generalObj["name"]);
console.log(generalObj[defaultStrType as keyof object]);

let specifiedObj: { // specific type of object
    name: string; //both ; and , works
    gender?: string, //optional property
    age: number
} = {
    name: "Amna",
    age: 30
};

console.log(specifiedObj);
console.log(specifiedObj.name);
console.log(specifiedObj["name"]);
console.log(specifiedObj[defaultStrType]);


let tuple: [string, number] = ["1", 1]; // will contain two and only two elements and type order will matter as well
console.log(tuple);

let arrOfString: Array<string> = ["1", "2", "3"];
console.log(arrOfString);

let implicitAnyType; //if no type mentioned and no initialization then its any type be default
implicitAnyType = 1;
implicitAnyType = "implicitAnyType";
console.log(implicitAnyType);

let explicitAnyType: any;
explicitAnyType = 2;
explicitAnyType = "explicitAnyType";
console.log(explicitAnyType);

let unknownType: unknown; //difference between any and unknown type is, unknown type will show intellisense of current assigned type
unknownType = 1;
unknownType = "unknownType";
console.log(unknownType);

let unionType: string | number; // can only assign string or number
unionType = 1;
unionType = "unionType";
console.log(unionType);


let literalType: "can-be-1" | "can-be-2"; //can only assign one of these values
// literalType = "1" //it will produce error
literalType = "can-be-2";
console.log(literalType);

enum color {red, green, blue}
console.log(color.red);
console.log(color["red"]);
console.log(color[explicitStrType]);

enum startWithCustomNumber {red = 4, green, blue}
console.log(startWithCustomNumber.green);

enum middleWithCustomNumber {red , green = 4, blue}
console.log(middleWithCustomNumber.red);
console.log(middleWithCustomNumber.green);
console.log(middleWithCustomNumber.blue);

enum assignValueToEveryEnum {red = 4 , green = "g", blue = "6"}
console.log(assignValueToEveryEnum.red);
console.log(assignValueToEveryEnum.green);
console.log(assignValueToEveryEnum.blue);
