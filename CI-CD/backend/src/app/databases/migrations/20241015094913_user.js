exports.up = function (knex) {
    return knex.schema.createTable('user', function (t) {
        t.increments('id').unsigned().primary();
        t.string('name').notNullable();

        t.dateTime('created_at').notNullable();
        t.dateTime('updated_at').nullable();
        t.dateTime('deleted_at').nullable();
    });
};


exports.down = function (knex) {
    return knex.schema.dropTable('user');
};
