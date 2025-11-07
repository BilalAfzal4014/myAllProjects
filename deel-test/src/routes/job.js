const jobRoutes = require('express').Router();
const {getJob} = require("../middleware/getJob");
const {getJobsOfAProfile, payContractorForAJobByClient} = require('../functionality/job');
const {returnErrorMessageIfCustom} = require('../errorHandler');
const {v4: uuidv4} = require("uuid");
const concurrencyQueue = require("../concurrentRequestQueue");

jobRoutes.get('/unpaid', async (req, res) => {
    try {
        const jobs = await getJobsOfAProfile(req.profile.id);
        res.status(200).json(jobs);
    } catch (e) {
        return res.status(500).json({message: 'Something went wrong'})
    }
});

jobRoutes.post('/:job_id/pay', getJob, async (req, res) => {
    try {
        const uuid = uuidv4()
        concurrencyQueue.push(uuid)
        while (concurrencyQueue[0] !== uuid) {
            await new Promise((resolve, _) => {
                setTimeout(() => {
                    resolve(true);
                }, 500)
            })
        }
        await payContractorForAJobByClient(req.params.job_id, req.profile.id, req.job);
        return res.status(200).json({message: 'You paid successfully'});
    } catch (error) {
        const errorMessage = returnErrorMessageIfCustom(error);
        const errorCode = errorMessage && 400;
        return res.status(errorCode || 500).json({message: errorMessage || 'Something went wrong'});
    } finally {
        concurrencyQueue.splice(0, 1);
    }
});

module.exports = jobRoutes;