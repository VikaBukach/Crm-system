<?php

namespace models\pages;

use models\Database;
use models\roles\Role;

class PageModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();

        try {
            $result = $this->db->query("SELECT 1 FROM `pages` LIMIT 1");
        } catch (\PDOException $e) {
            $this->createTable();
        }
    }

    public function createTable()
    {
        $pageTableQuery = "CREATE TABLE IF NOT EXISTS `pages`(
             `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
             `title` VARCHAR(255) NOT NULL,
             `slug` VARCHAR(255) NOT NULL,
             `role` VARCHAR(255) NULL, 
             `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
             `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )    ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";

        try {  //запит у БД
            $this->db->exec($pageTableQuery);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function insertPages(){
        $insertPagesQuery = "INSERT INTO `pages` (`id`, `title`, `slug`, `role`, `created_at`, `updated_at`) VALUES
        (1, 'Home', '/', '1,2,3,4,5', '2024-06-20 15:24:59', '2024-06-21 17:56:34'),
        (2, 'Users', 'users', '1,2,5', '2024-06-21 08:38:42', '2024-06-21 17:56:40'),
        (3, 'Pages', 'pages', '5', '2024-06-21 14:11:34', '2024-06-21 17:56:47'),
        (4, 'User edit', 'users/edit', '2,5', '2024-06-21 14:11:34', '2024-06-21 17:56:47'),
        (5, 'User create', 'users/create', '3,4,5', '2024-06-21 14:11:34', '2024-06-21 17:56:47'),
        (6, 'Users store', 'users/store','3,4,5', '2024-06-21 14:11:34', '2024-06-21 17:56:47'),
        (7, 'Users update', 'users/update', '5', '2024-06-21 14:11:34', '2024-06-21 17:56:47'),
        (8, 'Roles', 'roles','2,3,4,5', '2024-06-21 14:11:34', '2024-06-21 17:56:47'),
        (9, 'Roles create', 'roles/create','3,4,5', '2024-06-21 14:11:34', '2024-06-21 17:56:47'),
        (10, 'Roles store', 'roles/store', '3,4,5', '2024-06-21 14:11:34', '2024-06-21 17:56:47'),
        (11, 'Roles edit', 'roles/edit', '3,4,5', '2024-06-21 14:11:34', '2024-06-21 17:56:47'),
        (12, 'Roles update', 'roles/update', '5', '2024-06-21 14:11:34', '2024-06-21 17:56:47'),
        (13, 'Pages update', 'pages/update', '5', '2024-06-21 14:11:34', '2024-06-21 17:56:47'),
        (14, 'Users delete', 'users/delete', '5', '2024-06-21 14:11:34', '2024-06-21 17:56:47'),
        (15, 'Todo catagory create', 'todo/category/create','3,4,5', '2024-06-21 14:11:34', '2024-06-21 17:56:47'),
        (16, 'Todo catagory edit', 'todo/category/edit', '3,4,5', '2024-06-21 14:11:34', '2024-06-21 17:56:47'),
        (17, 'Todo category', 'todo/category', '3,4,5', '2024-06-21 14:11:34', '2024-06-21 17:56:47'),
        (18, 'Todo category store', 'todo/category/store', '3,4,5', '2024-06-22 17:42:49', '2024-06-22 17:44:01'),
        (19, 'Todo category delete', 'todo/category/delete', '3,4,5', '2024-06-22 17:47:16', '2024-06-22 17:47:16'),
        (20, 'Todo category update', 'todo/category/update', '3,4,5', '2024-06-22 17:47:16', '2024-06-22 17:47:16'),
        (21, 'Pages create', 'pages/create', '3,4,5', '2024-06-24 14:26:40', '2024-06-24 14:26:40'),
        (22, 'Tasks create', 'todo/tasks/create', '3,4,5', '2024-06-24 14:26:40', '2024-06-25 14:09:44'),
        (23, 'Tasks edit', 'todo/tasks/edit','5','2024-06-24 14:26:40','2024-06-25 14:09:53'),
        (24, 'Tasks delete', 'todo/tasks/delete','5','2024-06-24 14:26:40','2024-06-25 14:10:04'),
        (25, 'Tasks', 'todo/tasks', '3,4,5', '2024-06-24 14:26:40','2024-06-25 14:30:06'),
        (26, 'Tasks store','todo/tasks/store', '3,4,5', '2024-06-24 14:43:12','2024-06-25 14:30:06'),
        (27, 'Tasks update','todo/tasks/update', '5','2024-06-24 14:44:55','2024-06-25 14:11:04'),
        (28, 'Pages create','pages/create','5', '2024-07-18 07:59:38', '2024-07-18 07:59:38'),
        (29, 'Pages edit', 'pages/edit', '5', '2024-07-18 07:59:38', '2024-07-18 07:59:38'),
        (30, 'Pages delete', 'pages/delete','5', '2024-07-18 07:59:38', '2024-07-18 07:59:38'),
        (31, 'Pages store', 'pages/store', '5', '2024-07-22 13:41:08', '2024-07-22 13:41:43'),
        (32, 'Roles delete', 'roles/delete', '5', '2024-07-22 13:42:41', '2024-07-22 13:42:41'),
        (33, 'Todo tasks task', 'todo/tasks/task','2,3,4,5', '2024-07-22 13:43:23','2024-07-22 13:43:23'),
        (34, 'Todo tasks by tag', 'todo/tasks/by-tag', '2,3,4,5', '2024-07-22 13:44:02', '2024-07-22 13:44:02'),
        (35, 'Tasks completed', 'todo/tasks/completed', '3,4,5', '2024-07-22 13:44:02', '2024-07-22 13:44:02'),
        (36, 'Expired tasks', 'todo/tasks/expired', '3,4,5', '2024-07-22 13:44:02', '2024-07-22 13:44:02');";

        try {
            $this->db->exec($insertPagesQuery);
            return true;
        } catch(\PDOException $e) {
            return false;
        }
    }

    public function getAllPages()
    {

        try {
            $stmt = $this->db->query('SELECT * FROM pages');
            $pages = [];
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $pages[] = $row;
            }

            return $pages ?: [];
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function getPageById($id)
    {

        $query = "SELECT * FROM pages WHERE id = ?";

        try {
            $stmt = $this->db->prepare(($query));
            $stmt->execute([$id]);
            $page = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $page ? $page : false;
        } catch (\PDOException $e) {
            return false;
        }
    }


    public function createPage($title, $slug, $roles)
    {

        $query = "INSERT INTO pages(title, slug, role) VALUES (?, ?, ?)";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$title, $slug, $roles]);
            return true;
        } catch (\PDOException $e) {
             error_log($e->getMessage());
            return false;
        }
    }

    public function updatePage($id, $title, $slug, $roles)
    {
        $query = "UPDATE pages SET title = ?, slug = ?, role = ?  WHERE id = ?";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$title, $slug, $roles, $id]);

            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function deletePage($id)
    {
        $query = "DELETE FROM pages WHERE id = ?";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);
            return true;
        } catch (\PDOException $e) {
        }
        return false;
    }

    public function findBySlug($slug)
    {

        $query = "SELECT * FROM pages WHERE slug = ?";

        try {
            $stmt = $this->db->prepare(($query));
            $stmt->execute([$slug]);
            $page = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $page ? $page : false;
        } catch (\PDOException $e) {
            return false;
        }
    }
}

