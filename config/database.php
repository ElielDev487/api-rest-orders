<?php
// File : config/database.php

class Database{
    private static $instance = null;
    private $db;

    private function __construct(){
        $host = 'localhost';
        $dbname = 'api_test';
        $user = 'root';
        $pass = '';
        $dsn = 'mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8mb4';
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        try{
            $this->db = new PDO($dsn,$user,$pass, $options);
        }catch(PDOException $e){
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public static function getInstance(){
        if(self::$instance == null){
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(){
        return $this->db;
    }

    private function __clone(){
        throw new \Exception('Not implemented');
    }

    public function __wakeup(){
        throw new \Exception('Not implemented');
    }
}