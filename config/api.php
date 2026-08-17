<?php

$apiKey = getenv('GEMINI_API_KEY');

if (!$apiKey) {
    $apiKey = $_ENV['GEMINI_API_KEY'] ?? '';
}

return $apiKey;
?>