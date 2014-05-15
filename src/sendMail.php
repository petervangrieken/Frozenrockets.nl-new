<?php
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


$name= cleanInput($_POST['name']);
$email= cleanInput($_POST['email']);
$message= cleanInput($_POST['message']);

if(trim($name) !== "") {
	return 0;
} 
if(trim($email) !== "") {
	return 0;
} 
if(trim($message) !== "") {
	return 0
}

$mailBody= "Van: ".$name."\n E-mail: ".$email."\n Bericht: \n".$message;

if( mail('hi@frozenrockets.nl', 'Mail van contactformulier', $mailBody )) {
	echo 1;
} else {
	echo -1;
}
?>