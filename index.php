<?php
// File : index.php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, PATCH");
header("Access-Control-Allow-Headers: Content-Type");

require_once 'config/database.php';
require_once 'models/Order.php';
require_once 'controllers/OrderController.php';

$db = Database::getInstance()->getConnection();
$controller = new OrderController($db);

$method = $_SERVER['REQUEST_METHOD'];
$uri = explode("/", trim($_SERVER['PATH_INFO'] ?? "", "/"));

$ressource = $uri[0] ?? '';
$uri1 = $uri[1] ?? null;
$uri2 = $uri[2] ?? null;

if($ressource === 'orders'){
    if($method === 'GET' && $uri1 == null){
        $controller->getAll();
    }elseif($method === 'GET' && $uri1 != null){
        $controller->getOne($uri1);
    }elseif($method === 'POST'){
        $controller->create();
    }elseif($method === 'PUT' && $uri1 != null){
        $controller->update($uri1);
    }elseif($method === 'PATCH' && $uri1 != null && $uri2 != null){
        $controller->changeOrderStatus($uri1);
    }elseif($method === 'DELETE' && $uri1 != null){
        $controller->delete($uri1);
    }
}else{
    http_response_code(404);
    echo json_encode([
        'status' => 'error',
        'message' => 'Route non trouvée'
    ]);
}