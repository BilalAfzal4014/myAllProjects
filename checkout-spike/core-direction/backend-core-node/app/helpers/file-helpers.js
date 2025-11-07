const fs = require('fs');

module.exports = class FileHelpers {
    static readFileFromGivenPath(path) {
        return new Promise((resolve, reject) => {
            fs.readFile(path, function (error, fileContent) {
                if (error)
                    return reject(error);

                resolve(fileContent);
            });
        });
    }
}