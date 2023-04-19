<?php require_once('templates/header.php'); ?>

<?php
	//Enthält den Tag:
	if(isset($tag)){
		echo "<h1>Enthält den Tag: ".$tag."</h1>";
		$tag = $tag;
		$search_query = '';
	}
	//Suchergebnisse zu: 
	elseif(isset($_POST['search_term']) && strlen($_POST['search_term']) >= 4){
		echo "<h1>Suchergebnisse zu: ".$_POST['search_term']."</h1>";
		$search_query = $_POST['search_term'];
		$tag = '';
	}
	//Alle Einträge
	else{
		echo "<h1></h1>";
		$tag = '';
		$search_query = '';
	}
 ?>

	<script>
		$(document).ready(function() {
			var track_page = 1; // Startseite
			var loading  = false; // Keine Inhalte werden geladen
			var search_query = '<?php echo $search_query; ?>'; // Suchbegriffe
			var tag = '<?php echo $tag; ?>'; // Suchbegriffe

			load_contents(track_page); // Inhalte für die Startseite laden

			$(window).scroll(function() { // Wenn gescrollt wird
				var content_height = $(document).height(); // Höhe des Dokuments
				var scroll_height = $(window).height() + $(window).scrollTop() + 400; // Höhe des gescrollten Bereichs

				$('.content_height').text(content_height);
				$('.scroll_height').text(Math.trunc(scroll_height));

				if (scroll_height > content_height && loading == false) { // Wenn das Ende erreicht wurde und keine Inhalte geladen werden
					loading = true; // Inhalte werden geladen
					track_page++; // Nächste Seite
					load_contents(track_page); // Inhalte für die nächste Seite laden
				}
			});

			function load_contents(track_page) { // Funktion zum Laden der Inhalte
				$.ajax({ // AJAX-Aufruf
					url: '/modules/getItemsAjax.php',
					type: 'POST',
					data: {page:track_page, search_query:search_query, tag:tag},
					beforeSend: function() { // Vor dem Aufruf
						$('.loading').show(); // Lade-Animation anzeigen
					},
					
					success: function(response) { // Nach dem Aufruf
						$('.loading').hide(); // Lade-Animation ausblenden
						if (response.trim().length == 0) { // Wenn keine weiteren Inhalte zurückgegeben wurden
							
							if (loading == false) {
								$(".noresults").css("display", "block");
							}
							loading = true; // Keine weiteren Inhalte werden geladen
							
						} else { // Wenn weitere Inhalte zurückgegeben wurden
							$('.grid').append(response); // Inhalte der Seite hinzufügen
							loading = false; // Inhalte wurden geladen
						}
						
						// Readmore wird ausgeführt
						const dReadMore = new DReadMore();

						window.addEventListener('resize', function() {
							dReadMore.forEach(function(description) {
								description.update();
							});
						});

						window.onload = function() {
							dReadMore.forEach(function(description) {
								description.update();
							});
						 }
					     // Readmore ENDE		
						
					}
				});
			}
		});
	</script>

<style>
	.loading {display:none;text-align:center;}
</style>
<!--
<div style="position:fixed; right: 0px; bottom: 0px; background: green; padding: 1rem; z-index: 999;">
	<div class="content_height"></div>
	<div class="scroll_height"></div>
</div>
-->
	<div class='noresults' style="display:none;">
		<strong>Keine Ergebnisse gefunden!</strong>
	</div>

	<div class="grid">
		<!-- Inhalte der Startseite -->
	</div>
	<br /><br />
	<div class="loading">
		<div class="lds-ring"><div></div><div></div><div></div><div></div></div>
	</div>
	<br /><br />

<script src="/public/js/readmore.min.js"></script>
<script src="/public/js/lightbox.min.js"></script>

<script>
	// Modal Formular-Errors / UserProfile
	$(document).on('click', '.profile_picture, .profile_handle', function() {

		var sellerid = $(this).data('sellerid');
		
		$.ajax({ // AJAX-Aufruf
			url: '/modules/getProfileUser.php',
			type: 'POST',
			data: {sellerid:sellerid},
	
			success: function(response) { // Nach dem Aufruf
			
				response = response + '<br /><br /><a id="close_modal" class="button gradient small">Schließen</a>';
				$('#modal_content').html(response); 
				
			}
		});
		
		$('#pageoverlay').addClass("showcontent");
		
	});
</script>

<?php require_once('templates/footer.php'); ?>