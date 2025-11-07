const BaseMailer = require("../../__mocks__/base-mailer");

module.exports = class NodeMailer extends BaseMailer {


    prepareDateForSendingHtmlTemplate() {
        this.emailPayLoad = {
            ...this.prepareDateForSendingEmail(),
            html: this.htmlTemplate
        };
    }

    prepareDateForSendingEmail() {
        return {
            from: process.env.email,
            to: this.users.toString(),
            subject: this.subject,
        }
    }
}