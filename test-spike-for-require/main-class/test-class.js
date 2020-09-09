
let i = 0;

console.log("i am been required in two files but i will consoled only just one", i);

class testClass{

    printClassName(){
        i++;
        console.log(i, "testClass");
    }
}

module.exports = new testClass();