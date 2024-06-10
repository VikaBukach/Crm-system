<?php

class User{
    private  $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();

        try{
            $result = $this-> db->query("SELECT 1  from `users` LIMIT 1");
        }catch(PDOException $e) {
          $this->createTable();
        }
    }

    public function createTable(){
        $query = "CREATE TABLE IF NOT EXIST `users`(
    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `login` VARCHAR(255)m NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `is_admin` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

        try{
            $this->db->exec($query);
            return true;
        }catch(PDOException $e) {
            return false;
        }
    }

    public function readAll(){
       try {
           $stmt = $this->db->query("SELECT * FROM users");
           $users =[];
           while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
               $users[] = $row;
           }
           return $users;
       } catch(PDOException $e) {
           return  false;
       }
    }

    public function create($data){
        $login = $data['login'];
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $admin = $data['admin'] == '1' ? 1 : 0;
        $created_at = date('Y-m-d H:i:s');

        $query = "INSERT INTO users (login, password, is_admin, created_at) VALUE (?,?,?,?)";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$login, $password, $admin, $created_at]);
            return true;
        } catch(PDOException $e) {
            return false;
        }
    }
    public function delete($id)
    {
        $query = "DELETE FROM users WHERE id = ?";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    public function read($id){
        $query = "SELECT * FROM users WHERE id = ?";

        try{
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            return $res;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function update($id, $data) {
        $login = $data['login'];
        $admin = $data['admin'] == '1' ? 1 : 0;

        $query = "UPDATE users SET login = ?, is_admin = ? WHERE id = ?";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$login, $admin, $id]);
            return true;
        } catch(PDOException $e) {
            return false;
        }
    }
}
