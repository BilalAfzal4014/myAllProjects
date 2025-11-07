const Sequelize = require("sequelize");
const {Contract} = require('../database/models');
const getJob = async (req, res, next) => {
    const {Job} = req.app.get('models');
    const {job_id} = req.params;
    const job = await Job.findOne({
        include: {
            model: Contract,
            where: {
                [Sequelize.Op.or]: [
                    {ClientId: req.profile.id},
                ]
            }
        },
        where: {
            id: job_id,
            paid: {
                [Sequelize.Op.not]: true
            }
        }
    });
    if (!!job) {
        req.job = job.dataValues;
        return next();
    }
    return res.status(400).json({message: 'Either the job is already paid or not found'})
}
module.exports = {getJob}