<?php
/*
** deDexterPHP
** by Alex Mak (S-ed)
** This is a simple PHP script that is designed to decode the data affected by Dexter malware
** Based on original Ruby scrip by Josh Grunzweig
** https://github.com/SpiderLabs/Malware_Analysis/blob/master/Ruby/Dexter/dexter_decode.rb
*/
 
function xor_decode($text, $key) {
  $key_length = strlen($key);
  $encoded_data = base64_decode($text);
  $result = '';
  $length = strlen($encoded_data);
  for ($i = 0; $i < $length; $i++) {
    $tmp = $encoded_data[$i];
 
    for ($j = 0; $j < $key_length; $j++) {
        $tmp = chr(ord($tmp) ^ ord($key[$j]));
    }
 
    $result .= $tmp;
  }
  return $result;
}
 
function searchKey($data){
        foreach (explode("&", $data) as $param) {
                $param_arr = preg_split("/=/", $param, 2);
                if( $param_arr[0] == "val" ){
  				return base64_decode($param_arr[1]);
				}
        }
}

function deDexter($data, $key){
        $decoded_data = "";
        foreach (explode("&", $data) as $param) {
                $param_arr = preg_split("/=/", $param, 2);
                if( $param_arr[0] != "val" ){
                        $decoded_data = $decoded_data.$param_arr[0]."=".xor_decode($param_arr[1], $key)."\n";
                }
        }
        return $decoded_data;
}

$data = "Some Your Encrypted data";

$key = searchKey($data); //search for key separated on case of several calls of deDexter() using same key
$data = deDexter($data, $key);

?>
