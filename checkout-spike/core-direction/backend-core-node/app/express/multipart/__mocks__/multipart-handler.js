const dummyFile = Buffer.from('abc');

const handleSingleFileUpload = (name, supportedExtensions, fileSize = (30 * mb())) => {
    return (req, resp, next) => {
        const fileHandlerMocked = prepareFileHandlerMocked(name, supportedExtensions, fileSize, "single");
        fileHandlerMocked(req, resp, (error) => {
            if (error) {
                handleErrorMocked(resp, error);
            } else {
                next();
            }
        });
    };
};

const handleMultipleFileUpload = (req, resp, name, supportedExtensions, fileSize = (30 * mb())) => {
    const fileHandlerMocked = prepareFileHandlerMocked(name, supportedExtensions, fileSize, "array");
    return new Promise((resolve, reject) => {
        fileHandlerMocked(req, resp, function (error) {
            if (error) {
                handleErrorMocked(resp, error);
                reject(-1);
            }
            resolve(req.files);
        });
    });
};

const prepareFileHandlerMocked = (name, supportedExtensions, fileSize, fileUploadOption) => {
    return function (req, res, callback) {

        if (supportedExtensions.includes(".png")) {
            switch (fileUploadOption) {
                case "single":
                    req.file = dummyFile;
                    break;
                case "array":
                    req.files = [dummyFile];
                    break;
            }
            callback();
        } else {
            callback({error: "File upload error"});
        }
    }
}

const mb = () => 1024 * 1024;


const handleErrorMocked = (resp, error) => {
    console.error("Error occurred while uploading file(s)", error);
    if (error instanceof Object) {
        resp.status(500).send({message: "Some error occurred while uploading file(s), please consult your system administrator."});
    } else {
        resp.status(400).send({message: "Could not upload file(s), please verify file being uploaded.", error});
    }
}

module.exports = {
    handleSingleImageUpload: (name, supportedFormats) => handleSingleFileUpload(name, supportedFormats),
    handleMediaUpload: (req, resp, name, supportedFormats) => handleMultipleFileUpload(req, resp, name, supportedFormats)
};