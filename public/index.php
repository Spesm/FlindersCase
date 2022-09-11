<?php

$basedir = dirname(__DIR__);
require $basedir . '/vendor/autoload.php';

use Sem\FlindersCase\OrdersCsv;
use Sem\FlindersCase\Html;

$data = new OrdersCsv();
$clientData = $data->listClientsByOrders();
$countryData = $data->listOrdersPerCountry();

$output = [$clientData, $countryData];
$page = new Html($output);
