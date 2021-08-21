let genericImplicitFunctionTypeWithImplicitVoidReturnType = function (canHaveNnumberOfArg: string) {
    console.log(canHaveNnumberOfArg);
}
genericImplicitFunctionTypeWithImplicitVoidReturnType("genericImplicitFunctionTypeWithImplicitVoidReturnType");

let genericExplicitFunctionTypeWithExplicitReturnType: Function = function (canHaveNnumberOfArg): void {
    console.log(canHaveNnumberOfArg);
}
genericExplicitFunctionTypeWithExplicitReturnType("genericExplicitFunctionTypeWithExplicitReturnType")


let genericExplicitFunctionWithNeverReturnType: Function = function(){
    console.log("will be reassigned below");
}
genericExplicitFunctionWithNeverReturnType()

/*genericExplicitFunctionWithNeverReturnType = function (canHaveNnumberOfArg): never {
    throw {message: canHaveNnumberOfArg};
}
genericExplicitFunctionWithNeverReturnType("genericExplicitFunctionWithNeverReturnType");*/




let specificImplicitAssignmentWithOneArgument = function(arg){ //this argument type will be taken as any
    console.log(arg);
}
specificImplicitAssignmentWithOneArgument("specificImplicitAssignmentWithOneArgument");
specificImplicitAssignmentWithOneArgument(1);

/*
this will produce error bcz of the function is already assigned with type function having argument 1 and return type void
specificImplicitAssignmentWithOneArgument = function(arg1, arg2){
    console.log(arg1);
}*/


let specificExplicitAssignmentWithTwoArgument: (arg1: string, arg2: string) => string;
specificExplicitAssignmentWithTwoArgument = function(firstName: string, lastName){ // arg1 is explicit and arg2 is implicit string
    return firstName + " " + lastName;
}
console.log(specificExplicitAssignmentWithTwoArgument("Bilal", "Afzal"));

let callbackAsAnArgument: (cb: (arg: string) => string) => void;

callbackAsAnArgument = function(cb){
    let returnedValue: string = cb("callbackAsAnArgument");
    console.log(returnedValue);
}

callbackAsAnArgument(function(data: string){
    return data;
});

let promiseReturned: () => Promise<string>
promiseReturned = function (){
    return Promise.resolve("promiseReturned"); // Promise is a reserved keyword to make it work use this command tsc function-type.ts --target es6
}

promiseReturned()
.then((str: string) => {
    console.log(str);
});

