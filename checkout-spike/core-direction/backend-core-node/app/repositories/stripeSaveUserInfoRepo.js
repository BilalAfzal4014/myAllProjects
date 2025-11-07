const BaseRepo = require("./baseRepo");
const UserStripeInfoModel = require("../models/userStripeInfoModel");
const UserStripePaymentSaveModel = require("../models/UserStripePaymentSaveModel");
module.exports = class StripeSaveUserInfoRepo extends BaseRepo {
    static saveOrUpdateStripeFuturePayment(payload, transaction = null) {
        if(!payload.id){
            return UserStripeInfoModel.query(transaction).insertAndFetch(payload);
        } else {
            return UserStripeInfoModel.query(transaction).updateAndFetchById(payload.id,payload);
        }
    }

    static fetchSaveCard(payload,transaction = null){
        return UserStripeInfoModel.query(transaction).where('user_id',payload.user_id).andWhere('is_client_secret_enabled',true).andWhere('deleted_at',null);
    }

    static fetchCardByCustomerNameAndUserId(payload,transaction = null){
        return UserStripeInfoModel.query(transaction).where('user_id',payload.user_id).andWhere('customer',payload.customer).andWhere('deleted_at',null);
    }


    static saveUserStripePayment(payload,transaction = null){
        return UserStripePaymentSaveModel.query(transaction).insertAndFetch(payload);
    }

    static checkoutStripePayment(payload,transaction = null){
        return UserStripePaymentSaveModel.query(transaction).insertAndFetch(payload);
    }

}