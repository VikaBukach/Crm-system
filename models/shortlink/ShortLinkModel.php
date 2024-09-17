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
             title_link TEXT NOT NULL,
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
    public function createLink($title_link, $original_url, $shortCode){
        $query = "INSERT INTO short_links (title_link, original_url, short_url) VALUES (?, ?, ?)";

        try{
            $stmt = $this->db->prepare($query);
            $stmt->execute([$title_link, $original_url, $shortCode]);
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

    public function getAllShortLinksByIdUser($userId)
    {
        $query = "SELECT short_links.*
                  FROM short_links
                  JOIN user_links ON short_links.id = user_links.link_id
                  WHERE user_links.user_id = ?;";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            $short_links = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $short_links ? $short_links : $short_links = [];

        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
    public function getOriginalLinkByShortCode($code){
        $query = "SELECT original_url FROM short_links WHERE short_url = ? LIMIT 1";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$code]);
            $row = $stmt->fetch();
            return $row ? $row['original_url'] : null;

        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public function deleteShortLink($link_id, $user_id){

        try {
            //check link_id=user_id
            $checkUserQuery = "SELECT * FROM user_links WHERE link_id = ? AND user_id = ?";
            $stmtCheck = $this->db->prepare($checkUserQuery);
            $stmtCheck->execute([$link_id, $user_id]);

            if ($stmtCheck->rowCount() > 0) {
                // delete from user_links tab
                $deleteUserLinksQuery = "DELETE FROM user_links WHERE link_id = ?";
                $stmt = $this->db->prepare($deleteUserLinksQuery);
                $stmt->execute([$link_id]);

                // delete from short_links tab
                $deleteShortLinkQuery = "DELETE FROM short_links WHERE id = ?";
                $stmt = $this->db->prepare($deleteShortLinkQuery);
                $stmt->execute([$link_id]);

                return true;
            } else {
                return false;
            }
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function getShortLinkById($link_id, $user_id){
        try {
            //check link_id=user_id
            $checkUserQuery = "SELECT * FROM user_links WHERE link_id = ? AND user_id = ?";
            $stmtCheck = $this->db->prepare($checkUserQuery);
            $stmtCheck->execute([$link_id, $user_id]);

            if ($stmtCheck->rowCount() > 0) {
                // get records from user_links tab
                $query = "SELECT short_links.*, user_links.user_id
                FROM short_links
                JOIN user_links ON short_links.id = user_links.link_id
                WHERE short_links.id = ?";

                $stmt = $this->db->prepare($query);
                $stmt->execute([$link_id]);
                $shortLink = $stmt->fetch(\PDO::FETCH_ASSOC);

                return $shortLink ? $shortLink : null;
            } else {
                return null;
            }
        } catch (\PDOException $e) {
            return null;
        }
    }

    public function isShortUrlExistsWithIdAndCode($short_link_id, $shortCode){
        $query = "SELECT COUNT(*) FROM short_links WHERE id != ? AND short_url = ?";

        try{
            $stmt = $this->db->prepare($query);
            $stmt->execute([$short_link_id, $shortCode]);
            $count =$stmt->fetchColumn();
            return $count > 0;
        }catch(\PDOException $e){
            return false;
        }
    }

    public function updateLink($short_link_id, $title_link, $original_url, $shortCode) {
        $query = "UPDATE short_links SET title_link = ?, original_url = ?, short_url = ? WHERE id = ?";

        try{
            $stmt = $this->db->prepare($query);
            $stmt->execute([$title_link, $original_url, $shortCode, $short_link_id]);
            return true;
        }catch(\PDOException $e){
            return false;
        }

    }



}