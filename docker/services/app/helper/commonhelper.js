let ejs = require('ejs');
exports.GetHtmlStringFromView = (viewPath, data) => {
    return new Promise((resolve, reject) => {
        ejs.renderFile(__basedir + viewPath, data, (err, str) => {
            if (err) {
                console.log('err', err);
                console.log('str', err);
                reject(err);
            } else {
                resolve(str);
            }
        })
    });
};
exports.getStatus = (code,id,user) => {
    return new Promise((resolve, reject) => {
        ejs.renderFile(__basedir + viewPath, data, (err, str) => {
            if (err) {
                console.log('err', err);
                console.log('str', err);
                reject(err);
            } else {
                resolve(str);
            }
        })
    });
};
