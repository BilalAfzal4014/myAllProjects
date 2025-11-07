const Minio = require('minio')

const minioClient = new Minio.Client({
    endPoint: (process.env.MINIO_CLIENT || 'localhost'),
    port: (process.env.MINIO_PORT || 9000),
    useSSL: ('true' == process.env.MINIO_USE_SSL),
    accessKey: (process.env.MINIO_ACCESS_KEY || 'minioadmin'),
    secretKey: (process.env.MINIO_SECRET_KEY || 'minioadmin')
});

module.exports = minioClient;