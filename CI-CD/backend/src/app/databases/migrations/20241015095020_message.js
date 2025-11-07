exports.up = function (knex) {
    return knex.schema.createTable('message', function (t) {
        t.increments('id').unsigned().primary();
        t.integer('user_chat_group_id').unsigned().references('id').inTable('user_chat_group');
        t.string('text').notNullable();

        t.dateTime('created_at').notNullable();
        t.dateTime('updated_at').nullable();
        t.dateTime('deleted_at').nullable();
    });
};


exports.down = function (knex) {
    return knex.schema.dropTable('message');
};
