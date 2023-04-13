<?php require_once('templates/header.php'); ?>

<?php

# We need the Client to get a list of available tags for autocompletion
require_once('classes/class_sk_api_client.php');
$API = new SK_API();
$available_tags = $API->get_tag_list();

# SET TARGET URL FOR FORM
$form_target_url = '/newitem_submit';


//get current btc price in eur
$btcdata =  $API->get_btc_in_eur();
$json = json_decode($btcdata, true);
if(isset($json['bitcoin']['eur'])){
	$bitcoin_price = $json['bitcoin']['eur'];
}

?>
<div class="page">

	<h1>Ein Inserat einreichen.</h1>
	<div class="grid-1-2">
		<div>
			<div class="content_box">
				<h2 class=" text-center fw-bold">So funktioniert's!</h2>
				Bitte lese den folgenden ganzen Text durch!

				<ul>
					<li>Ein oder mehrere Bild auswählen</li>
					<li>Aussagekräftigen Titel eingeben</li>
					<li>Mehrere Tags um dein Inserat besser finden zu können</li>
					<li>Einen Preis in Satoshi</li>
				</ul>

<p>Admins leiten die Nachricht in den <a href="https://t.me/+Qovt3WKA6z00ZTBi" target="_blank" rel="noopener">Anzeigen Kanal</a> weiter.</p>

<p>Ist deine Anzeige verkauft? antworte auf diese mit 'verkauft', dann können Admins sie löschen.</p>
				
			</div>
		</div>
		<div>
			<div class="content_form">
			<h2 class=" text-center fw-bold">Inserat einreichen</h2>
          <!-- While submitting (only visible on submit)-->
          <div id="stand_by">
            <div class="alert alert-info text-center" role="alert">
            <div class="lds-ring"><div></div><div></div><div></div><div></div></div><br />Dein Inserat wird übermittelt.
            </div>
          </div>

          <!-- Thanks for submitting (only visible on success)-->
          <div id="thanks_for_submit">
            <div class="alert alert-success text-center" role="alert">
              Vielen Dank. Dein Inserat wurde übermittelt.
            </div>
            <p>
              Es wird von den Administratoren im Kanal
              <a href="https://t.me/+Qovt3WKA6z00ZTBi" target="_blank"><strong>Sats Kleinanzeigen Inserieren & Chat</strong></a>
              freigegeben, wenn es in Ordnung ist. Schau dort vorbei, um zu sehen, ob es noch Fragen gibt.
            </p>
          </div>

          <!-- Form to submit (not visible on success and while submitting)-->
          <form id="submit_to_telegram" method="POST" target="submit_frame" action="<?php echo $form_target_url; ?>" enctype="multipart/form-data">
            <div class="form-group">
              <label for="telegram_handle">Telgeram Benutzername *</label>
              <input type="text" class="form-control countchars" id="telegram_handle" name="telegram_handle" value="<?php echo '@'.$_SESSION['username'];?>" readonly>
            </div>
            <div class="form-group">

			
				<label for="picture-list">
					<span class="tooltip"><i class="fa-solid fa-circle-info"></i>
						<span class="tooltiptext">Lade bis zu 20 Bilder mit einer maximalen Größe von 12 MB hoch.</span>
					</span> Bild(er)
				
				</label>

				<!-- loading symbol when image is processed by file input-->
				<img src="/img/loader.gif" class="ml-auto mr-auto" id="pic_loader"> 

				<!-- container for file input replacements, containing one input to start with-->
				<ul class="attachments input-group input-lg mt-0" id="picture-list">
					<li id="label-conainer_1">
					  <label for="uploade_image_1" class="custom-file-upload position-relative" id="upload-label_1">
						<div class="plus" id="upload-icon-replacement_1">
						  <i class="fas fa-plus"></i>
						</div>
						<div class="delete d-none" data-id="_1" id="remove-icon-replacement_1">
						  <i class="fas fa-trash"></i>
						</div>
						<img id="uploaded_image_1" src="public/images/upload_default.png" class="" />
					  </label>
					  <input id="uploade_image_1" class="picture-input" type="file" data-form="upload-form" name="uploade_image_1" data-this_id="_1" accept="image/*" />
					</li>
				</ul>
           
            </div>
            <!-- more form input elements-->
            <div class="form-group">

				<label for="text_desc">
					<span class="tooltip"><i class="fa-solid fa-circle-info"></i>
						<span class="tooltiptext">Mit einem aussagekräftigen Titel verkaufst du besser.</span>
					</span> Bezeichnung * (was verkaufst du?)
				</label>
				<input type="text" class="form-control countchars" id="text_desc" name="text_desc" placeholder="Raspberry 4">
            </div>
            <div class="form-group">
				<label for="text">
					<span class="tooltip"><i class="fa-solid fa-circle-info"></i>
						<span class="tooltiptext">Mit einer ausführlichen Beschreibung verkaufst du besser.</span>
					</span> Text *
				</label>
				<textarea class="form-control countchars" maxlength="800" name="text" id="text" rows="4" cols="80" placeholder="Inserat Text" style="margin-top: 0px; margin-bottom: 0px; height: 246px;"></textarea>
            </div>
            <div class="form-group">
				<label for="tags">
					<span class="tooltip"><i class="fa-solid fa-circle-info"></i>
						<span class="tooltiptext">Über passende Tags kein dein Inserat besser gefunden werden.</span>
					</span> Tags *
				</label>
              <textarea class="form-control countchars" maxlength="800" name="tags" id="tags" rows="4" cols="80" placeholder="#technik #handgemacht" style="margin-top: 0px; margin-bottom: 0px; height: 246px;"></textarea>
            </div>
            <div class="form-group">
				<div class="price-group">
					<div>
						<label for="sats">Angebots Typ *</label>
						<select class='select' name='pricetype' id='pricetype'>
							<option selected value=''>Bitte wählen</option>
							<option value='Beginnt bei'>Beginnt bei</option>
							<option value='Festpreis'>Festpreis</option>
							<option value='Verhandlungsbasis'>Verhandlungsbasis</option>
						</select>
					</div>
					<div>
						<label for="sats">Preis in Sats *</label>
						<input type="number" class="form-control countchars" placeholder="10000" aria-label="sats" name="sats" id="sats" aria-describedby="basic-addon1">
						<div id="eurovalue" class="price_eur"></div>
					</div>
					<div>
						<label for="sats">Versand</label>
						<select class='select' name='delivery' id='delivery'>
							<option selected value=''>Bitte wählen</option>
							<option value='inklusive Versand'>inklusive Versand</option>
							<option value='zuzüglich Versand'>zuzüglich Versand</option>
						</select>
					</div>
				</div>
			</div>
            <div class="send-button">
              <button type="submit" class="button gradient btn-primary btn-round w-100 shadow  btn-lg" data-form="submit_to_telegram" ; data-target-url="/newitem_submit" data-success-url="/newitem_success">Absenden</button>
            </div>
          </form>
          <span class="char_count">
          </span>
		</div>
	</div>
</div>

</div>
  <!-- hidden iframe the form posts to. we use this to capture the json response-->
  <iframe name="submit_frame" id="submit_frame" frameBorder="0" src="" width="0" height="0">
  </iframe>

  </script>
  <!-- bootstrap-->




  <script type="text/javascript">

	$(document).ready(function() {
	  $('#sats').on('input', function() {
		var inputWert = $(this).val();
		if (inputWert < 5) {
		  $('#eurovalue').empty(); // Wenn das Eingabefeld leer ist, wird das Div-Element geleert
		} else {
		  var ergebnis = parseFloat(inputWert) / 100000000 * <?php echo $bitcoin_price; ?>;
		  ergebnis = ergebnis.toFixed(2);
		  $('#eurovalue').text('EUR: ' + ergebnis + ' €');
		}
	  });
	});

    //setup page
    //hide elements on page load
    $('#thanks_for_submit').hide();
    $('#stand_by').hide();
    $('#pic_loader').hide();

    //function to count characters in form inputs (telegram caption can have only 1024 characters)
    function count_character() {
      let char_count = $('#telegram_handle').val().length + $('#text_desc').val().length + $('#sats').val().length + $('#text').val().length + $('#tags').val().length;
      console.log(char_count);
      return char_count;
    }

    $(document).on('keyup', '.countchars', function() {
      $('#char_count').html(count_character());
    });

    //tag plugin options
    var input = document.querySelector('textarea[name=tags]'),
      tagify = new Tagify(input, {
        enforceWhitelist: false,
        delimiters: " ",
        trim: false,
        whitelist: <?php echo $available_tags; ?>,
        callbacks: {
          add: console.log, // callback when adding a tag
          remove: console.log // callback when removing a tag
        }
      });
    
    //
    //picture upload buttons
    //
    $(document).on('change', '.picture-input', function(e) {
      e.preventDefault();
      $('#pic_loader').show();
      let this_id = $(this).data('this_id');
      let input = this;
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
          $('#uploaded_image' + this_id)
            .attr('src', e.target.result)
            //.width(100)
            //.height(100)
			;
          $('#uploaded_image' + this_id).removeClass('d-none');
          $('#remove-icon-replacement' + this_id).removeClass('d-none');
          $('#upload-icon-replacement' + this_id).addClass('d-none');
          $('#upload-label' + this_id).removeAttr('for');
          add_upload_button();
          $('#pic_loader').hide();
        };
        reader.readAsDataURL(input.files[0]);
      }
    });
    //picture remove button
    $(document).on('click', '.delete', function(e) {
      let id_to_remove = $(this).attr('data-id');
      $('#label-conainer' + id_to_remove).remove();
    });

    //picture helper functions
    function add_upload_button() {
      let id = makeid(10);
      let html = $('#custom_file_inbut').html();
      let result = html.replaceAll("_1", id);
      $('.attachments').append(result);
    }

    //picture helper function to generate ids for the dynamically added divs
    function makeid(length) {
      let result = '';
      const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
      const charactersLength = characters.length;
      let counter = 0;
      while (counter < length) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
        counter += 1;
      }
      return result;
    }
  </script>

  <!-- template for upload button -->
  <template id="custom_file_inbut">
    <li id="label-conainer_1">
      <label for="uploade_image_1" class="custom-file-upload position-relative" id="upload-label_1">
        <div class="plus" id="upload-icon-replacement_1">
          <i class="fas fa-plus">
          </i>
        </div>
        <div class="delete d-none" data-id="_1" id="remove-icon-replacement_1">
          <i class="fas fa-trash">
          </i>
        </div>
        <img id="uploaded_image_1" src="public/images/upload_default.png" class="" />
      </label>
      <input id="uploade_image_1" class="picture-input" type="file" data-form="upload-form" name="uploade_image_1" data-this_id="_1" accept="image/*" />
    </li>
  </template>

  <script>
    //
    // handle submitting form
    //
    // by catching the submit event of the form

    $(document).on('submit', '#submit_to_telegram', function() {
      // show "submitting" message
      $('#stand_by').show();
      $('#submit_to_telegram').hide();
    });

    //get respose by catching the load event of the iframe
    let myIframe = document.getElementById('submit_frame');
    myIframe.addEventListener("load", function() {
      msg = JSON.parse($("#submit_frame").contents().text());
      if (msg['success'] == false) {
        $('#stand_by').hide();
        $('#submit_to_telegram').show();
        let errors = '<h2>Upps. Da fehlt noch was.</h2><ul class="errorlist">';
        $.each(msg.errors, function(val) {
          errors = errors + '<li>' + msg.errors[val] + '</li>';
        });
        errors = errors + '</ul><a id="close_modal" class="button gradient small">OK</a>';

        $('#modal_content').html(errors);
		$('#pageoverlay').addClass("showcontent");
      }
      if (msg['success'] == true) {
        $('#submit_to_telegram').hide();
        $('#stand_by').hide();
        $('#thanks_for_submit').show();
      }
    });
	
  </script>

<?php require_once('templates/footer.php'); ?>