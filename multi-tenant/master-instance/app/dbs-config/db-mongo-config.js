const mongoose = require('mongoose');

function makeMongoTenantConnection(host) {
    const mongoConnection = mongoose.createConnection(`mongodb://${host}/leaderboards?authSource=admin`, {
        useNewUrlParser: true,
        useUnifiedTopology: true
    });

    return mongoConnection;
}


module.exports = makeMongoTenantConnection;