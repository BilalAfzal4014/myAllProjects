const {Model} = require("../sql-connection");

class Base extends Model {

    $beforeInsert() {
        this.created_at = new Date();
        this.updated_at = new Date();
    }

    $beforeUpdate() {
        this.updated_at = new Date();
    }
}

module.exports = Base;