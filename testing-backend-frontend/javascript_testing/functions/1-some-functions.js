const someFunctions2 = require("./2-some-functions");
const someFunctions3 = require("./3-some-functions");

const sum = (a, b) => a + b;

const concat = (a, b) => `${a}${b}`;

const getPerson = () => ({
    name: "Bilal",
    gender: "Male"
});

global.something = "something";

const asyncApi = () => {

    return new Promise((resolve, reject) => {
        setTimeout(() => {
            resolve([1, 2, 3]);
        }, 6000)
    })

};

const asyncApiCallback = (cb) => {

    setTimeout(() => {
        cb([1, 2, 3]);
    }, 6000)

};

const arrangeTheName = (firstName, lastName) => {
    let arrange = `${firstName} ${lastName}`;
    arrange = someFunctions2.appendGender(arrange, "Male");
    return arrange;
};


const sumAndDivide = (num1, num2, num3) => {

    let sum = num1 + num2;
    let divide = someFunctions3.divide(sum, num3);
    return divide;
};


module.exports = {
    sum,
    concat,
    getPerson,
    asyncApi,
    arrangeTheName,
    sumAndDivide,
    asyncApiCallback
};