</div>
</div>

<div id="pageoverlay">
	<div class="box">
		<div id="modal_content"></div>
	</div>
</div>

<footer>
	<div class='container'>
			<a href="https://de.wikipedia.org/wiki/Satoshi_Nakamoto" target="_blank" rel="noopener">© Satoshi Nakamoto</a> | <a href="/ci-leitfaden">CI-Leitfaden</a> | <a href="/spenden">Spenden</a> 
	</div>
</footer>

	<script src="/public/js/menu.js"></script>

	<script>

		/*--- Benutzer Navigation ---*/
		$(document).ready(function(){
		  var timeoutId;

		  // Öffnet das Menü, wenn der Klick auf ".usermenu_icon" erfolgt
		  $('.usermenu_icon').click(function(){
			$(this).siblings('.usermenu_overlay').fadeToggle();
			$(this).toggleClass("open");
		  });
		  
		  // Verbirgt das Menü, wenn der Mauszeiger das Menü verlässt
		  $('.usermenu_overlay').on('mouseleave', function() {
			// Verzögerung um 2 Sekunden, bevor das Menü ausgeblendet wird
			timeoutId = setTimeout(() => {
			  $(this).fadeOut();
			}, 2000);
		  });
		  
		  // Stoppt das Verstecken des Menüs, wenn der Mauszeiger zurückkehrt, bevor das Menü ausgeblendet wird
		  $('.usermenu_overlay').on('mouseover', function() {
			clearTimeout(timeoutId);
		  });
		});


		$(document).ready(function() {
			// Suchoptionen
			$('.search').click(function() {
				$('.search_options').slideToggle(600, 'swing');
			});
			
			$('#close_modal, #pageoverlay').on('click', function(){
				$('#pageoverlay').removeClass("showcontent");
			});
		});
	
    </script>
	
	
</body>
</html>