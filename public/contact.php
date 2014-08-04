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
		if(time() - $_SESSION['lastsend'] < 60) {
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
<!DOCTYPE html>
<html lang="nl">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Neem contact op met Frozen Rockets</title>

	<link rel="stylesheet" href="./css/style.min.css">
	<link rel="stylesheet" type="text/css" href="//cloud.typography.com/7599872/689164/css/fonts.css" />
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
</head>

<body class="page page--contact">


	<header role="banner" class="sidebar screen--contact">

		<a href="./index" class="borderless logo"><img src="./images/fr-logo.svg" alt="Frozen Rockets" id="main-logo-image"></a>

		<nav role="navigation">
			<ol class="mainnav">
				<li class="mainnav__item"><a href="./projecten">Projecten</a></li>
				<li class="mainnav__item"><a href="./over-frozen-rockets">Over Frozen Rockets</a></li>
				<li class="mainnav__item"><a href="http://blog.frozenrockets.nl">Artikelen</a></li>
				<li class="mainnav__item"><a href="./contact.php">Contact</a></li>
			</ol>
		</nav>
	</header>


	<main role="main">

	<h1>Contact</h1>


	<div class="grid">
		<div class="grid__column grid__column--2of3">
			<form method="POST" action="contact.php?send">

	<?php
	      if ($status === 0) {
	        echo '<p class="status error">Oeps! Het lijkt er op dat niet alle velden ingevuld zijn.</p>';
	      } elseif ($status === -1) {
	        echo '<p class="status error">Oeps! Er ging iets mis aan mijn kant. Kunt u het nog eens proberen?</p>';
	      } elseif ($status === -2) {
	        echo '<p class="status error">U stuurt 2 berichten in korte tijd (binnen 1 minuut), om spam tegen te gaan mag dat helaas niet.</p>';
	      } elseif ($status === 1) {
	      	echo '<p class="status success">Uw bericht is verzonden. Ik zal zo snel mogelijk contact opnemen.</p>';
	      	$hideForm= true;
	      }

	      if(!$hideForm) {
	?>
				<p>
					Als u onderstaand formulier invult, nemen wij zo snel mogelijk contact op.
				</p>

				<label>
					<span>Naam</span>

					<input type="text" name="name" id="contact-name" pattern="^[a-zA-Z-\' ]{3,50}$" required value="<?php echo $name; ?>"
					title="Vul uw naam in" x-moz-errormessage="Vul uw naam in">
					<span class="validation"></span>
				</label>

				<label>
					<span>E-mailadres</span>

					<input type="email" name="email" id="contact-email" pattern="[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,5}" required value="<?php echo $email; ?>"
					title="Vul uw geldige e-mailadres in" x-moz-errormessage="Vul uw geldige e-mailadres in">
					<span class="validation"></span>
				</label>

				<label>

					<span>Uw bericht</span>

					<textarea name="message" id="contact-message" required  value="<?php echo $$_POST['message']; ?>" title="Vul uw bericht in" x-moz-errormessage="Vul uw bericht in"></textarea>

				</label>


				<input type="submit" value="Verstuur" class="button button--secondary js-submit-contact">

	<?php
			}
	?>
			</form>

		</div>

		<div class="grid__column grid__column--1of3">

			<h2>Frozen Rockets BV</h2>

			<p>
				Binckhorstlaan 36<br>
				2516 BE Den Haag
			</p>

			<p>
				KvK: 54419069
			</p>

			<h2>E-mail</h2>

			<p>
				<a href="mailto:hi@frozenrockets.nl">hi@frozenrockets.nl</a>
			</p>

			<h2>Social media</h2>

			<p>
				<a href="http://nl.linkedin.com/in/petervangrieken"><span>LinkedIn</span></a>
				<a href="http://twitter.com/petervangrieken"><span>Twitter</span></a>
			</p>
		</div>
	</div>

  </main>


<!-- Piwik -->
<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u=(("https:" == document.location.protocol) ? "https" : "http") + "://frozenrockets.nl/piwik/";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', 1]);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0]; g.type='text/javascript';
    g.defer=true; g.async=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();

</script>
<noscript><p><img src="http://frozenrockets.nl/piwik/piwik.php?idsite=1" style="border:0;" alt="" /></p></noscript>
<!-- End Piwik Code -->
</body>
</html>