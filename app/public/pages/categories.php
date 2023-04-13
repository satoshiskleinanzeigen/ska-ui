<?php require_once('templates/header.php'); ?>

<div>
<h1>Kategorien</h2>

<?php 
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

	require_once(APP_PATH .'classes/class_sk_api_client.php');
	$API = new SK_API;

	$data = $API->get_tag_list_numbered();
	$taglist_array =json_decode($data, true);
	arsort($taglist_array);
	//$taglist_array_reversed = array_reverse($taglist_array, true);

	echo "<ul class='grid taglist'>";
	foreach ($taglist_array as $key => $val) {
		echo "<li><a href='/tag/".trim($key, '#')."'>".$key."</a><span class='tag_count'>".$val."</span></li>";
	}
	echo "</ul>";

?>

</div>

<?php require_once('templates/footer.php'); ?>