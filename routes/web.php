<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorsController;
use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\AffiliateController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['register'=>false]);

Route::get('user/login','FrontendController@login')->name('login.form');
Route::post('user/login','FrontendController@loginSubmit')->name('login.submit');
Route::get('user/logout','FrontendController@logout')->name('user.logout');

Route::get('user/register','FrontendController@register')->name('register.form');
Route::post('user/register','FrontendController@registerSubmit')->name('register.submit');
// Reset password
Route::post('password-reset', 'FrontendController@showResetForm')->name('password.reset');
// Socialite
Route::get('login/{provider}/', 'Auth\LoginController@redirect')->name('login.redirect');
Route::get('login/{provider}/callback/', 'Auth\LoginController@Callback')->name('login.callback');

Route::get('/','FrontendController@home')->name('home');

//bookdoctor
Route::get('/bookdoctor', 'FrontendController@bookingdoctor')->name('bookdoctor');
Route::post('/submitbookdoctor', 'FrontendController@submitbookdoctor')->name('bookdoctor.submit');

// Frontend Routes
Route::get('/home', 'FrontendController@index');
Route::get('/about-us','FrontendController@aboutUs')->name('about-us');
Route::get('/contact','FrontendController@contact')->name('contact');
Route::post('/contact/message','MessageController@store')->name('contact.store');
Route::get('/product-detail/{slug}','FrontendController@productDetail')->name('product-detail');
Route::post('/product/search','FrontendController@productSearch')->name('product.search');
Route::get('/product-cat/{slug}','FrontendController@productCat')->name('product-cat');
Route::get('/product-sub-cat/{slug}/{sub_slug}','FrontendController@productSubCat')->name('product-sub-cat');
Route::get('/product-brand/{slug}','FrontendController@productBrand')->name('product-brand');

// Cart section
Route::get('/add-to-cart/{slug}','CartController@addToCart')->name('add-to-cart')->middleware('user');
Route::post('/add-to-cart','CartController@singleAddToCart')->name('single-add-to-cart')->middleware('user');
Route::get('cart-delete/{id}','CartController@cartDelete')->name('cart-delete');
Route::post('cart-update','CartController@cartUpdate')->name('cart.update');

Route::get('/cart',function(){
    return view('frontend.pages.cart');
})->name('cart');
Route::get('/checkout','CartController@checkout')->name('checkout')->middleware('user');
// Wishlist
Route::get('/wishlist',function(){
    return view('frontend.pages.wishlist');
})->name('wishlist');
Route::get('/wishlist/{slug}','WishlistController@wishlist')->name('add-to-wishlist')->middleware('user');
Route::get('wishlist-delete/{id}','WishlistController@wishlistDelete')->name('wishlist-delete');
Route::post('cart/order','OrderController@store')->name('cart.order');
Route::get('order/pdf/{id}','OrderController@pdf')->name('order.pdf');
Route::get('/income','OrderController@incomeChart')->name('product.order.income');
// Route::get('/user/chart','AdminController@userPieChart')->name('user.piechart');
Route::get('/product-grids','FrontendController@productGrids')->name('product-grids');
Route::get('/product-lists','FrontendController@productLists')->name('product-lists');
Route::match(['get','post'],'/filter','FrontendController@productFilter')->name('shop.filter');
// Order Track
Route::get('/product/track','OrderController@orderTrack')->name('order.track');
Route::post('product/track/order','OrderController@productTrackOrder')->name('product.track.order');
// Blog
Route::get('/blog','FrontendController@blog')->name('blog');
Route::get('/blog-detail/{slug}','FrontendController@blogDetail')->name('blog.detail');
Route::get('/blog/search','FrontendController@blogSearch')->name('blog.search');
Route::post('/blog/filter','FrontendController@blogFilter')->name('blog.filter');
Route::get('blog-cat/{slug}','FrontendController@blogByCategory')->name('blog.category');
Route::get('blog-tag/{slug}','FrontendController@blogByTag')->name('blog.tag');

//Checkoutnow
Route::get('/checkout-now/{product_id}', 'CartController@checkoutNow')->name('checkout-now')->middleware('user');

// ReviewDoctor
 Route::resource('doctor-review', 'DoctorReviewController');
 Route::post('/doctor/{doctorID}/review', 'DoctorReviewController@store')->name('doctor.review.store');


// NewsLetter
Route::post('/subscribe','FrontendController@subscribe')->name('subscribe');

// Product Review
Route::resource('/review','ProductReviewController');
Route::post('product/{slug}/review','ProductReviewController@store')->name('review.store');

// Post Comment
Route::post('post/{slug}/comment','PostCommentController@store')->name('post-comment.store');
Route::resource('/comment','PostCommentController');
// Coupon
Route::post('/coupon-store','CouponController@couponStore')->name('coupon-store');
// Payment
Route::get('payment', 'PayPalController@payment')->name('payment');
Route::get('cancel', 'PayPalController@cancel')->name('payment.cancel');
Route::get('payment/success', 'PayPalController@success')->name('payment.success');



// Backend section start

Route::group(['prefix'=>'/admin','middleware'=>['auth','admin']],function(){
    Route::get('/','AdminController@index')->name('admin');
    Route::get('/file-manager',function(){
        return view('backend.layouts.file-manager');
    })->name('file-manager');
    // user route
    Route::resource('users','UsersController');
    // Banner
    Route::resource('banner','BannerController');
    // Brand
    Route::resource('brand','BrandController');
    // Profile
    Route::get('/profile','AdminController@profile')->name('admin-profile');
    Route::post('/profile/{id}','AdminController@profileUpdate')->name('profile-update');
    // Category
    Route::resource('/category','CategoryController');
    // Product
    Route::resource('/product','ProductController');
    // Bác Sĩ
    Route::resource('doctor','AdDocController');
    // Ajax for sub category
    Route::post('/category/{id}/child','CategoryController@getChildByParent');
    // POST category
    Route::resource('/post-category','PostCategoryController');
    // Post tag
    Route::resource('/post-tag','PostTagController');
    // Post
    Route::resource('/post','PostController');
    // Message
    Route::resource('/message','MessageController');
    Route::get('/message/five','MessageController@messageFive')->name('messages.five');

    // ListDoctor

    // Order
    Route::resource('/order','OrderController');
    // Shipping
    Route::resource('/shipping','ShippingController');
    // Coupon
    Route::resource('/coupon','CouponController');
    // Settings
    Route::get('settings','AdminController@settings')->name('settings');
    Route::post('setting/update','AdminController@settingsUpdate')->name('settings.update');

    // Notification
    Route::get('/notification/{id}','NotificationController@show')->name('admin.notification');
    Route::get('/notifications','NotificationController@index')->name('all.notification');
    Route::delete('/notification/{id}','NotificationController@delete')->name('notification.delete');
    // Password Change
    Route::get('change-password', 'AdminController@changePassword')->name('change.password.form');
    Route::post('change-password', 'AdminController@changPasswordStore')->name('change.password');

    //Ajax
    Route::post('ajax/route', 'AjaxController@method')->middleware('verify.csrf.ajax');


});


// User section start
Route::group(['prefix'=>'/user','middleware'=>['user']],function(){
    Route::get('/','HomeController@index')->name('user');
     // Profile
     Route::get('/profile','HomeController@profile')->name('user-profile');
     Route::post('/profile/{id}','HomeController@profileUpdate')->name('user-profile-update');
    //  Order
    Route::get('/order',"HomeController@orderIndex")->name('user.order.index');
    Route::get('/order/show/{id}',"HomeController@orderShow")->name('user.order.show');
    Route::delete('/order/delete/{id}','HomeController@userOrderDelete')->name('user.order.delete');
    // Product Review
    Route::get('/user-review','HomeController@productReviewIndex')->name('user.productreview.index');
    Route::delete('/user-review/delete/{id}','HomeController@productReviewDelete')->name('user.productreview.delete');
    Route::get('/user-review/edit/{id}','HomeController@productReviewEdit')->name('user.productreview.edit');
    Route::patch('/user-review/update/{id}','HomeController@productReviewUpdate')->name('user.productreview.update');

    // Post comment
    Route::get('user-post/comment','HomeController@userComment')->name('user.post-comment.index');
    Route::delete('user-post/comment/delete/{id}','HomeController@userCommentDelete')->name('user.post-comment.delete');
    Route::get('user-post/comment/edit/{id}','HomeController@userCommentEdit')->name('user.post-comment.edit');
    Route::patch('user-post/comment/udpate/{id}','HomeController@userCommentUpdate')->name('user.post-comment.update');

    // Password Change
    Route::get('change-password', 'HomeController@changePassword')->name('user.change.password.form');
    Route::post('change-password', 'HomeController@changPasswordStore')->name('change.password');

});


// Doctor section start
Route::group(['prefix'=>'/doctor','middleware'=>['doctor']],function(){
    Route::get('/','DoctorsController@index')->name('doctor');
    // Appointment
    Route::get('/appointment', 'AppointmentController@index')->name('doctor.appointment.index');

    // Listdoctor

    // Affiliate
    Route::get('/affiliate', [AffiliateController::class, 'index'])->name('doctor.affiliate.index');
    Route::get('/affiliate/link/{id}', [AffiliateController::class, 'createLink'])->name('doctor.products.link');

    // Danh sách bệnh nhân
    Route::get('/patients', [AppointmentsController::class, 'getAllAppointmentsForDoctors'])->name('doctor.patients.index');
    Route::patch('/appointments/{id}/updateStatus', [AppointmentsController::class, 'updateStatus'])->name('appointments.updateStatus');

    // Danh sách đặt khám bệnh nhân
    Route::get('/patients/appointments', [AppointmentsController::class, 'getAllAppointmentsForDoctors'])->name('doctor.patients.appointments');
    // Profile
     Route::get('/profile','DoctorsController@profile')->name('doctor-profile');
     Route::post('/profile/{id}','DoctorsController@profileUpdate')->name('doctor-profile-update');
    //  Order
    Route::get('/order',"DoctorsController@orderIndex")->name('doctor.order.index');
    Route::get('/order/show/{id}',"DoctorsController@orderShow")->name('doctor.order.show');
    Route::delete('/order/delete/{id}','DoctorsController@doctorOrderDelete')->name('doctor.order.delete');
    // Product Review
    Route::get('/doctor-review','DoctorsController@productReviewIndex')->name('doctor.productreview.index');
    Route::delete('/doctor-review/delete/{id}','DoctorsController@productReviewDelete')->name('doctor.productreview.delete');
    Route::get('/doctor-review/edit/{id}','DoctorsController@productReviewEdit')->name('doctor.productreview.edit');
    Route::patch('/doctor-review/update/{id}','DoctorsController@productReviewUpdate')->name('doctor.productreview.update');

    // Post comment
    Route::get('doctor-post/comment','DoctorsController@doctorComment')->name('doctor.post-comment.index');
    Route::delete('doctor-post/comment/delete/{id}','DoctorsController@doctorCommentDelete')->name('doctor.post-comment.delete');
    Route::get('doctor-post/comment/edit/{id}','DoctorsController@doctorCommentEdit')->name('doctor.post-comment.edit');
    Route::patch('doctor-post/comment/udpate/{id}','DoctorsController@doctorCommentUpdate')->name('doctor.post-comment.update');

    // Password Change
    Route::get('change-password', 'DoctorsController@changePassword')->name('doctor.change.password.form');
    Route::post('change-password', 'DoctorsController@changPasswordStore')->name('change.password');

});

    // Route hiển thị chi tiết bác sĩ
    Route::get('/doctor/{id}', [DoctorsController::class, 'showDoctorDetail'])->name('doctor.detail');

    // login riêng bác sĩ
    Route::get('/doctor/login', 'Auth\DoctorLoginController@showLoginForm')->name('doctor.login');
    Route::post('/doctor/login', 'Auth\DoctorLoginController@login')->name('doctor.login.submit');
    Route::post('/doctor/logout', 'Auth\DoctorLoginController@logout')->name('doctor.logout');





Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

Route::get('/deep-link/product-detail/{slug}', function ($slug) {
    $refCode = request()->has('ref') ? request('ref') : null;
    return view('deeplink_redirect', compact('slug', 'refCode'));
});
