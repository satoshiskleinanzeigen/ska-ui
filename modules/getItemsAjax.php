<?php
//ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);

require_once('../config.php');


if (isset($_POST['page'])) {
	$page = $_POST['page'];
}

	//get json from api
	require_once(APP_PATH .'classes/class_sk_api_client.php');
	$API = new SK_API;

	//get current btc price in eur
	$btcdata =  $API->get_btc_in_eur();
	$json = json_decode($btcdata, true);
	if(isset($json['bitcoin']['eur'])){
		$bitcoin_price = $json['bitcoin']['eur'];
	}

	if(isset($_POST['tag']) && strlen($_POST['tag']) >= 1 && $page == 1){
		$tag = '#'.urldecode($_POST['tag']);
		$data =  $API->get_posts_by_tag($tag);
	}

	elseif(isset($_POST['search_query']) && strlen($_POST['search_query']) >= 4 && $page == 1){
		$search_query = urldecode($_POST['search_query']);
		$data =  $API->get_search_results($search_query);
	}
	
	elseif($_POST['search_query'] == '' && $_POST['tag'] == ''){
		$data = $API->get_page($page);
	}
	
	else{
		$json_empty = array();
		$data = json_encode($json_empty);
	}

	//print_r($data);

	// Decodiere das Ergebnis als JSON-Objekt
	$data = json_decode($data, true);

	// Überprüfen Sie, ob das JSON-Objekt gültig ist
	if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
		echo 'Ungültige JSON-Datei!';
		exit();
	}


	// Ausgabe des JSON-Objekts
	for($i = 0; $i < count($data); $i++){

	echo "<div class='item'>";
		echo "<div class='content'>";
		if($data[$i]['photos'] != NULL){
			echo "<div class='image'>";
			echo "<div class='gallery".$i."'>";
			
			for($p = 0; $p < count($data[$i]['photos']); $p++) {
				if($p == 0){
					echo "<a class='image_single' href='/image.php?image=".$data[$i]['photos'][$p]."' data-lightbox='set".$data[$i]['id']."'><img src='/image.php?image=".$data[$i]['photos'][$p]."'></a>";
				
					$gallery_start = "image.php?image=".$data[$i]['photos'][$p];
				}
				else{
					echo "<a style='display:none;' class='image' href='/image.php?image=".$data[$i]['photos'][$p]."' data-lightbox='set".$data[$i]['id']."'><img src='/image.php?image=".$data[$i]['photos'][$p]."'></a>";
				}
			}
			echo "</div>";	
			echo "</div>";
		}
		else{
			echo "<div class='image_default'><img src='/public/images/default.jpg' alt='Image'></div>";
			$gallery_start = '';
		}

		// Timestamp des zu überprüfenden Ereignisses
		$event_timestamp = $data[$i]['post_date'];
		$current_timestamp = time();
		$diff_seconds = $current_timestamp - $event_timestamp;
		$diff_hours = $diff_seconds / 3600;
		// Code ausführen, wenn der Unterschied weniger als 24 Stunden beträgt
		if ($diff_hours < 24) {
			
			if($gallery_start !== ''){
				echo "<a class='new' href='".$gallery_start."' data-lightbox='set".$data[$i]['id']."'><img src='/public/images/new.svg' alt='NEW'></a>";
			}
			else{
				echo "<span class='new'><img src='/public/images/new.svg' alt='NEW'></span>";
			}
		}

		//$date = date('d.m.Y H:i', $data[$i]['post_date']);
		$date = date('d.m.Y', $data[$i]['post_date']);
			
		echo "<div class='profile'>";
			
		$seller_id = substr($data[$i]['seller_id'], 1);
			
		?>	
			
		<div class='profile_picture' data-sellerid="<?=$seller_id?>">
		
		<?php
		
		if($data[$i]['seller_profile_picture'] == 'user_default.png'){
			echo "<img src='/public/images/user_default.png'>";
			}
		else{
			echo "<img src='/image.php?image=".$data[$i]['seller_profile_picture']."'>";
		}
		echo "</div>";
		
		//$trustcheck = 'tustcheck';
		$trustcheck = '';
		?>	
			<div data-sellerid="<?=$seller_id?>" class='profile_handle <?=$trustcheck?>'><?=$data[$i]['seller_handle']?></div>
			<div class='date'><?=$date?></div>

			<div class="menu">
				<div class="menu_icon"><i class="fa-solid fa-ellipsis-vertical"></i></div>
				<div class="menu_overlay">
					<a class='tg_link' href='https://t.me/satoshiskleinanzeige/<?=$data[$i]['message_id']?>' target='_blank'><i class='fa-brands fa-telegram'></i> in Telegram Öffnen</a>
					<a class='tg_link' href='https://telegram.me/JJ21420' target='_blank'><i class="fa-solid fa-skull-crossbones"></i> Dieses Inserat melden</a>
				</div>
			</div>
		
		<?php
		
		echo "</div>";
		
		$caption = $data[$i]['caption'];
		
		$caption = nl2br($data[$i]['caption'], false);
		
		if (preg_match_all('/https:\/\/\S+/', $caption, $matches)) {
				
			foreach ($matches[0] as $url) {

				$short_url = substr($url, 0, 30) . '...';
				
				$link = '<a href="' . $url . '" target="_blank">' . $short_url . '</a>';
				$caption = str_replace($url, $link, $caption);
			}
		}
		
		?>
		<div class="description">
			<div class="dreadmore">
				<?=$caption?>
			</div>
			<button class="readmore" type="button" data-drm-toggler>mehr anzeigen <i class="fa-solid fa-chevron-down"></i></button>
		
		</div>
		<?php
		
			if($data[$i]['tags'] != NULL){
			echo "<div class='tags'>";
			for($t = 0; $t < count($data[$i]['tags']); $t++) {
				
				$tag = $data[$i]['tags'][$t];
				
				$tag_wrapped = substr($tag, 1);

				echo "<a href='/tag/".$tag_wrapped."'>".$tag."</a>";
			}
			echo "</div>";
		}
		echo "</div>";
		
		echo "<div class='footer'>";
		
		if(!empty($data[$i]['price_in_sats'])){
			echo "<div class='price'>";
			//echo "<div class='price_sats'>".$data[$i]['price_in_sats']." <i class='sats'>s</i></div>";
			
			echo "<div class='price_sats'>".number_format($data[$i]['price_in_sats'], 0, '.', '.')." <i class='sats'>s</i></div>";
		
			if(isset($bitcoin_price)){
				$eur_value = $data[$i]['price_in_sats'] / 100000000 * $bitcoin_price;
				echo "<div class='price_eur'>EUR: ".round($eur_value, 2)." €</div>";
			}
			echo "</div>";
		}
		
		if(isset($data[$i]['seller_handle'])){
			$sellerlink = ltrim($data[$i]['seller_handle'], '@');
			echo "<a class='button' href='https://telegram.me/".$sellerlink."' target='_blank'>Nachricht schreiben</a>";
		}
		
		echo "</div>";
		
	echo "</div>";

	}
  
?>
