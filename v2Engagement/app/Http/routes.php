<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::auth();
Route::post('/login', ['middleware' => 'throttle:5', 'uses' => 'Auth\AuthController@login']);
Route::get('/campaign/tracking/{encryptUrl}', 'CampaignTrackingController@index');
Route::get('/campaign/inapp/view/{encryptUrl}', 'CampaignInAppNotificationViewController@index');
Route::get('/notification/inapp/view', 'NotificationInAppViewController@index');
Route::get('email/optout/{track_key}', 'NotificationInAppViewController@optout');
Route::post('updateTrackingStatus', 'NotificationInAppViewController@updateTrackingStatus');
Route::get('/trackLink', 'CampaignController@getCampaignUrl');
Route::group(['middleware' => ['auth', 'privilege']], function () {

Route::get('/', 'HomeController@index');
Route::get('/getCompanyLogo/{companyId}', 'HomeController@companyLogo');
    
    Route::get('/dashboard', 'HomeController@index')->name('dashboard');
    //Route::get('/dashboard', 'AttributeDataController@attributeDataView')->name('dashboard');
    
    Route::get('/newDashboard', 'HomeController@newDashboard')->name('newDashboard');
    Route::get('/emailListing', 'HomeController@emailListing')->name('emailListing');
    Route::get('/email/listing/fetch', 'HomeController@emailListingFetch');
    Route::get('/email/listing/{filter}', 'HomeController@emailListingFilter');
    Route::get('/email/delete/{id}', 'HomeController@emaildelete');
    Route::get('/email/change/{statue}/{id}', 'HomeController@emailstatus');

    Route::get('/backend/campaign/campaigns', 'CampaignController@campaignView');
    Route::get('/backend/campaign/createCampaign', 'CampaignController@createCampaign')->name("campaignCreate");
    Route::get('/backend/campaign/campaignAction/{action}/{id}', 'CampaignController@campaignAction');
    Route::get('/backend/campaign/preData/{companyId}/{campaignId?}', 'CampaignController@campaignPreData');
    Route::get('/backend/campaign/listing/{companyId}', 'CampaignController@campaignListing');
    Route::get('/backend/campaign/campaignTrackingListing/{companyId}', 'CampaignController@campaignTrackingListing');
    Route::get('/backend/campaign/campaignActionTrigger/{companyId}', 'CampaignController@campaignActionTrigger');
    Route::get('/backend/campaign/campaignDetail/{companyId}', 'CampaignController@campaignDetail');
    Route::get('/backend/campaign/resendNotification/{companyId}/{id}', 'CampaignController@resendNotification');
    Route::get('/backend/campaign/filters/{companyId}', 'CampaignController@campaignFilters');
    Route::get('/backend/campaign/get/{campaignId}', 'CampaignController@campaignGet');
    Route::post('/backend/campaign/campaignInsertion', 'CampaignController@campaignInsertion');
    Route::post('/backend/campaign/getUserIdByEmail', 'CampaignController@getUserIdByEmail');
    Route::get('/backend/campaign/suspend/{campaignId}', 'CampaignController@suspendCampaign');
    Route::get('/backend/campaign/stats/{campaignId}', 'CampaignController@campaignStaticsView');
    Route::post('/backend/campaign/getStats', 'CampaignController@getCampaignStatics');
    Route::get('/backend/campaign/getTestUsers', 'CampaignController@getSendTestUsers');
    Route::post('/backend/checkAndGetCampaignTemplate', 'CampaignController@checkAndGetCampaignTemplate');
    Route::get('/backend/getusersagainstcampaign/{campaignId}', 'CampaignController@campaignGetCacheUsers');
    Route::get('/backend/campaign/getSegments', 'CampaignController@getCompanySegmentsWithSearch');
    Route::get('/backend/campaign/capping-settings', 'CampaignController@getCappingSettingsView');
    Route::get('/backend/get/campaign/capping-settings/{companyId}', 'CampaignController@getCappingSettings');
    Route::post('/backend/submit-capping-rules/{companyId}', 'CampaignController@submitCappingSettings');


    //news Feed
    Route::get('/newsfeed/new', 'NewsFeedController@test');

    Route::post('/newsfeed/search-table', 'NewsFeedController@getNewsfeedBySearch');
    Route::get('/backend/newsfeed/list', 'NewsFeedController@index')->name("newsfeedList");
    Route::get('/backend/newsfeed/create', 'NewsFeedController@create')->name("newsfeedcreate");
    Route::get('/backend/newsfeed/edit/{id?}', 'NewsFeedController@edit')->name('editNewsfeed');
    Route::get('/backend/newsfeed/view/{id?}', 'NewsFeedController@show')->name('viewNewsfeed');
    Route::post('/backend/newsfeed/update/{id?}', 'NewsFeedController@update')->name('updateNewsfeed');
    Route::post('/backend/newsfeed/updateLanguage/{id?}', 'NewsFeedController@updateLanguage')->name('updateLanguageNewsfeed');
    Route::post('/backend/newsfeed/save', 'NewsFeedController@store')->name('saveNewsfeed');
    Route::post('/backend/newsfeed/destroy', 'NewsFeedController@destroy')->name('deleteNewsfeed');
    Route::get('/backend/newsFeeds/getTemplateData/{id?}', 'NewsFeedController@getTemplateData')->name('getTemplateData');
    Route::get('/backend/newsFeeds/getTranslation/{id?}', 'NewsFeedController@getTranslationData')->name('getTranslation');
    Route::get('/backend/newsfeed/listing/{companyId}', 'NewsFeedController@newsfeedsListing');
    Route::get('/backend/newsfeed/segment/count', 'NewsFeedController@newsfeedSegmentCount');
    Route::get('/backend/newsfeed/suspend/{id?}', 'NewsFeedController@newsfeedSuspend');
    Route::post('/backend/newsfeed/check/duplication', 'NewsFeedController@checkDuplication')->name("newsfeedDuplication");
    Route::get('/backend/newsfeed/preloadData', 'NewsFeedController@newsfeedPreLoadData');
    Route::post('/backend/newsFeed/get-multilingual', 'NewsFeedController@newsFeedGetMultiLingual');

    //Gallery Route
    Route::get('/sendmail', 'GalleryController@sendmail');
    Route::get('/gallery', 'GalleryController@index')->name('gallery');
    Route::get('/gallery/fetch', 'GalleryController@fetch')->name('fetchGallery');
    Route::post('/gallery/crop', 'GalleryController@crop')->name('cropImage');
    Route::post('/gallery/destroy', 'GalleryController@destroy')->name('gallery.destroy');
    Route::post('/gallery/do_upload', "GalleryController@do_upload")->name('doUpload');
    Route::get('/backend/gallery/listingForModal/{companyId}', "GalleryController@modalListing");


    //setting Route
    Route::get('/setting', 'SettingController@index')->name('setting');
    Route::get('/setting/edit/{id?}', 'SettingController@edit')->name('setting.edit');
    Route::post('/setting/update', 'SettingController@update')->name('setting.update');

    //Lookup Route
    Route::get('/lookup/listing', 'LookupController@show_all')->name('lookup.index');
    Route::get('/lookup/listing/fetch', 'LookupController@fetch_lookup');
    Route::get('/lookup/create', 'LookupController@create');
    Route::get('/lookup/check_duplication', 'LookupController@checkDuolication')->name('lookup.checkDuplication');
    Route::post('/lookup/submit', 'LookupController@store')->name('lookup.submit');
    Route::get('/lookup/delete/{id?}', 'LookupController@delete_lookup')->name('lookup.delete');

    // Location Route
    Route::get('/location/index', 'LocationController@index')->name('location.index');
    Route::get('/location/create', 'LocationController@crud')->name('location.create');
    Route::get('/location/edit/{id?}', 'LocationController@crud')->name('location.update');
    Route::post('/location/store', 'LocationController@store')->name('location.store');
    Route::get('/location/get', 'LocationController@fetch')->name('location.fetch');
    Route::get('/location/delete/{id?}', 'LocationController@delete_location')->name('location.delete');
    Route::post('/location/check/duplication/', 'LocationController@checkDuplication')->name('location.duplication');

//    App Routes

    Route::get('/backend/app/listing', 'AppController@index')->name("app.index");//->middleware(['role:segment-listing']);
    Route::get('/backend/app/listing/fetch', 'AppController@fetch')->name("app.fetch");//->middleware(['role:segment-listing']);
    Route::get('/backend/app/create', 'AppController@crud')->name("app.create");//->middleware(['role:segment-listing']);
    Route::get('/backend/app/edit/{id?}', 'AppController@crud')->name("app.edit");//->middleware(['role:segment-listing']);
    Route::post('/backend/app/crud/submit', 'AppController@submit')->name("app.submit");//->middleware(['role:segment-listing']);
    Route::get('/backend/app/delete/{id?}', 'AppController@delete_app')->name('lookup.delete');
    //Segment Routes
    // Route::get('/backend/segment/segments', 'SegmentController@segmentView')->middleware(['role:segment-listing']);
    // Route::get('/backend/segment/listing', 'SegmentController@segmentListing')->middleware(['role:segment-listing']);
    Route::get('/backend/segment/segments', 'SegmentController@segmentView');//->middleware(['role:segment-listing']);
    Route::get('/backend/segment/createSegment', 'SegmentController@segmentCreateView');
    Route::get('/backend/segment/segmentAction/{action}/{id}', 'SegmentController@segmentAction');
    Route::get('/backend/segment/listing/{companyId}', 'SegmentController@segmentListing');
    Route::post('/backend/segment/create', 'SegmentController@segmentCreate');
    Route::get('/backend/segment/filters/{companyId}', 'SegmentController@segmentGetFilters');
    Route::get('/backend/segment/get/{id}', 'SegmentController@segmentGet');
    Route::get('/backend/segment/cacheusers/{segmentId}', 'SegmentController@getSegmentCacheUsers');


    //Quick action route
    Route::get('/backend/send/quick/notification', 'QuickActionController@index');

    //Quick notification
    Route::get('/notification/quickNotification', 'QuickNotificationController@quickNotification');
    Route::get('/notification/getCompanyKey/{id}', 'QuickNotificationController@getCompanyKey');
    Route::get('/notification/getQuickNotificationData/{companyId}', 'QuickNotificationController@getPreData');
    Route::get('/notification/getQuickNotificationDataOnSelection/{companyId}/{step}/{appName}/{PlatForm?}/{version?}/{build?}', 'QuickNotificationController@getDataOnSelection');
    Route::get('/getUserList/{companyId}/{step}/{appName}/{PlatForm?}/{version?}/{build?}', 'QuickNotificationController@getUserList');


    /***************************************** Attributes Data **************************************************************/
    /***************************************** Attributes Data **************************************************************/
    Route::get('/backend/attribute/attributeData', 'AttributeDataController@attributeDataView')->name('attributeDataView');
    Route::get('/backend/attribute/user/Stat/{id}', 'AttributeDataController@attributeDataStat')->name('attributeDataUserStat');
    Route::get('/backend/attribute/attributeDataFilter', 'AttributeDataController@attributeDataViewFilter')->name('attributeDataViewFilter');
    Route::get('/backend/curl/test', 'AttributeDataController@testDelete')->name('testDelete');
    Route::get('/backend/attribute/delete/test/cache', 'AttributeDataController@testDeleteFile')->name('testDeletefile');
    Route::get('/backend/attribute/delete/test/file/uplaod', 'AttributeDataController@testDeleteFileUpload');
    /***************************************** Other Attributes Data **************************************************************/
    /***************************************** Other Attributes Data **************************************************************/
    Route::get('otherAttributedata', 'AttributeDataController@otherAttributedata');
    Route::get('other-attribute-data-view', 'AttributeDataController@otherAttributeDataView')->name('otherAttributeDataView');
    Route::get('other-attribute-data-dt', 'AttributeDataController@otherAttributeDataDT')->name('otherAttributeDataDT');
    Route::get('lookupCodeListing/{id}', 'AttributeDataController@lookupCodeListing');
    Route::post('/saveOtherAttribute', 'AttributeDataController@saveOtherAttribute');
    Route::get('/deleteOtherAttributeData/{id}', 'AttributeDataController@deleteOtherAttributeData');
    Route::get('/editOtherAttributedata/{id}', 'AttributeDataController@editOtherAttributedata');
    /***************************************** Other Attributes Data **************************************************************/
    /***************************************** Other Attributes Data **************************************************************/
    /***************************************** Attributes Crud**************************************************************/
    Route::get('/attributes/list', 'AttributeDataController@attributeList');
    Route::get('/create/attribute', 'AttributeDataController@createAttribute');
    Route::get('/attribute/listing', 'AttributeDataController@attributeListing');
    Route::get('/delete/attributes/{id}', 'AttributeDataController@deleteAttributes');
    Route::get('/edit/attributes/{id}', 'AttributeDataController@editAttributes');
    Route::get('/attributes/duplication/check/{id}', 'AttributeDataController@attributeDuplicationCheck');
    Route::post('/save/attribute', 'AttributeDataController@saveAttribute')->name('attribute.submit');

    /***************************************** Attributes Crud**************************************************************/

    Route::get('email-list-view', 'AttributeDataController@emailListView')->name('emailListView');
    Route::get('email-list-dt', 'AttributeDataController@emailListDT')->name('emailListDT');
    Route::post('delete-email-list', 'AttributeDataController@deleteEmailList')->name('deleteEmailList');

    Route::get('/backend/attributeData/listing/{companyId}', 'AttributeDataController@attributeDataListing');
    Route::get('/backend/attribute/import-attribute-data', 'AttributeDataController@importAttributeDataView')->name('importAttributeDataView');
    Route::post('/backend/attribute-data/import-targeted-users', 'AttributeDataController@importTargetedUsers')->name('importTargetedUsers');
    Route::post('attribute-data/delete-attr', 'AttributeDataController@deleteAttr')->name('deleteAttr');

    Route::post('attribute-data/upload-file', 'AttributeDataController@uploadFile')->name('uploadFile');
    Route::get('import-data/import-file-view', 'AttributeDataController@importFileView')->name('importFileView');
    Route::get('import-data/import-file-view-filter', 'AttributeDataController@importFileViewFilter')->name('importFileViewFilter');
    Route::get('import-data/upload-file-listing', 'AttributeDataController@importFileListing')->name('importFileListing');
    Route::post('import-data/importFileDelete', 'AttributeDataController@importFileDelete')->name('importFileDelete');
    //end Attribute data route
    //------------------------


    //    Route::get('/backend/attributeData/listing/{companyId}', 'AttributeDataController@attributeDataListing');
    Route::get('/backend/company/companyStats', 'AttributeDataController@companyStats')->name('companyStats');
    Route::get('/backend/attribute/getProfileDetails/{companyId}', 'AttributeDataController@getProfileDetails');
    Route::get('/backend/attribute/getCustomAttributes/{companyId}', 'AttributeDataController@getCustomAttributes');
    Route::get('/backend/user/attribute/campaignTracking/{type?}/{rowId?}', 'AttributeDataController@campaignTrackingType');
    Route::get('/backend/user/attribute/data/{type?}/{rowId?}', 'AttributeDataController@getUserAttributeDataCache');
    Route::get('/backend/attribute/getCampaignClick/{companyId}', 'AttributeDataController@getCampaignClick');
    Route::get('/backend/attribute/getSegmentsInfo/{companyId}', 'AttributeDataController@getSegmentsInfo');
    Route::get('/backend/user/getNewsfeedClick/{rowId}', 'AttributeDataController@getNewsfeedClick');
    Route::get('/backend/user/getCampaignConversion/{type?}/{rowId?}', 'AttributeDataController@getCampaignConversion');
    Route::get('/backend/user/getAppLastLogin/{rowId?}', 'AttributeDataController@getAppLastLogin');
    Route::get('/backend/user/getAppListing/{rowId?}', 'AttributeDataController@getAppListing');
    Route::post('/backend/user/update', 'UserController@updateUser');
    Route::get('/backend/attribute-data/download-sample-file', 'AttributeDataController@downloadSampleFile')->name('downloadSampleFile');
    Route::get('attribute-data/download-ad-file/{id?}', 'AttributeDataController@downloadADFile')->name('downloadADFile');


    Route::post('news-feed/mobile-plateform', 'NewsFeedController@mobilePlateformStatics')->name('mobilePlateformStatics');

    Route::resource('users', 'UserController');
    Route::get('/user/status/{userId}/{status}', 'UserController@Status')->name('userStatus');

    Route::get('/user/delete/{userId}/{status}', 'UserController@deleteEntries')->name('userDeleteEntries');
    Route::get('/backend/user/listing', 'UserController@userListing');
    Route::resource('roles', 'RoleController');
    Route::resource('permissions', 'PermissionController');


    //news feed templates Route
    Route::get('newsFeedTemplates', 'NewsFeedTemplate@index');
    Route::get('newsFeedTemplates/listing/{id}', 'NewsFeedTemplate@NewsFeedTemplateListing');
    Route::get('newsFeedTemplatesCreate', 'NewsFeedTemplate@newsFeedTemplatesCreate')->name('newsFeedTemplates.create');
    Route::get('newFeedTemplatesStatus/{id}/{status}', 'NewsFeedTemplate@newFeedTemplatesStatus');
    Route::post('saveNewsFeedTemplate', 'NewsFeedTemplate@saveNewsFeedTemplate');
    Route::get('newFeedTemplates/edit/{id}', 'NewsFeedTemplate@edit');

//campaign feed templates Route

    Route::get('campaignTemplates', 'CampaignTemplateController@index');
    Route::get('campaignTemplates/listing/{id}', 'CampaignTemplateController@listing');
    Route::get('campaignTemplatesCreate', 'CampaignTemplateController@campaignTemplatesCreate')->name('campaignTemplates.create');
    Route::post('saveCampaignTemplate', 'CampaignTemplateController@saveCampaignTemplate');
    Route::get('campaignTemplates/edit/{id}', 'CampaignTemplateController@edit');

    // campaignQueue
    Route::get('campaignQueue', 'CampaignTemplateController@campaignQueue');
    Route::get('/updateCampaignStatus/{id}', 'CampaignTemplateController@updateCampaignStatus');
    Route::get('/deleteCampaignQueue/{id}', 'CampaignTemplateController@deleteCampaignQueue');
    Route::get('/campaignQueue/filter/{id}', 'CampaignTemplateController@filter');
    Route::get('/campaignQueueFilter', 'CampaignTemplateController@campaignQueueFilter');
    Route::get('/queueJobFilter', 'CampaignTemplateController@queueJobFilter');
    Route::get('/campaign/trackinglist/{id}', 'CampaignTemplateController@tracking');
    Route::get('/campaign/campaignTracklistingFilter/{id}', 'CampaignTemplateController@campaignTracklistingFilter');
    Route::get('/campaignResendNotification/{compaignid}/{id}/{companyId}', 'CampaignTemplateController@campaignResendNotification');
});

Route::group(['middleware' => ['JwtAuth']], function () {
    Route::get('apitest', function () {
        echo 'Authenticated';

    });
});

Route::get('/backend/queue/listQueueJobs', 'QueueController@listQueueJobs');
//Route::get('/backend/queue/sendCampaignDataInQueue', 'QueueController@sendCampaignDataInQueue');
Route::get('/backend/queue/sendNewsFeedDataInQueue', 'QueueController@sendNewsFeedDataInQueue');
Route::get('/backend/queue/campaignDataBroadCast/{queueId}', 'QueueController@campaignDataBroadCast');
Route::get('/backend/queue/newsfeedBroadCast/{queueId}', 'QueueController@newsfeedBroadCast');

Route::post('my_send_app_data', 'AttributeDataController@sendAppData');

Route::group(['middleware' => ['api'], 'prefix' => 'api/{version}'], function () {
    Route::post('message/send', 'Api\\MessageController@store');
    Route::post('notification/toggle', 'Api\\NotificationToggleController@store');

//    User Registration and info api
    Route::post('initialize/platform', 'Api\\CompanyController@initializePlatform')->name('initializePlatform');
    Route::post('update/bulk/user', 'Api\\CompanyController@initializeusers')->name('initializeUsers');
    Route::post('initialize/app', 'Api\\CompanyController@initializeApp');
    Route::post('get/action/list', 'Api\\CampaignController@getActionList');
    Route::post('get/user/actions', 'Api\\CompanyController@getUserActions');

//    Custom test jwt token
    Route::post('generateJWTToken/{companyKey}', 'QueueController@generateJwtToken');
    Route::post('generateJWTToken/company/key/{companyKey}', 'QueueController@generateJWTTokenFromCompanyKey');


    //    Campaign Api
    Route::post('create/campaign/conversion', 'Api\\CampaignController@createCampaign');
    Route::post('create/campaign/action/trigger', 'Api\\CampaignController@createActionTrigger');
    Route::post('create/campaign/api/trigger', 'Api\\CampaignController@createTriggerActionCampaign');
    Route::post('push/tracking/service', 'Api\\CampaignController@pushAnalyticalTracking');

    //  Newsfeed Api
    Route::post('newsfeed/get_news', 'NewsFeedController@getNews');
    Route::post('newsfeed/get_news_count', 'NewsFeedController@getNewsCount');
    Route::get('newsfeed/get_news/modify', 'NewsFeedController@getNewsModify');
});

Route::get('stats', 'StatsController@index');
Route::post('stats', 'StatsController@store');
Route::post('changeNotification', 'StatsController@changeNotification');


Route::get('/campaignTrack/listingFilter/{id}/{filter}', 'CampaignTrackingController@campaignTracklistingFilter');
Route::resource('jobs', 'JobsController');

Route::resource('company/cache', 'CompanyCacheController');
Route::post('company/cache/store', 'CompanyCacheController@store');
Route::get('/duplicates/attribute', 'CompanyCacheController@duplicateAttributes')->name('duplicates.attribute');
Route::get('/fix/duplication', 'CompanyCacheController@duplicateFix')->name('duplicates.fix');

Route::resource('cache', 'CacheController@index');
Route::resource('cache/execute_command', 'CacheController@ExecuteCommand');

