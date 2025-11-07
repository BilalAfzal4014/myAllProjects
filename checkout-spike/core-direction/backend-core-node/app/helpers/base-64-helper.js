const isBase64 = require('is-base64');

module.exports = class Base64Helper {
    static base64Encode(str) {
        return Buffer.from(str).toString('base64');
    }

    static base64Decode(base64EncodedStr) {
        return Buffer.from(base64EncodedStr, "base64").toString();
    }

    static getMimeTypeAndExtensionFromBase64EncodedFileWhichIncludesMime(base64EncodedFile) {
        return base64EncodedFile.match(/[^:]\w+\/[\w-+\d.]+(?=;|,)/)[0];
    }

    static getMimeTypeFromBase64EncodedFileWhichIncludesMime(base64EncodedFile) {
        let mimeType = Base64Helper.getMimeTypeAndExtensionFromBase64EncodedFileWhichIncludesMime(base64EncodedFile)
        return mimeType.split("/")[0];
    }

    static getExtensionFromBase64EncodedFileWhichIncludesMime(base64EncodedFile) {
        let mimeType = Base64Helper.getMimeTypeAndExtensionFromBase64EncodedFileWhichIncludesMime(base64EncodedFile)
        return mimeType.split("/")[1];
    }

    static isBase64EncodedFileWithMimeType(val) {
        return Base64Helper.isBase64EncodedFile(val, {allowMime: true});
    }

    static isBase64EncodedFile(val, options = {}) {
        return isBase64(val, options);
    }

    static pruneMimeTypeFromBase64EncodedFile(file){
        return Buffer.from(file.replace(/^data:image\/\w+;base64,/, ""),'base64');
    }

}