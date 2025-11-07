const Sequelize = require("sequelize");
const {Contract, Job, sequelize, Profile} = require("../database/models");

const getJobsOfAProfile = (profileId, status = 'in_progress', paid = false) => {
    return Job.findAll({
        include: {
            model: Contract,
            where: {
                [Sequelize.Op.or]: [
                    {ContractorId: profileId},
                    {ClientId: profileId},
                ],
                status
            }
        },
        where: {
            paid: {
                [Sequelize.Op.not]: !paid
            }
        }
    });
};

const payContractorForAJobByClient = async (jobId, profileId, jobInfo) => {
    const t1 = await sequelize.transaction();
    try {
        const jobPromise = Job.findOne({
            limit: 1,
            lock: true,
            transaction: t1,
            where: {id: jobId}
        });

        const clientProfilePromise = Profile.findOne({
            limit: 1,
            lock: true,
            transaction: t1,
            where: {id: profileId}
        });

        const contractualProfilePromise = Profile.findOne({
            limit: 1,
            lock: true,
            transaction: t1,
            where: {id: jobInfo.Contract.ContractorId}
        });

        const [job, clientProfile, contractualProfile] = await Promise.all([jobPromise, clientProfilePromise, contractualProfilePromise]);

        if (clientProfile.dataValues.balance >= job.dataValues.price) {
            contractualProfile.dataValues.balance += job.dataValues.price;
            clientProfile.dataValues.balance -= job.dataValues.price;


            const clientProfileUpdatePromise = Profile.update({balance: clientProfile.dataValues.balance}, {
                transaction: t1,
                where: {
                    id: profileId
                }
            });

            const contractualProfileUpdatePromise = Profile.update({balance: contractualProfile.dataValues.balance}, {
                transaction: t1,
                where: {
                    id: jobInfo.Contract.ContractorId
                }
            });

            const jobUpdatePromise = Job.update({paid: true, paymentDate: new Date()}, {
                transaction: t1,
                where: {
                    id: jobId
                }
            });

            await Promise.all([clientProfileUpdatePromise, contractualProfileUpdatePromise, jobUpdatePromise]);
        } else {
            t1.commit();
            throw new Error(JSON.stringify({message: `You don't have enough balance to pay`, custom: true}));
        }
        t1.commit();
    } catch (e) {
        if (t1.finished !== 'commit') {
            t1.rollback();
        }
        throw e;
    }
}

module.exports = {
    getJobsOfAProfile,
    payContractorForAJobByClient
};