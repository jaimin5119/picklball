<?php
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\FaqController as AdminFAQController;
use App\Http\Controllers\Admin\CmsController as AdminCmsController;
use App\Http\Controllers\Admin\RatingController as AdminRatingController;
use App\Http\Controllers\Admin\ContactUsController as AdminContactUsController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\UsersController as AdminUsersController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\Admin\UserlistController; 
use App\Http\Controllers\Admin\UserNotificationController;
use App\Http\Controllers\Admin\TournamentController;
use App\Http\Controllers\Admin\MatchController;


// Route::get('/', [AdminController::class, 'login']);
Route::get('/home', function () {
    return view('banner'); // this loads banner.blade.php
});

Route::get('/about-us', [FrontendController::class, 'about'])->name('about');
Route::get('/contact-us', [FrontendController::class, 'contact'])->name('contact');

Route::get('/faqs', [FrontendController::class, 'faqs'])->name('faqs');
Route::get('/privacy-policy', [FrontendController::class, 'privacyPolicy'])->name('privacyPolicy');
Route::get('/terms-and-conditions', [FrontendController::class, 'termsAndConditions'])->name('termsAndConditions');

Route::post('/contact-us', [ContactController::class, 'send'])->name('contact.send');

Route::post('/admin-login', [AdminController::class, 'postLogin'])->name('admin.signin');


Route::group(['prefix' => 'admin'], function () {

Route::get('login', [AdminController::class, 'login'])->name('admin.login');
Route::post('/admin-login', [AdminController::class, 'postLogin'])->name('admin.signin');
Route::get('/forgot-password', [AdminController::class, 'forgotPass'])->name('admin.forgot_pass');
Route::post('/forgot-password', [AdminController::class, 'postForgotPass'])->name('admin.forgot_pwd.post');

Route::get('/reset-password/{xstr}', [AdminController::class, 'resetPassword'])->name('admin.reset_pwd');
Route::post('/set-new-password', [AdminController::class, 'setNewPassword'])->name('admin.set_new_pwd');

});

Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
    // admin routes post login
    Route::get('admin-users', [AdminController::class, 'adminUsers'])->name('admin.admin_users');
    Route::get('/edit-admin-page/{id}', [AdminController::class, 'admineditPage'])->name('admin.admineditPage');
    Route::post('/update-admin-page', [AdminController::class, 'adminupdatePage'])->name('admin.adminupdatePage');


    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dash');
    Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');

    Route::get('/change-password', [AdminController::class, 'changePwd'])->name('admin.change_pwd');
    Route::post('/update-password', [AdminController::class, 'updatePwd'])->name('admin.update_pwd');

    

    Route::get('/sample-listing-page', function(){
        return view('admin.sample_listing');
    })->name('admin.sample_listing');

    Route::get('/sample-form-page', function(){
        return view('admin.sample_form');
    })->name('admin.sample_form');


    // FAQs
    Route::get('/list-faqs', [AdminFAQController::class, 'index'])->name('admin.list_faqs');
    Route::get('/add-faqs', [AdminFAQController::class, 'addFaq'])->name('admin.add_faqs');
    Route::post('/store-faqs', [AdminFAQController::class, 'storeFaq'])->name('admin.store_faqs');
    Route::get('/edit-faq/{id}', [AdminFAQController::class, 'editFaq'])->name('admin.edit_faq');
    Route::post('/update-faqs', [AdminFAQController::class, 'updateFaq'])->name('admin.update_faqs');
    Route::post('/delete-faqs', [AdminFAQController::class, 'delFaq'])->name('admin.delete_faqs');

    // CMS Pages
    Route::get('/list-CMS-pages', [AdminCmsController::class, 'index'])->name('admin.list_cms_page');
    Route::get('/add-CMS-page', [AdminCmsController::class, 'addPage'])->name('admin.add_cms_page');
    Route::post('/store-CMS-page', [AdminCmsController::class, 'storePage'])->name('admin.store_cms_page');
    Route::get('/edit-CMS-page/{id}', [AdminCmsController::class, 'editPage'])->name('admin.edit_cms_page');
    Route::post('/update-CMS-page', [AdminCmsController::class, 'updatePage'])->name('admin.update_cms_page');
    // Route::post('/delete-CMS-page', [AdminCmsController::class, 'delPage'])->name('admin.delete_cms_page');


    // Ratings
    Route::get('/list-ratings', [AdminRatingController::class, 'index'])->name('admin.list_ratings');
    Route::post('/rating-visiblity', [AdminRatingController::class, 'displayStatus'])->name('admin.rating_visiblity');
    Route::post('/delete-rating', [AdminRatingController::class, 'delRating'])->name('admin.delete_rating');
    
    // Contact Us
    Route::get('/list-contact-us', [AdminContactUsController::class, 'index'])->name('admin.list_contact_us');


    // Notifications
    Route::get('/list-notifications', [AdminNotificationController::class, 'index'])->name('admin.list_notif');
    Route::get('/new-notification', [AdminNotificationController::class, 'newNotif'])->name('admin.new_notif');
    Route::post('/send-notification', [AdminNotificationController::class, 'sendNotifi'])->name('admin.send_notif');

    // Users
    Route::get('/list-users', [AdminUsersController::class, 'index'])->name('admin.list_users');
    Route::post('/block-unblock-users', [AdminUsersController::class, 'accountStatus'])->name('admin.block_unblock_users');
    Route::get('bulk-user-add', [AdminUsersController::class, 'bulkUploadUsers'])->name('admin.bulk_add_user');

    Route::post('bulk-user-store', [AdminUsersController::class, 'bulkUserStore'])->name('admin.bulk_store_user');
    Route::post('bulk-update-store', [AdminUsersController::class, 'bulkUserUpdate'])->name('admin.bulk_update_user');

    Route::get('export-users',[AdminUsersController::class,'exportUsers'])->name('export-users');

    Route::get('/view-vistor-page/{id}', [AdminUsersController::class, 'viewVistorPage'])->name('admin.view_vistor_page');

    Route::get('/vistor-dash-page/{id}', [AdminUsersController::class, 'vistorDashPage'])->name('admin.vistor_dash_page');
    Route::get('/vistor-login-page/{id}', [AdminUsersController::class, 'vistorLoginPage'])->name('admin.vistor_login_page');
    Route::get('/vistor-reg-page/{id}', [AdminUsersController::class, 'vistorRegPage'])->name('admin.vistor_reg_page');


    //user listing
    Route::get('users', [UserlistController::class, 'index'])->name('admin.usersindex');
    Route::get('/users/list', [UserlistController::class, 'getUsersList'])->name('admin.userslist');
    Route::delete('/users/delete/{id}', [UserlistController::class, 'deleteUsersList'])->name('admin.usersdelete');
    Route::post('/admin/user/toggle-status', [UserlistController::class, 'toggleStatus'])->name('admin.toggleUserStatus');



    

// Notification list page


Route::get('/notifications/create', [UserNotificationController::class, 'create'])->name('admin.notificationscreate');
Route::post('/notifications/store', [UserNotificationController::class, 'store'])->name('admin.notificationsstore');
Route::get('/notifications', [UserNotificationController::class, 'index'])->name('admin.notificationsindex');
Route::get('/notifications/scheduled', [UserNotificationController::class, 'getScheduledNotifications'])->name('admin.scheduled.notifications');
Route::get('/scheduled-notification/edit/{id}', [UserNotificationController::class, 'edit'])->name('admin.schedulededit');
Route::post('/notifications/update', [UserNotificationController::class, 'update'])->name('admin.notificationsupdate');
Route::post('/admin/notification/scheduled/delete', [UserNotificationController::class, 'destroy'])->name('admin.scheduleddelete');


    
    // DataTable AJAX route
    Route::get('admin/tournaments', function () {
    return view('admin.tournaments.index');
})->name('admin.tournaments.index');

    Route::get('tournaments-list', [TournamentController::class, 'getTournamentsList'])->name('admin.tournamentslist');

    // Delete Tournament
    Route::post('tournaments/delete', [TournamentController::class, 'delete'])->name('admin.tournamentsdelete');



    Route::get('/matches', [MatchController::class, 'index'])->name('admin.matches');;
Route::get('/matches/list', [MatchController::class, 'getMatchesList'])->name('admin.matcheslist');
Route::delete('/matches/{id}', [MatchController::class, 'destroy'])->name('admin.matchesdelete');

    
});



// ### Sign In ###
// Route::get('signin', [UserController::class, 'createSignIn']);
// Route::post('signin/create', [UserController::class, 'storeLogin']);

// ### Sign Up ###
Route::get('signup', [UserController::class, 'create']);
Route::post('signup/create', [UserController::class, 'store']);

// ### Verify Email ###
// Route::get('/verify-user',[UserController::class,'verifyUserView']);
// Route::post('verify/create', [UserController::class, 'storeVerify']);