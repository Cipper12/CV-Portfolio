<?php

$dbhost = "localhost";
$dbuser = "cipper12";
$dbpass = "welovestntv25565";
$dbname = "ECommerceDB";

if(!$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname))
{
    die("failed to connect!");
}
?>