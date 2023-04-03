<?php
require_once('config.php');
require_once(APP_PATH .'classes/telegram_auth.php');

$response['status'] = 'error';
$response['message'] = 'No data received';


$telegram_auth = new TG_AUTH();

if($telegram_auth->login_user($_POST)){
    $response['status'] = 'success';
    $response['message'] = 'Successfully logged in';
} else {
    $response['status'] = 'error';
    $response['message'] = 'Failed to login';
};

echo json_encode($response, true);


?>