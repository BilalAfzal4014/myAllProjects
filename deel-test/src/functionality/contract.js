const Sequelize = require("sequelize");
const {Contract} = require("../database/models");
const getContractById = (id, profileId) => {
    return Contract.findOne({
        where: {
            id,
            [Sequelize.Op.or]: [
                {ContractorId: profileId},
                {ClientId: profileId},
            ],
        }
    });
};

const getAllNonTerminatedContractOfAProfile = (profileId) => {
    return Contract.findAll({
        where: {
            [Sequelize.Op.or]: [
                {ContractorId: profileId},
                {ClientId: profileId},
            ],
            status: {
                [Sequelize.Op.not]: 'terminated'
            }
        }
    });
};

module.exports = {
    getContractById,
    getAllNonTerminatedContractOfAProfile
}