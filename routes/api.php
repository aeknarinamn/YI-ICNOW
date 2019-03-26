<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
use Carbon\Carbon;
// Route::get('/aa', function () {
//  $now = Carbon::now();
//    // $dateNow1 = $now->toDateTimeString();
//    $dateNow1 = $now;
 
//  sleep(5);
//  $now = Carbon::now();
//    // $dateNow2 = $now->toDateTimeString();
//    $dateNow2 = $now;

//     dd($dateNow1->diffInSeconds($dateNow2)); 
// });
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

//Route::middleware('cors')->resource('/test', 'testController');
/*
Route::get('/plural/{plural}', function ($plural) {
	$plural = str_plural($plural);

	return $plural;
});
*/
/*
Route::get('/ss', function () {
	$channelSecret = '7c231619ab14f6698b7c91a515dff5d4'; // Channel secret string
	$httpRequestBody = '{
		  "replyToken": "nHuyWiB7yP5Zw52FIkcQobQuGDXCTA",
		  "type": "message",
		  "timestamp": 1462629479859,
		  "source": {
		    "type": "user",
		    "userId": "U206d25c2ea6bd87c17655609a1c37cb8"
		  },
		  "message": {
		    "id": "325708",
		    "type": "text",
		    "text": "Hello, world"
		  }
		}'; // Request body string
	$hash = hash_hmac('sha256', $httpRequestBody, $channelSecret, true);
	$signature = base64_encode($hash);
	dd($signature);
});
*/

Route::get('line/receives', 'API\v1\LineWebHoocksController@getReceive');
Route::post('line/receives', 'API\v1\LineWebHoocksController@postReceive');
Route::resource('auto-reply-keyword-folder', 'API\v1\AutoReplyKeywordFolderController');
Route::resource('setting-location', 'API\v1\LocationController');
Route::resource('setting-location-item', 'API\v1\LocationItemController');


Route::group(['middleware' => 'cors'], function() {
    Route::resource('auto-reply-keyword-folder', 'API\v1\AutoReplyKeywordFolderController');
    Route::resource('auto-reply-location', 'API\v1\AutoReplyLocationController');
	Route::resource('setting-location', 'API\v1\LocationController');
	Route::resource('setting-location-item', 'API\v1\LocationItemController');
	Route::resource('setting-user', 'API\v1\UserController');
	Route::post('setting-user-dt', 'API\v1\UserController@storeUserDt');
	Route::resource('setting-field', 'API\v1\FieldController');
	Route::resource('setting-master-field', 'API\v1\MasterFieldController');
	Route::resource('setting-field-folder', 'API\v1\FieldFolderController');
	Route::resource('setting-phpmailer', 'API\v1\PhpmailerController');
	Route::resource('setting-subscriber-folder', 'API\v1\SubscriberFolderController');

	//subscriber
	Route::resource('setting-subscriber', 'API\v1\SubscriberController');
	Route::get('setting-subscriber-download', 'API\v1\SubscriberController@downloadSubscriber');
	Route::get('setting-subscriber-download-single/{id}', 'API\v1\SubscriberController@downloadSubscriberSingle');
	Route::get('subscriber-get-field/{id}', 'API\v1\SubscriberController@getField');
	Route::get('subscriber-get-data/{id}', 'API\v1\SubscriberController@getData');
	Route::resource('subscriber-category', 'API\v1\Subscriber\SubscriberCategoryController');
	//

	Route::resource('setting-carousel-folder', 'API\v1\CarouselFolderController');

	Route::resource('setting-carousel', 'API\v1\CarouselController');
	Route::resource('setting-carousel-item', 'API\v1\CarouselItemController');
	Route::get('setting-carousel-download', 'API\v1\CarouselController@exportCarousel');

	Route::resource('line-profilling', 'API\v1\ProfillingController');
	Route::get('line-profilling-achieve/{id}', 'API\v1\ProfillingController@achieve');
	Route::get('line-profilling-un-achieve/{id}', 'API\v1\ProfillingController@unAchieve');
	Route::get('line-profilling-getdata-achieve', 'API\v1\ProfillingController@getDataAchieve');
	Route::resource('line-profilling-folder', 'API\v1\ProfillingFolderController');

	Route::resource('img-upload', 'API\v1\ImageFileController');
	Route::post('img-upload-multiple', 'API\v1\ImageFileController@uploadMultiple');

	Route::resource('tracking-bc', 'API\v1\TrackingBcController');

	Route::resource('list-menu', 'API\v1\ListMenuController');

	Route::post('setting-line-bussiness/{id}/issue', 'API\v1\LineSettingBusinessController@postIssueToken');
	Route::resource('setting-line-bussiness', 'API\v1\LineSettingBusinessController');


	Route::post('auto_reply_default/{id}/active', 'AutoReplyDefaultController@postActive');
	Route::resource('auto_reply_default', 'AutoReplyDefaultController');

	Route::post('auto_reply_keyword/{id}/active', 'AutoReplyKeywordController@postActive');
	Route::resource('auto_reply_keyword', 'AutoReplyKeywordController');
	Route::resource('auto-reply-keyword-sharelocation', 'API\v1\AutoReplyKeyword\AutoReplyKeywordShareLocationController');
	Route::resource('auto-reply-keyword-carousel', 'API\v1\AutoReplyKeyword\AutoReplyKeywordCarouselController');

	Route::post('campaign/{id}/active', 'API\v1\CampaignController@postActive');
	Route::post('campaign/{id}/schedule-active', 'API\v1\CampaignController@scheduleActive');
	Route::post('campaign/{id}/schedule-un-active', 'API\v1\CampaignController@scheduleUnActive');
	Route::resource('campaign', 'API\v1\CampaignController');
	Route::resource('campaign-folder', 'API\v1\CampaignFolderController');

	//RichMessage
	Route::resource('richmessage-folder', 'API\v1\RichMessageFolderController');
	Route::resource('richmessage', 'API\v1\RichmessageV2\RichmessageController');
	Route::post('richmessage-upload', 'API\v1\RichmessageV2\RichmessageController@uploadMultiple');
	//

	//Dashboard
	Route::get('dashboard-1', 'API\v1\Dashboard@report1');
	Route::post('dashboard-report-calendar-campaign', 'API\v1\Dashboard@reportCalendarCampaign');
	Route::post('dashboard-report-tracking-bc', 'API\v1\Dashboard@reportTrackingBC');
	Route::post('dashboard-report-tracking-bc-of-the-day', 'API\v1\Dashboard@reportTrackingBCofTheDay');
	Route::post('dashboard-report-campaign-statistic', 'API\v1\Dashboard@reportCampaignStatistic');
	Route::post('dashboard-report-up-comming-event', 'API\v1\Dashboard@reportUpCommingEvent');
	Route::post('dashboard-report-add-block', 'API\v1\Dashboard@reportFriendAddBlock');
	Route::post('dashboard-recieve-message-monitor', 'API\v1\Dashboard@reportRecieveMessageMonitor');
	Route::post('dashboard-keyword-stat', 'API\v1\Dashboard@reportKeywordStatistic');
	Route::post('dashboard-report-campaign-statistic-campaign', 'API\v1\Dashboard@reportCampaignStatisticCampaign');
	Route::post('dashboard-report-tracking-bc-by-tracking-bc', 'API\v1\Dashboard@reportTrackingBCByTrackingBC');
	//

	//Bot
	Route::resource('setting-bot-connect', 'API\v1\SettingBotConnectController');
	Route::resource('train-bot', 'API\v1\Bot\BottrainController');
	Route::resource('setting-bot', 'API\v1\Bot\SettingBotController');
	Route::get('restart-bot', 'API\v1\Bot\SettingBotController@restartBot');
	Route::resource('setting-bot-upload-csv', 'API\v1\Bot\SettingBotUploadCsvController');
	Route::post('train-bot-csv', 'API\v1\Bot\SettingBotUploadCsvController@uploadCsvTrainBot');
	Route::get('train-bot-result', 'API\v1\Bot\SettingBotUploadCsvController@uploadResult');
	Route::get('train-bot-csv-remove-single/{id}', 'API\v1\Bot\SettingBotUploadCsvController@removeSingle');
	Route::get('train-bot-csv-export/{id}', 'API\v1\Bot\SettingBotUploadCsvController@exportTrainbotCSV');
	Route::get('train-bot-retry/{id}', 'API\v1\Bot\SettingBotUploadCsvController@retryBot');
	//

	//test-upload
	Route::resource('test-upload', 'API\v1\TestUploadFileController');
	//

	//share location
	Route::resource('setting-share-location-folder', 'API\v1\ShareLocation\ShareLocationFolderController');

	Route::resource('setting-share-location', 'API\v1\ShareLocation\ShareLocationController');
	Route::resource('setting-share-location-item', 'API\v1\ShareLocation\ShareLocationItemController');
	Route::get('setting-share-location-download', 'API\v1\ShareLocation\ShareLocationController@exportShareLocation');
	//

	Route::resource('report-bot', 'API\v1\ReportBotController');
	Route::get('report-bot-export-csv', 'API\v1\ReportBotController@exportDataCsv');

	//setting confirmation
	Route::resource('setting-conf-location', 'API\v1\SettingConfirmation\SettingConfirmationShareLocationController');
	Route::resource('setting-conf-carousel', 'API\v1\SettingConfirmation\SettingConfirmationCarouselController');
	//

	//thailand Country
	Route::get('province', 'API\v1\Country\CountryController@getProvince');
	Route::get('all-district', 'API\v1\Country\CountryController@getAllDistrict');
	Route::get('all-sub-district', 'API\v1\Country\CountryController@getAllSubDistrict');
	Route::get('province-district', 'API\v1\Country\CountryController@getProvinceDistrict');
	Route::get('district-sub-district', 'API\v1\Country\CountryController@getDistrictSubDistrict');
	//

	//upload auto-reply
	Route::post('upload-auto-reply', 'API\v1\AutoReplyKeyword\AutoReplyKeywordController@uploadAutoReplyFile');
	//

	//master subscriber
	Route::get('get-field-master-subscriber', 'API\v1\Subscriber\MasterSubscriberController@getFieldMasterSubscriber');
	Route::resource('master-subscriber', 'API\v1\Subscriber\MasterSubscriberController');
	//

	//update data carousel
	Route::post('update-single-row-carousel', 'API\v1\CarouselController@updateSingleRow');
	//

	//update data sharelocation
	Route::post('update-single-row-sharelocation', 'API\v1\ShareLocation\ShareLocationController@updateSingleRow');
	//

	//segment
	Route::resource('setting-segment-folder', 'API\v1\Segment\SegmentFolderController');
	Route::resource('setting-segment', 'API\v1\Segment\SegmentController');
	Route::post('segment-get-subscriber-field', 'API\v1\Segment\SegmentController@getSubscriberListField');
	Route::get('segment-get-beacon', 'API\v1\Segment\SegmentController@getBeacon');
	Route::get('segment-get-campaign', 'API\v1\Segment\SegmentController@getCampaign');
	Route::post('segment-get-data', 'API\v1\Segment\SegmentController@getDataSegment');
	Route::post('segment-get-data-count', 'API\v1\Segment\SegmentController@countDataSegment');
	Route::get('segment-get-subscriber', 'API\v1\Segment\SegmentController@getSubscriber');
	Route::get('segment-get-tracking-source', 'API\v1\Segment\SegmentController@getTrackingSource');
	Route::get('segment-get-tracking-campaign', 'API\v1\Segment\SegmentController@getTrackingCampaign');
	Route::get('segment-get-tracking-ref', 'API\v1\Segment\SegmentController@getTrackingRef');
	Route::post('segment-export', 'API\v1\Segment\SegmentController@segmentExportdata');
	Route::get('get-subscriber-all', 'API\v1\Segment\SegmentController@getSusbcriberAll');
	Route::get('get-segment-name', 'API\v1\Segment\SegmentController@getSegmentName');
	Route::get('get-coupon-name', 'API\v1\Segment\SegmentController@getCouponName');

	Route::resource('setting-quick-segment', 'API\v1\Segment\QuickSegmentController');
	Route::post('setting-quick-segment-upload/{id}', 'API\v1\Segment\QuickSegmentController@uploadQuickSegment');
	Route::get('setting-quick-segment-import-result', 'API\v1\Segment\QuickSegmentController@importResult');
	Route::get('setting-quick-segment-export/{id}', 'API\v1\Segment\QuickSegmentController@exportQuickSegment');
	Route::get('get-quick-segment-name', 'API\v1\Segment\QuickSegmentController@getQuickSegmentName');
	//

	Route::get('/ecommerce-product', function()
	{
	    return response()->json([
            'datas' => [],
        ]);
	});

	Route::get('/ecommerce-category', function()
	{
	    return response()->json([
            'datas' => [],
        ]);
	});

	//report location sharing
	Route::get('report-location-sharing', 'API\v1\Report\LocationSharingController@getData');
	Route::get('report-location-sharing-export', 'API\v1\Report\LocationSharingController@exportData');
	//

	//Field
	Route::get('get-field-master-susbcriber', 'API\v1\FieldController@getMasterSubscriberField');
	Route::get('get-field-personalize', 'API\v1\GeneralController\GetDataAllController@getPersonalizeField');
	//

	Route::get('send-message-campaign/{id}', 'API\v1\CampaignController@sendCampaign');

	Route::get('env_angular', 'API\v1\EnvAngularController@getData');

	//Greeting
	Route::post('setting-greeting/{id}/active', 'API\v1\Greeting\GreetingController@postActive');
	Route::resource('setting-greeting', 'API\v1\Greeting\GreetingController');
	//

	//Role Permission
	Route::resource('role-permission', 'API\v1\RolePermission\RolePermissionController');
	//

	//Google Analytic
	Route::resource('google-analytic-setting', 'API\v1\GoogleAnalytic\GoogleAnalyticController');
	//

	//General Controller
	Route::get('get-all-user-profile-id', 'API\v1\GeneralController\GetDataAllController@getAllUserID');
	Route::get('get-all-province', 'API\v1\GeneralController\GetDataAllController@getProvince');
	//

	//Bot JoinGroup And JoinRoom
	Route::post('bot-join-auto-reply-keyword/{id}/active', 'API\v1\BotJoinGroupAndRoom\BotJoinGroupAndRoomAutoReplyKeyword@postActive');
	Route::resource('bot-join-auto-reply-keyword', 'API\v1\BotJoinGroupAndRoom\BotJoinGroupAndRoomAutoReplyKeyword');
	//

	//Template Message
	Route::resource('template-message-folder', 'API\v1\TemplateMessage\TemplateMessageFolderController');
	Route::resource('template-message', 'API\v1\TemplateMessage\TemplateMessageController');
	//

	//Campaign File
	Route::resource('campaign-photo', 'API\v1\Photo\CampaignPhotoController');
	Route::resource('campaign-video', 'API\v1\Video\CampaignVideoController');
	//

	//PageList Label
	Route::resource('page-list', 'API\v1\PageList\PageListController');
	Route::resource('page-list-label', 'API\v1\PageList\PageListLabelController');
	//

	//DownloadFile
	Route::get('get-file-subscriber', 'API\v1\DownloadFile\DownloadFileController@getFileSubscriber');
	//

	///////////////////////////////////////Message File/////////////////////////////////
	Route::post('message-file', 'API\v1\MessageFile\MessageFileController@uploadMultiple');
	/////////////////////////////////////////////////////////////////////////////////////

	///////////////////////////////////////Report///////////////////////////////////
	Route::post('report-keyword-inquiry', 'API\v1\Report\KeywordInquiryController@keywordInquiryReport');
	Route::post('report-keyword-inquiry-export', 'API\v1\Report\KeywordInquiryController@keywordInquiryReportExport');
	////////////////////////////////////////////////////////////////////////////////

	///////////////////////////////////////ICNOW///////////////////////////////////
	Route::resource('icnow-section', 'API\v1\ICNOW\Section\SectionController');
	Route::resource('icnow-banner-carousel', 'API\v1\ICNOW\BannerCarousel\BannerCarouselController');
	Route::post('icnow-banner-carousel-index', 'API\v1\ICNOW\BannerCarousel\BannerCarouselController@index');
	Route::resource('icnow-product', 'API\v1\ICNOW\Product\ProductController');
	Route::post('icnow-product-custom', 'API\v1\ICNOW\Product\ProductController@storeProductCustom');
	Route::put('icnow-product-custom/{id}', 'API\v1\ICNOW\Product\ProductController@updateProductCustom');
	Route::get('icnow-product-custom/{id}', 'API\v1\ICNOW\Product\ProductController@showProductCustom');
	Route::post('icnow-product-index', 'API\v1\ICNOW\Product\ProductController@index');
	Route::resource('icnow-setting-mini', 'API\v1\ICNOW\Mini\MiniController');
	Route::post('icnow-setting-mini-index', 'API\v1\ICNOW\Mini\MiniController@index');
	Route::post('icnow-setting-mini-import', 'API\v1\ICNOW\Mini\MiniController@importData');
	Route::post('icnow-upload-img', 'API\v1\ICNOW\Images\ImagesController@uploadMultiple');

	Route::post('icnow-order-status', 'API\v1\ICNOW\OrderCustomer\OrderCustomerController@orderStatusListing');
	Route::get('icnow-order-status/{id}', 'API\v1\ICNOW\OrderCustomer\OrderCustomerController@orderStatusDetail');
	Route::post('icnow-order-status-history', 'API\v1\ICNOW\OrderCustomer\OrderCustomerController@orderStatusHistory');
	Route::post('icnow-profile', 'API\v1\ICNOW\OrderCustomer\OrderCustomerController@customerProfileListing');
	Route::post('icnow-profile-detail/{id}', 'API\v1\ICNOW\OrderCustomer\OrderCustomerController@customerProfileDetail');
	Route::post('icnow-profile-update', 'API\v1\ICNOW\OrderCustomer\OrderCustomerController@updateDataProfile');
	Route::post('icnow-order-status-update', 'API\v1\ICNOW\OrderCustomer\OrderCustomerController@updateDataOrder');

	Route::post('icnow-report-end-of-day', 'API\v1\ICNOW\Report\ReportController@reportEndOfDay');
	Route::get('icnow-report-end-of-day-export', 'API\v1\ICNOW\Report\ReportController@reportEndOfDayExport');
	Route::post('icnow-report-shop-behav', 'API\v1\ICNOW\Report\ReportController@reportShoppingBehavior');
	Route::get('icnow-report-shop-behav-export', 'API\v1\ICNOW\Report\ReportController@reportShoppingBehaviorExport');
	Route::post('icnow-report-return-visit', 'API\v1\ICNOW\Report\ReportController@reportReturningVisitor');
	Route::get('icnow-report-return-visit-export', 'API\v1\ICNOW\Report\ReportController@reportReturningVisitorExport');
	Route::post('icnow-report-new-visit', 'API\v1\ICNOW\Report\ReportController@reportNewVisitor');
	Route::post('icnow-report-product-click', 'API\v1\ICNOW\Report\ReportController@reportProductClick');
	Route::get('icnow-report-product-click-export', 'API\v1\ICNOW\Report\ReportController@reportProductClickExport');
	Route::post('icnow-report-product-perfor', 'API\v1\ICNOW\Report\ReportController@reportProductPerformance');
	Route::get('icnow-report-product-perfor-export', 'API\v1\ICNOW\Report\ReportController@reportProductPerformanceExport');
	Route::get('icnow-report-export-summary-order', 'API\v1\ICNOW\Report\ReportController@reportSummaryOrderExport');

	Route::post('icnow-address-add-to-cookie', 'ICNOW\View\AddressController@storeAddressDataToCookie');
	Route::post('icnow-address-check-area-service', 'ICNOW\View\AddressController@checkAreaService');
	Route::post('icnow-save-data-cache', 'API\v1\ICNOW\CacheData\CacheDataController@saveDataCache');
	Route::post('icnow-submit-rating', 'ICNOW\View\CustomerOrderController@submitRating');
	////////////////////////////////////////////////////////////////////////////////
});
