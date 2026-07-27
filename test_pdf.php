<?php

require __DIR__ . "/vendor/autoload.php";


if(class_exists("Dompdf\\Dompdf"))
{
    echo "Dompdf Working";
}
else
{
    echo "Dompdf Not Loaded";
}

?>