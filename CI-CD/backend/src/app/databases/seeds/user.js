exports.seed = async function (knex) {

    //await knex('user').del()

    await knex('user').insert([
        {id: 1, name: 'Bilal', created_at: new Date()},
        {id: 2, name: 'Amna', created_at: new Date()},
    ]);
};
