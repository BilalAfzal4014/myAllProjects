function homeController(req) {
    const validation = require('./../middleware/validation');
    const {User, RefreshToken, FosGroup, FosUserGroup, sequelize, MemberBilling, CorprateKey, MemberKey, Action} = require('../config/config');
    const defaultResponse = require('../helper/defaultResponse');
    const {getStatus} = require('../helper/commonhelper');
    const {QueryTypes, Sequelize} = require('sequelize');
    const Op = Sequelize.Op;
    return ({
        home: async (res) => {
            try {
                var headers = validation.homeheadersValidation(req);
                if (headers) {
                    const identifierkey = req.body.identifier_key;
                    if (identifierkey != null) {
                        if (typeof req.body.identifier_key === 'string') {
                            console.log('string');
                        } else {
                            throw 'Identifier key is not a string';
                        }
                    }
                    const identifier_key = req.body.identifier_key;
                    console.log('id', req.headers.userid);
                    const user = await User.findOne({
                        where: {
                            id: req.headers.userid,
                        }
                    });
                    if (user == null) {
                        throw process.env.INVALID_USER;
                    }
                    const action = await Action.findAll({
                        where: {
                            parent_id: null,
                        },
                    });
                    if (action == null) {
                        throw 'Actions not found';
                    }

                    const actionList = action.map(result => {
                        console.log('res', result);
                        return ({
                            'id': result.id,
                            'title': result.name,
                            'code': result.code,
                            'detail': result.description,
                            'status': getStatus(result.id,result.code,user),
                            'points': result.parent_id,
                            'iconUrl': result.parent_id,
                            'message': result.parent_id,
                        });
                    });
                    console.log('action', actionList);
                    res.status(200).json(defaultResponse.createSuccessResponse("200", "success", user));
                } else {
                    throw 'Please provide headers';
                }
            } catch (err) {
                if (typeof err === "object") {
                    res.status(400).json(defaultResponse.createErrorResponse(process.env.errorStatus, "error", err.message));
                } else {
                    res.status(400).json(defaultResponse.createErrorResponse(process.env.errorStatus, "error", err));
                }
            }
        }
    });
}

module.exports = homeController;