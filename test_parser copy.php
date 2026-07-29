<?php

require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/ai/parser.php';


$pdfFile = __DIR__ . '/uploads/sample.pdf';


echo "Reading PDF:<br>";
echo $pdfFile . "<br><br>";


if(file_exists($pdfFile))
{
    echo "PDF Found<br><br>";

    $text = extractResumeText($pdfFile);

   echo "<h3>Extracted Resume Text:</h3>";

echo "<div style='
background:#f5f5f5;
padding:20px;
border-radius:10px;
width:80%;
margin:auto;
font-family:Arial;
line-height:1.6;
white-space:pre-wrap;
'>";

echo htmlspecialchars($text);

echo "</div>";

}
else
{
    echo "PDF not found";
}

?>