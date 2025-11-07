function userController(req) {
    const {User, RefreshToken, FosGroup, FosUserGroup, sequelize, MemberBilling, CorprateKey, MemberKey} = require('../config/config');
    const defaultResponse = require('../helper/defaultResponse');
    const userResponse = require('../helper/userlogin');
    const datetime = require('node-datetime');
    const {GetHtmlStringFromView} = require('../helper/commonhelper');
    const {QueryTypes, Sequelize} = require('sequelize');
    const Op = Sequelize.Op;
    var gender = '';
    var cardCode = '';
    var paymentMethod = '';
    var cardLastFour = '';
    const finalResponse = [];
    const userProfile = [];
    return ({
        login: async (res) => {
            try {
                var userResponse = {};
                const refreshTokens = {};
                const dt = datetime.create();
                const offsetInDays = dt.offsetInDays(30);
                const formatted = dt.format('Y-m-d H:M:S');
                console.log(process.env.allowTempUseToLogin === false);
                console.log(false === false)
                if (process.env.allowTempUseToLogin) {
                    console.log('here');
                    // axios.post('http://dev.coredirection.com/api/v1/user/login.json', {
                    //     email: req.body.email,
                    //     password: req.body.password
                    // }).then(function (response) {
                    //
                    // }).catch(function (error) {
                    //     res.status(400).json(defaultResponse.createErrorResponse(process.env.errorStatus, "error", error));
                    // });
                }else {
                    // User.findOne({
                    //     where: {
                    //         email: req.body.email,
                    //     }, include: [MemberKey]
                    // }).then(user => {
                    //     const refreshToken = randtoken.uid(256);
                    //     const userToken = randtoken.uid(10);
                    //     const appId = req.appId;
                    //     if (user == null) {
                    //         res.status(400).json(defaultResponse.createErrorResponse(process.env.errorStatus, "error", process.env.userMessage));
                    //         return false;
                    //     }
                    //     if (user.enabled == 0) {
                    //         res.status(400).json(defaultResponse.createErrorResponse(process.env.errorStatus, "error", process.env.emailVerficationMessage));
                    //         return false;
                    //     }
                    //
                    //     sequelize.query('SELECT * FROM fos_user_user_group inner join fos_user_group on fos_user_group.id=fos_user_user_group.group_id where fos_user_user_group.user_id=:status', {
                    //         replacements: {status: user.id},
                    //         type: QueryTypes.SELECT
                    //     }).then(function (member) {
                    //         if (member != null) {
                    //             if (member[0].name != "Member") {
                    //                 res.status(400).json(defaultResponse.createErrorResponse(process.env.memberCode, "error", process.env.memberError));
                    //             }
                    //         }
                    //         switch (user.gender) {
                    //             case 'm':
                    //                 // code block
                    //                 gender = 'male';
                    //                 break;
                    //             case 'f':
                    //                 gender = 'female';
                    //                 // code block
                    //                 break;
                    //             default:
                    //                 gender = 'undefined';
                    //                 break;
                    //             // code block
                    //         }
                    //         // fetching member_ids
                    //         if (user.member_keys.length != 0) {
                    //             var userProfileIds = user.member_keys.map(result => {
                    //                 return result.key_id
                    //             });
                    //             CorprateKey.findAll({
                    //                 where: {
                    //                     id: {
                    //                         [Op.in]: userProfileIds
                    //                     },
                    //                     is_active: 1,
                    //                     type: {
                    //                         [Op.in]: ["Default", "Package"]
                    //                     }
                    //                 },
                    //                 include: [User]
                    //             }).then(result => {
                    //                 console.log('result');
                    //                 if (result) {
                    //                     console.log('coOPrateREsponse', result);
                    //                     for (let val = 0; val < result.length; val++) {
                    //                         userProfile.push({
                    //                             'key_id': result[val]['id'],
                    //                             'company_id': result[val]['fos_user_user']['id'],
                    //                             'company_name': result[val]['fos_user_user']['company_name'],
                    //                             'company_logo': result[val]['fos_user_user']['company_logo'],
                    //                             'key_type': result[val]['type'],
                    //                         });
                    //                     }
                    //                 }
                    //             }).catch(function (error) {
                    //                 res.status(400).json(defaultResponse.createErrorResponse(process.env.errorStatus, "error", error));
                    //             });
                    //         }
                    //         User.update({token: userToken, appVersion_id: appId, is_gdpr: true}, {
                    //             where: {
                    //                 email: req.body.email
                    //             }
                    //         }).then((updatedUser) => {
                    //             if (updatedUser == null) {
                    //                 res.status(400).json(defaultResponse.createErrorResponse(process.env.errorStatus, "error", process.env.userMessage));
                    //                 return false;
                    //             }
                    //             RefreshToken.create({
                    //                 refresh_token: refreshToken,
                    //                 username: user.username,
                    //                 valid: formatted
                    //             }).then(refreshTokenResponse => {
                    //                 // console.log('refreshTokenResponse', refreshTokenResponse);
                    //                 if (refreshTokenResponse == null) {
                    //                     res.status(400).json(defaultResponse.createErrorResponse(process.env.errorStatus, "error", process.env.refreshTokenError));
                    //                     return false;
                    //                 }
                    //                 sequelize.query('SELECT card_id,last_four,transaction_response FROM member_billing where user_id=:userid AND transaction_type=:transaction order by id DESC limit 1', {
                    //                     replacements: {
                    //                         userid: user.id,
                    //                         logging: console.log,
                    //                         transaction: process.env.TRANSACTION_TYPE_PAYMENT
                    //                     },
                    //                     type: QueryTypes.SELECT
                    //                 }).then(card => {
                    //                     if (card) {
                    //                         let transcation = JSON.parse(card[0].transaction_response);
                    //                         cardCode = member[0].card_id;
                    //                         cardLastFour = member[0].last_four;
                    //                         paymentMethod = transcation['card']['paymentMethod'];
                    //                     }
                    //                     const token = jwt.sign({
                    //                         username: user.username,
                    //                         token: userToken
                    //                     }, process.env.PRIVATE_KEY, {expiresIn: process.env.EXPIRETIME});
                    //                     var joiningDate = datetime.create(user.created_at);
                    //                     var step_date_time = datetime.create(user.step_date_time);
                    //                     var date_of_birth = datetime.create(user.date_of_birth);
                    //                     userResponse = {
                    //                         "id": user.id,
                    //                         "firstName": user.firstname,
                    //                         "lastName": user.lastname,
                    //                         "username": user.username,
                    //                         "email": user.email,
                    //                         "phone": user.phone,
                    //                         "latitude": user.latitude,
                    //                         "longitude": user.longitude,
                    //                         "gender": gender,
                    //                         "joiningDate": user.updated_at,
                    //                         "imageUrl": "/images/member/5c860ea25e88c.jpg",
                    //                         "lastUpdated": user.updated_at,
                    //                         "step_count": user.step_count,
                    //                         "distance": user.distance,
                    //                         "step_date_time": step_date_time.format('Y-m-d H:M:S'),
                    //                         "last_reward_steps": user.last_reward_steps,
                    //                         "dateOfBirth": date_of_birth.format('Y-m-d'),
                    //                         "cardId": cardCode,
                    //                         "lastFour": cardLastFour,
                    //                         "paymentMethod": paymentMethod,
                    //                         "userProfile": userProfile
                    //                     };
                    //                     var finalResponse = {
                    //                         "isTempLoginEnabled": process.env.allowTempUseToLogin,
                    //                         'authToken': token,
                    //                         'refresh_token': refreshToken,
                    //                         'user': userResponse
                    //                     };
                    //                     res.status(200).json(defaultResponse.createSuccessResponse("200", "success", finalResponse));
                    //
                    //                 }).catch(function (error) {
                    //                     res.status(400).json(defaultResponse.createErrorResponse(process.env.errorStatus, "error", error));
                    //                 });
                    //             }).catch(function (error) {
                    //                 res.status(400).json(defaultResponse.createErrorResponse(process.env.errorStatus, "error", error));
                    //             });
                    //         }).catch(function (error) {
                    //             res.status(400).json(defaultResponse.createErrorResponse(process.env.errorStatus, "error", error));
                    //         });
                    //     }).catch(function (error) {
                    //         res.status(400).json(defaultResponse.createErrorResponse(process.env.errorStatus, "error", error));
                    //     });
                    // }).catch(function (error) {
                    //     res.status(400).json(defaultResponse.createErrorResponse(process.env.errorStatus, "error", error));
                    // });
                }
                // }).catch(function (error) {
                //     res.status(400).json(defaultResponse.createErrorResponse(process.env.errorStatus, "error", error));
                // });
            } catch (err) {
                res.status(400).json(defaultResponse.createErrorResponse("400", "error", err));
            }
        },
        userToken: async (res) => {
            try {
                const dt = datetime.create();
                const formatted = dt.format('Y-m-d');
                if (req.body.refresh_token) {
                    const response = await RefreshToken.findOne({
                        where: {
                            refresh_token: req.body.refresh_token,
                            valid: {
                                [Op.gt]: formatted
                            },
                        },
                        limit: 1,
                        order: [[sequelize.literal('"id"'), 'DESC']]
                    });
                    if (response) {
                        const user = await userResponse.getUserListByUserName(response.username);
                        if (user) {
                            const refreshToken = randtoken.uid(256);
                            const userToken = randtoken.uid(10);
                            const appId = req.appId;
                            switch (user.gender) {
                                case 'm':
                                    // code block
                                    gender = 'male';
                                    break;
                                case 'f':
                                    gender = 'female';
                                    // code block
                                    break;
                                default:
                                    gender = 'undefined';
                                    break;
                                // code block
                            }
                            if (user.enabled == 0) {
                                res.status(400).json(defaultResponse.createErrorResponse(process.env.errorStatus, "error", process.env.emailVerficationMessage));
                                return false;
                            }
                            if (process.env.allowTempUseToLogin) {
                                const tempLogin = await userResponse.checkUserTempLogin(user);
                            }
                            const member = await userResponse.getUserGroupList(user);
                            if (member) {
                                console.log('member', member);
                                if (member != null) {
                                    if (member[0].name != "Member") {
                                        res.status(400).json(defaultResponse.createErrorResponse(process.env.memberCode, "error", process.env.memberError));
                                    }
                                }
                                // fetching member_ids
                                if (user.member_keys.length != 0) {
                                    var userProfileIds = user.member_keys.map(result => {
                                        return result.key_id
                                    });
                                    const result = await userResponse.getCoporateList(userProfileIds);
                                    if (result) {
                                        console.log('coOPrateREsponse', result);
                                        for (let val = 0; val < result.length; val++) {
                                            userProfile.push({
                                                'key_id': result[val]['id'],
                                                'company_id': result[val]['fos_user_user']['id'],
                                                'company_name': result[val]['fos_user_user']['company_name'],
                                                'company_logo': result[val]['fos_user_user']['company_logo'],
                                                'key_type': result[val]['type'],
                                            });
                                        }
                                    }
                                }
                                const updatedUser = await userResponse.updateUser(userToken, appId, user);
                                if (updatedUser) {
                                    const refreshTokenResponse = await userResponse.addRefreshToken(refreshToken, user);
                                    if (refreshTokenResponse) {
                                        const card = await userResponse.getCardDetail(user);
                                        if (card) {
                                            let transcation = JSON.parse(card[0].transaction_response);
                                            cardCode = member[0].card_id;
                                            cardLastFour = member[0].last_four;
                                            paymentMethod = transcation['card']['paymentMethod'];
                                        }
                                        const token = jwt.sign({
                                            username: user.username,
                                            token: userToken
                                        }, process.env.PRIVATE_KEY, {expiresIn: process.env.EXPIRETIME});
                                        var joiningDate = datetime.create(user.created_at);
                                        var step_date_time = datetime.create(user.step_date_time);
                                        var date_of_birth = datetime.create(user.date_of_birth);
                                        var userInfo = {
                                            "id": user.id,
                                            "firstName": user.firstname,
                                            "lastName": user.lastname,
                                            "username": user.username,
                                            "email": user.email,
                                            "phone": user.phone,
                                            "latitude": user.latitude,
                                            "longitude": user.longitude,
                                            "gender": gender,
                                            "joiningDate": joiningDate,
                                            "imageUrl": "/images/member/5c860ea25e88c.jpg",
                                            "lastUpdated": user.updated_at,
                                            "step_count": user.step_count,
                                            "distance": user.distance,
                                            "step_date_time": step_date_time.format('Y-m-d H:M:S'),
                                            "last_reward_steps": user.last_reward_steps,
                                            "dateOfBirth": date_of_birth.format('Y-m-d'),
                                            "cardId": cardCode,
                                            "lastFour": cardLastFour,
                                            "paymentMethod": paymentMethod,
                                            "userProfile": userProfile
                                        };
                                        var finalResponse = {
                                            "isTempLoginEnabled": process.env.allowTempUseToLogin,
                                            'authToken': token,
                                            'refresh_token': refreshToken,
                                            'user': userInfo
                                        };
                                        res.status(200).json(defaultResponse.createSuccessResponse("200", "success", finalResponse));
                                    } else {
                                        throw process.env.refreshTokenError;
                                    }
                                } else {
                                    throw process.env.userMessage;
                                }
                            } else {
                                throw 'user member group not found';
                            }
                        } else {
                            throw process.env.userMessage;
                        }
                    } else {
                        throw 'Token Not Found';
                    }
                } else {
                    throw 'Token is required';
                }
            } catch (err) {
                if (typeof err === "object") {
                    res.status(400).json(defaultResponse.createErrorResponse(process.env.errorStatus, "error", err.message));
                } else {
                    res.status(400).json(defaultResponse.createErrorResponse(process.env.errorStatus, "error", err));
                }
            }
        },
        emailLogin: async (res) => {
            try {
                const dt = datetime.create();
                const formatted = dt.format('Y-m-d');
                if (req.body.email) {
                    const user = await userResponse.getUserListByEmail(req.body.email);
                    if (user) {
                        const refreshToken = randtoken.uid(256);
                        const userToken = randtoken.uid(256);
                        const appId = req.appId;
                        switch (user.gender) {
                            case 'm':
                                // code block
                                gender = 'male';
                                break;
                            case 'f':
                                gender = 'female';
                                // code block
                                break;
                            default:
                                gender = 'undefined';
                                break;
                            // code block
                        }
                        if (user.enabled == 0) {
                            res.status(400).json(defaultResponse.createErrorResponse(process.env.errorStatus, "error", process.env.emailVerficationMessage));
                            return false;
                        }
                        if (process.env.allowTempUseToLogin) {
                            const tempLogin = await userResponse.checkUserTempLogin(user);
                        }
                        const member = await userResponse.getUserGroupList(user);
                        if (member) {
                            console.log('member', member);
                            if (member != null) {
                                if (member[0].name != "Member") {
                                    res.status(400).json(defaultResponse.createErrorResponse(process.env.memberCode, "error", process.env.memberError));
                                }
                            }
                            // fetching member_ids
                            if (user.member_keys.length != 0) {
                                var userProfileIds = user.member_keys.map(result => {
                                    return result.key_id
                                });
                                const result = await userResponse.getCoporateList(userProfileIds);
                                if (result) {
                                    console.log('coOPrateREsponse', result);
                                    for (let val = 0; val < result.length; val++) {
                                        userProfile.push({
                                            'key_id': result[val]['id'],
                                            'company_id': result[val]['fos_user_user']['id'],
                                            'company_name': result[val]['fos_user_user']['company_name'],
                                            'company_logo': result[val]['fos_user_user']['company_logo'],
                                            'key_type': result[val]['type'],
                                        });
                                    }
                                }
                            }
                            const updatedUser = await userResponse.updateUser(userToken, appId, user);
                            if (updatedUser) {
                                const refreshTokenResponse = await userResponse.addRefreshToken(refreshToken, user);
                                if (refreshTokenResponse) {
                                    const card = await userResponse.getCardDetail(user);
                                    if (card) {
                                        let transcation = JSON.parse(card[0].transaction_response);
                                        cardCode = member[0].card_id;
                                        cardLastFour = member[0].last_four;
                                        paymentMethod = transcation['card']['paymentMethod'];
                                    }
                                    const token = jwt.sign({
                                        username: user.username,
                                        token: userToken
                                    }, process.env.PRIVATE_KEY, {expiresIn: process.env.EXPIRETIME});
                                    var joiningDate = datetime.create(user.created_at);
                                    var step_date_time = datetime.create(user.step_date_time);
                                    var date_of_birth = datetime.create(user.date_of_birth);
                                    var userInfo = {
                                        "id": user.id,
                                        "firstName": user.firstname,
                                        "lastName": user.lastname,
                                        "username": user.username,
                                        "email": user.email,
                                        "phone": user.phone,
                                        "latitude": user.latitude,
                                        "longitude": user.longitude,
                                        "gender": gender,
                                        "joiningDate": user.updated_at,
                                        "imageUrl": "/images/member/5c860ea25e88c.jpg",
                                        "lastUpdated": user.updated_at,
                                        "step_count": user.step_count,
                                        "distance": user.distance,
                                        "step_date_time": step_date_time.format('Y-m-d H:M:S'),
                                        "last_reward_steps": user.last_reward_steps,
                                        "dateOfBirth": date_of_birth.format('Y-m-d'),
                                        "cardId": cardCode,
                                        "lastFour": cardLastFour,
                                        "paymentMethod": paymentMethod,
                                        "userProfile": userProfile
                                    };
                                    var finalResponse = {
                                        "isTempLoginEnabled": process.env.allowTempUseToLogin,
                                        'authToken': token,
                                        'refresh_token': refreshToken,
                                        'user': userInfo
                                    };
                                    res.status(200).json(defaultResponse.createSuccessResponse("200", "success", finalResponse));
                                } else {
                                    throw process.env.refreshTokenError;
                                }
                            } else {
                                throw process.env.userMessage;
                            }
                        } else {
                            throw 'user member group not found';
                        }
                    } else {
                        throw process.env.userMessage;
                    }
                } else {
                    throw 'Email is required';
                }
            } catch (err) {
                if (typeof err === "object") {
                    res.status(400).json(defaultResponse.createErrorResponse(process.env.errorStatus, "error", err.message));
                } else {
                    res.status(400).json(defaultResponse.createErrorResponse(process.env.errorStatus, "error", err));
                }
            }
        },
        forgotPassword: async (res) => {
            try {
                var newMessage = '/app/views/index.twig';
                var message = 'yooy';
                var emailBody = await GetHtmlStringFromView(
                    newMessage, {'name': message}
                );
                sgMail.setApiKey(process.env.SENDGRID_API_KEY);
                const msg = {
                    to: req.body.email,
                    from: 'abdulmajidashraf81@gmail.com',
                    subject: 'Sending with Twilio SendGrid is Fun',
                    text: 'and easy to do anywhere, even with Node.js',
                    html: emailBody,
                };
                var emailResponse = sgMail.send(msg).then((sent) => {
                    console.log('sent', sent);
                    res.status(200).json(defaultResponse.createSuccessResponse("200", "success", emailResponse));
                }).catch((err) => {
                    throw err.message;
                });
            } catch (err) {
                if (typeof err === "object") {
                    res.status(400).json(defaultResponse.createErrorResponse(process.env.errorStatus, "error", err.message));
                } else {
                    res.status(400).json(defaultResponse.createErrorResponse(process.env.errorStatus, "error", err));
                }
            }
        }
    })
}

module.exports = userController;