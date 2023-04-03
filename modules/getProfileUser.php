<h2>Anbieter Information</h2>

<?php

	if ($_POST['sellerid'] > 0) {	
		echo "Aktiv seit: dd.mm.yyyy<br />";
		$sellerid = $_POST['sellerid'];
		echo "Anbieter ID: ".$sellerid;	
	} else{
		echo "Benutzer wurde nicht gefunden.<br />(Angebot wurde über TG inseriert!)";
	}
	

?>