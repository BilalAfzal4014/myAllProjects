const MinioStorage = require('./impl/minio/minio-storage');
const S3Storage = require('./impl/s3/s3-storage');

const ProviderTypes = require('./provider-types');

let provider = null;
const getStorageProvider = () => {
    if (null === provider) {
        const type = process.env.STORAGE_PROVIDER_TYPE || ProviderTypes.MINIO;

        switch (type) {
            case ProviderTypes.AMAZON_S3:
                provider = new S3Storage();
                break;
            case ProviderTypes.MINIO:
                provider = new MinioStorage();
                break;
            default:
                throw new Error("Could not find suitable storage provider");
        }
    }
    return provider;
};

module.exports = {
    getStorageProvider
}