const Sequelize = require("sequelize");
const {sequelize, Contract, Profile, Job} = require("../database/models");
const depositMoneyOnClientAccount = async (userId, balanceToDeposit) => {
    const t1 = await sequelize.transaction();
    try {
        const contract = await Contract.findAll({
            where: {
                ClientId: userId
            },
            include: {
                model: Job,
                attributes: [[sequelize.fn('SUM', sequelize.col('price')), 'totalAmountToPay']],
                where: {
                    paid: {
                        [Sequelize.Op.not]: true
                    }
                }
            }
        });

        const thresholdInPercent = 0.25;
        const amountToPayForJobsAtThisPoint = contract?.[0]?.dataValues?.Jobs?.[0]?.dataValues?.totalAmountToPay || 0;
        const amountClientCanDeposit = amountToPayForJobsAtThisPoint * thresholdInPercent;


        const clientProfile = await Profile.findOne({
            limit: 1,
            lock: true,
            transaction: t1,
            where: {id: userId}
        });

        if (clientProfile?.dataValues?.type?.toLowerCase() !== 'client') {
            t1.commit();
            throw new Error(JSON.stringify({message: 'User must be client', custom: true}));
        } else if (balanceToDeposit <= amountClientCanDeposit) {
            clientProfile.dataValues.balance += balanceToDeposit;
            await Profile.update({balance: clientProfile.dataValues.balance}, {
                transaction: t1,
                where: {
                    id: userId
                }
            });
            t1.commit();
            return true;
        } else {
            t1.commit();
            throw new Error(JSON.stringify({
                message: 'Your deposit amount is greater than 25% amount you have to pay for jobs',
                custom: true
            }));
        }
    } catch (e) {
        if (t1.finished !== 'commit') {
            t1.rollback();
        }
        throw e;
    }

}

module.exports = {
    depositMoneyOnClientAccount
};