<?php

session_start();

require_once '../../config.php';
require_once '../../autoload.php';

use models\Database;

$db = Database::getInstance()->getConnection();

try{
    //get all tasks reminder_at <= 7 days
    $query = "SELECT * FROM todo_list WHERE reminder_at BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)";

    $stmt = $db->query($query);
    $tasks = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $stmt = $db->query($query);
    $tasks = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    //recording tasks in todo_reminders table
    $insertQuery = "INSERT INTO 
        todo_reminders (user_id, task_id, reminder_at) 
        SELECT :user_id, :task_id, :reminder_at 
        FROM dual 
        WHERE 
            NOT EXISTS (SELECT * FROM todo_reminders WHERE task_id = :task_id)";

    $insertStmt = $db->prepare($insertQuery);

    foreach ($tasks as $task){
        $insertStmt->bindParam(':user_id', $task['user_id'], \PDO::PARAM_INT);
        $insertStmt->bindParam(':task_id', $task['id'], \PDO::PARAM_INT);
        $insertStmt->bindParam(':reminder_at', $task['reminder_at']);
        $insertStmt->execute();
    }
    echo "Задачі були перенесені ";


}catch(\PDOException $e){
    echo " Error: " . $e->getMessage();
}