exports.up = function (knex) {
    return knex.schema.createTable('message_detail', function (t) {
        t.increments('id').unsigned().primary();
        t.integer('message_id').unsigned().references('id').inTable('message');
        t.integer('user_id').unsigned().references('id').inTable('user');
        t.boolean('is_sender').defaultTo(false);

        t.dateTime('created_at').notNullable();
        t.dateTime('updated_at').nullable();
        t.dateTime('deleted_at').nullable();
    });
};


exports.down = function (knex) {
    return knex.schema.dropTable('message_detail');
};
