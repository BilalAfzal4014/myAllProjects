const transporter = require("./index");
const BaseMailer = require("../base-mailer");

module.exports = class SendGrid extends BaseMailer {

    prepareDateForSendingHtmlTemplate() {
        this.emailPayLoad = transporter.emptyRequest({
            method: process.env.method,
            path: process.env.path,
            body: {
                personalizations: [
                    {
                        to: this.prepareDateForToUsers(),
                        subject: this.subject,
                    }
                ],
                from: {
                    email: process.env.from_email_send_grid,
                    name: 'Core Direction',
                    cc: 'tech@coredirection.com'
                },
                content: [
                    {
                        type: 'text/html',
                        value: this.htmlTemplate
                    }
                ]
            }
        });
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

    sendEmails() {
        return transporter.API(this.emailPayLoad);
    }
}