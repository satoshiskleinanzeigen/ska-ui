<?php
//ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);

session_start();
require_once('config.php');

require_once(APP_PATH .'classes/AltoRouter.php');
require_once(APP_PATH .'classes/telegram_auth.php');

function has_tg_user_session(){
	$telegram_auth = new TG_AUTH();
	return $telegram_auth->check_user_session();

}

function needs_tg_user_login(){
	if(has_tg_user_session()){
		return true;
	} else {
		require APP_PATH.'pages/auth/login.php';
		die();
	}
}

/**
 * This can be useful if you're using PHP's built-in web server, to serve files like images or css
 * @link https://secure.php.net/manual/en/features.commandline.webserver.php
 */
if (file_exists($_SERVER['SCRIPT_FILENAME']) && pathinfo($_SERVER['SCRIPT_FILENAME'], PATHINFO_EXTENSION) !== 'php') {
    return;
}

$router = new AltoRouter();
$router->addMatchTypes(array('char' => '(?:[^\/]*)'));


$router->map( 'GET', '/', function() {
	
	require APP_PATH.'pages/inserate.php';
});

$router->map( 'GET', '/tag/[char:tag]', function($tag) {
	
	require APP_PATH.'pages/inserate.php';
});

$router->map( 'POST', '/suche', function() {
	
	require APP_PATH.'pages/inserate.php';
});


$router->map( 'GET', '/news', function() {
	
	require APP_PATH.'pages/news.php';
});

$router->map( 'GET', '/kategorien', function() {
	
	require APP_PATH.'pages/categories.php';
});

$router->map( 'GET', '/satoshi', function() {
	
	require APP_PATH.'pages/satoshi.php';
});

$router->map( 'GET', '/inserieren', function() {
	needs_tg_user_login();
	require APP_PATH.'pages/newitem.php';
});

$router->map( 'POST', '/newitem_submit', function() {
	needs_tg_user_login();
	require APP_PATH.'pages/newitem_submit.php';
});

$router->map( 'GET', '/ci-leitfaden', function() {
	
	require APP_PATH.'pages/ci-leitfaden.php';
});

$router->map( 'GET', '/spenden', function() {
	
	require APP_PATH.'pages/spenden.php';
});

$router->map( 'GET', '/entwicklung', function() {
	
	require APP_PATH.'pages/entwicklung.php';
});

$router->map( 'GET', '/useritems', function() {
	needs_tg_user_login();
	require APP_PATH.'pages/useritems.php';
});

/*
* Login Page
*/
$router->map( 'GET', '/auth/login', function() {
	
	require APP_PATH.'pages/auth/login.php';
});
/*
* Login Callback for ajax post
*/
$router->map( 'POST', '/auth/login', function() {
	
	require APP_PATH.'pages/auth/login_tg_callback.php';
});

/*
* Logout
*/
$router->map( 'GET', '/auth/logout', function() {
	
	require APP_PATH.'pages/auth/logout.php';
});

// match current request
$match = $router->match();


/*
* Call closure or throw 404 status
*/
if( is_array($match) && is_callable( $match['target'] ) ) {
	call_user_func_array( $match['target'], $match['params'] ); 
} else {
    require APP_PATH.'/pages/error.php';

}
?>