<!DOCTYPE html>
<html>
<head>

	<title>Satoshis Kleinanzeigen</title>
	<meta http-equiv='content-type' content='text/html; charset=utf-8'>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport" />
	<meta name="description" content="Fiat war gestern. Finde tolle Angebote und bezahle mit Bitcoin! Kaufe oder verkaufe Neues & Gebrauchtes auf Satoshis Kleinanzeigen. Hier wird Bicoin Lightning akzeptiert!">
	<meta name="keywords" content="Kleinanzeigen, Satsoshi, E-Bay, Alternative">
	<meta name="author" content="Satoshi Nakamoto">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link rel="apple-touch-icon" sizes="180x180" href="/public/images/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/public/images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/public/images/favicon-16x16.png">
	<link rel="manifest" href="/site.webmanifest">
	
	  <!-- tag plugin-->
	<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
	<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
	
	<link rel='stylesheet' href='/public/css/style.css' media='all'>
	<link rel='stylesheet' href='/public/css/grid.css' media='all'>
	<link rel='stylesheet' href='/public/css/form.css' media='all'>
	<link rel="stylesheet" href="/public/css/menu.css" media="all">
	<link rel='stylesheet' href='/public/css/item.css' media='all'>
	<link rel="stylesheet" href="/public/css/lightbox.min.css">
	<link rel='stylesheet' href='/public/css/dreadmore.min.css' media='all'>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
	
	<script language='javascript' type='text/javascript'  src='/public/js/jquery-3.6.3.min.js'></script>
	
</head>
<body>

<header>

	<div class="header_line1">
		<div class='container'>
			<a href="/" class="logo">
				<img src="/public/images/logo_white.svg"/>
			</a>

			<?php
				include ("templates/menu.php"); 
			?>

			<div class="useraction">

				<a class="button small" href="/inserieren"><i class="fa-solid fa-tag"></i> Inserieren</a>
				<span class="button small search"><i class="fa-solid fa-magnifying-glass"></i></span>
				
			<div class="usermenu">
				<div class="usermenu_icon">
				
				<?php 
					if(isset($_SESSION['photo_url'])){
						echo "<img src='" .$_SESSION['photo_url']. "'>";
					} else {
						echo "<img src='/public/images/user_default.png'>";
					}
				?>

				</div>
				<div class="usermenu_overlay">
					<div class="usermenu_overlay_content">
						<?php if(has_tg_user_session()){ ?>
							<strong>Hallo <?php echo $_SESSION['username']; ?></strong>
							<br /><br />
							<a class="button small" href="/useritems">Meine Inserate</a>
							<br />
							<a class="button small gradient" href="/auth/logout">Logout</a>
						<?php }else{ ?>
							<a class="button small gradient" href="/auth/login">Login</a>
						<?php } ?>
					</div>
				</div>
			</div>
				
				

				
				
			</div>
		</div>
	</div>


	<div class="search_options">
			<div class='container'>
				<form id="search_form" method="POST" action="/suche" enctype="multipart/form-data">
					<input id="search_field" type="text" name="search_term" value="" placeholder="Was suchst du?">
					<span  onclick="search_form.submit()" class="button small gradient"><i class="fa-solid fa-magnifying-glass"></i> Suchen</span>
				</form>
		</div>
	</div>

</header>



	
<div class='container'>

<div class="main">