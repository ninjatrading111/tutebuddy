<?php

use App\Http\Controllers\LanguageController;

// Switch between the included languages
Route::get('lang/{lang}', [LanguageController::class, 'swap']);

/*
 * Frontend Routes
 */
// Route::get('/modal',function(){
//     return view('home');
// });
Route::get('/', 'Frontend\HomeController@index')->name('homepage');
// Route::get('ajax/carousel/featured/{to}', 'Frontend\HomeController@getFeaturedCarouselData')->name('carousel.featured');
// Route::get('/user/verify/{remember_token}', 'Auth\RegisterController@verifyUser')->name('user.verify');
// Route::post('verfication/resend', 'Auth\RegisterController@resend')->name('verification.resend');

// // Super admin login
// Route::get('admin', 'Auth\LoginController@showLoginForm');

// Contact Email
// Route::get('ajax/email/contact', 'Frontend\PageController@sendContactEmail')->name('ajax.email.contact');

Auth::routes();



// Route::post('account/{id}/update', 'Backend\UserController@updateAccount')->name('admin.myaccount.update');
// Route::get('account/{id}/approve', 'Backend\UserController@approveAccount')->name('admin.account.approve');
// Route::get('account/{id}/decline', 'Backend\UserController@declineAccount')->name('admin.account.decline');

// Route::get('ajax/categories/select', 'Backend\CategoryController@getSelet2Data')->name('admin.select.getCategoriesByAjax');


Route::group(['middleware' => 'auth'], function () {
    Route::get('/layouts/light','LayoutController@light');
    Route::get('/layouts/dark','LayoutController@dark');
    Route::group(['namespace' => 'Frontend'], function () {
        include_route_files(__DIR__ . '/frontend/');
       

      
        Route::group(['middleware'=>'checkUserRole'],function(){
            Route::get('profile', 'ProfileController@index')->name('profile.stress');
            Route::post('profile/fetchdata', 'ProfileController@fetchdata')->name('profile.fetchdata');
            Route::get('ordering/writing', 'WritingController@index')->name('writing');
            Route::get('editing', 'EditingController@index')->name('editing');
            Route::get('other', 'OtherController@index')->name('other');
            Route::get('/dashboard/active','DashboardController@index')->name('dashboard');
            Route::get('/dashboard/draft','DraftController@index')->name('dashboard');
        });

    });
    // Admin
    // Route::get('users','Backend\UsersController@index');

Route::group(['prefix' => 'dashboard', 'as' => 'admin.','namespace' => 'Backend'], function () {

    include_route_files(__DIR__ . '/backend/');
    Route::group(['middleware'=>'AdminCheck'],function(){
        Route::get('switchMode', 'DashboardController@switchMode')->name('switchMode');
    Route::get('client','ClientController@index')->name('client');
    Route::get('client/getdata','ClientController@getdata')->name('client.getdata');
    // Route::get('writer/getdata','WriterController@getdata')->name('writer.getdata');
    Route::post('client/postdata','ClientController@postdata')->name('client.postdata');
    Route::post('client/removedata','ClientController@removedata')->name('client.removedata');
    Route::post('client/fetchdata','ClientController@fetchdata')->name('client.fetchdata');

    Route::get('writer','WriterController@index')->name('writer');
    Route::get('writer/getdata','WriterController@getdata')->name('writer.getdata');
    Route::post('writer/postdata','WriterController@postdata')->name('writer.postdata');
    Route::post('writer/removedata','WriterController@removedata')->name('writer.removedata');
    Route::post('writer/fetchdata','WriterController@fetchdata')->name('writer.fetchdata');
    //===== Courses Routes =====//
Route::get('courses', 'OrdersController@index')->name('courses.index');
// Route::get('orders', 'PaymentController@getOrders')->name('orders');

// Route::get('courses/restore/{id}', 'CourseController@restore')->name('courses.restore');
// Route::get('courses/get/favorites', 'CourseController@favorites')->name('courses.favorites');
// Route::get('ajax/courses/list1/{type}', 'CourseController@getList')->name('getCoursesByAjax');
// Route::get('ajax/courses/publish/{id}', 'CourseController@publish')->name('courses.publish');
// Route::get('ajax/courses/delete/forever/{id}', 'CourseController@foreverDelete')->name('courses.foreverDelete');
// Route::get('ajax/course/add-favorite/{course_id}', 'CourseController@addFavorite')->name('course.addFavorite');
// Route::get('ajax/course/remove-favorite/{course_id}', 'CourseController@removeFavorite')->name('course.removeFavorite');
// Route::get('my/courses', 'CourseController@studentCourses')->name('student.courses');
// Route::get('ajax/my-courses/{type}', 'CourseController@getStudentCoursesByAjax')->name('student.getMyCoursesByAjax');

// Route::get('ajax/slug', 'CourseController@getSlugByTitle')->name('slug');
Route::get('orders', 'PaymentController@getOrders')->name('orders');
Route::get('transactions', 'PaymentController@getTransactions')->name('transactions');
Route::get('affiliate', 'PaymentController@getAffiliate')->name('affiliate');
Route::get('settings/term', 'ConfigController@getTerms')->name('settings.term');
Route::get('settings/commission', 'ConfigController@getCommissions')->name('settings.commission');
Route::get('settings/paygateway', 'ConfigController@getPaygateways')->name('settings.paygateway');
Route::get('settings/feature', 'ConfigController@getFeatures')->name('settings.feature');
Route::get('settings/gateDetail', 'ConfigController@gateDetail')->name('settings.gateDetail');

// Route::get('ajax/users/list/{type}', 'UserController@getList')->name('getUsersByAjax');

// Route::get('orders/detail/{id}', 'PaymentController@orderDetail')->name('orders.detail');
// Route::get('orders/invoice/{id}', 'PaymentController@downloadInvoice')->name('orders.invoice');
// Route::get('orders/refund/{id}', 'PaymentController@refundRequest')->name('orders.refundrequest');

});


});
});
// Route::get("sitemap.xml" , function () {
//     return \Illuminate\Support\Facades\Redirect::to('sitemap.xml');
// });