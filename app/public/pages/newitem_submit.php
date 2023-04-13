<?php

# SET API KEY
$API_KEY= API_KEY;

#
# This Script shoult allways respont JSON ->
#
function json_response($data){
    header('Content-Type: application/json');
    print_r(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

#
# Response Array ->
#

$response=array();
$response['success'] = true;
$response['errors'] = array();


#
# Check / format required user input ->
#

if (isset($_POST['telegram_handle'])) {

    if (strlen($_POST['telegram_handle']) < 2) {
        $response['success'] = false;
        $response['errors'][] = 'Telegram Handle nicht eingetragen';
    }else{
        $_POST['telegram_handle'] = '@'.str_replace('@', '', $_POST['telegram_handle']);
    }
    
}else{
    $response['success'] = false;
    $response['errors'][] = 'Telegram Benutzername fehlt.';
}

if (isset($_POST['text_desc'])) {

    if (strlen($_POST['text_desc']) < 2) {
        $response['success'] = false;
        $response['errors'][] = 'Beschreibung zu kurz, oder fehlt.';
    }else{
        $_POST['text_desc'] =  strip_tags($_POST['text_desc']);
    }
    
}else{
    $response['success'] = false;
    $response['errors'][] = 'Beschreibung zu kurz, oder fehlt.';
}

if (isset($_POST['text'])) {

    if (strlen($_POST['text']) < 2) {
        $response['success'] = false;
        $response['errors'][] = 'Text zu kurz, oder fehlt.';
    }else{
        $_POST['text'] =  strip_tags($_POST['text']);
    }
    
}else{
    $response['success'] = false;
    $response['errors'][] = 'Text zu kurz, oder fehlt.';
}

//Überprüfung Preistyp
if (strlen($_POST['pricetype']) > 2) {

    $_POST['pricetype'] =  $_POST['pricetype'];
    
}else{
    $response['success'] = false;
    $response['errors'][] = 'Preistyp wurde nicht ausgewählt.';
}

// Überprüfung Preis
if (isset($_POST['sats'])) {

    if (strlen($_POST['sats']) < 2) {
        $response['success'] = false;
        $response['errors'][] = 'Betrag in sats fehlt.';
    }else{
        $_POST['sats'] =  strip_tags($_POST['sats']);
    }
    
}else{
    $response['success'] = false;
    $response['errors'][] = 'Betrag in sats fehlt.';
}

//Überprüfung Versand
if (strlen($_POST['delivery']) > 2) {

    $delivery = 'Versand: ' . $_POST['delivery'] . "\r\n". "\r\n";
    
}else{
    $delivery = '';
}

$taglist = array();
$tags_string = '';

if (isset($_POST['tags'])) {

    if (strlen($_POST['tags']) < 2) {
        $response['success'] = false;
        $response['errors'][] = 'Es wurden keine Tags gesetzt';
    }else{

        $tags = json_decode($_POST['tags'], true);

        foreach($tags as $tag){
            $taglist[] = '#'.str_replace('#','',$tag['value']);
        }

        $tags_string = implode(' ', $taglist);
    }
    
}else{
    $response['success'] = false;
    $response['errors'][] = 'Es wurden keine Tags gesetzt';
}


#
# If one user input check fails, exit here and send errors back ->
#

if($response['success'] == false){
    json_response($response);
    die();
}

#
# Build Message Text from user inputs ->
#

$postfields = array();

$postfields['caption'] = $_POST['text_desc'] . "\r\n". "\r\n".
$_POST['text'] . "\r\n". "\r\n".
$delivery .
$_POST['pricetype'] . ': '  . $_POST['sats'] . "sats\r\n". "\r\n".
"@".$_SESSION['username'] .  "\r\n". "\r\n".
"_ID_" .$_SESSION['id'] . "__" .  "\r\n". "\r\n".
$tags_string;


#
# Finally check if message is not to long (Image caption limit = 1024) ->
#
if (strlen($postfields['caption']) > 1024 ) {
    $response['success'] = false;
    $response['errors'][] = 'Die Nachricht ist zu lang. Maximal 1024 Zeichen erlaubt. <br> Bitte kürze die Nachricht oder entferne Tags.';
    json_response($response);
    die();
}


#
# We need a clean files array for the api ->
#

foreach ($_FILES as $key => $value) {
    if($value['tmp_name'] == ''){
        continue;
    }
    $postfields[$key] = new CURLFILE($value['tmp_name'], $value['type'], $value['name']);
}


#
# Build and send Curl POST request to API ->
#

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => API_PATH .'post-advertisement',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => $postfields,
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.$API_KEY,
    'UserAuthData:' . base64_encode(json_encode($_SESSION['auth_data']))
  ),
));

curl_exec($curl);

curl_close($curl);

json_response($response);
die();
?>
