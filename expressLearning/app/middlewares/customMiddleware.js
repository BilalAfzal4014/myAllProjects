module.exports = function customMiddleware(data) {
    console.log("i m in custom middleware");
    return function (req, res, next) {
        console.log("passed parameter in middleware", data);
        next();
    }
};