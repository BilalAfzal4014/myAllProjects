const NodeMailer = require('./impl/node-mailer/node-mailer');
const SendGrid = require("./impl/send-grid/send-grid");
const ProviderTypes = require('./provider-types');

let provider = null;
const getEmailProvider = () => {
    if (null === provider) {
        const type = process.env.EMAIL_PROVIDER_TYPE || ProviderTypes.NODE_MAILER;
        switch (type) {
            case ProviderTypes.NODE_MAILER:
                provider = NodeMailer;
                break;
            case ProviderTypes.SEND_GRID:
                provider = SendGrid;
                break;
            default:
                throw new Error("Could not find suitable email provider");
        }
    }
    return provider;
};

module.exports = {
    getEmailProvider
}