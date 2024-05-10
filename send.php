<!DOCTYPE html>
<html lang="zxx">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<!-- color of address bar in mobile browser -->
	<meta name="theme-color" content="#28292C">
	<!-- favicon  -->
	<link rel="shortcut icon" href="img/light/favicon.png" type="image/x-icon">
	<!-- bootstrap css -->
	<link rel="stylesheet" href="css/plugins/bootstrap.min.css">
	<!-- font awesome css -->
	<link rel="stylesheet" href="css/plugins/font-awesome.min.css">
	<!-- swiper css -->
	<link rel="stylesheet" href="css/plugins/swiper.min.css">
	<!-- fancybox css -->
	<link rel="stylesheet" href="css/plugins/fancybox.min.css">
	<!-- mapbox css -->
	<link href="css/plugins/mapbox-style.css" rel='stylesheet'>
	<!-- main css -->
	<link rel="stylesheet" href="css/style-light.css">

	<title>Maghji</title>

</head>

<body onLoad="setTimeout('delayedRedirect()', 5000)">

<?php

/* Validate User Inputs
==================================== */

// Name 
if ($_POST['firstName'] != '') {
	
	// Sanitizing
	$_POST['firstName'] = filter_var($_POST['firstName'], FILTER_SANITIZE_STRING);

	if ($_POST['firstName'] == '') {
		$errors .= 'Please enter a valid name.<br/>';
	}
}
else { 
	// Required to fill
	$errors .= 'Please enter your name.<br/>';
}

// Name 
if ($_POST['lastName'] != '') {
	
	// Sanitizing
	$_POST['lastName'] = filter_var($_POST['lastName'], FILTER_SANITIZE_STRING);

	if ($_POST['lastName'] == '') {
		$errors .= 'Please enter a valid name.<br/>';
	}
}
else { 
	// Required to fill
	$errors .= 'Please enter your name.<br/>';
}

// Email 
if ($_POST['email'] != '') {

	// Sanitizing 
	$_POST['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

	// After sanitization validation is performed
	$_POST['email'] = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
	
	if($_POST['email'] == '') {
		$errors .= 'Please enter a valid email address.<br/>';
	}
}
else {
	// Required to fill
	$errors .= 'Please enter your email address.<br/>';
}

// Phone 
if ($_POST['phone'] != '') {

	// Sanitizing
	$_POST['phone'] = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);

	// After sanitization validation is performed
	$pattern_phone = array('options'=>array('regexp'=>'/^\+{1}[0-9]+$/'));
	$_POST['phone'] = filter_var($_POST['phone'], FILTER_VALIDATE_REGEXP, $pattern_phone);
	
	if($_POST['phone'] == '') {
		$errors .= 'Please enter a valid phone number like: +363012345<br/>';
	}
}

// Message
if ($_POST['message'] != '') {

	// Sanitizing
	$_POST['message'] = filter_var($_POST['message'], FILTER_SANITIZE_STRING);
	
	if($_POST['message'] == '') {
		$errors .= 'Please enter a valid message.<br/>';
	}
}

// Continue if NO errors found after validation
if (!$errors) {	

	// Customer Details
	$customer_first_name = $_POST['firstName'];
	$customer_last_name = $_POST['lastName'];
	$customer_mail = $_POST['email'];
	$customer_phone = $_POST['phone'];	
	$customer_message = $_POST['message'];	
	
	/* Mail Sending
	==================================== */

	// Setup for site owner
	$to = "maghjiironscompany@gmail.com"; // Your email goes here	
	$subject = "Request";
	$headers = "From: Maghji <replyto@yourdomain.com>";	
	$message = "Request is arrived with the details below." . "\n\n";
	$message .= "CONTACT DATA" . "\n";
	$message .= "--\n";
	$message .= "First Name: " . $customer_first_name . "\n";
	$message .= "Last Name: " . $customer_last_name . "\n";
	$message .= "Email: " . $customer_mail . "\n";
	$message .= "Phone: " . $customer_phone . "\n\n";	
	$message .= "MESSAGE" . "\n";
	$message .= "--\n";
	$message .= $customer_message . "\n";
												
	// Send to site owner
	mail($to, $subject, $message, $headers);
	
	// Setup for the user
	$user = $_POST['email'];
	$usersubject = "Request confirmation";
	$usermessage = "Dear " . $customer_first_name . " " . $customer_last_name . "," . "\n\n" . "Thank you for contacting us. We will reply shortly." . "\n\n";
	$usermessage .= "Best Regards," . "\n";	
	$usermessage .= "Maghji Team";

	// Send to the user
	mail($user, $usersubject, $usermessage, $headers);

	// Success Page
	echo '<div id="success">';
	echo '<div class="icon icon-order-success svg">';
	echo '<svg width="72px" height="72px">';
	echo '<g fill="none" stroke="#02b843" stroke-width="2">';
	echo '<circle cx="36" cy="36" r="35" style="stroke-dasharray:240px, 240px; stroke-dashoffset: 480px;"></circle>';
	echo '<path d="M17.417,37.778l9.93,9.909l25.444-25.393" style="stroke-dasharray:50px, 50px; stroke-dashoffset: 0px;"></path>';
	echo '</g>';
	echo '</svg>';
	echo '</div>';    
	echo '<h4>Thank you for contacting us.</h4>';
	echo '<small>Check your mailbox.</small>';
	echo '</div>';
	echo '<script src="js/redirect.js"></script>';

} else {

	// Error Page
	echo '<div style="color: #e9431c">' . $errors . '</div>';
	echo '<div id="success">';    
	echo '<h4>Something went wrong.</h4>';
	echo '<a class="animated-link" href="../index.html">Go Back</small>';
	echo '</div>';	
}

?>
<!-- END PHP -->

</body>
</html>