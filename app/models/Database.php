<?php

//application/models/Database.php

class Database {
    private static $instance = null;

    private $conn;
    private $host = 'mysql';
    private $user = 'root';
    private $pass = 'root';
    private $name = 'crm_for_tgBot';

    private function __construct() {
        // $this->conn  обєкт підключення до БД
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->name);
        if($this-> conn->connect_error){
            die('Connect failed: ' .$this-> conn->connect_error);
        }
    }
    // повертає сам обєкт класа 'Database'
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
// метод який повертає обєкт підключення до БД
    public function getConnection(){
        return $this->conn;
    }
}

















