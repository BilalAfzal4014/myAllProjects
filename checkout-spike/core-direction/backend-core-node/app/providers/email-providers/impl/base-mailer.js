const EjsHelper = require("../../../helpers/ejs-helper");

module.exports = class BaseMailer {
    constructor(users, subject, text, filePath, dataForHtmlTemplate = null) {
        this.users = users;
        this.subject = subject;
        this.text = text;
        this.htmlFilePath = filePath;
        this.dataForHtmlTemplate = dataForHtmlTemplate;
        this.htmlTemplate = null;
        this.emailPayLoad = null;
    }

    sendEmailsWithHtmlTemplate() {
        return this.getRenderedFileFromTemplatingEngine()
            .then(() => {
                return this.prepareDateForSendingHtmlTemplate();
            }).then(() => {
                return this.sendEmails();
            });
    }

    getRenderedFileFromTemplatingEngine() {
        return EjsHelper.renderFileFromGivenPathWithBaseTemplate(this.htmlFilePath, this.dataForHtmlTemplate)
            .then((renderedHtmlTemplate) => {
                return this.htmlTemplate = renderedHtmlTemplate;
            });
    }

    prepareDateForSendingHtmlTemplate() {
        console.log("parent prepareDateForSendingHtmlTemplate");
    }

    sendEmails() {
        console.log("parent sendEmails")
    }
}