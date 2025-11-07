const BaseModel = require("./baseModel");
const {knex} = require("../databases/sql-connection");

module.exports = class FacilityGalleryModel extends BaseModel {
    static get tableName() {
        return "facility_gallery";
    }

}