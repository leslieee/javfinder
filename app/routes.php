<?php

use Slim\App;
use Slim\Container;
use App\Controllers;
use App\Middleware\Admin;
use App\Middleware\Api;
use App\Middleware\Auth;
use App\Middleware\Guest;
use App\Middleware\Mu;
use Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware;

/***
 * The slim documents: http://www.slimframework.com/docs/objects/router.html
 */

// config
$debug = false;
if (defined("DEBUG")) {
    $debug = true;
}

$app = new App([
   'settings' => [
       'debug' => $debug,
       'whoops.editor' => 'sublime'
   ]
]);
$app->add(new WhoopsMiddleware);

// Home
$app->get('/', 'App\Controllers\HomeController:index');
$app->get('/movie/page/{page}', 'App\Controllers\HomeController:moviePage');
$app->get('/watch/{id}', 'App\Controllers\HomeController:watch');
$app->get('/search/{key}', 'App\Controllers\HomeController:search');

$app->get('/api/index', 'App\Controllers\HomeController:apiIndex');
$app->get('/api/page/{page}', 'App\Controllers\HomeController:apiPage');
$app->get('/api/watch/{id}', 'App\Controllers\HomeController:apiWatch');
$app->get('/api/search/{key}', 'App\Controllers\HomeController:apiSearch');

$app->get('/404', 'App\Controllers\HomeController:page404');
$app->get('/405', 'App\Controllers\HomeController:page405');
$app->get('/500', 'App\Controllers\HomeController:page500');
$app->get('/code', 'App\Controllers\HomeController:code');
$app->get('/tos', 'App\Controllers\HomeController:tos');
$app->get('/debug', 'App\Controllers\HomeController:debug');
$app->post('/debug', 'App\Controllers\HomeController:postDebug');
$app->get('/configclient', 'App\Controllers\HomeController:configclient');
$app->get('/getserverconfig', 'App\Controllers\HomeController:getServerConfig');
$app->get('/getandroidserverconfig', 'App\Controllers\HomeController:getAndroidServerConfig');

// User Center
$app->group('/user', function () {
    $this->get('', 'App\Controllers\UserController:index');
    $this->get('/', 'App\Controllers\UserController:index');
    $this->post('/checkin', 'App\Controllers\UserController:doCheckin');
    $this->get('/node', 'App\Controllers\UserController:node');
    $this->get('/node/{id}', 'App\Controllers\UserController:nodeInfo');
    $this->get('/profile', 'App\Controllers\UserController:profile');
    $this->get('/invite', 'App\Controllers\UserController:invite');
    $this->post('/invite', 'App\Controllers\UserController:doInvite');
    $this->get('/edit', 'App\Controllers\UserController:edit');
    $this->post('/password', 'App\Controllers\UserController:updatePassword');
    $this->post('/sspwd', 'App\Controllers\UserController:updateSsPwd');
    $this->post('/method', 'App\Controllers\UserController:updateMethod');
    $this->get('/sys', 'App\Controllers\UserController:sys');
    $this->get('/trafficlog', 'App\Controllers\UserController:trafficLog');
    $this->get('/kill', 'App\Controllers\UserController:kill');
    $this->post('/kill', 'App\Controllers\UserController:handleKill');
    $this->get('/logout', 'App\Controllers\UserController:logout');

    $this->get('/getclient', 'App\Controllers\UserController:getclient');
    $this->get('/payment', 'App\Controllers\UserController:payment');
    $this->get('/payment/{id}', 'App\Controllers\UserController:paymentHandleById');
    $this->get('/order', 'App\Controllers\UserController:order');
    $this->get('/getwinzip', 'App\Controllers\UserController:getWinZip');
    $this->get('/watchvideo', 'App\Controllers\UserController:watchVideo');
    $this->get('/getserverconfig', 'App\Controllers\UserController:getServerConfig');
    $this->post('/fingerprint', 'App\Controllers\UserController:postFingerPrint');

})->add(new Auth());

// Auth
$app->group('/auth', function () {
    $this->get('/login', 'App\Controllers\AuthController:login');
    $this->post('/login', 'App\Controllers\AuthController:loginHandle');
    $this->get('/register', 'App\Controllers\AuthController:register');
    $this->post('/register', 'App\Controllers\AuthController:registerHandle');
    $this->post('/sendcode', 'App\Controllers\AuthController:sendVerifyEmail');
    $this->get('/logout', 'App\Controllers\AuthController:logout');
})->add(new Guest());

// Password
$app->group('/password', function () {
    $this->get('/reset', 'App\Controllers\PasswordController:reset');
    $this->post('/reset', 'App\Controllers\PasswordController:handleReset');
    $this->get('/token/{token}', 'App\Controllers\PasswordController:token');
    $this->post('/token/{token}', 'App\Controllers\PasswordController:handleToken');
})->add(new Guest());

// Admin
$app->group('/admin', function () {
    $this->get('', 'App\Controllers\AdminController:index');
    $this->get('/', 'App\Controllers\AdminController:index');
    $this->get('/trafficlog', 'App\Controllers\AdminController:trafficLog');
    $this->get('/checkinlog', 'App\Controllers\AdminController:checkinLog');
    // app config
    $this->get('/config', 'App\Controllers\AdminController:config');
    $this->put('/config', 'App\Controllers\AdminController:updateConfig');
    // Node Mange
    $this->get('/node', 'App\Controllers\Admin\NodeController:index');
    $this->get('/node/create', 'App\Controllers\Admin\NodeController:create');
    $this->post('/node', 'App\Controllers\Admin\NodeController:add');
    $this->get('/node/{id}/edit', 'App\Controllers\Admin\NodeController:edit');
    $this->put('/node/{id}', 'App\Controllers\Admin\NodeController:update');
    $this->delete('/node/{id}', 'App\Controllers\Admin\NodeController:delete');
    $this->get('/node/{id}/delete', 'App\Controllers\Admin\NodeController:deleteGet');

    // User Mange
    $this->get('/user', 'App\Controllers\Admin\UserController:index');
    $this->get('/user/{id}/edit', 'App\Controllers\Admin\UserController:edit');
    $this->put('/user/{id}', 'App\Controllers\Admin\UserController:update');
    $this->delete('/user/{id}', 'App\Controllers\Admin\UserController:delete');
    $this->get('/user/{id}/delete', 'App\Controllers\Admin\UserController:deleteGet');
    $this->get('/user/checkpaymentstatus', 'App\Controllers\Admin\UserController:checkPaymentStatus');

    // Order
    $this->get('/order', 'App\Controllers\Admin\UserController:order');
    $this->get('/order/{id}/pass', 'App\Controllers\Admin\UserController:orderPass');
    $this->get('/order/{id}/orderremarks', 'App\Controllers\Admin\UserController:orderRemarks');
    $this->post('/order/orderremarks/add', 'App\Controllers\Admin\UserController:orderRemarksAdd');

    // userinfo
    $this->get('/userinfo', 'App\Controllers\Admin\UserController:userInfo');
    $this->get('/userinfo/{id}/hidden', 'App\Controllers\Admin\UserController:userInfoHidden');
    $this->get('/userinfo/{id}/edit', 'App\Controllers\Admin\UserController:userInfoEdit');
    $this->post('/userinfo/edit', 'App\Controllers\Admin\UserController:userInfoEditPost');
    $this->get('/userinfo/new', 'App\Controllers\Admin\UserController:userInfoNew');
    $this->post('/userinfo/new', 'App\Controllers\Admin\UserController:userInfoNewHandle');
    $this->get('/userinfo/{id}/delete', 'App\Controllers\Admin\UserController:userInfoDelete');

    // content
    $this->post('/content/edit', 'App\Controllers\Admin\UserController:contentEditPost');
    $this->post('/contentupload', 'App\Controllers\Admin\UserController:contentUpload');
    $this->post('/htmlchange', 'App\Controllers\Admin\UserController:htmlChange');

    // Test
    $this->get('/test/sendmail', 'App\Controllers\Admin\TestController:sendMail');
    $this->post('/test/sendmail', 'App\Controllers\Admin\TestController:sendMailPost');

    $this->get('/profile', 'App\Controllers\AdminController:profile');
    $this->get('/invite', 'App\Controllers\AdminController:invite');
    $this->post('/invite', 'App\Controllers\AdminController:addInvite');
    $this->get('/sys', 'App\Controllers\AdminController:sys');
    $this->get('/logout', 'App\Controllers\AdminController:logout');
})->add(new Admin());

// API
$app->group('/api', function () {
    $this->get('/token/{token}', 'App\Controllers\ApiController:token');
    $this->post('/token', 'App\Controllers\ApiController:newToken');
    $this->get('/node', 'App\Controllers\ApiController:node')->add(new Api());
    $this->get('/user/{id}', 'App\Controllers\ApiController:userInfo')->add(new Api());
});

// mu
$app->group('/mu', function () {
    $this->get('/users', 'App\Controllers\Mu\UserController:index');
    $this->post('/users/{id}/traffic', 'App\Controllers\Mu\UserController:addTraffic');
    $this->post('/nodes/{id}/online_count', 'App\Controllers\Mu\NodeController:onlineUserLog');
    $this->post('/nodes/{id}/info', 'App\Controllers\Mu\NodeController:info');
})->add(new Mu());

// mu
$app->group('/mu/v2', function () {
    $this->get('/users', 'App\Controllers\MuV2\UserController:index');
    $this->post('/users/{id}/traffic', 'App\Controllers\MuV2\UserController:addTraffic');
    $this->post('/nodes/{id}/online_count', 'App\Controllers\MuV2\NodeController:onlineUserLog');
    $this->post('/nodes/{id}/info', 'App\Controllers\MuV2\NodeController:info');
    $this->post('/nodes/{id}/traffic', 'App\Controllers\MuV2\NodeController:postTraffic');
})->add(new Mu());

// res
$app->group('/res', function () {
    $this->get('/captcha/{id}', 'App\Controllers\ResController:captcha');
});

return $app;


