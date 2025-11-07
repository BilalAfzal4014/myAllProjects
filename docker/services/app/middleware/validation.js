const {User, Apikey, AppVersion} = require('../config/config');

function headerValidation(req) {
    var headers = req.headers;
    console.log('headers', headers.devicetype);
    if (headers.lang !=null && headers.version !=null && headers.devicetype !=null) {
        return true;
    } else {
        return false;
    }
}
function homeheadersValidation(req){
    var headers = req.headers;
    if(headers.lang !='' && headers.userid !='') {
        return true;
    } else {
        return false;
    }
}
function authValidation(param) {
    if (param.name !=null && param.pass !=null) {
        return true;
    } else {
        return false;
    }
}

function authApiValidation(name, pass) {
    return Apikey.findOne({
        where: {
            api_key: name,
            api_password: pass
        }
    });
}
function appVersionValidation(version, devicetype) {
    return AppVersion.findOne({
        where: {
            version: version,
            device_type: devicetype
        }
    });
}
module.exports = {
    headerValidation,
    authValidation,
    authApiValidation,
    appVersionValidation,
    homeheadersValidation
};