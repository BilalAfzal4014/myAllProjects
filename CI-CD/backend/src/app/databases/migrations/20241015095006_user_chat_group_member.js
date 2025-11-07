exports.up = function (knex) {
    return knex.schema.createTable('user_chat_group_member', function (t) {
        t.increments('id').unsigned().primary();
        t.integer('user_id').unsigned().references('id').inTable('user');
        t.integer('user_chat_group_id').unsigned().references('id').inTable('user_chat_group');

        t.dateTime('created_at').notNullable();
        t.dateTime('updated_at').nullable();
        t.dateTime('deleted_at').nullable();
    });
};


exports.down = function (knex) {
    return knex.schema.dropTable('user_chat_group_member');
};
