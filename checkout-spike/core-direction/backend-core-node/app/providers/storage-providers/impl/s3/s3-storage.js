const AWS = require("./index");
const {v4: uuid} = require("uuid");
const s3Instance = new AWS.S3();
const Base64Helper = require("../../../../helpers/base-64-helper");

module.exports = class S3Storage {

    saveFile(uploadedFile, directory) {
        const ext = uploadedFile.originalname.split(".").pop();
        const uuidFileName = uuid() + "." + ext;

        const params = {
            Bucket: process.env.BUCKET + directory,
            Key: uuidFileName,
            Body: uploadedFile.buffer
        };

        return s3Instance.upload(params).promise()
            .then((uploadResult) => {
                return uuidFileName;
            });
    }

    saveFiles(files, directory) {
        const collectBulkUploadPromise = [];
        for (let file of files) {
            collectBulkUploadPromise.push(this.saveFile(file, directory));
        }
        return Promise.all(collectBulkUploadPromise)
            .then((uploadResults) => {
                const collectFileUrls = [];
                for (let uploadResult of uploadResults) {
                    collectFileUrls.push(uploadResult);
                }
                return collectFileUrls;
            });
    }

    removeFile(fileName, directory) {
        const params = {
            Bucket: process.env.BUCKET + directory,
            Key: fileName
        };

        return s3Instance.deleteObject(params).promise()
            .then((deleteResult) => {
                return deleteResult;
            });
    }

    saveBase64File(file, directory) {
        const fileExtension = Base64Helper.getExtensionFromBase64EncodedFileWhichIncludesMime(file);
        return this.saveFile({
            originalname: `randomString.${fileExtension}`,
            buffer: Base64Helper.pruneMimeTypeFromBase64EncodedFile(file),
        }, directory);
    }

}