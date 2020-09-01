const Queue = require('bull');

let normalQueue = new Queue("normalQueue", 'redis://127.0.0.1:6379');

exports.normalQueue = normalQueue;