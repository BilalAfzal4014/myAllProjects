// method 1 starts

// can do multiple module.exports but will fetch the last module.exports, in this case it will be fun2

/*module.exports = function fun1(){
	return "hello world";
};

module.exports = function fun2(){
	return "hello johny";
};*/

// output
// const fun = require("path");

//console.log(fun); // it will print hello johny and will pick fun2

// method 1 ends


// method 2 starts

/*function fun1() {
    return "hello world";
};

function fun2() {
    return "hello johny1";
};


module.exports = {
    fun1,
    fun2
};*/

// method 2 ends


// method 3 starts

//can do multiple exports, exports will assign the exported values in an object

/*exports.fun1 = function fun1() {
	return "hello world";
};

exports.fun2 = function fun2() {
	return "hello johny1";
};*/

//const obj = require("somepath")
// console.log(obj) will have both fun1 and fun2 attach to this object


// method 3 ends


// method 4 starts

/*exports = module.exports = function fun1(){
	return "hello world"
};

exports = module.exports = function fun2(){
	return "hello johny"
};*/

// again will pick the later one fun2 when require

// method 4 ends


// method 5 starts


// both means the same

/*module.exports = function(){
	return "hello world";
};

exports = module.exports = function(){
	return "hello johny";
};*/

// it will still pick the later when required, sequence doesn't matter
// method 5 ends


//method 6 starts
// will also do the samething

/*module.exports = function(str){
	return str;
};

exports = module.exports = function(str){
	return str;
};*/

//method 6 ends


// method 7 starts
// no matter the sequence between them module.exports will always be picked

/*
module.exports = function(){
    return "whole module exported"
};

exports.fun1 = function(){
    return "i m coming from exports"
};

*/

// method 7 ends