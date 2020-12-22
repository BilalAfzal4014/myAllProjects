/*
const myLogger = (state) => (next) => (action) => {
    // console.log("Logged Action: ", action);
    next(action);
};
*/


// above code is equivalent to below code

const myLogger = (state) => {
    return (next) => {
        return (action) => {
            console.log("i am custom middleware");
            next(action);
        }
    }
};

export default myLogger;


/*const myLogger = (state) => {
    return (next) => {
        return (action) => {
            console.log("i am working", action);
            next(action);
        }
    }
};


const myLogger = (state) => ((next) => ((action) => {
    console.log("i am working", action);
    next(action);
}));

const myLogger = (state) => (next) => (action) => {
    console.log("i am working", action);
    next(action);
};


const myLogger = state => next => action => {
    console.log("i am working", action);
    next(action);
};

export default myLogger;*/
