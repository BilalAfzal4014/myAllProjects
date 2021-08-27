"use strict";
exports.__esModule = true;
exports.argumentsConcat = exports.argumentsSum = void 0;
var argumentsSum;
(function (argumentsSum) {
    function sum(a, b) {
        return a + b;
    }
    argumentsSum.sum = sum;
})(argumentsSum = exports.argumentsSum || (exports.argumentsSum = {}));
var argumentsConcat;
(function (argumentsConcat) {
    function concat(a, b) {
        return a + b;
    }
    argumentsConcat.concat = concat;
})(argumentsConcat = exports.argumentsConcat || (exports.argumentsConcat = {}));
// console.log(argumentsSum.sum(1, 1)); //will also work
