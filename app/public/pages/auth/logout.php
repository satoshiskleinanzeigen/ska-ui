<?php
require_once('config.php');
require_once(APP_PATH .'classes/telegram_auth.php');
$telegram_auth = new TG_AUTH();
$telegram_auth->logout_user();
header('Location: /');
die();
?>