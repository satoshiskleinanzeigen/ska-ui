<?php

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

	if (!empty($API->get_user_items())){
		$data = $API->get_user_items();
	}
	
	else {
		$json_empty = array();
		$data = json_encode($json_empty);
	}

	// Decodiere das Ergebnis als JSON-Objekt
	$data = json_decode($data, true);

	// Überprüfen Sie, ob das JSON-Objekt gültig ist
	if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
		echo 'Ungültige JSON-Datei!';
		exit();
	}

	// Ausgabe des JSON-Objekts
	for($i = 0; $i < count($data); $i++){

	echo "<div class='item ".$data[$i]['pub_state']."'>";
		echo "<div class='content'>";
		if($data[$i]['photos'] != NULL){
			echo "<div class='image'>";
			echo "<div class='gallery".$i."'>";
			
			for($p = 0; $p < count($data[$i]['photos']); $p++) {
				if($p == 0){
					echo "<a class='image_single' href='/image.php?image=".$data[$i]['photos'][$p]."' data-lightbox='set".$data[$i]['id']."'><img src='/image.php?image=".$data[$i]['photos'][$p]."' alt='Image'></a>";
				
					$gallery_start = "image.php?image=".$data[$i]['photos'][$p];
				}
				else{
					echo "<a style='display:none;' class='image' href='/image.php?image=".$data[$i]['photos'][$p]."' data-lightbox='set".$data[$i]['id']."'><img src='/image.php?image=".$data[$i]['photos'][$p]."' alt='Image'></a>";
				}
			}
			echo "</div>";	
			echo "</div>";
		}
		else{
			echo "<div class='image_default'><img src='/public/images/default.jpg' alt='Image'></div>";
			$gallery_start = '';
		}

		//##############
		
		$timestamp = $data[$i]['post_date'];
		
		// Aktuelle Zeit als DateTime-Objekt
		$now = new DateTime();

		// DateTime-Objekt für den Timestamp
		$timestamp_datetime = DateTime::createFromFormat('U', $timestamp);

		// Zeitdifferenz berechnen
		$diff = $now->diff($timestamp_datetime);

		//print_r($diff);

		// Verarbeiten der Zeitdifferenz
		if ($diff->h == 0) {
			// Differenz bis zu 24 Stunden
			$onlinetime = $diff->format('%i Minuten online');
		} 
		elseif ($diff->d < 1) {
			// Differenz bis zu 24 Stunden
			$onlinetime = $diff->format('%h Stunden %i Minuten online');
		} 
		
		
		elseif ($diff->d >= 1) {
			if($diff->format('%d') != '1'){
				$daylabel ="Tage";
			}
			else{
				$daylabel ="Tag";
			}
			// Differenz zwischen 24 Stunden und einer Woche
			$onlinetime = $diff->format('%d '.$daylabel.' %h Stunden online');
		} 
		//##############
		
		if ($data[$i]['pub_state'] == 'unpublished') {
			$onlinetime = "Inserat offline";
		}

		//$date = date('d.m.Y H:i', $data[$i]['post_date']);
		$date = date('d.m.Y', $data[$i]['post_date']);
			
		echo "<div class='profile'>";
			
		?>	

			<div class='date'><strong>Inseriert:</strong> <?=$date?></div>

			<div class='date state'><i class="fa-sharp fa-solid fa-circle"></i>&nbsp;&nbsp; <?=$onlinetime?></div>

			<div class="menu">
				<div class="menu_icon"><i class="fa-solid fa-ellipsis-vertical"></i></div>
				<div class="menu_overlay">
				
				<?php
					if ($data[$i]['pub_state'] == 'published') {
				?>	
				
					<a class='tg_link' href='https://t.me/satoshiskleinanzeige/<?=$data[$i]['message_id']?>' target='_blank'><i class='fa-brands fa-telegram'></i> in Telegram Öffnen</a>
					<!--<a class='tg_link' href='https://telegram.me/JJ21420' target='_blank'><i class="fa-solid fa-trash"></i> Inserat löschen</a>-->
					
				<?php
					}
				?>	
					
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
		
		echo "</div>";
		
	echo "</div>";

	}

?>
