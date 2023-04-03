<?php require_once('templates/header.php'); ?>

<h1>Meine Inserate</h2>
<div class="grid-1-2">
	<div class="content_box">
		<h2 class=" text-center fw-bold">Meine Bewertungen!</h2>
					
	</div>
	<div class="grid">
		<?php require_once('modules/getItemsUser.php'); ?>
	</div>
</div>
	
<script>
	$(document).ready(function() {
		
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
		
		});
	</script>
<script src="/public/js/readmore.min.js"></script>
<script src="/public/js/lightbox.min.js"></script>

<?php require_once('templates/footer.php'); ?>