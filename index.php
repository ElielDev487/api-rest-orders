<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type");

require_once 'config/database.php';
require_once 'models/Order.php';
require_once 'controllers/OrderController.php';

$db = Database::getInstance()->getConnection();
$controller = new OrderController($db);

$method = $_SERVER['REQUEST_METHOD'];
$uri = explode("/", trim($_SERVER['PATH_INFO'] ?? "", "/"));

$ressource = $uri[0];
