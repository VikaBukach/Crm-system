<?php

namespace models\quiz;

use models\Database;


class QuizModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();

        try {
            $result = $this->db->query("SELECT 1 FROM `quiz_questions` LIMIT 1");
        } catch (\PDOException $e) {
            $this->createTable();
        }
    }

    public function createTable()
    {
        $quizTableQuery = "CREATE TABLE IF NOT EXISTS `quiz_questions`(
             `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
             `question` TEXT NOT NULL,
             `answer_1` VARCHAR(255) NOT NULL,
             `answer_2` VARCHAR(255) NOT NULL,
             `answer_3` VARCHAR(255) NOT NULL,
             `correct_answer` TINYINT(1) NOT NULL,
             `explanation` TEXT,
             `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
             `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );";

        try {  //запит у БД
            $this->db->exec($quizTableQuery);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function readAll()
    {
        try {
            $stmt = $this->db->query('SELECT * FROM quiz_questions');
            $quiz = [];
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $quiz[] = $row;
            }

            return $quiz ?: [];
        } catch (\PDOException $e) {
            return false;
        }
    }


    public function createQuiz($data)
    {
//        tte($data);

        $query = "INSERT INTO quiz_questions (question, answer_1, answer_2, answer_3, correct_answer, explanation) VALUES (?, ?, ?, ?, ?, ?)";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $data['question'],
                $data['answer_1'],
                $data['answer_2'],
                $data['answer_3'],
                $data['correct_answer'],
                $data['explanation']
            ]);

                return true;
        } catch (\PDOException $e) {
            error_log('Error: ' . $e->getMessage());

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


}


