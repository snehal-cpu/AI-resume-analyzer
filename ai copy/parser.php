<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Smalot\PdfParser\Parser;


function extractResumeText($pdfFile)
{

    try
    {

        $parser = new Parser();

        $pdf = $parser->parseFile($pdfFile);


        $text = $pdf->getText();




        return trim($text);


    }
    catch(Exception $e)
    {

        return "PDF extraction failed: " . $e->getMessage();

    }

}

?>