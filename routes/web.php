<?php

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

Route::get('/', function () {
    return redirect('/prod/login');
});


////////// Admin Routes /////////////
//Route::get('/', function() {
//    App\User::create([
//        'name' => "Admin",
//        'email' => "admin@wellhello.com",
//        'password' => Hash::make('admin@123'),
//        'user_token' => sha1(mt_rand(10000, 99999) . time()),
//        'role' => 'super_admin'
//    ]);
//});

$environment = config("app.env") === 'production' ? 'prod' : 'dev';

Route::group(['prefix' => $environment], function () {
    Route::get('login', ['as' => 'adminLogin', 'uses' => 'LoginController@getLoginView']);
    Route::post('postAdminLogin', ['as' => 'postAdminLogin', 'uses' => 'LoginController@adminLoginProcess']);
    Route::get('logout', ['as' => 'adminLogout', 'uses' => 'LoginController@adminLogout']);

    Route::group(['middleware' => ['authAdmin']], function () {
        Route::get('/', ['as' => 'dashboard', 'uses' => 'MainController@adminDashboard']);

        /*     * ******* Complete Category CRUD URLs ************ */
        Route::get('categories', ['as' => 'getAllCategories', 'uses' => 'CategoryController@getAllCategories']);
        Route::get('add-category-form', ['as' => 'showCategoryForm', 'uses' => 'CategoryController@showAddCategoryForm']);
        Route::post('addCategory', ['as' => 'addCategory', 'uses' => 'CategoryController@addCategory']);
        Route::get('edit-category/{id}', ['as' => 'editCategory', 'uses' => 'CategoryController@editCategory']);
        Route::post('updateCategory', ['as' => 'updateCategory', 'uses' => 'CategoryController@updateCategory']);
        Route::post('deleteCategory', ['as' => 'deleteCategory', 'uses' => 'CategoryController@deleteCategory']);

        /*     * ******* Complete Sub Category CRUD URLs *********** */
        Route::get('sub-categories', ['as' => 'getAllSubCategories', 'uses' => 'SubCategoryController@getAllSubCategories']);
        Route::get('add-sub-category-form', ['as' => 'showSubCategoryForm', 'uses' => 'SubCategoryController@showSubCategoryForm']);
        Route::post('addSubCategory', ['as' => 'addSubCategory', 'uses' => 'SubCategoryController@addSubCategory']);
        Route::get('edit-sub-category/{id}', ['as' => 'editSubCategory', 'uses' => 'SubCategoryController@editSubCategory']);
        Route::post('updateSubCategory', ['as' => 'updateSubCategory', 'uses' => 'SubCategoryController@updateSubCategory']);
        Route::post('deleteSubCategory', ['as' => 'deleteSubCategory', 'uses' => 'SubCategoryController@deleteSubCategory']);

        /*     * ******* Complete Appointments URLs *********** */
        Route::get('pending-appointments', ['as' => 'getPendingAppointments', 'uses' => 'AppointmentController@getPendingAppointments']);
        Route::get('confirmed-appointments', ['as' => 'getConfirmedAppointments', 'uses' => 'AppointmentController@getPendingAppointments']);
        Route::get('completed-appointments', ['as' => 'getCompletedAppointments', 'uses' => 'AppointmentController@getPendingAppointments']);
        Route::get('cancelled-appointments', ['as' => 'getCancelledAppointments', 'uses' => 'AppointmentController@getPendingAppointments']);
    //    Route::get('add-new-appointment/{id}', ['as' => 'addNewappointment', 'uses' => 'AppointmentController@addNewappointmentForm']);
        Route::get('newAppointment/{id?}', ['as' => 'newAppointment', 'uses' => 'AppointmentController@newAppointment']);
        Route::post('create-new-appointment', ['as' => 'createNewAppointment', 'uses' => 'AppointmentController@createNewAppointment']);
        Route::get('edit-appointment/{uuid}', ['as' => 'editAppointmentFrom', 'uses' => 'AppointmentController@editAppointmentFrom']);
        Route::post('getAppointmentDetail', ['as' => 'getAppointmentDetail', 'uses' => 'AppointmentController@getAppointmentDetail']);
        Route::get('getAppointment/{uuid}', ['as' => 'getAppointment', 'uses' => 'AppointmentController@getAppointment']);
        Route::post('updateAppointmentDetail', ['as' => 'updateAppointmentDetail', 'uses' => 'AppointmentController@updateAppointmentDetail']);
        Route::post('changeAppointmentStatus', ['as' => 'changeAppointmentStatus', 'uses' => 'AppointmentController@changeAppointmentStatus']);
        Route::get('updateAppointmentStatus/{id}/{status}', ['as' => 'updateAppointmentStatus', 'uses' => 'AppointmentController@updateAppointmentStatus']);

        /*     * ******* Freelancers Listing URLs *********** */
        Route::get('getAllFreelancers', ['as' => 'getAllFreelancers', 'uses' => 'FreelancerController@getAllFreelancers']);
        Route::get('freelancers-iban', ['as' => 'getAllFreelancersWithIban', 'uses' => 'FreelancerController@getAllFreelancersWithIban']);
        Route::get('edit-freelancers-iban/{uuid}', ['as' => 'editFreelancerIbanInfo', 'uses' => 'FreelancerController@editFreelancerIbanInfo']);
        Route::post('updateFreelancerBankInfo', ['as' => 'updateFreelancerBankInfo', 'uses' => 'FreelancerController@updateFreelancerBankInfo']);
        Route::get('getNotActiveFreelancers', ['as' => 'getNotActiveFreelancers', 'uses' => 'FreelancerController@getNotActiveFreelancers']);
        Route::get('notverified-freelancers', ['as' => 'getNotVerfiedFreelancers', 'uses' => 'FreelancerController@getNotVerfiedFreelancers']);
        Route::get('active-freelancers', ['as' => 'getActiveFreelancers', 'uses' => 'FreelancerController@getActiveFreelancers']);
        Route::get('deleted-freelancers', ['as' => 'getDeletedFreelancers', 'uses' => 'FreelancerController@getDeletedFreelancers']);
        Route::post('updateFreelancerStatus', ['as' => 'updateFreelancerStatus', 'uses' => 'FreelancerController@updateFreelancerStatus']);
        Route::post('deleteFreelancer', ['as' => 'deleteFreelancer', 'uses' => 'FreelancerController@deleteFreelancer']);
        Route::get('freelancer-detail-page/{uuid}', ['as' => 'freelancerDetailPage', 'uses' => 'FreelancerController@freelancerDetailPage']);
        Route::post('update-freelancer-profile', ['as' => 'updateFreelancerProfileByAdmin', 'uses' => 'FreelancerController@updateFreelancerProfileByAdmin']);
        Route::get('appointment-form/{id}', ['as' => 'appointmentEditForm', 'uses' => 'FreelancerController@appointmentEditForm']);
        Route::get('getFreelancerAppointments', ['as' => 'getFreelancerAppointments', 'uses' => 'AppointmentController@getFreelancerAppointments']);
        Route::get('getFreelancerBlocktimes', ['as' => 'getFreelancerBlocktimes', 'uses' => 'BlocktimeController@getFreelancerBlocktimes']);
        Route::get('getFreelancerSchedules', ['as' => 'getFreelancerSchedule', 'uses' => 'ScheduleController@getFreelancerSchedules']);
        Route::get('addNewFreelancerForm', ['as' => 'addNewFreelancerForm', 'uses' => 'FreelancerController@addNewFreelancerForm']);
        Route::get('getFreelancerCalendarData', ['as' => 'getFreelancerCalendarData', 'uses' => 'FreelancerController@getFreelancerCalendarData']);
        Route::post('updateFreelancerPicture', ['as' => 'updateFreelancerPicture', 'uses' => 'FreelancerController@updateFreelancerPicture']);
        Route::post('saveSubscription', ['as' => 'saveSubscription', 'uses' => 'FreelancerController@saveFreelancerSubscription']);
        Route::post('getSubscriptionDetail', ['as' => 'getSubscriptionDetail', 'uses' => 'FreelancerController@getSubscriptionDetail']);

        /*     * ******* Customers Listing URLs *********** */
        Route::get('all-customers', ['as' => 'getAllCustomers', 'uses' => 'CustomerController@getAllCustomers']);
        Route::get('blocked-customers', ['as' => 'getBlockedCustomers', 'uses' => 'CustomerController@getBlockedCustomers']);
        Route::get('pending-customers', ['as' => 'getPendingCustomers', 'uses' => 'CustomerController@getPendingCustomers']);
        Route::get('active-customers', ['as' => 'getActiveCustomers', 'uses' => 'CustomerController@getActiveCustomers']);
        Route::get('deleted-customers', ['as' => 'getDeletedCustomers', 'uses' => 'CustomerController@getDeletedCustomers']);
        Route::post('updateCustomerStatus', ['as' => 'updateCustomerStatus', 'uses' => 'CustomerController@updateCustomerStatus']);
        Route::post('deleteCustomer', ['as' => 'deleteCustomer', 'uses' => 'CustomerController@deleteCustomer']);
        Route::get('customer-detail-page/{uuid}', ['as' => 'customerDetailPage', 'uses' => 'CustomerController@customerDetailPage']);
        Route::post('update-customer-profile', ['as' => 'updateCustomerProfileByAdmin', 'uses' => 'CustomerController@updateCustomerProfileByAdmin']);
        Route::get('customer-schedule', ['as' => 'customerSchedules', 'uses' => 'CustomerController@customerSchedules']);
        Route::get('getCustomerAppointments', ['as' => 'getCustomerAppointments', 'uses' => 'AppointmentController@getCustomerAppointments']);
        Route::get('addNewCustomerForm', ['as' => 'addNewCustomerForm', 'uses' => 'CustomerController@addNewCustomerForm']);
        Route::post('updateCustomerPicture', ['as' => 'updateCustomerPicture', 'uses' => 'CustomerController@updateCustomerPicture']);
        Route::post('saveCustomerSubscription', ['as' => 'saveCustomerSubscription', 'uses' => 'CustomerController@saveCustomerSubscription']);
        Route::post('getCustomerSubscriptionDetail', ['as' => 'getCustomerSubscriptionDetail', 'uses' => 'CustomerController@getCustomerSubscriptionDetail']);

        Route::get('addPromoCode/{id?}', ['as' => 'addPromoCode', 'uses' => 'PromoCodeController@addPromoCode']);
        Route::post('create-new-promocode', ['as' => 'createNewPromoCode', 'uses' => 'PromoCodeController@createNewPromoCode']);
        Route::get('active-promocode', ['as' => 'getActivePromoCodes', 'uses' => 'PromoCodeController@getActivePromoCodes']);
        Route::get('expired-promocode', ['as' => 'getExpiredPromoCodes', 'uses' => 'PromoCodeController@getExpiredPromoCodes']);
        Route::get('send-promocode-form/{id?}', ['as' => 'sendPromoCodeForm', 'uses' => 'PromoCodeController@sendPromoCodeForm']);
        Route::post('send-promocode', ['as' => 'sendPromoCodes', 'uses' => 'PromoCodeController@sendPromoCodes']);
        Route::post('deletePromoCode', ['as' => 'deletePromoCode', 'uses' => 'PromoCodeController@deletePromoCode']);
        Route::get('edit-promo-code/{id}', ['as' => 'editPromoCode', 'uses' => 'PromoCodeController@editPromoCode']);
        Route::post('updatePromoCode', ['as' => 'updatePromoCode', 'uses' => 'PromoCodeController@updatePromoCode']);
        // Route::get('all-promoCodes', ['as' => 'getAllPromoCodes', 'uses' => 'PromoCodeController@getActivePromoCodes']);


        Route::get('reported_post', ['as' => 'getReportedPost', 'uses' => 'PostController@getReportedPost']);
        Route::post('updatePostStatus', ['as' => 'updatePostStatus', 'uses' => 'PostController@updatePostStatus']);
        Route::get('reported_post/{id}', ['as' => 'getPostDetail', 'uses' => 'PostController@getPostDetail']);
        Route::post('updateReportedPost', ['as' => 'updateReportedPost', 'uses' => 'PostController@updateReportedPost']);
        Route::get('blocked-posts', ['as' => 'getBlockedPosts', 'uses' => 'PostController@getBlockedPosts']);
        Route::get('blocked_post/{id}', ['as' => 'getBlockedPostDetail', 'uses' => 'PostController@getBlockedPostDetail']);
        //Route::post('updatePostPicture', ['as' => 'updatePostPicture', 'uses' => 'PostController@updatePostPicture']);

        Route::get('getAppEarning', ['as' => 'getAppEarning', 'uses' => 'MainController@getAppEarning']);

        Route::post('updateLocationByAdmin', ['as' => 'updateLocationByAdmin', 'uses' => 'FreelancerController@updateLocationByAdmin']);

        /*     * ******* Common URLs *********** */
        Route::post('updateAppointByAdmin', ['as' => 'updateAppointByAdmin', 'uses' => 'AppointmentController@updateAppointByAdmin']);
        Route::post('updateBlocktimeByAdmin', ['as' => 'updateBlocktimeByAdmin', 'uses' => 'BlocktimeController@updateBlocktimeByAdmin']);
        Route::post('updateSchedulesByAdmin', ['as' => 'updateSchedulesByAdmin', 'uses' => 'ScheduleController@updateSchedulesByAdmin']);
        Route::post('createFreelancerByAdmin', ['as' => 'createFreelancerByAdmin', 'uses' => 'FreelancerController@createFreelancerByAdmin']);
        Route::post('createCustomerByAdmin', ['as' => 'createCustomerByAdmin', 'uses' => 'CustomerController@createCustomerByAdmin']);
        Route::post('getFreelancersSubscriptions', ['as' => 'getFreelancersSubscriptions', 'uses' => 'FreelancerController@getFreelancersSubscriptions']);

        /* Graphs links */
        Route::post('getAppointmentsCurrentMonth', ['as' => 'getAppointmentsCurrentMonth', 'uses' => 'GraphController@getAppointmentsCurrentMonth']);
        Route::get('getNewFreelancerGraphs', ['as' => 'getNewFreelancerGraphs', 'uses' => 'GraphController@getNewFreelancerGraphs']);
        Route::get('getNewCustomerGraphs', ['as' => 'getNewCustomerGraphs', 'uses' => 'GraphController@getNewCustomerGraphs']);

        Route::get('getAllProfessions', ['as' => 'getAllProfessions', 'uses' => 'ProfessionController@getAllProfessions']);
        Route::post('saveProfession', ['as' => 'saveProfession', 'uses' => 'ProfessionController@saveProfession']);
        Route::post('deleteProfession', ['as' => 'deleteProfession', 'uses' => 'ProfessionController@deleteProfession']);
        Route::get('edit-profession/{id}', ['as' => 'editProfession', 'uses' => 'ProfessionController@editProfession']);
        Route::post('updateProfession', ['as' => 'updateProfession', 'uses' => 'ProfessionController@updateProfession']);

        /* Payment Request */
        Route::get('payment-transfer', ['as' => 'paymentFreelancerListing', 'uses' => 'PaymentController@paymentFreelancerListing']);
        Route::get('freelancer/payment-transfer/{uuid}', ['as' => 'paymentTransferList', 'uses' => 'PaymentController@paymentTransferList']);
        Route::post('freelancer/store/payment-transfer', ['as' => 'storeFreelancerPaymentRequest', 'uses' => 'PaymentController@processPaymentTransfer']);
        Route::get('freelancer/payment-transfer/detail/{uuid}', ['as' => 'freelancerPaymentRequestDetail', 'uses' => 'PaymentController@freelancerPaymentRequestDetail']);
        Route::get('freelancer/payment-transfer/detail/{uuid}/list', ['as' => 'freelancerPaymentRequestList', 'uses' => 'PaymentController@freelancerPaymentRequestList']);
        Route::post('freelancer/payment-transfer/update', ['as' => 'updateFreelancerPaymentTransfer', 'uses' => 'PaymentController@updateFreelancerPaymentTransfer']);

        // pdf invoice for transfer payments
        Route::get('freelancer/payment-transfer/generate/pdf/{uuid}', ['as' => 'transferPaymentPDFDownload', 'uses' => 'PaymentController@transferPaymentPDFDownload']);

        Route::get('availabe-payouts', ['as' => 'availabeEarnings', 'uses' => 'PayoutController@getAllFreelancerAvailableEarnings']);
        Route::get('payouts', ['as' => 'payouts', 'uses' => 'PayoutController@getAllFreelancersPayouts']);
        Route::get('payout-details', ['as' => 'payoutDetails', 'uses' => 'PayoutController@payoutDetails']);
        Route::get('send-notification', ['as' => 'sendPaymentStatusNotification', 'uses' => 'PayoutController@sendPaymentStatusNotification']);
        Route::get('download-funds-transfer-csv', ['as' => 'fundsTransferCSV', 'uses' => 'PayoutController@fundsTransferCSV']);
        Route::get('download-freelancers-csv', ['as' => 'freelancerCSV', 'uses' => 'PayoutController@freelancerCSV']);

        Route::post('update-payout-status-with-file', ['as' => 'updatePayoutStatusWithFile', 'uses' => 'PayoutController@updatePayoutStatusWithFile']);


        Route::get('payment-requests', ['as' => 'getAllPaymentRequests', 'uses' => 'PaymentController@getAllPaymentRequests']);
        Route::get('payment-request/{uuid}', ['as' => 'getPaymentRequestDetail', 'uses' => 'PaymentController@getPaymentRequestDetail']);
        Route::post('rejectPaymentRequest', ['as' => 'rejectPaymentRequest', 'uses' => 'PaymentController@rejectPaymentRequest']);
        Route::post('approvePaymentRequest', ['as' => 'approvePaymentRequest', 'uses' => 'PaymentController@approvePaymentRequest']);

        Route::get('message-codes', ['as' => 'getAllMessageCodes', 'uses' => 'MainController@getAllMessageCodes']);
        Route::get('transaction-detail/{tr_id}/{due_id?}', ['as' => 'getTransactionDetail', 'uses' => 'FreelancerController@getTransactionDetail']);

        // Freelancer Classes
        Route::get('classes/{id}', ['as' => 'freelancerClasses', 'uses' => 'FreelancerController@freelancerClasses']);

        // Freelancer Packages
        Route::get('packages/{id}', ['as' => 'freelancerPackages', 'uses' => 'FreelancerController@freelancerPackages']);
        Route::get('cron-jobs', ['as' => 'CronJobs', 'uses' => 'CronJobController@index']);
        Route::get('cron-jobs/{id}', ['as' => 'CronJobs.show', 'uses' => 'CronJobController@show']);

        Route::get('ses-bounces', ['as' => 'SESBounces', 'uses' => 'SESBounceController@index']);
        Route::get('ses-complaints', ['as' => 'SESComplaints', 'uses' => 'SESComplaintController@index']);

        // system settings
        Route::get('system-settings', ['as' => 'systemSettings', 'uses' => 'SystemSettingController@index']);



    });

    Route::post('splitOrderNotificationHook', ['as' => 'splitOrderNotificationHook', 'uses' => 'PaymentController@splitOrderNotificationHook']);
});
