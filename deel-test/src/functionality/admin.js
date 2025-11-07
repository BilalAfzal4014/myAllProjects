const {sequelize} = require("../database/models");
const Sequelize = require("sequelize");
const getBestProfession = async (startDate, endDate) => {
    const query = `
        SELECT Profiles.profession, sum(price) as earning
        from Profiles join Contracts on Profiles.id = Contracts.ContractorId 
        join Jobs on Contracts.id = Jobs.ContractId 
        where Profiles.type = 'contractor' and Jobs.paid = true and Profiles.createdAt >= :startDate and Profiles.createdAt <= :endDate
        group by Profiles.profession 
        order by price DESC 
        limit 1
    `;

    const [bestProfession] = await sequelize.query(query, {
        replacements: {startDate: `${startDate} 00:00:00.000`, endDate: `${endDate} 23:59:59.999`},
        type: Sequelize.QueryTypes.SELECT,
    });
    return bestProfession;
};


const getBestClients = (startDate, endDate, limit = null) => {
    const defaultLimit = 2;
    const query = `
        SELECT Profiles.id, concat(Profiles.firstName, ' ', Profiles.lastName) as fullName, sum(price) as paid
        from Profiles join Contracts on Profiles.id = Contracts.ClientId  
        join Jobs on Contracts.id = Jobs.ContractId 
        where Profiles.type = 'client' and Jobs.paid = true and Profiles.createdAt >= :startDate and Profiles.createdAt <= :endDate
        group by Profiles.id, Profiles.firstName, Profiles.lastName
        order by paid desc
        limit :limit
    `;

    return sequelize.query(query, {
        replacements: {
            startDate: `${startDate} 00:00:00.000`,
            endDate: `${endDate} 23:59:59.999`,
            limit: limit || defaultLimit
        },
        type: Sequelize.QueryTypes.SELECT,
    });
};

module.exports = {
    getBestProfession,
    getBestClients
}