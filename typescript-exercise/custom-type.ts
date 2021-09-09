type combinable = string | number;
const arrayOfStringAndNumbers: Array<combinable> = [1, 2, "3", 4, "Bilal"];
console.log(arrayOfStringAndNumbers)



type Record1<K extends keyof any, T> = { //basically Record1 behave sames like interface
    [L in K]: T;
};

type LanguageMessages = Record1<string, string>;

let a : LanguageMessages = {};

a[0] = "1";
console.log(a);