require("dotenv").config();
const {establishDatabaseConnection} = require("./databases/sql-connection");

establishDatabaseConnection()
    .then(() => {
        startServer();
    }).catch(error => {
    console.error("Could not start Application due to", error);
    process.exit(-1);
})


const startServer = () => {
    const {ApolloServer} = require("apollo-server");
    const {typeDefs} = require("./graphql/schema");
    const {resolvers} = require("./graphql/resolvers");
    const attachDataLoadersWithContext = require("./graphql/dataloaders");

    const server = new ApolloServer({
        typeDefs,
        resolvers,
        context: async () => {
            return {
                ...attachDataLoadersWithContext(),
                //add other thing here as well
            }
        }
    });

    server.listen(4009)
        .then((serverInfo) => {
            console.log(`Server is running at:`, serverInfo.url);
        });
}


/*
const DataLoader = require('dataloader');
const fakeDB = ['Tom', 'Bo', 'Kate', 'Sara', 'Gene', 'Noel'];
const batchGetUserById = async (ids) => {
    console.log("called once per tick:", ids);
    return ids.map(id => fakeDB[id - 1]);
};
const userLoader = new DataLoader(batchGetUserById);

userLoader.load(1)
    .then((result) => {
        console.log("result for 1 is", result);
    });

userLoader.load(1) // the ids in batchGetUserById will still be [1] and not [1, 1]
    .then((result) => {
        console.log("result for 1 is", result);
    });

userLoader.load(2)
    .then((result) => {
        console.log("result for 2 is", result);
    });

Promise.all([
    userLoader.load(1),
    userLoader.load(2)
]).then((result) => {
    console.log("result for 1 and 2 is", result);
});

console.log('\nEvent Loop Tick 1');
userLoader.load(1);
userLoader.load(2).then((user) => {
    console.log('Here is the user: ', user);
});
setTimeout(() => {
    console.log('\nTick 2');
    userLoader.load(3);
    userLoader.load(4);
}, 1000);
setTimeout(() => {
    console.log('\nTick 3');
    userLoader.load(5);
    userLoader.load(6);
}, 2000);
*/
