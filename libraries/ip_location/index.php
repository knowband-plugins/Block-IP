<?php
require_once('IpLocation/Ip.php');
require_once('IpLocation/Service/CsvWebhosting.php');
require_once('IpLocation/Service/GeoIp.php');

$objIpLocationObject = new KbIpLocation_Ip(new KbIpLocation_Service_GeoIp());


$results = $objIpLocationObject->getIpLocation('56.45.44.33'); // google.com IP address
print_r($results); // Print out the results object


$results = $objIpLocationObject->getIpLocation('122.177.193.17'); 
print_r($results); // Print out the results object

$results = $objIpLocationObject->getIpLocation('106.219.53.84'); 
print_r($results); // Print out the results object

echo "HERE";

?>