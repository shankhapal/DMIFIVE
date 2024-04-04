<?php
if (isset($_REQUEST['to']))
{
$to = $_REQUEST['to'];
$to = htmlspecialchars($to);
$host=$_SERVER['SERVER_NAME'];
$ip=$_SERVER['SERVER_ADDR'];
$from="dmiqc@nic.in";
$subject="PHP Mail Test";
$message="This is a test message sent from $host. It originated from the IP address $ip. If you received this email, that means that the PHP mail function is working on this server.";
$headers="From: $from" . "\r\n" . "Reply-To: dmiqc@nic.in" . "\r\n" . "X-Mailer: PHP/" . phpversion();
$success=mail($to,$subject,$message,$headers);

$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
if (preg_match($regex, $to)) {

if($success) {
echo "The email was sent successfully";
}
else {
echo "An error occurred, and the email was not sent. Check your domains' error logs and mail log for more info.";
}

} else {

echo "
<center></br>
<form method='post' action='email.php'><br>PHP mail() Test<br>
To: <input name='to' type='text'>
<input type='submit'>
</form><font color='red'>";

echo $to . " is an invalid email. Please try again.</font></center>";

}}

else
{
echo "<center></br>
<form method='post' action='email.php'><br>PHP mail() Test<br>
To: <input name='to' type='text'>
<input type='submit' value='Send Message'>
</form></center>";
}

//code to send sms starts here
//echo "sendsms.php";
// Initialize the sender variable
$sender=urlencode("AGMARK");
//$uname=urlencode("aqcms.sms");
$uname="aqcms.sms";
//$pass=urlencode("Y&nF4b#7q");
$pass="Y%26nF4b%237q";
$send=urlencode("AGMARK");
$dest='98606493110';
$msg=urlencode('Hello, Naveen, Your company profile has been updated successfully. AGMARK');

$template_id='1107160800912289815';

// Initialize the URL variable
$URL="http://smsgw.sms.gov.in/failsafe/HttpLink";
// Create and initialize a new cURL resource
$ch = curl_init();
// Set URL to URL variable
curl_setopt($ch, CURLOPT_URL,$URL);
// Set URL HTTPS post to 1
curl_setopt($ch, CURLOPT_POST, true);
// Set URL HTTPS post field values

$entity_id = '1101424110000041576'; //updated on 18-11-2020

// if message lenght is greater than 160 character then add one more parameter "concat=1" (Done by pravin 07-03-2018)
if(strlen($msg) <= 160 ){

	curl_setopt($ch, CURLOPT_POSTFIELDS,"username=$uname&pin=$pass&signature=$send&mnumber=$dest&message=$msg&dlt_entity_id=$entity_id&dlt_template_id=$template_id");

}else{

	curl_setopt($ch, CURLOPT_POSTFIELDS,"username=$uname&pin=$pass&signature=$send&mnumber=$dest&message=$msg&concat=1&dlt_entity_id=$entity_id&dlt_template_id=$template_id");
}

// Set URL return value to True to return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// The URL session is executed and passed to the browser
$curl_output =curl_exec($ch);
//echo $curl_output;

?>