<?php

if(empty($_POST["data"])) {
    exit("no data");
}

$data = \json_decode($_POST["data"], true);

require_once __DIR__ . "/../vendor/autoload.php";

echo json_encode($data);