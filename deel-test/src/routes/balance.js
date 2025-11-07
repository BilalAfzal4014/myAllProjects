const balanceRoutes = require('express').Router();
const { v4: uuidv4 } = require('uuid');
const {depositMoneyOnClientAccount} = require('../functionality/balance');
const {returnErrorMessageIfCustom} = require('../errorHandler');
const concurrencyQueue = require('../concurrentRequestQueue')
const validateMoneyToDeposit = (req, res, next) => {
    if (!isNaN(req.body.balanceToDeposit)) {
        return next()
    }
    return res.status(400).json({message: 'Please provide balance to deposit'});
}

balanceRoutes.post('/deposit/:userId', validateMoneyToDeposit, async (req, res) => {
    try {
        const uuid = uuidv4()
        concurrencyQueue.push(uuid)
        while(concurrencyQueue[0] !== uuid){
            await new Promise((resolve, _) => {
                setTimeout(() => {
                    resolve(true);
                }, 500)
            })
        }
        await depositMoneyOnClientAccount(req.params.userId || 0, req.body.balanceToDeposit);
        return res.status(200).json({message: 'Balance deposit successfully'});
    } catch (error) {
        const errorMessage = returnErrorMessageIfCustom(error);
        const errorCode = errorMessage && 400;
        return res.status(errorCode || 500).json({message: errorMessage || 'Something went wrong'});
    }
    finally {
        concurrencyQueue.splice(0, 1);
    }
});

module.exports = balanceRoutes