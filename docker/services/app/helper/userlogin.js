const {User, RefreshToken, FosGroup, FosUserGroup, sequelize, MemberBilling, CorprateKey, MemberKey} = require('../config/config');
const {QueryTypes, Sequelize} = require('sequelize');
const Op = Sequelize.Op;
const datetime = require('node-datetime');
function getUserListByUserName(username) {
    return User.findOne({
        where: {
            username: username,
        }, include: [MemberKey]
    });
}
function getUserListByEmail(email) {
    return User.findOne({
        where: {
            email: email,
        }, include: [MemberKey]
    });
}
function checkUserTempLogin(user) {
    return axios.post('http://dev.engagiv.auth.local/api/v1/userLogin', {
        email: user.email,
        password: user.password
    });
}
function getCoporateList(userProfileIds) {
    return CorprateKey.findAll({
        where: {
            id: {
                [Op.in]: userProfileIds
            },
            is_active: 1,
            type: {
                [Op.in]: ["Default", "Package"]
            }
        },
        include: [User]
    });
}
function getUserGroupList(user) {
    return sequelize.query('SELECT * FROM fos_user_user_group inner join fos_user_group on fos_user_group.id=fos_user_user_group.group_id where fos_user_user_group.user_id=:status', {
        replacements: {status: user.id},
        type: QueryTypes.SELECT
    });
}
function updateUser(userToken, appId, user) {
    return User.update({token: userToken, appVersion_id: appId, is_gdpr: true}, {
        where: {
            email: user.email
        }
    });
}
function addRefreshToken(refreshToken, user) {
    const dt = datetime.create();
    const offsetInDays = dt.offsetInDays(30);
    const formatted = dt.format('Y-m-d H:M:S');
    return RefreshToken.create({
        refresh_token: refreshToken,
        username: user.username,
        valid: formatted
    });
}
function getCardDetail(user) {
    sequelize.query('SELECT card_id,last_four,transaction_response FROM member_billing where user_id=:userid AND transaction_type=:transaction order by id DESC limit 1', {
        replacements: {
            userid: user.id,
            logging: console.log,
            transaction: process.env.TRANSACTION_TYPE_PAYMENT
        },
        type: QueryTypes.SELECT
    })
}
module.exports = {
    getUserListByUserName,
    checkUserTempLogin,
    getUserGroupList,
    getCoporateList,
    updateUser,
    addRefreshToken,
    getCardDetail,
    getUserListByEmail
};