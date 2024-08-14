<?php

namespace models\users;

use models\Database;

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();

        try {
            $result = $this->db->query("SELECT 1 FROM `users` LIMIT 1");
        } catch (\PDOException $e) {
            $this->createTable();
        }
    }

    //checking for tables and records availability:
    private function rolesExist()
    {
         try {
             $query = "SELECT COUNT(*) FROM `roles`";
             $stmt = $this->db->query($query);
             return $stmt->fetchColumn() > 0;
         }catch(\PDOException $e){
             return false;
         }

    }

    private function adminUserExists()
    {
        try {
            $query = "SELECT COUNT(*) FROM `users` WHERE `username` = 'Admin' AND `is_admin` = 1";
            $stmt = $this->db->query($query);
            return $stmt->fetchColumn() > 0;
        }catch(\PDOException $e){
            return false;
        }

    }



    public function createTable()
    {
        $roleTableQuery = "CREATE TABLE IF NOT EXISTS `roles`(
             `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
             `role_name` VARCHAR(255) NOT NULL,
             `role_description` TEXT
        )";

        $userTableQuery = "CREATE TABLE IF NOT EXISTS `users`(
             `id` INT(11) NOT NULL AUTO_INCREMENT,
             `username` VARCHAR(255) NOT NULL,
             `email` VARCHAR(255) NOT NULL,
             `email_verification` TINYINT(1) NOT NULL DEFAULT 0,
             `password` VARCHAR(255) NOT NULL,
             `is_admin` TINYINT(1) NOT NULL DEFAULT 0,
             `role` INT(11) NOT NULL DEFAULT 0,
             `is_active` TINYINT(1) NOT NULL DEFAULT 1,
             `last_login` TIMESTAMP NULL,
             `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
             PRIMARY KEY (`id`),
             FOREIGN KEY (`role`) REFERENCES `roles`(`id`)
        );";

        //create the table for OTP codes:

        $OTPTableQuery = "CREATE TABLE IF NOT EXISTS `otp_codes`(
             `id` INT(11) NOT NULL AUTO_INCREMENT,
             `user_id` INT(11) NOT NULL,
             `otp_code` INT(7) NOT NULL,
             `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
             PRIMARY KEY (`id`),
             FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
            );";

        //create the table for save states of users:

        $userStatesQuery = "CREATE TABLE IF NOT EXISTS `user_states`(
             `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
             `chat_id` INT(11) NOT NULL,
             `user_id` INT(11) DEFAULT NULL,
             `state` VARCHAR(255) NOT NULL,
             `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
             `updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
             UNIQUE INDEX(chat_id)
            );";

        //create the table for users of the telegram :

        $userTelegramQuery = "CREATE TABLE IF NOT EXISTS `user_telegrams`(
             `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
             `user_id` INT(11) NOT NULL,
             `telegram_chat_id` VARCHAR(255) NOT NULL,
             `telegram_username` VARCHAR(255) NOT NULL,
             `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
             FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
            );";

        try {  // request in DB:
            $this->db->exec($roleTableQuery);
            $this->db->exec($userTableQuery);
            $this->db->exec($OTPTableQuery);
            $this->db->exec($userStatesQuery);
            $this->db->exec($userTelegramQuery);

            //insert defolts 'roles' in the roles table:
            if (!$this->rolesExist()) {
                $this->insertDefaultRoles();
            }

            //insert record about admin in 'users' table:
            if (!$this->adminUserExists()) {
                $this->insertAdminUser();
            }

            return true;
        } catch (\PDOException $e) {
            echo "Error creating tables or inserting data: " . $e->getMessage();
            return false;
        }
    }

    private function insertDefaultRoles()
    {
        $insertRolesQuery = "INSERT INTO `roles` (`role_name`, `role_description`) VALUES
                ('Subscriber', 'can only read articles and leave comments, but does not have the right to create own content or manage the site'),
                ('Editor', 'access to management and publication of articles, pages and other content materials on the site. The editor can also manage comments and allow or prohibit their publication'),
                ('Author', 'can create and publish his own articles, but he does not have the ability to manage them content of other users'),
                ('Contributor', 'can create their own articles, but they cannot be published until approved by an administrator or editor'),
                ('Administrator', 'full access to all site functions, including user management, plugins, as well as creating and publishing articles');";

        try {
            $this->db->exec($insertRolesQuery);
        } catch(\PDOException $e){
            echo "Error inserting roles:" . $e->getMessage();
        }
    }

    private function insertAdminUser()
    {
        $insertAdminQuery = "INSERT INTO `users` (`username`, `email`, `password`, `is_admin`, `role`) VALUES
                ('Admin', 'admin@gmail.com', '\$2y\$10\$se6VOpWD4H7DInxrtwCF3evkxF609.sGqax.k12RkGtbLWteGO6eS', 1, (SELECT `id` FROM `roles` WHERE `role_name` = 'Administrator' ));";

        try {
            $this->db->exec($insertAdminQuery);
        } catch(\PDOException $e) {
            echo "Error inserting admin user:" . $e->getMessage();
        }
    }


    public function readAll()
    {
        try {
            $stmt = $this->db->query("SELECT * FROM `users`");

            $users = [];
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $users[] = $row;
            }
            return $users;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function create($data)
    {
        $username = $data['username'];
        $email = $data['email'];
        $password = $data['password'];
        $role = $data['role'];

        $created_at = date('Y-m-d H:i:s');

        $query = "INSERT INTO users (username, email, password, role, created_at) VALUES (?,?,?,?,?)";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT), $role, $created_at]);
            return true;
        } catch (\PDOException $e) {
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
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function read($id)
    {
        $query = "SELECT * FROM users WHERE id = ?";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);
            $res = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $res;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function update($id, $data)
    {
        $username = $data['username'];
        $admin = !empty($data['is_admin']) && $data['is_admin'] !== 0 ? 1 : 0;
        $email = $data['email'];
        $role = $data['role'];
        $is_active = isset($data['is_active']) ? 1 : 0;

        $query = "UPDATE users SET username = ?, email = ?, is_admin = ?, role = ?, is_active = ? WHERE id = ?";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$username, $email, $admin, $role, $is_active, $id]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function writeOTPCodeByUserId($data)
    {
        $user_id = $data['user_id'];
        $otp = $data['otp'];
        $created_at = date('Y-m-d H:i:s');

        $query = "INSERT INTO otp_codes (user_id, otp_code, created_at) VALUES (?,?,?)";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$user_id, $otp, $created_at]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function getLastOtpCodeByUserId($user_id)
    {
        $query = "SELECT * FROM otp_codes WHERE user_id = ? ORDER BY created_at DESC LIMIT 1";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$user_id]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return false;
        }
    }

    //get users state for authorization through the telegram
    public function getUserState($chatId)
    {
        $query = "SELECT * FROM user_states WHERE chat_id = ?";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$chatId]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return false;
        }
    }

    // record user`s state for authorization through the telegram
    public function setUserState($chatId, $state, $userId = null)
    {
        $query = "INSERT INTO user_states (chat_id, state, user_id) VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE state = ?, user_id = ?";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$chatId, $state, $userId, $state, $userId]);
        } catch (\PDOException $e) {
            return false;
        }
    }


    //get info about user to his ID and otp password
    public function getOtpInfoByUserIdAndCode($user_id, $otpCode)
    {
        $query = "SELECT * FROM otp_codes WHERE user_id = ? AND otp_code = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 60 MINUTE)";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$user_id, $otpCode]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return false;
        }
    }

    // create user of Tg + crm
    public function createUserTelegram($user_id, $chatId, $username)
    {
        $query = "INSERT INTO * FROM users_telegrams (user_id, telegram_chat_id, telegram_username) VALUES (?, ?, ?)";
        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$user_id, $chatId, $username]);
        } catch (\PDOException $e) {
            return false;
        }
    }

//get info about user from 'user_telegrams' tables
    public function getInfoByUserIdFromTelegramTable($user_id)
    {
        $query = "SELECT * FROM user_telegrams WHERE user_id = ?";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$user_id]);
            $res = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $res;
        } catch(\PDOException $e){
            return false;
        }
    }

    //get info about user to telegram_chat_id from 'user_telegrams' tables
    public function getUserByTelegramChatId($telegram_chat_id)
    {
        $query = "SELECT * FROM user_telegrams WHERE telegram_chat_id = ?";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$telegram_chat_id]);
            $res = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $res;
        } catch(\PDOException $e){
            return false;
        }
    }


}
