<?php

session_start();

$_SESSION['test'] = "working";

echo "Session ID: ".session_id();

echo "<br>";

echo $_SESSION['test'];

?>