<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\RemoteBrowserAdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\RemoteAccessController;
use App\Http\Controllers\LbankApiController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\PaymentRegistrationController;
use App\Http\Controllers\SubscriptionPaymentController;
use App\Http\Controllers\Admin\SupportTicketController;
use App\Http\Controllers\Admin\SupportAccessController;
use App\Http\Controllers\Admin\UserGroupController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\MtWebProxyController;



Route::get('/', function(){
    return view('index');
})->name('index');

Auth::routes();


Route::get('/ref/{code}', function ($code) {

    // اگر لاگین هست → کاری نکن
    if (Auth::check()) {
        return redirect('/dashboard');
    }

    // بررسی وجود کد
    $referrer = \App\Models\User::where('ref_code', $code)->first();

    if (!$referrer) {
        return redirect('/login');
    }

    // ذخیره در سشن فقط برای مهمان
    session(['ref_code' => $code]);

    return redirect('/login');

})->name('ref.link');


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/learnblog', [BlogPostController::class, 'homeBlog'])->name('blog');
Route::get('/catblog/{cat}', [BlogPostController::class, 'homeCatBlog'])->name('catblog');
Route::get('/learnblog/{slug}', [BlogPostController::class, 'homeSingleBlog'])->name('homeSingleBlog');
Route::get('/contact', [App\Http\Controllers\HomeController::class, 'contact'])->name('contact');

Route::any('/mt/terminal', function (Request $request) {
    $query = $request->getQueryString();

    return redirect('/terminal' . ($query ? ('?' . $query) : ''), 302);
})->name('mt.terminal.redirect');

Route::any('/mt/terminal/{path?}', function (Request $request, ?string $path = null) {
    $query = $request->getQueryString();
    $suffix = ltrim((string) ($path ?? ''), '/');
    $target = '/terminal' . ($suffix === '' ? '' : ('/' . $suffix));

    return redirect($target . ($query ? ('?' . $query) : ''), 302);
})->where('path', '.*')->name('mt.terminal.path.redirect');

Route::match(['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'], '/mt/{path?}', [MtWebProxyController::class, 'proxy'])
    ->where('path', '.*')
    ->name('mt.proxy');

$mtBrokerController = app(MtWebProxyController::class);

Route::get('/terminal/{broker}', [MtWebProxyController::class, 'brokerEntry'])
    ->where('broker', $mtBrokerController->brokerRoutePattern())
    ->name('mt.proxy.terminal.broker');

Route::match(['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'], '/terminal/{path?}', [MtWebProxyController::class, 'proxy'])
    ->where('path', '.*')
    ->name('mt.proxy.terminal');

Route::post('/sendotp', [App\Http\Controllers\Auth\SMSController::class, 'index'])->name('sendOtp');
Route::get('/otp', [App\Http\Controllers\Auth\SMSController::class, 'otp_page'])->name('otpPage');
Route::post('/otpprocess', [App\Http\Controllers\Auth\SMSController::class, 'vilidation_code'])->name('otpProcess');

Route::get('/payment', [PaymentRegistrationController::class, 'show'])->name('payment.form');
Route::post('/payment', [PaymentRegistrationController::class, 'request'])->name('payment.request');
Route::get('/payment/callback', [PaymentRegistrationController::class, 'callback'])->name('payment.callback');


Route::get('/access/{token}', [RemoteAccessController::class, 'access'])->name('remote.access');
Route::post('/access/{token}', [RemoteAccessController::class, 'login'])->name('remote.access.login');
Route::get('/access', [RemoteAccessController::class, 'unifiedAccess'])->name('remote.access.unified');
Route::post('/access', [RemoteAccessController::class, 'unifiedLogin'])->name('remote.access.unified.login');
Route::match(['GET', 'HEAD'], '/__auth/novnc', [RemoteAccessController::class, 'novncAuthorize']);
Route::get('/remote/{instance}', [RemoteAccessController::class, 'remoteViewer'])
    ->name('remote.viewer');
Route::get('/subscription/payment/callback', [SubscriptionPaymentController::class, 'callback'])->name('subscription.payment.callback');


Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\DashboardController::class, 'profile'])->name('dashboard.profile');
    Route::post('/profile/update', [App\Http\Controllers\DashboardController::class, 'update_profile'])->name('dashboard.profile.update');
    Route::get('/profile/uid', [App\Http\Controllers\DashboardController::class, 'profile_uid'])->name('dashboard.profile.uid');
    Route::post('/profile/update_uid', [App\Http\Controllers\DashboardController::class, 'update_uid'])->name('dashboard.profile.update_uid');
    Route::get('/profile/api_set', [App\Http\Controllers\DashboardController::class, 'profile_api_set'])->name('dashboard.profile.api_set');
    Route::get('/notifications', [App\Http\Controllers\DashboardController::class, 'notifications'])->name('notifications');
    Route::get('/readAllNotifs', [App\Http\Controllers\DashboardController::class, 'readAllNotifs'])->name('readAllNotifs');
    Route::post('/notifRead', [App\Http\Controllers\DashboardController::class, 'notifRead'])->name('notifRead');
    Route::post('/notifDelete', [App\Http\Controllers\DashboardController::class, 'notifDelete'])->name('notifDelete');
    Route::get('/subscription', [App\Http\Controllers\DashboardController::class, 'subscription'])->name('subscription');
    Route::post('/subscription/payment', [SubscriptionPaymentController::class, 'request'])->name('subscription.payment.request');
    Route::get('/autoTradeSetting', [App\Http\Controllers\DashboardController::class, 'autoTradeSetting'])->name('autoTradeSetting');
    Route::post('/lbank_connector', [LbankApiController::class, 'store'])->name('lbankConnector');
    Route::post('/profile/getDemoVIP', [UserController::class, 'getDemoVIP'])->name('users.getDemoVIP');
    Route::get('/referrals', [App\Http\Controllers\DashboardController::class, 'referralsPage'])->name('referrals');

    Route::get('/internalBlog', [BlogPostController::class, 'internalBlog'])->name('internalBlog');
    Route::get('/internalBlog/cat/{slug}', [BlogPostController::class, 'internalBlogCat'])->name('internalBlogCat');
    Route::get('/internalSingleBlog/{slug}', [BlogPostController::class, 'internalSingleBlog'])->name('internalSingleBlog');

    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/ticket/{tracking_code}', [TicketController::class, 'show'])->name('tickets.show');
    Route::post('/ticket/{tracking_code}/reply', [TicketController::class, 'reply'])->name('tickets.reply');
    Route::post('/ticket/{tracking_code}/close', [TicketController::class, 'close'])->name('tickets.close');
    Route::get('/tickets/attachments/{attachment}', [TicketController::class, 'downloadAttachment'])->name('tickets.attachments.download');

    Route::get('/wallet', [WalletController::class, 'show'])->name('wallet');
    Route::post('/wallet/deposit', [WalletController::class, 'deposit'])->name('wallet.deposit');
    Route::post('/wallet/withdraw', [WalletController::class, 'withdraw'])->name('wallet.withdraw');
    Route::post('/wallet/transfer-by-mobile', [WalletController::class, 'transferByMobile'])->name('wallet.transferByMobile');
    Route::post('/wallet/swap', [WalletController::class, 'swap'])->name('wallet.swap');
    Route::post('/wallet/operation', [WalletController::class, 'operation'])->name('wallet.operation');

    Route::group(['middleware' => ['checkSupport']], function () {
        Route::get('/support/tickets', [SupportTicketController::class, 'index'])->name('support.tickets.index');
        Route::get('/support/tickets/{ticket}', [SupportTicketController::class, 'show'])->name('support.tickets.show');
        Route::post('/support/tickets/{ticket}/reply', [SupportTicketController::class, 'reply'])->name('support.tickets.reply');
        Route::post('/support/tickets/{ticket}/assign', [SupportTicketController::class, 'assign'])->name('support.tickets.assign');
        Route::post('/support/tickets/{ticket}/close', [SupportTicketController::class, 'close'])->name('support.tickets.close');
        Route::post('/support/tickets/{ticket}/reopen', [SupportTicketController::class, 'reopen'])->name('support.tickets.reopen');
    });


    Route::group(['middleware' => ['checkAdmin']], function () {
        Route::get('/admin/newSignal', [App\Http\Controllers\DashboardController::class, 'newSignal'])->name('newSignal');
        Route::post('/admin/NewSignalProcess', [App\Http\Controllers\SignalController::class, 'NewSignalProcess'])->name('NewSignalProcess');
        Route::get('/admin/signalDetails/{signal}', [App\Http\Controllers\SignalController::class, 'signalDetails'])->name('signalDetails');
        Route::post('/admin/updateSignalProcess/{signal}', [App\Http\Controllers\SignalController::class, 'updateSignalProcess'])->name('updateSignalProcess');
        Route::get('/admin/signalsHistory', [App\Http\Controllers\SignalController::class, 'signalsHistory'])->name('signalsHistory');
        Route::delete('/admin/signals/{signal}', [App\Http\Controllers\SignalController::class, 'destroySignal'])->name('signals.destroy');
        

        Route::get('/admin/bot1', [App\Http\Controllers\Bot1Controller::class, 'dashboard'])->name('bot1.dashboard');
        Route::get('/admin/bot1/users', [App\Http\Controllers\Bot1Controller::class, 'users'])->name('bot1.users');
        Route::get('/admin/bot1/activeUsers', [App\Http\Controllers\Bot1Controller::class, 'activeUsers'])->name('bot1.activeUsers');
        Route::get('/admin/bot1/deactiveUsers', [App\Http\Controllers\Bot1Controller::class, 'deactiveUsers'])->name('bot1.deactiveUsers');
        Route::get('/admin/bot1/botUserInfo/{user}', [App\Http\Controllers\Bot1Controller::class, 'botUserInfo'])->name('bot1.botUserInfo');
        Route::post('/admin/bot1/UpdateUserInfo/{user}', [App\Http\Controllers\Bot1Controller::class, 'UpdateUserInfo'])->name('bot1.UpdateUserInfo');

        Route::get('/admin/bot2', [App\Http\Controllers\Bot2Controller::class, 'dashboard'])->name('bot2.dashboard');
        Route::get('/admin/bot2/users', [App\Http\Controllers\Bot2Controller::class, 'users'])->name('bot2.users');
        Route::get('/admin/bot2/activeUsers', [App\Http\Controllers\Bot2Controller::class, 'activeUsers'])->name('bot2.activeUsers');
        Route::get('/admin/bot2/deactiveUsers', [App\Http\Controllers\Bot2Controller::class, 'deactiveUsers'])->name('bot2.deactiveUsers');
        Route::get('/admin/bot2/botUserInfo/{user}', [App\Http\Controllers\Bot2Controller::class, 'botUserInfo'])->name('bot2.botUserInfo');
        Route::post('/admin/bot2/UpdateUserInfo/{user}', [App\Http\Controllers\Bot2Controller::class, 'UpdateUserInfo'])->name('bot2.UpdateUserInfo');

        Route::any('/admin/userSarch', [App\Http\Controllers\DashboardController::class, 'userSearch'])->name('userSearch');

        Route::get('/lbank_checkBalance', [App\Http\Controllers\DashboardController::class, 'lbank_checkBalance'])->name('lbank.checkBalance');
        Route::post('/lbank_checkBalance_process', [App\Http\Controllers\LbankApiController::class, 'lbank_checkBalance_process'])->name('lbank.checkBalance.process');


        Route::get('/admin/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/admin/users/all', [UserController::class, 'all'])->name('users.all');
        Route::get('/admin/users/activeUsers', [UserController::class, 'activeUsers'])->name('users.activeUsers');
        Route::get('/admin/users/deactiveUsers', [UserController::class, 'deactiveUsers'])->name('users.deactiveUsers');
        Route::get('/admin/users/leftUsers', [UserController::class, 'leftUsers'])->name('users.leftUsers');
        Route::get('/admin/users/search', [UserController::class, 'search'])->name('users.search');
        Route::get('/admin/user-camps', [UserController::class, 'userCamps'])->name('users.camps');
        Route::get('/admin/user/{userId}', [UserController::class, 'edit'])->name('users.detail');
        Route::post('/admin/removeUserUid/{user}', [UserController::class, 'removeUserUid'])->name('users.removeUserUid');
        Route::post('/admin/UpdateUserInfo/{user}', [UserController::class, 'update'])->name('user.UpdateUserInfo');
        Route::post('/admin/user/updatePermission/{user}', [UserController::class, 'updatePermission'])->name('user.updatePermission');
        Route::post('/admin/user/{user}/wallet', [UserController::class, 'updateWallet'])->name('user.wallet.update');
        Route::post('/admin/sendNotif/{user}', [UserController::class, 'sendNotif'])->name('user.sendNotif');
        Route::post('/admin/user/updateAdminNote/{user}', [UserController::class, 'updateAdminNote'])->name('user.updateAdminNote');

        Route::get('/admin/users/sendNotif', [UserController::class, 'sendNotif'])->name('users.sendNotif');
        Route::post('/admin/users/sendNotifProcess', [UserController::class, 'sendNotifProcess'])->name('sendNotif.process');
        Route::get('/admin/users/editNotif/{notif}', [UserController::class, 'editNotif'])->name('users.editNotif');
        Route::post('/admin/users/notifUpdateProcess/{notif}', [UserController::class, 'NotifUpdate'])->name('notif.update');

        Route::post('/admin/users/deleteNotif/{notif}', [UserController::class, 'destroy'])->name('users.deleteNotif');

        Route::get('/admin/users/export/excel', [ExportController::class, 'usersExcel'])->name('users.export.excel');

        Route::get('/admin/user-groups', [UserGroupController::class, 'index'])->name('user-groups.index');
        Route::get('/admin/user-groups/create', [UserGroupController::class, 'create'])->name('user-groups.create');
        Route::post('/admin/user-groups', [UserGroupController::class, 'store'])->name('user-groups.store');
        Route::get('/admin/user-groups/{group}', [UserGroupController::class, 'show'])->name('user-groups.show');
        Route::post('/admin/user-groups/{group}/update', [UserGroupController::class, 'update'])->name('user-groups.update');
        Route::post('/admin/user-groups/{group}/commissions', [UserGroupController::class, 'updateCommissions'])->name('user-groups.commissions.update');
        Route::post('/admin/user-groups/{group}/members', [UserGroupController::class, 'addMember'])->name('user-groups.members.add');
        Route::post('/admin/user-groups/{group}/members/{user}/remove', [UserGroupController::class, 'removeMember'])->name('user-groups.members.remove');
        Route::post('/admin/user-groups/{group}/supports', [UserGroupController::class, 'addSupport'])->name('user-groups.supports.add');
        Route::post('/admin/user-groups/{group}/supports/{user}/remove', [UserGroupController::class, 'removeSupport'])->name('user-groups.supports.remove');

        Route::post('/admin/user/{user}/groups', [UserController::class, 'addUserToGroup'])->name('user.groups.add');
        Route::post('/admin/user/{user}/groups/{group}/remove', [UserController::class, 'removeUserFromGroup'])->name('user.groups.remove');

        //blog
        Route::get('/admin/blogCats', [BlogCategoryController::class, 'index'])->name('blogCategoriesAdmin.index');
        Route::post('/admin/blogCats/store', [BlogCategoryController::class, 'store'])->name('blogCategoriesAdmin.store');
        Route::get('/admin/blogCats/edit/{category}', [BlogCategoryController::class, 'edit'])->name('blogCategoriesAdmin.edit');
        Route::put('/admin/blogCats/update/{category}', [BlogCategoryController::class, 'update'])->name('blogCategoriesAdmin.update');
        Route::delete('/admin/blogCats/delete/{category}', [BlogCategoryController::class, 'destroy'])->name('blogCategoriesAdmin.destroy');
        
            Route::get('/admin/blogPosts', [BlogPostController::class, 'index'])->name('blogAdmin');
            Route::get('/admin/blogPosts/create', [BlogPostController::class, 'create'])->name('blogPosts.create');
            Route::post('/admin/blogPosts/store', [BlogPostController::class, 'store'])->name('blogPosts.store');
            Route::get('/admin/blogPosts/edit/{post}', [BlogPostController::class, 'edit'])->name('blogPosts.edit');
            Route::put('/admin/blogPosts/update/{post}', [BlogPostController::class, 'update'])->name('blogPosts.update');
            Route::delete('/admin/blogPosts/delete/{post}', [BlogPostController::class, 'destroy'])->name('blogPosts.destroy');

        Route::post('/admin/users/{userId}/lbank/issue', [RemoteBrowserAdminController::class, 'issue'])->name('setRemoteBrowserToken');
        Route::get('/admin/users/{userId}/remote/stats', [RemoteBrowserAdminController::class, 'stats'])->name('admin.remote.stats');
        Route::get('/admin/users/{userId}/remote/monitor', [RemoteBrowserAdminController::class, 'monitor'])->name('admin.remote.stats.monitor');

        Route::get('/admin/support-access', [SupportAccessController::class, 'index'])->name('support.access.index');
        Route::post('/admin/support-access/{user}', [SupportAccessController::class, 'update'])->name('support.access.update');

        Route::get('/admin/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/admin/settings', [SettingController::class, 'update'])->name('settings.update');
    });


    Route::group(['middleware' => ['checkVip']], function () {
        Route::get('/channel', [App\Http\Controllers\DashboardController::class, 'channel'])->name('channel');
    });
});

