<?php


$apiKey = getenv('GEMINI_API_KEY');
require_once "config/api.php";



$url =
"https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key="
.GOOGLE_GEMINI_KEY;


$data=[

"contents"=>[
[
"parts"=>[
[
"text"=>"Say hello"
]
]
]
]

];


$ch=curl_init($url);


curl_setopt($ch,CURLOPT_POST,true);

curl_setopt($ch,CURLOPT_HTTPHEADER,[

"Content-Type: application/json"

]);


curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($data));

curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);


$response=curl_exec($ch);


curl_close($ch);


echo "<pre>";
print_r($response);
echo "</pre>";

?>