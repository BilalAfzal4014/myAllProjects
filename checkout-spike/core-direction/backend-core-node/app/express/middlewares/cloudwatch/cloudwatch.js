const Sentry = require("@sentry/node");
const SentryTracing = require("@sentry/tracing");
const transaction = Sentry.startTransaction({
    op: "test",
    name: "My First Test Transaction",
});
const winston = require('winston'),
    WinstonCloudWatch = require('winston-cloudwatch');
const logger = new winston.createLogger({
    format: winston.format.json(),
    transports: [
        new (winston.transports.Console)({
            timestamp: true,
            colorize: true,
        })
    ]
});
// if (process.env.NODE_ENV === 'production') {
const cloudwatchConfig = {
    logGroupName: process.env.LOG_GROUP_NAME,
    logStreamName: process.env.LOG_STREAM_NAME,
    awsAccessKeyId: process.env.ACCESS_KEY,
    awsSecretKey: process.env.SECRET_KEY,
    awsRegion: process.env.REGION,
    messageFormatter: ({level, message, additionalInfo}) => `[${level}] : ${message} \nAdditional Info: ${JSON.stringify(additionalInfo)}}`
}
logger.add(new WinstonCloudWatch(cloudwatchConfig))
// }

module.exports = makeLog = (req, data, key) => {
    if (key == 'node-error') {
        Sentry.captureException(data);
    }
    logger.log('info', `${key} ${req.method} ${req.originalUrl}`, {
        tags: 'http',
        additionalInfo: {result: data, body: req.body, headers: req.headers}
    });
}
