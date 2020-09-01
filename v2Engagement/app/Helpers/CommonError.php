<?php

namespace App\Helpers;

class CommonError
{
    const  PLATFORM_ERROR_ENCOUNTER = 'Platform Error Encountered. Contact Engagement Support';
    const AUTH_MISSING = 'Authorization Missing';
    const CAMPAIGN_VALIDITY_EXPIRED = 'Campaign Validity Expired';
    const CAMPAIGN_EVENT_NOT_FOUND = 'Campaign Conversion Event not found';
    const INVALID_TRACK_KEY = 'Invalid Campaign Tracking key';
    const INVALID_USER_ID = 'Invalid User Id provided';
    const USER_NOT_ACTIVE = 'User is not active';
    const INVALID_USER_TOKEN = 'User token is incorrect or User may have logged in on some other device';
    const APP_NOT_REGISTER = 'App is not register with Campaign';
    const NO_CAMPAIGN_SEGMENT_EXIST = 'No Campaign Segment Exist';
    const USER_ID_NOT_EXIST_SEGMENT = 'User Id Not Exist in this Segment';
    const DELIVERY_CONTROL_ERROR = 'Delivery control is enabled for campaign, and associated users have already been sent the campaign and delivery limit time hasn\'t been reached yet';
    const AUTHORIZATION_HEADER_MISSING = 'Authorization headers missing';
    const TOKEN_EXPIRED = 'Token has expired';
    const USER_NOT_FOUND = 'User not found';
    const COMPANY_NOT_FOUND = 'Company not found';
    const NOTIFICATION_TYPE_INVALID = "Invalid notification type provided. Should either be 'email' or 'app'";
    const NOTIFICATION_ENABLED = 'Invalid toggle value provided. It should always be a boolean';
    const NOTIFICATION_PREFERENCE_UPDATE = 'Unable to update notification preferences';
    const LOOK_UP_CODE_ERROR = 'Invalid  code Provided or campaign already sent';
    const CAMPAIGN_NOT_FOUND = 'No Campaign Found';

    const STATUS_CODE_UNAUTHORIZED = 401;
    const STATUS_CODE_FORBIDDEN = 403;
    const STATUS_CODE_NOT_FOUND = 404;
    const STATUS_CODE_LENGTH_REQUIRED = 411;
    const STATUS_CODE_UNPROCESSABLE_ENTITY = 422;
    const STATUS_CODE_BAD_GATEWAY = 502;
}
