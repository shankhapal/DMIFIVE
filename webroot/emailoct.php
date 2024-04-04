<?php
/*
//code to send sms starts here
//echo "sendsms.php";
// Initialize the sender variable
$sender=urlencode("AGMARK");
//$uname=urlencode("aqcms.sms");
$uname="aqcms.sms";
//$pass=urlencode("Y&nF4b#7q");
$pass="Y%26nF4b%237q";
$send="AGMARK";
$dest='919860493110';
//$msg=urlencode('Hello, Naveen, Your company profile has been updated successfully. AGMARK');
$msg=urlencode('Hello Naveen, Your company profile has been updated successfully with AGMARK.');
$msg = "Hello+Amul+Choudhari%2C+Your+company+profile+has+been+updated+successfully+with+AGMARK.";

$template_id='1107160800912289815';


//&pin=&message=&mnumber=919860493110&signature=AGMARK&dlt_entity_id=1101424110000041576&dlt_template_id=1107160800912289815

// Initialize the URL variable
$URL="https://smsgw.sms.gov.in/failsafe/MLink";

/*
// Create and initialize a new cURL resource
$ch = curl_init();
// Set URL to URL variable
curl_setopt($ch, CURLOPT_URL,$URL);
// Set URL HTTPS post to 1
curl_setopt($ch, CURLOPT_POST, true);
// Set URL HTTPS post field values





				$ch = curl_init();
				// Set URL to URL variable
				curl_setopt($ch, CURLOPT_URL,$URL);
				// Set URL HTTPS post to 1
				curl_setopt($ch, CURLOPT_POST, true);
				// Set URL HTTPS post field values
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$entity_id = '1101424110000041576'; //updated on 18-11-2020

// if message lenght is greater than 160 character then add one more parameter "concat=1" (Done by pravin 07-03-2018)
if(strlen($msg) <= 160 ){
	
	

	curl_setopt($ch, CURLOPT_POSTFIELDS,"username=$uname&pin=$pass&signature=$send&mnumber=$dest&message=$msg&dlt_entity_id=$entity_id&dlt_template_id=$template_id");
	

}else{

	curl_setopt($ch, CURLOPT_POSTFIELDS,"username=$uname&pin=$pass&signature=$send&mnumber=$dest&message=$msg&concat=1&dlt_entity_id=$entity_id&dlt_template_id=$template_id");
}
//print_r($ch); exit;
// Set URL return value to True to return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// The URL session is executed and passed to the browser
$curl_output =curl_exec($ch);
echo $curl_output;

*/


				//code to send sms starts here
				//echo "sendsms.php";
				// Initialize the sender variable
				$sender=urlencode("AGMARK");
				//$uname=urlencode("aqcms.sms");
				$uname="aqcms.sms";
				//$pass=urlencode("Y&nF4b#7q");
				$pass="Y&nF4b#7q";
				$send=urlencode("AGMARK");
				//$dest=$destination_mob_nos_values;
				 $dest='9860493110';
				
				// $sms_message = "Your OTP to reset password isxxxx. The OTP is valid only for xxxxx minutes. Please click to reset your password xxxxxx";
				// $sms_message = "Hello S., PAO/DDO has confirmed payment verification for the application of firm KCOGF limited having ID: 5876/1/BGU/001. AGMARK";
				// $sms_message = "Your submitted mining plan vide acknowledgement No. xxxxxxxxx has been dis-approved i.r.o your mining lease.";
				// $sms_message = "Your return for the mine code XX415, for the month of JUNE -  2022 has been submitted. IBMMTS1";
				//$msg=urlencode("Hello Amul CHoudhary, Your company profile has been updated successfully with AGMARK.");
				$msg=urlencode("Hello,  Naveen, Your firm profile details have been updated successfully. AGMARK");

				// Initialize the URL variable
				// $URL="https://smsgw.sms.gov.in/failsafe/HttpLink";
				$URL="https://smsgw.sms.gov.in/failsafe/MLink";
				// Create and initialize a new cURL resource
				
				$ch = curl_init();
				// Set URL to URL variable
				curl_setopt($ch, CURLOPT_URL,$URL);
				// Set URL HTTPS post to 1
				curl_setopt($ch, CURLOPT_POST, true);
				// Set URL HTTPS post field values
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

				$entity_id = '1101424110000041576';
				$template_id = '1107166486430332299';

				// if message lenght is greater than 160 character then add one more parameter "concat=1" (Done by pravin 07-03-2018)
				if(strlen($msg) <= 160 ){

					//curl_setopt($ch, CURLOPT_POSTFIELDS,"username=$uname&pin=$pass&signature=$send&mnumber=$dest&message=$msg&dlt_entity_id=$entity_id&dlt_template_id=$template_id");
					// curl_setopt($ch, CURLOPT_POSTFIELDS,"username=".$uname."&pin=".$pass."&message=".$msg."&mnumber=".$dest."&signature=IBMMTS&dlt_entity_id=".$entity_id."&dlt_template_id=".$template_id);

				}else{

					//curl_setopt($ch, CURLOPT_POSTFIELDS,"username=$uname&pin=$pass&signature=$send&mnumber=$dest&message=$msg&concat=1&dlt_entity_id=$entity_id&dlt_template_id=$template_id");
					// curl_setopt($ch, CURLOPT_POSTFIELDS,"username=".$uname."&pin=".$pass."&message=".$msg."&mnumber=".$dest."&signature=IBMMTS&dlt_entity_id=".$entity_id."&dlt_template_id=".$template_id);
				}
//print_r("https://smsgw.sms.gov.in/failsafe/MLink?". "username=aqcms.sms&pin=Y&nF4b#7q&message=".$msg."&mnumber=".$dest."&signature=AGMARK&dlt_entity_id=".$entity_id."&dlt_template_id=".$template_id);
//exit;
				
				// curl_setopt($ch, CURLOPT_POSTFIELDS,"username=$uname&pin=$pass&signature=$send&mnumber=$dest&message=$msg&concat=1&dlt_entity_id=$entity_id&dlt_template_id=$template_id");
				// curl_setopt($ch, CURLOPT_POSTFIELDS,"username=apportalstg.sms&pin=AKsu@1990&message=".$msg." IBMMTS&mnumber=".$dest."&signature=IBMMTS&dlt_entity_id=".$entity_id."&dlt_template_id=".$template_id);
				// echo '<br>msg: '.$msg;
				// echo '<br>dest: '.$dest;
				// echo '<br>entity_id: '.$entity_id;
				// echo '<br>template_id: '.$template_id;
				// exit;
				curl_setopt($ch, CURLOPT_POSTFIELDS,"username=aqcms.sms&pin=Y&nF4b#7q&message=".$msg."&mnumber=".$dest."&signature=AGMARK&dlt_entity_id=".$entity_id."&dlt_template_id=".$template_id);

				// Set URL return value to True to return the transfer as a string
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				// The URL session is executed and passed to the browser
				$curl_output =curl_exec($ch); //production mode only

?>