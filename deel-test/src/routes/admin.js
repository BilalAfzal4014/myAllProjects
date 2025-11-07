const adminRoutes = require('express').Router();
const {getBestProfession, getBestClients} = require('../functionality/admin');


const isNumber = (value) => !isNaN(value);

const validateQueryParams = (req, res, next) => {
    if (!req.query.start || !req.query.start) return res.status(400).json({message: `Please provide 'start' and 'end' query params`})
    if (!!req.query.limit && !isNumber(req.query.limit)) return res.status(400).json({message: `Limit should be number`})
    if (!!req.query.limit) parseInt(req.query.limit)
    return next()
}

adminRoutes.get('/best-profession', validateQueryParams, async (req, res) => {
    try {
        const bestProfession = await getBestProfession(req.query.start, req.query.end);
        return res.status(200).json(bestProfession);
    } catch (e) {
        return res.status(500).json({message: 'Something went wrong'});
    }
});

adminRoutes.get('/best-clients', validateQueryParams, async (req, res) => {
    try {
        const bestClients = await getBestClients(req.query.start, req.query.end, req.query.limit);
        return res.status(200).json(bestClients);
    } catch (e) {
        return res.status(500).json({message: 'Something went wrong'});
    }
});

module.exports = adminRoutes