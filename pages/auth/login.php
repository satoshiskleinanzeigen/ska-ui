<?php
require_once('config.php');

if(has_tg_user_session()){
    header('Location: /');
}

require_once(APP_PATH .'classes/telegram_auth.php');
require_once('templates/header.php');

$telegram_auth = new TG_AUTH();


?>

<div style='max-width: 1000px; text-align:center; margin:auto;'>
    <h1>Bitte melde Dich bitte via Telegram an</h2>


        <script async src="https://telegram.org/js/telegram-widget.js?21" data-telegram-login="<?php echo TG_BOT_NAME; ?>" data-size="large" data-onauth="onTelegramAuth(user)" data-request-access="write"></script>
        <script type="text/javascript">
			let success_url = window.location.href;
		
            function onTelegramAuth(user) {
                console.log(user);
                $.ajax({
                    contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                    dataType : "json",
                    method: "POST",
                    url: "/auth/login",
                    data: user
                })
                .done(function(data) {  
                    window.open(success_url, "_self");

                })
                .fail(function(data) {
                    console.log(data);
                    //do something here to notify the user about the error
                });
            }
        </script>
        <p>
            Privacy: Deine Telefonnummer wird nicht gespeichert und wird nur für die Anmeldung bei Telegram verwendet.
        </p>

</div>

<?php require_once('templates/footer.php'); ?>