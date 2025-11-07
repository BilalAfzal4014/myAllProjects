const multer = require("multer");
const multerS3 = require("multer-s3");
const aws = require("aws-sdk");
const MulterS3UploadConstants = require("../../../constants/multer-s3-upload");

const accessKeyId = process.env.ACCESS_KEY;
const secretAccessKey = process.env.SECRET_KEY;

const s3 = new aws.S3({
    accessKeyId,
    secretAccessKey,
});
exports.uploadS3 = multer({
    storage: multerS3({
        s3: s3,
        bucket: process.env.BUCKET + MulterS3UploadConstants.MEMBER_IMAGE_PATH,
        acl: "public-read",
        contentType: multerS3.AUTO_CONTENT_TYPE,
        key: function (req, file, cb) {
            cb(null, req.user_id + Date.now() + '.' + file.mimetype.split('/')[1]);
        },
    }),
});