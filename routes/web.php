<?php
use Illuminate\Http\Request;
use Carbon\Carbon;
use YellowProject\Ecommerce\Task\CoreFunction;
use YellowProject\Subscriber\MasterSubscriber;
use YellowProject\Ecommerce\CoreFunction as EcomCoreFunction;
use YellowProject\JobScheduleFunction;

Route::get('/rating-view', function()
{
    return view('icnow.rating.index');
});

Route::get('/check-mini-response', function()
{
    JobScheduleFunction::checkMiniNoResponse();
});

Route::get('/gen-pass', function()
{
    $pass = "Aa1234567890123456789";
    dd(bcrypt($pass));
});

Route::get('/test-check-mini', function()
{
  // $response = \YellowProject\ICNOW\APIConnection\MiniConnection::getUserMini();
  JobScheduleFunction::getMiniUser();
  // dd($response);

});

Route::get('/test-check-time', function()
{
  // $time = "00:00:00";
  // dd(($time >= "18:00:00" && $time <= "23:59:59") || ($time >= "00:00:00" && $time <= "08:00:00" ));
  $expDate = Carbon::now()->addHours(1)->format('Y-m-d H:i:s');
  dd($expDate);
});

Route::get('/test-push-message', function()
{
  $lineUserProfile = \YellowProject\LineUserProfile::find(2);
  $orderCustomer = \YellowProject\ICNOW\OrderCustomer\OrderCustomer::find(27);
  \YellowProject\ICNOW\CoreLineFunction\CoreLineFunction::pushMessageToCustomerOrder($lineUserProfile,$orderCustomer);
  // \YellowProject\ICNOW\CoreLineFunction\CoreLineFunction::pushMessageToCustomerCancleBySystem($orderCustomer);
});

Route::get('/test-get-mini-user', function()
{
  // \YellowProject\JobScheduleFunction::getMiniUser();
  $str = 'SWpFeU16UWk=';
  $str2 = base64_decode($str);
  $password = base64_decode($str2);
  echo $password;
});

Route::get('/test-page', function()
{
  // return view('icnow.home.index');
  // return view('icnow.product.diy');
  // return view('icnow.product.party-set');
  // return view('icnow.contact-us.index');
  // return view('icnow.cart.index');
  // return view('icnow.profile.index');
  // return view('icnow.address.address');
  // return view('icnow.address.add-address');
  // return view('icnow.address.empty-address');
  // return view('icnow.how-to-buy.index');
  // return view('icnow.address.confirm-data');
  return view('icnow.admin-register.index');
});

Route::get('/gen-data-banner', function()
{
  \YellowProject\ICNOW\BannerCarousel\BannerCarousel::genData();
});

Route::get('/gen-data-section', function()
{
  \YellowProject\ICNOW\Section\Section::genData();
});

Route::get('/logout', function()
{
    abort(404);
});

Route::get('gendata-master-susbcriber', function()
{
    MasterSubscriber::genMasterSubscriber();
});

Route::get('test-check-product', function()
{
    $dateNow = Carbon::now()->format('Y-m-d H:i:s');
    $datas = \DB::table('dim_ecommerce_order')
      ->where('order_status','Waiting for Payment')
      ->where('send_message_48_hours',0)
      ->where(\DB::raw('TIMESTAMPDIFF(HOUR, created_at, "'.$dateNow.'")'),'>=',48)
      ->get();
    dd($datas);
});

Route::get('500', function()
{
    abort(500);
});

//SAMMY
Route::get('/sam-test', function () {
    //return view('fwd.show.taxcer-sendmail');
});

//do not delete
Route::get('storage/line/image/{messageType}/{imageid}', function ($imageid)
{
    //DIRECTORY_SEPARATOR
    // dd(storage_path('public'.DIRECTORY_SEPARATOR .'line'.DIRECTORY_SEPARATOR.'image'.DIRECTORY_SEPARATOR.'message'.DIRECTORY_SEPARATOR. $filename));
    return Image::make(storage_path('public'.DIRECTORY_SEPARATOR .'line'.DIRECTORY_SEPARATOR.'image'.DIRECTORY_SEPARATOR.'message'.DIRECTORY_SEPARATOR. $imageid))->response();
});

//TODO: samtest
Route::get('/device-test', function () {
    return view('sam-test.index');
});

Route::get('/test-line-login', function () {
    return view('line-login');
});

// Route::get('/hellosoda', function () {
//     return view('hello_soda.index');
// });

Route::group(['middleware' => 'guest'], function() {
  Route::get('/cms-lineofficialadmin', function () {
      return view('auth.login');
  });
});

Route::group(['middleware' => 'auth'], function() {
  Route::get('/cms-lineofficialadmin', function () {
      // dd(\Session::get('FwdKey', ''));
      return view('web-view.index');
  });
  // Route::get('/test', function () {
  //     return view('web-view.index');
  // });
  Route::middleware('cors')->resource('fwd-encrypt', 'API\v1\FWD\FWDEncryptController');
});

Auth::routes();

Route::get('signin', 'HomeController@index');

Route::get('line-login', 'Auth\AuthController@redirectToProvider')->name('line-login');
Route::get('callback', 'Auth\AuthController@handleProviderCallback');
Route::post('line-logout', 'Auth\AuthController@logout')->name('line-logout');

Route::get('dashboard', 'DashboardController@index');

Route::get('bc/{code}', 'RecieveTrackingBCController@bcCenter');
Route::get('bc-recieve/{code}', 'RecieveTrackingBCController@recieveCode');
Route::get('bc-app', 'RecieveTrackingBCController@recieveLiff');
Route::get('dt-recieve/{code}', 'RecieveDTManagementController@recieveCode');

Route::resource('activity', 'ProfillingController');
// Route::resource('ecom', 'EcomController');
Route::get('shopping-line', function () {
  return redirect()->action('Auth\AuthController@redirectToProvider',['type' => 'ecom']);
});

// Route::get('ecommerce-admin-regis', function (Request $request) {
//   return redirect()->action('Auth\AuthController@redirectToProvider',['type' => 'ecommerce_admin']);
// });

// Route::get('dt/{code}', function (Request $request,$code) {
//   \Session::put('dt_code', $code);
//   return redirect()->action('Auth\AuthController@redirectToProvider',['type' => 'dt_code']);
// });

// Route::get('bc/{code}', function (Request $request,$code) {
//   \Session::put('tracking_bc_code', $code);
//   return redirect()->action('Auth\AuthController@redirectToProvider',['type' => 'bc_tracking']);
// });

Route::get('/page/errormsg', function () {
    return view('fwd.error-page.index');
});

Route::get('/register', function () {
    return view('errors.404');
});

Route::get('/password/email', function () {
    return view('errors.404');
});


Route::get('upload-auto-reply', function () {
  return view('upload-auto-reply.index');
});

// Auth::routes();

// Route::get('/home', 'HomeController@index');

Route::post('field','FeildController@index');

Route::get('/notfound-page', function () {
    return view('errors.404');
});

Route::get('/test-tableu', function () {
    return view('tableu.index');
});


Route::resource('api-folder-campagin', 'API\v1\CampaignFolderController');

//ICNOW
  Route::get('home-page', 'ICNOW\View\HomeController@homePage');
  Route::get('profile-page', 'ICNOW\View\ProfileController@profilePage');
  Route::get('profile-page-recent/{id}', 'ICNOW\View\ProfileController@recentOrder');
  Route::get('how-to-buy-page', 'ICNOW\View\HowToBuyController@howToBuyPage');
  Route::get('contact', 'ICNOW\View\ContactController@contactPage');
  Route::get('shopping-cart', 'ICNOW\View\ShoppingCartController@shoppingCartPage');
  Route::post('shopping-cart-add-diy', 'ICNOW\View\ShoppingCartController@saveShoppingCartDiy');
  Route::post('shopping-cart-add-party-set', 'ICNOW\View\ShoppingCartController@saveShoppingCartPartySet');
  Route::post('shopping-cart-add-custom', 'ICNOW\View\ShoppingCartController@saveShoppingCartCustom');
  Route::get('shopping-cart-remove/{id}', 'ICNOW\View\ShoppingCartController@shoppingCartRemove');
  Route::get('address', 'ICNOW\View\AddressController@addressPage');
  Route::get('address-empty', 'ICNOW\View\AddressController@addressEmptyPage');
  Route::get('address-add', 'ICNOW\View\AddressController@addressAddPage');
  Route::post('address-add-store', 'ICNOW\View\AddressController@addressAddStore');
  Route::get('address-edit/{id}', 'ICNOW\View\AddressController@addressEditPage');
  Route::post('address-add-update', 'ICNOW\View\AddressController@addressDataUpdate');
  Route::get('address-data', 'ICNOW\View\AddressController@addressData');
  Route::post('address-data-store', 'ICNOW\View\AddressController@addressDataStore');
  Route::get('address-remove/{id}', 'ICNOW\View\AddressController@addressRemove');
  Route::get('product-detail/{id}', 'ICNOW\View\ProductController@productDetail');
  Route::get('product-diy', 'ICNOW\View\ProductController@diyPage');
  Route::get('product-party-set', 'ICNOW\View\ProductController@partySetPage');
  Route::post('submit-order', 'ICNOW\View\CustomerOrderController@submitOrder');
  Route::get('/test-google-map', function () {
      return view('icnow.test-google-map.index');
  });
  Route::get('thank', 'ICNOW\View\ThankController@thankPage');
  Route::get('admin-register', 'ICNOW\View\AdminUserController@adminUserPage');
  Route::post('admin-register-store', 'ICNOW\View\AdminUserController@adminUserStore');
  Route::get('admin-register-thank', 'ICNOW\View\AdminUserController@thank');

  Route::get('rating-page', 'ICNOW\View\CustomerOrderController@ratingPage');
  Route::get('out-service-page', 'ICNOW\View\CustomerOrderController@outServicePage');

  Route::get('mini-page', 'ICNOW\Mini\MiniController@mainPage');
  Route::get('mini-page/bp/{id}', 'ICNOW\Mini\MiniController@mainPageByPass');
  Route::get('mini-order-detail/{id}', 'ICNOW\Mini\MiniController@orderDetail');
  Route::get('mini-order-detail-cf/{id}', 'ICNOW\Mini\MiniController@orderDetailSuccess');
  Route::get('mini-order-accept-order/{id}', 'ICNOW\Mini\MiniController@acceptOrder');
  Route::get('mini-order-cancle-order/{id}', 'ICNOW\Mini\MiniController@cancleOrder');
  Route::post('mini-order-cancle-order-store', 'ICNOW\Mini\MiniController@cancleOrderStore');
  Route::get('mini-login', 'ICNOW\Mini\MiniController@loginPage');
  Route::post('mini-check-login', 'ICNOW\Mini\MiniController@checkLogin');
  Route::get('mini-update-status-deliver/{id}', 'ICNOW\Mini\MiniController@updateStatusDelivery');
  Route::get('mini-cancle-order/{id}', 'ICNOW\Mini\MiniController@miniCancleOrder');
  Route::post('mini-cancle-order-store', 'ICNOW\Mini\MiniController@miniCancleOrderStore');
  Route::get('mini-logout', 'ICNOW\Mini\MiniController@logOutPage');
//

  Route::get('/test-send-message-nn', function () {
      $lineUserProfile = \YellowProject\LineUserProfile::find(2);
      $orderCustomer = \YellowProject\ICNOW\OrderCustomer\OrderCustomer::find(6);
      \YellowProject\ICNOW\CoreLineFunction\CoreLineFunction::pushMessageToCustomerOrder($lineUserProfile,$orderCustomer);
  });

// Route::get('/clear-data', function () {
//   \YellowProject\ICNOW\OrderCustomer\CustomerShippingAddress::truncate();
//   \YellowProject\ICNOW\OrderCustomer\OrderCustomer::truncate();
//   \YellowProject\ICNOW\OrderCustomer\OrderCustomerHistory::truncate();
//   \YellowProject\ICNOW\ShoppingCart\ShoppingCart::truncate();
//   \YellowProject\ICNOW\ShoppingCart\ShoppingCartItem::truncate();
//   \YellowProject\ICNOW\ShoppingCart\ShoppingCartItemDetailDiy::truncate();
//   \YellowProject\ICNOW\ShoppingCart\ShoppingCartItemDetailDiyItem::truncate();
//   \YellowProject\ICNOW\ShoppingCart\ShoppingCartItemDetailPartySet::truncate();
//   \YellowProject\ICNOW\ShoppingCart\ShoppingCartItemDetailPartySetItem::truncate();
// });

// Route::get('/', function () {
//     return redirect('/bc/icnow');
// });

