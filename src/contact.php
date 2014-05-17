<?php
session_start();

function cleanInput($input) {
	$search = array(
		'@<script[^>]*?>.*?</script>@si',   // Strip out javascript
		'@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
		'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
		'@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
	);

	$output = preg_replace($search, '', $input);
	return $output;
}


if(empty($_POST)) {
	
} else {
	$ref = $_SERVER['HTTP_REFERER'];
	if (	strpos($ref, 'frozenrockets.nl') === false 
		&&  strpos($ref, 'petervangrieken.nl') === false
		&&  strpos($ref, 'frozenrockets.local') === false) {
	   die('Error: Wrong domain referer.');
	}


	$status= -1;
	$preventSend= false;

	if (isset($_SESSION['lastsend'])) {
		echo time();
		echo "-".$_SESSION['lastsend'];
		if(time() - $_SESSION['lastsend'] < 60) {
			echo " : Yes";
			$status= -2;
			$preventSend= true;
		} else {
			unset($_SESSION['lastsend']);
		}
	}


	$name= cleanInput($_POST['name']);
	$email= cleanInput($_POST['email']);
	$message= cleanInput($_POST['message']);

	if(trim($name) === "") {
		$status= 0;
		$preventSend= true;
	} 
	if(trim($email) === "") {
		$status= 0;
		$preventSend= true;
	} 
	if(trim($message) === "") {
		$status= 0;
		$preventSend= true;
	}


	if(!$preventSend) {
		$_SESSION['lastsend']= time();

		$mailBody= "Van: ".$name."\n E-mail: ".$email."\n Bericht: \n".$message;

		if( mail('hi@frozenrockets.nl', 'Mail van contactformulier', $mailBody )) {
			$status= 1;
		} else {
			$status= -1;
		}	
	}
}
?>
<!--(bake html_includes/head.html _section="contact")-->

	<h1>Contact</h1>


	<div class="grid">
		<div class="grid__column grid__column--2of3">
			<form method="POST" action="contact.php">

	<?php
	      if ($status === 0) {
	        echo '<p class="status error">Oeps! Het lijkt er op dat niet alle velden ingevuld zijn.</p>';
	      } elseif ($status === -1) {
	        echo '<p class="status error">Oeps! Er ging iets mis aan mijn kant. Kunt u het nog eens proberen?</p>';
	      } elseif ($status === -2) {
	        echo '<p class="status error">Je stuurt 2 berichten in korte tijd (binnen 1 minuut), om spam tegen te gaan mag dat helaas niet.</p>';
	      } elseif ($status === 1) {
	      	echo '<p class="status success">Je bericht is verzonden. Ik zal zo snel mogelijk contact opnemen.</p>';
	      	$hideForm= true;
	      }

	      if(!$hideForm) {
	?>
				<p>
					De snelste manier om contact op te nemen, is door middel van onderstaand formulier.
					Ik bel of mail zo snel mogelijk terug.
				</p>

				<label>
					<span>Naam</span>

					<input type="text" name="name" id="contact-name" pattern="^[a-zA-Z-\' ]{3,50}$" required value="<?php echo $name; ?>">
					<span class="validation"></span>
				</label>

				<label>
					<span>E-mailadres</span>

					<input type="email" name="email" id="contact-email" pattern="[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,5}" required value="<?php echo $email; ?>">
					<span class="validation"></span>
				</label>

				<label>

					<span>Uw bericht</span>

					<textarea name="message" id="contact-message" required  value="<?php echo $$_POST['message']; ?>"></textarea>

				</label>


				<input type="submit" value="Verstuur" class="button button--secondary js-submit-contact">


	<?php
			}
	?>
			</form>

		</div>

		<div class="grid__column grid__column--1of3">

			<h2>Adres</h2>

			<p>
				Binckhorstlaan 36<br>
				2516 BE Den Haag
			</p>

			<h2>E-mail</h2>

			<p>
				<a href="mailto:hi@frozenrockets.nl">hi@frozenrockets.nl</a>
			</p>

			<h2>Social media</h2>

			<p>
				<a href="http://nl.linkedin.com/in/petervangrieken">LinkedIn</a><br>
				<a href="http://twitter.com/petervangrieken">Twitter</a>
			</p>
		</div>
	</div>

<!--(bake html_includes/footer.html)-->