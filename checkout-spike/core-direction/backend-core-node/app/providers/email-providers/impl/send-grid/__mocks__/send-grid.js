const BaseMailer = require("../../__mocks__/base-mailer");

module.exports = class SendGrid extends BaseMailer {

    prepareDateForSendingHtmlTemplate() {
        this.emailPayLoad = {
            method: process.env.method,
            path: process.env.path,
            body: {
                personalizations: [
                    {
                        to: this.prepareDateForToUsers(),
                        subject: this.subject
                    }
                ],
                from: {
                    email: process.env.from_email_send_grid
                },
                content: [
                    {
                        //type: 'text/plain',
                        type: 'text/html',
                        value: this.htmlTemplate
                    }
                ]
            }
        };
    }

    prepareDateForToUsers() {
        const toUsers = [];
        for (let user of this.users) {
            toUsers.push({
                email: user
            });
        }
        return toUsers;
    }
}