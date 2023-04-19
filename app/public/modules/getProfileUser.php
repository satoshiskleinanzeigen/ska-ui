<h2>Anbieter Information</h2>

<?php

	if ($_POST['sellerid'] > 0) {	
		echo "Anbieter ID: ".$_POST['sellerid'];	
	} else{
		echo "Benutzer wurde nicht gefunden.<br />(Angebot wurde über TG inseriert!)";
	}
	

?>