const router = require('express').Router();
const BookingEmail = require('../../usecases/booking/booking-email')
var makeLog = require('../middlewares/cloudwatch/cloudwatch');
router.get('/health-check', (_, resp) => {
    resp.status(200).json("Core-Direction application is working");
});

router.get('/dummy-layout', async (_, resp) => {

    // const ookingEmail = new BookingEmail(36537);
    // ookingEmail.sendEmail();
    // resp.send("sssss")
    resp.render("templates/booking/confirmation-online.ejs", {
            id: 36537,
            confirmation_token: null,
            title: "sdsdsdsdsd",
            qr_code: '80f4ef5f-d4bd-40c7-a76f-5836c03813cc.png',
            zone_title: 'xcx',
            activity_image: '60855461cd3d4679256284.png',
            created_date: "2021-09-24T22:22:39.000Z",
            class_duration: 90,
            class_date: '24-09-2021',
            startTime: '11:03 PM',
            endTime: '12:33 AM',
            activity_name: 'Sunset Breath Dance',
            offer_online: 1,
            facility_email: 'dubaiopera@coredirection.com',
            login_url_online: '1111111',
            login_password_online: '111111',
            meeting_id_online: '11111111',
            activity_type_name: 'Yoga and Meditation',
            member_name: 'Danielle Riach',
            member_email: 'danielle.riach@hotmail.co.uk',
            city: 'Dubai',
            facility: 'Dubai Opera',
            classLatitude: '25.195241',
            classLongitude: '55.273433',
            facilityLatitude: '25.195241',
            facilityLongitude: '55.273433',
            address: 'Unnamed Road - Downtown Dubai - Dubai - United Arab Emirates',
            activity_description: 'Unnamed Road - Downtown Dubai - Dubai - United Arab Emirates',
            activity_schedule_address: 'Unnamed Road - Downtown Dubai - Dubai - United Arab Emirates',
            instruction_file: 'https://coredirection.s3.us-east-2.amazonaws.com/dev/images/activity/xxxxx',
            activity_logo: 'https://coredirection.s3.us-east-2.amazonaws.com/dev/images/activity/60855461cd3d4679256284.png'
        }
    );
});

module.exports = router;
