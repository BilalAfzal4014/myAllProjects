const BaseUseCase = require("../../base/base-usecase");
const {getEmailProvider} = require("../../../providers/email-providers/email-provider-factory");
module.exports = class ContactUs extends BaseUseCase {

    constructor(body) {
        super();
        this.body = body;
        this.emailProviderInstance = null
    }

    sendMail() {
        this.emailProviderInstance = this.getEmailProvidedInstance();
        return this.emailProviderInstance.sendEmailsWithHtmlTemplate();
    }

    getEmailProvidedInstance() {
        const EmailProviderClass = getEmailProvider();
        return new EmailProviderClass(
            [process.env.contact_us_to_email],
            'Contact Us | Core direction',
            "",
            "templates/contact-us/contact-us.ejs", {
                name: this.body.name,
                email: this.body.email,
                subject: this.body.subject,
                type: this.body.type,
                message: this.body.message,
            }
        );
    }
}
