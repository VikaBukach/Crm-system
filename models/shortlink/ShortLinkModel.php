<?php

namespace models\shortlink;

use models\Database;

class ShortLinkModel
{
    private $db;

    public function __construct (){
        $this->db = Database::getInstance()->getConnection();

        try{
            $result = $this->db->query("SELECT 1 FROM `short_links` LIMIT 1");
        }catch(\PDOException $e){
            $this->createTable();
        }
    }
    public function createTable()
    {
        $queryShortLinks = "CREATE TABLE IF NOT EXISTS short_links (
             id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
             original_url TEXT NOT NULL,
             short_url TEXT NOT NULL,
             created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
             updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );";

        $queryUserLinks = "CREATE TABLE IF NOT EXISTS user_links (
             id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
             user_id INT(11) NOT NULL,
             link_id INT(11) NOT NULL,
             created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
             FOREIGN KEY (user_id) REFERENCES users(id),
             FOREIGN KEY (link_id) REFERENCES short_links(id)
        );";

    try{
        $this->db->exec($queryShortLinks);
        $this->db->exec($queryUserLinks);
        return true;
    } catch(\PDOException $e){
        return false;
    }
    }

    public function isShortUrlExists($shortCode){
        $query = "SELECT COUNT(*) FROM short_links WHERE short_url = ?";

        try{
            $stmt = $this->db->prepare($query);
            $stmt->execute([$shortCode]);
            $count =$stmt->fetchColumn();
            return $count > 0;
        }catch(\PDOException $e){
            return false;
        }
    }
    public function createLink($original_url, $shortCode){
        $query = "INSERT INTO short_links (original_url, short_url) VALUES (?, ?)";

        try{
            $stmt = $this->db->prepare($query);
            $stmt->execute([$original_url, $shortCode]);
            return $this->db->lastInsertId();
        }catch(\PDOException $e){
            return false;
        }
    }

    public function createUserLink($user_id, $shortUrlId){
        $query = "INSERT INTO user_links (user_id, link_id) VALUES (?, ?)";

        try{
            $stmt = $this->db->prepare($query);
            $stmt->execute([$user_id, $shortUrlId]);
            return $this->db->lastInsertId();
        }catch(\PDOException $e){
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

}