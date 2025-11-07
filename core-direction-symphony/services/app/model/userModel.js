// var type = require('type');
// const Model = type.Model;
module.exports = (sequelize, type) => {
    return sequelize.define('fos_user_user', {
        id:{
            type: type.INTEGER,
            primaryKey: true,
            autoIncrement: true
        },
        parent_id: {
            type: type.INTEGER,
            allowNull: true
        }, username: {
            type: type.STRING,
            allowNull: false
        }, username_canonical: {
            type: type.STRING,
            allowNull: false
        }, email: {
            type: type.STRING,
            allowNull: false
        }, email_canonical: {
            type: type.STRING
        }, enabled: {
            type: type.INTEGER,
            allowNull: false
            // allowNull defaults to true
        }, salt: {
            type: type.STRING,
            allowNull: false
        }, password: {
            type: type.STRING,
            allowNull: false
        }, last_login: {
            type: type.DATE,
            allowNull: true
        }, locked: {
            type: type.INTEGER,
            allowNull: false
        }, expired: {
            type: type.INTEGER,
            allowNull: false
        }, expires_at: {
            type: type.DATE,
            allowNull: true
        }, confirmation_token: {
            type: type.STRING,
            allowNull: true
        }, password_requested_at: {
            type: type.STRING,
            allowNull: true
        }, roles: {
            type: type.TEXT,
            allowNull: false
        }, credentials_expired: {
            type: type.INTEGER,
            allowNull: false
        }, credentials_expire_at: {
            type: type.DATE,
            allowNull: false
        }, created_at: {
            type: type.DATE,
            allowNull: false
        }, updated_at: {
            type: type.DATE,
            allowNull: false
        }, date_of_birth: {
            type: type.DATE,
            allowNull: true
        }, firstname: {
            type: type.STRING,
            allowNull: true
        }, lastname: {
            type: type.STRING,
            allowNull: true
        }, website: {
            type: type.STRING,
            allowNull: true
        }, biography: {
            type: type.STRING,
            allowNull: true
        }, gender: {
            type: type.STRING,
            allowNull: true
        }, locale: {
            type: type.STRING,
            allowNull: true
        }, timezone: {
            type: type.STRING,
            allowNull: true
        }, phone: {
            type: type.STRING,
            allowNull: true
        }, facebook_uid: {
            type: type.STRING,
            allowNull: true
        }, facebook_name: {
            type: type.STRING,
            allowNull: true
        }, facebook_data: {
            type: type.TEXT,
            allowNull: true
        }, twitter_uid: {
            type: type.STRING,
            allowNull: true
        }, twitter_name: {
            type: type.STRING,
            allowNull: true
        }, twitter_data: {
            type: type.TEXT,
            allowNull: true
        }, gplus_uid: {
            type: type.STRING,
            allowNull: true
        }, gplus_name: {
            type: type.STRING,
            allowNull: true
        }, gplus_data: {
            type: type.TEXT,
            allowNull: true
        }, token: {
            type: type.TEXT,
            allowNull: true
        }, two_step_code: {
            type: type.STRING,
            allowNull: true
        }, address: {
            type: type.STRING,
            allowNull: true
        }, address_one: {
            type: type.STRING,
            allowNull: true
        }, address_two: {
            type: type.STRING,
            allowNull: true
        }, po_box: {
            type: type.STRING,
            allowNull: true
        }, city: {
            type: type.STRING,
            allowNull: true
        }, country: {
            type: type.STRING,
            allowNull: true
        }, contact_title: {
            type: type.STRING,
            allowNull: true
        }, contact_firstname: {
            type: type.STRING,
            allowNull: true
        }, contact_lastname: {
            type: type.STRING,
            allowNull: true
        },  designation: {
            type: type.STRING,
            allowNull: true
        },  contact_email: {
            type: type.STRING,
            allowNull: true
        },  contact_no: {
            type: type.STRING,
            allowNull: true
        },  contact_url: {
            type: type.STRING,
            allowNull: true
        },  notes: {
            type: type.STRING,
            allowNull: true
        },  company_logo: {
            type: type.STRING,
            allowNull: true
        },  company_name: {
            type: type.STRING,
            allowNull: true
        },  latitude: {
            type: type.STRING,
            allowNull: true
        },  longitude: {
            type: type.STRING,
            allowNull: true
        },  card_id: {
            type: type.STRING,
            allowNull: true
        },  notification: {
            type: type.INTEGER,
            allowNull: true
        },  is_gdpr: {
            type: type.INTEGER,
            allowNull: true
        },  appVersion_id: {
            type: type.INTEGER,
            allowNull: true
        },  enable_discount: {
            type: type.INTEGER,
            allowNull: true
        },  distance: {
            type: type.DOUBLE,
            allowNull: true
        },  step_count: {
            type: type.INTEGER,
            allowNull: true
        },  step_date_time: {
            type: type.DATE,
            allowNull: true
        },  last_reward_steps: {
            type: type.INTEGER,
            allowNull: true
        },


    }, {
        tableName: "fos_user_user",
    })
};
// class User extends Model {
// }
// User.init({
//     // attributes
//   
//
//
// }, {
//     type,
//     modelName: 'fos_user_user'
//     // options
// });
// module.exports = {
//     User
// };

// const userList = function (cb) {
//     //var post  = {from:'me', to:'you', msg:'hi'};
//     connection.query('select * from members',function (err, result,fields) {
//         cb(result);
//     });
//     //connection.end();
// };
