const { Model, transaction } = require("objection");
const Knex = require("knex");

const knex = Knex({
   client: process.env.DB_CLIENT,
   connection: {
      host: process.env.DB_HOST,
      port: process.env.DB_PORT,
      user: process.env.DB_USER,
      password: process.env.DB_PASSWORD,
      database: process.env.DB_NAME,
      //debug: process.env.DB_DEBUG
      //debug: true
   },
   pool: {
      min: parseInt(process.env.DB_MIN_POOL),
      max: parseInt(process.env.DB_MAX_POOL),
      afterCreate: function (_, done) {
         done();
      },
   },
   acquireConnectionTimeout: process.env.DB_CONNECTION_TIMEOUT,
});

Model.knex(knex);

const establishDatabaseConnection = async () => {
   const CONNECTION_CHECK_QUERY = "SELECT 1+1 as result";

   await knex.raw(CONNECTION_CHECK_QUERY);
   console.log("Maria-DB connection established successfully");

   return Promise.resolve();
};

module.exports = {
   knex,
   Model,
   transaction,
   establishDatabaseConnection,
};
