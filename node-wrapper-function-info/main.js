function foo(){
    console.log(this)
}

let bar = () => {

        console.log(this);
        console.log(module);
        console.log(exports);

}
exports = "abc";
module.exports = "xyz"
this.a = "ijk"
bar();

foo();

/*
nodejs wraps all the code of the file inside a function and self invoke that function as well
(function(exports, require, module, __filename, __dirname) {

})();

so if we imagine that all of the above lines are written inside that wrapper function
then arrow function will inherit its this from that wrapper function which in this case is an empty object
and as we know the normal function work flow, its this is always will be global as long as its being called as function foo way

this outside of the wrapper function will also be global
*/