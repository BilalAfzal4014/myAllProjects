<?php /** @noinspection PhpLanguageLevelInspection */

namespace App\Coredirection\Common;


final class Shared
{

    public static $COUNTRY_CODES_LIST_REST_API_URL ="https://restcountries.eu/rest/v2/all";
    public static $NO_RECORD_FOUND="No record found!";
    public static $CHECKOUT_SANDBOX="sandbox";
    public static $APPROVED="Approved";
    public static $REFUNDED="Refunded";
    public static $ERROR_REFUNDED_MSG="Oops an error occur in refunding amount";

    public static $UNAUTHORIZED_REQUEST="Unauthorized request";
    public static $INVALID_LANGUAGE="Invalid language parameter!";

    public static $NO_APP_CONFIG_FOUND ="Application configuration not available";

    ############################### Checkout Mssages ###########################
    public static $NO_DATA_RECEIVED_CHECKOUT ="No product data received, please try again!";
    public static $ZERO_AMOUNT_TRANSACTION_ERROR ="Transaction amount should be greater then zero!";
    public static $CLASS_NOT_PROVIDED_CHECKOUT ="Class Not Provided for Checkout";
    public static $INVALID_USER_CHECKOUT ="Invalid User Found for Checkout";
    public static $BANK_INFO_NOT_FOUND_CHECKOUT ="Bank info not found for checkout. Please contact Coredirection support";
    public static $MASTER_FRANCHISE_NOT_FOUND_CHECKOUT ="Master fracnchise not found. Please contact Coredirection support";


    ############################### Billing ###########################
    public static $MEMBER_SECRET_KEY ="Member secret key not found";
    public static $BILLILNG_DETAIL_NOT_FOUND ="Billing detail not found";
    public static $MEMBER_BILLILNG_NOT_FOUND ="Member billing not found";
    ############################### Member Controller ###########################

    public static $INVALID_USER="You were logged out because you logged in on another device or need to update your current app. Please check for updates or log in again.";
    public static $INVALID_COMPANY_CODE="Invalid Company code provided";
    public static $PROVIDE_VALID_CHART_INTERVALS="Please provide valid chart intervals";
    public static $COMPANY_KEY_EXPIRED="Company key has been expired.";
    public static $INVALID_COMPANY_KEY="Invalid membership number.";
    public static $COMPANY_KEY_ALREADY_USED="Company key already used.";

    public static $USERNAME_ALREADY_EXIST="Username already exists!";
    public static $EMAIL_ALREADY_EXIST="Email already exists!";
    //public static $DEPARTMENT_NOT_EXIST="Department does not exist!";
    public static $INVALID_CORPORATE_USER_KEY="Invalid Corporate user or corporate key!";
    public static $CHECK_YOUR_EMAIL_ACTIVATION="Please check your email and activate your account to login.";
    public static $PASSWORD_ALREADY_REQUESTED="Password already requested. Please check your email";
    public static $IMAGE_FIELD_REQUIRED="Image field Required!";




    public static $INVALID_USERNAME_OR_EMAIL= "Oops! You have entered wrong username/email";
    public static $EMAIL_VERIFICATION_REQUIRED= "Oops! Account not activated yet. Please check your email and activate your account to login.";
    public static $ONLY_MEMBER_GROUP_ALLOWED_LOGIN= "Oops! Only member group is allowed to login from the app.";
    public static $WRONG_PASSWORD= "Oops! You have entered wrong password.";
    public static $USER_ALREADY_HAS_DISCOUNT_KEY_= "User already has a valid discount key";

    ############################### Activity Controller ###########################
    public static $NO_FAVORITE_ACTIVITY_FOUND="No Favorite activities found";
    public static $NO_RECOMMENDED_ACTIVITY_FOUND="No Recommended activities found";
    public static $NO_ACTIVITY_FOUND="No activities found";
    public static $NO_FACILITY_FOUND_WITH_THIS_ID="No facility found";
    public static $NOT_A_NON_PARTNER="Given Facility is not a non partner";
    public static $NO_CLASS_SCHEDULE_FOUND_WITH_THIS_ID="No class schedule found";
    public static $NUMBER_OF_CLASSIDS_PACKAGES_MUST_SAME="Number of class ids and package ids must be same";
    public static $CLASS_BOOKING_ALREADY_CANCELED="Class booking already canceled.";
    public static $NO_CLASS_BOOKING_FOUND_TO_CANCEL="No class booking found to cancel.";
    public static $PLEASE_ENTER_CLASSID_FACILITYID="Please enter class id!";
    public static $NO_PACKAGE_FOUND_WITH_THIS_ID="No package found";
    public static $NO_PACKAGE_FOUND_WITH_DEFAULT_CODE="No package found with default code";
    public static $MEMBER_PACKAGE_ID_NOT_FOUND="Member package ID not found";
    public static $WRONG_TYPE_SESSION_MEMBERSHIP="Type should be Session or Membership";
    public static $HISTORY_NOT_FOUND="History not found";
    public static $PRODUCT_NOT_FOUND="Products array is required";
    public static $CLASS_NOT_PROVIDED="Class Not Provided for Checkout";
    public static $BANK_INFO_NOT_FOUND="Bank info not found for checkout";
    public static $NO_SLOTS_AVAILABLE="Oops, this activity is fully booked. Please search our listings to find an alternative option.";
    public static $LOCATION_DO_NOT_CORRESPOND_WITH_GYM = "It seems that you are not present at the correct facility/gym.";
    public static $NO_BOOKINGS_FOR_NEXT_HOUR = "You have no bookings within the next hour or you are not within checkin range.";
    public static $CHECK_IN_SUCCESSFUL = "You have successfully checked in for ";
    public static $INVALID_MEMBER_SCHEDULE_ACTIVITY_ID = "Invalid Member Schedule Activity Id";
    public static $TIME_ISSUE_MEMBER_SCHEDULE_ACTIVITY_ID = "You can only check-in 1 hour prior to booking start time.";
    public static $AUTO_CHECK_IN_MESSAGE = "You have been automatically checked in to {activity_name} at {facility_name} based on your GPS location";
    public static $AUTO_CHECK_IN_TITLE = "Auto Check";
    public static $CLASS_TIME_CANCELLED_TITLE = "Booking Cancellation";
    public static $CLASS_TIME_CANCELLED_DESCRIPTION = "Your {activity_name} scheduled for {time_stamp} has been cancelled. Please check in app bookings for details. ";
    public static $CLASS_TIME_CHANGED_DESCRIPTION = "Your {activity_name} scheduled for {old_time_stamp} has been changed to {new_time_stamp}. Please check in app bookings for details.";
    public static $CLASS_TIME_CHANGED_TITLE = "Change to Class Schedule";
    public static $NON_PARTNER_ALREADY_CHECK_IN = "Non partner already checked in";
    public static $MANUAL_ALREADY_CHECK_IN = "Activity already checked in";
    public static $NON_PARTNER_CHECK_IN_ERROR = "You are not within 250m check-in range. Please check-in when you arrive at the location to collect your activity points.";
    public static $_AVAILABLE = 0;
    public static $_BOOK= 1;
    public static $_ALREADY_CHECK_IN= 2;
    public static $_CANCELLED= 3;
//    public static $_ACTIVITY = 0;


    ############################### Workout Controller ###########################
    public static $NO_EXERCISE_FOUND="No exercises found";
    public static $NO_WORKOUT_FOUND="No workout found";
    public static $WORKOUT_ALREADY_EXIST_IN_FAVORITE="Workout already exists in favorite list.";
    public static $KEY_ID_NOT_FOUND="No data available to show.";

    ############################### Article Controller ###########################
    public static $NO_FAVORITE_ARTICLE_FOUND="No Favorite articles found";
    public static $NO_ARTICLE_FOUND="No article found";
    public static $ARTICLE_ALREADY_EXIST_IN_FAVOITE="Article already exists in favorite list.";



    ############################### Package ###########################
    public static $PACKAGE_CODE="DEFAULT_PACKAGE";

    ###################### Check List Messages ############################
    public static $CHECKLIST_ABOUT_MSG="5 tasks to personalise your profile";
    public static $CHECKLIST_MEASUREMENTS_MSG="Step 2 - Log your measurements";
    public static $CHECKLIST_TAKE_SELFIE_MSG="Step 3 - Take your Before/After selfie";
    public static $CHECKLIST_GOALS_MSG="Step 4 - Set some goals";
    public static $CHECKLIST_OBJECTIVES_MSG="Step 5 - Tell us what you're trying to achieve";

    ###################### Enum List ############################
    public static $FAVORITE="favorite";
    public static $BOOK="booked";
    public static $RESERVED="reserved";
    public static $Active="active";
    public static $IS_ACTIVE=1;
    public static $IS_NOT_ACTIVE=0;
    public static $CANCELLED="cancelled";
    public static $EXPIRED="expired";
    public static $REFUND="Refund";
    public static $TRANSACTION_TYPE_PAYMENT="Payment";
    public static $TRANSACTION_TYPE_REFUND="Refund";
    public static $TRANSACTION_TYPE_CANCELLATION="Cancellation";
    public static $PACKAGE_SESSION="Session";
    public static $PACKAGE_MEMBERSHIP="Membership";

    public static $KEY_TYPE_PACKAGE="Package";
    public static $KEY_TYPE_PROFILE="Profile";
    public static $KEY_TYPE_DISCOUNT="Discount";
    public static $KEY_TYPE_COREPASS="CorePass";
    public static $KEY_TYPE_DEFAULT="Default";
    public static $KEY_TYPE_Referral="Referral";

    public static $FRANCHISE_TYPE="MasterFranchise";
    public static $GROUPG_TYPE_INSTRUCTOR="Instructor";
    public static $GROUPG_TYPE_FACILITY="Facility";
    public static $GROUPG_TYPE_SUPER_FRANCHISE="SuperFranchise";
    public static $GROUPG_TYPE_NON_PARTNER="NON_PARTNER";
    public static $GROUPG_TYPE_MEMBER="Member";

    public static $LANGUAGES = array("en","ar","fr");

    ###################### Error Messages ############################
    public static $FIELD_REQUIRED="field required!";
    public static $INVALID_DATATYPE="Invalid datatype";
    public static $INVALID_PATTERN="Invalid pattern";
    public static $INVALID_DATA_LENGTH="Invalid data length found";

    public static $PHONE_FORMAT_INCORRECT="Mobile phone format is incorrect for selected region.";
    public static $USERNAME_FORMAT_INCORRECT="Username can only contain alphanumeric characters.";
    public static $EMAIL_FORMAT_INCORRECT="Email address is invalid. Please provide an alternative email.";
    public static $LATLONG_INCORRECT="Turn on location services in your device settings to improve your activity search.";


    ###################### Mailing Address ############################
    public static $FROM_EMAIL="noreply@coredirection.com";
    public static $BCC_EMAIL_1="ashfaqjan@rebeltechnology.io";
    public static $BCC_EMAIL_2="maavia.kamran@rebeltechnology.io";
    public static $BCC_EMAIL_3="ashfaqjan@rebeltechnology.io";
    public static $BCC_EMAIL_4="maavia.kamran@rebeltechnology.io";
    public static $SMTP_EMAIL="support@coredirection.com";
    public static $SMTP_PASSWORD="Cvt04061985$";
//    public static $BCC_EMAIL_ARRAY_PROD =array(self::$BCC_EMAIL_2,self::$BCC_EMAIL_3,self::$BCC_EMAIL_1);
//    public static $BCC_EMAIL_ARRAY_DEV =array(self::$BCC_EMAIL_3,self::$BCC_EMAIL_4);


    ###################### Adaptive Layout  ############################
    public static $BUILT_SETTING_NOT_FOUND="App Settings not found for Build number ";

    ###################### GDPR Messages  ############################
    public static $GDPR_ERROR_MESSAGE="You have been logged out . Please update your app to ";

    ####################


    public static $CORPORATE_KEY_SENT_MESSAGE="Your corporate key has been generated and sent to your email. Please have a look on your email inbox";




    ############################# API WHITE LABLES #####################
    const WHITE_LABLE_CLIENTS = ['HDFC_PRIVATE'];
    const IMAGE_FACILITY_PATH = '/web/images/member/';

    /**
     * @param $version
     * @return string
     */
    public static function getForceLogoutMessage($version){
        return 'Please update your app to '.$version.' to login.';
}


    ############################ App Version ####################
    const iphone = 'iphone';
    const android = 'android';


    ##################### Invitation Member #####################

    const ENQUEUE = 'enqueue';
    const PENDING = 'pending';
    const SENT = 'sent';
    const FAILED = 'failed';
    const CONFIRMED = 'confirmed';
    const CORPORATE_SUCCESS_MESSAGE = " registration completed successfully.";
    const CORPORATE_WITH_CHALLENGE_INVITE = "You have successfully joined the {challenge_name} Challenge.";

    ################## Configurations  ###################
    const  checkin_distance_buffer = 'checkin_distance_buffer';
    const  member_invitation_limit = 'member_invitation_limit';
    const  APP_CONFIGURATION = 'APP_CONFIGURATIONS';
    const  confirmed_corporate_account_title = 'Confirm Your Corporate Account';
    const  confirmed_corporate_account_content = ' has invited you to join their group. Please follow the steps below to receive access to thousands of local activities, downloadable workouts and a library of fitness, nutrition and lifestyle articles.';

    const INVALID_KEY_CORPORATE= "You cannot accept a group invite while logged in under a different email address. Please request invite under your registered account or create a new account under your current invite.";
    const Expire_MESSAGE_CORPORATE = "This invite has been accepted or expired. Please request re-invite to join or check profile to see if you’ve already accepted correctly.";
    const CHECK_IN_CALL = "checkin";

    const checked_in = "Checked-in";
    const completed = "Complete";
    const MANUAL_ACTIVITY_DESC = "Manual activity log";
    const NON_PARTNER_CHECK_DESC = "Non partner log";
    const Absent = "Absent";
    const CHECKIN = "CHECKIN";
    const WORKOUT = "WORKOUT";
    const ARTICLE = "ARTICLE";
    const NON_PARTNER_CHECKIN = "NON_PARTNER_CHECKIN";
    const INVALID_NAME_ERROR = "Name should not contain special characters";
    const INVALID_NAME_ERROR_FOR_ACTIVITY = "Name should not contain special characters like ' or \"";
    const MANUAL_ACTIVITY_ERROR_MESSAGE = "You can only add activity completed today or a maximum of 2 days in the past.";
}
