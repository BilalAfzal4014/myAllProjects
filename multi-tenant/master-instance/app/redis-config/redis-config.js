const redis = require("redis");

const redisClient = redis.createClient({
    port: process.env.REDIS_PORT,
    host: process.env.REDIS_SERVER_IP,
    auth_pass: process.env.REDIS_PASSWORD
});

// Authenticate with Redis.
/*redisClient.auth(process.env.REDIS_AUTH, function(err, reply) {
    if (err) {
        console.error(err);
	}
	else {
	    console.log(reply);
	}
});*/


redisClient.set("some_key", "some val");
//console.log(redisClient.get("some_key"));

redisClient.get("some_key", function (err, reply) {
    console.log(reply);
});

