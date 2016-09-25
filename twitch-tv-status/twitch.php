<?php

if ($_SERVER['REMOTE_ADDR'] !== $_SERVER['SERVER_ADDR']) die("Access Forbidden");

$clientID = '';

function jsonp_decode($jsonp, $assoc = false) { 
    if($jsonp[0] !== '[' && $jsonp[0] !== '{') { 
       $jsonp = substr($jsonp, strpos($jsonp, '('));
    }
    return json_decode(trim($jsonp,'();'), $assoc);
}

$streams = (isset($_GET)) ? $_GET : array('SaltyTeemo');

$result = array();

foreach ($streams as $stream) {

  $data = jsonp_decode(@file_get_contents("https://api.twitch.tv/kraken/streams/$stream?client_id=$clientID"));
  
  $result[$stream] = ($data->stream == null) ? 0 : 1;

}

echo json_encode($result);

?>
