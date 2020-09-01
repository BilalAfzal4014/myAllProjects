const mongoose = require("mongoose");

const UserStatsSchema = new mongoose.Schema({
        name: {
            type: String,
            required: true,
            unique: true,
            index: true
        },
        userStats: {}
    },
    {
        timestamps: true,
        versionKey: false
    }
);

function getUserStatsModelMongo(mongoConnection) {
    let UserStats = mongoConnection.model('UserStats', UserStatsSchema, 'UserStats');
    return UserStats;
}


module.exports = getUserStatsModelMongo;