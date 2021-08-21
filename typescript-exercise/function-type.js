let genericImplicitFunctionTypeWithImplicitVoidReturnType = function (canHaveNnumberOfArg) {
    console.log(canHaveNnumberOfArg);
};
genericImplicitFunctionTypeWithImplicitVoidReturnType("genericImplicitFunctionTypeWithImplicitVoidReturnType");
let genericExplicitFunctionTypeWithExplicitReturnType = function (canHaveNnumberOfArg) {
    console.log(canHaveNnumberOfArg);
};
genericExplicitFunctionTypeWithExplicitReturnType("genericExplicitFunctionTypeWithExplicitReturnType");
let genericExplicitFunctionWithNeverReturnType = function () {
    console.log("will be reassigned below");
};
genericExplicitFunctionWithNeverReturnType();
/*genericExplicitFunctionWithNeverReturnType = function (canHaveNnumberOfArg): never {
    throw {message: canHaveNnumberOfArg};
}
genericExplicitFunctionWithNeverReturnType("genericExplicitFunctionWithNeverReturnType");*/
let specificImplicitAssignmentWithOneArgument = function (arg) {
    console.log(arg);
};
specificImplicitAssignmentWithOneArgument("specificImplicitAssignmentWithOneArgument");
specificImplicitAssignmentWithOneArgument(1);
/*
this will produce error bcz of the function is already assigned with type function having argument 1 and return type void
specificImplicitAssignmentWithOneArgument = function(arg1, arg2){
    console.log(arg1);
}*/
let specificExplicitAssignmentWithTwoArgument;
specificExplicitAssignmentWithTwoArgument = function (firstName, lastName) {
    return firstName + " " + lastName;
};
console.log(specificExplicitAssignmentWithTwoArgument("Bilal", "Afzal"));
let callbackAsAnArgument;
callbackAsAnArgument = function (cb) {
    let returnedValue = cb("callbackAsAnArgument");
    console.log(returnedValue);
};
callbackAsAnArgument(function (data) {
    return data;
});
let promiseReturned;
promiseReturned = function () {
    return Promise.resolve("promiseReturned");
};
promiseReturned()
    .then((str) => {
    console.log(str);
});
