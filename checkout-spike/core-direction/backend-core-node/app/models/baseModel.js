const {Model} = require("../databases/sql-connection");

class Base extends Model {

    $beforeInsert() {
        this.created_date = new Date();
        this.updated_date = new Date();
    }

    $beforeUpdate() {
        this.updated_date = new Date();
    }
}

module.exports = Base;