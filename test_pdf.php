<?php

$uploads = __DIR__ . "/uploads";

echo "<h2>Project Directory</h2>";
echo __DIR__ . "<hr>";

if (!is_dir($uploads)) {
    die("Uploads folder does not exist!");
}

echo "<h3>Files in uploads folder:</h3>";

echo "<pre>";
print_r(scandir($uploads));
echo "</pre>";