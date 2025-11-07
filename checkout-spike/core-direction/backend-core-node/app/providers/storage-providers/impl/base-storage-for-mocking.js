module.exports = class BaseStorage {

    saveFile(uploadedFile, directory) {
        return Promise.resolve(`http://localhost/${directory}/random_file_name`);
    }

    removeFile(fileName, directory) {
        return Promise.resolve(true);
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

};