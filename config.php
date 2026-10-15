<?php

require_once __DIR__ . '/vendor/autoload.php';

$client = new MongoDB\Client("mongodb://127.0.0.1:27017");

$db = $client->digital_certificate;

$admins = $db->admins;
$students = $db->students;
$certificates = $db->certificates;

?>