const MemberScheduleActivityRepo = require('../../repositories/memberScheduleActivityRepo')
const BaseUseCase = require("../base/base-usecase");
const {getEmailProvider} = require("../../providers/email-providers/email-provider-factory");
var QRCode = require('qrcode')
const GeneralHelper = require("../../helpers/general-helper");
const _ = require('lodash')

module.exports = class BookingEmail extends BaseUseCase {

    constructor(memberScheduleActivityId, sendActivationEmail = false, gifterData = null, sponserBooking = false) {
        super();
        this.data = {};
        this.sponserEmail = sponserBooking
        this.sendActivationEmail = sendActivationEmail;
        this.gifterData = gifterData;
        this.emailTemplate = '';
        this.emailSubject = '';
        this.memberScheduleActivityId = memberScheduleActivityId;
    }

    bookingCancellationEmail = () => {
        return this.fetchBookingEmailData()
            .then(() => {
                this.emailSubject = 'Booking Cancellation | Core Direction'
                this.emailTemplate = 'templates/booking/cancellation.ejs';
                return this.sendEmailToTheUserBooking([this.data.member_email])
                    .then(()=>{

                        this.emailSubject = 'Class Cancellation | Core Direction';
                        this.emailTemplate = 'templates/booking/cancellation-facility.ejs';
                        return this.sendEmailToTheUserBooking([this.data.facility_email])
                    })
            })

    }
    sendEmail = () => {


        return this.fetchBookingEmailData()
            .then(() => {
                this.emailSubject = 'Booking Confirmed! | Core Direction'
                this.emailTemplate = 'templates/booking/confirmation.ejs'
                if (this.sendActivationEmail) {
                    this.emailTemplate = 'templates/booking/booking-and-activation.ejs'
                    this.data.confirmation_token = process.env.booking_confirmation_link + this.data.confirmation_token
                    this.data = _.merge(this.data, this.gifterData)
                } else {
                    if (this.data.offer_online) {
                        this.emailSubject = 'Booking confirmed! | Core direction'

                        this.emailTemplate = 'templates/booking/confirmation-online.ejs'
                    } else if (this.sendActivationEmail) {

                        this.emailTemplate = 'templates/booking/booking-and-activation.ejs'
                    }
                }
                //TODO: if member_email contains ForUnder18 then send a different email, split member_email based on underscore and fetch the first index for email then

                if(this.data.member_email.toLowerCase().match('forunder18')){
                    this.data.member_email = this.data.member_email.split("_")[1];
                }

                return this.sendEmailToTheUserBooking([this.data.member_email])
                    .then(() => {
                        this.emailSubject = 'Class Booking | Core Direction';
                        this.emailTemplate = 'templates/booking/facility-confirmation.ejs';
                        return this.sendEmailToTheUserBooking([this.data.facility_email])

                    })
            })
    }


    fetchBookingEmailData = () => {

        return MemberScheduleActivityRepo.fetchEmailData(this.memberScheduleActivityId)
            .then((data) => {
                data = data[0];
                data.instruction_file = data.instruction_file ? process.env.S3_ASSETS_BASE_URL + "activity/" + data.instruction_file : null;
                data.activity_logo = process.env.S3_ASSETS_BASE_URL + 'activity/' + data.activity_image;
                this.data = data
            })
    }

    sendEmailToTheUserBooking = (email) => {

        this.emailProviderInstance = this.getEmailProvidedInstance(email);
        return this.emailProviderInstance.sendEmailsWithHtmlTemplate();
    }


    getEmailProvidedInstance(email) {
        //email.push('info@dubaifitnesschallenge.com')
        const EmailProviderClass = getEmailProvider();
        return new EmailProviderClass(
            email,
            this.emailSubject,
            "",
            this.emailTemplate, this.data
        );

    }
}
