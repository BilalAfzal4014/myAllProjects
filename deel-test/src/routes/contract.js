const contractRoutes = require('express').Router();
const {getContractById, getAllNonTerminatedContractOfAProfile} = require('../functionality/contract');
/**
 * FIX ME!
 * @returns contract by id
 */
contractRoutes.get('/:id', async (req, res) => {
    try {
        const contract = await getContractById(req.params.id, req.profile.id);
        if (!contract) return res.status(404).end();
        res.status(200).json(contract);
    } catch (e) {
        return res.status(500).json({message: 'Something went wrong'});
    }
});

contractRoutes.get('/', async (req, res) => {
    try {
        const contracts = await getAllNonTerminatedContractOfAProfile(req.profile.id);
        res.status(200).json(contracts);
    } catch (e) {
        return res.status(500).json({message: 'Something went wrong'});
    }
});

module.exports = contractRoutes