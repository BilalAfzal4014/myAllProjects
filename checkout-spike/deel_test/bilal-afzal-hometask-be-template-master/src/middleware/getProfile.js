const {Contract} = require('../database/models');
const getProfile = async (req, res, next) => {
    const {Profile} = req.app.get('models')
    const profile = await Profile.findOne({
        include: [
            {model: Contract, as: 'Contractor'},
            {model: Contract, as: 'Client'},
        ],
        where: {id: req.get('profile_id') || 0}
    })
    const isProfileEngagedWithContracts = !!profile?.Contractor?.length || !!profile?.Client?.length;
    if (isProfileEngagedWithContracts) {
        const {Contractor, Client, ...rest} = profile.dataValues;
        req.profile = rest;
        return next();
    }
    return res.status(401).json({message: 'No contract found for your profile'})
}
module.exports = {getProfile}