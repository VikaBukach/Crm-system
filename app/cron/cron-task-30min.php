<?php

//this file will be pulled from the task database every 30 minutes and sent to Telegram as a deadline reminder
require_once '../../config.php';
require_once '../../autoload.php';
require_once '../../models/telegram/TelegramBot.php';

use models\Database;
use models\todo\tasks\TaskModel;
use models\telegram\TelegramBot;

$db = Database::getInstance()->getConnection();

try{
    //the field `created_at` not later than 7 days, <= 7 days
    //the field `reminder_at` corresponds to current date and time, with + 15 minutes
    $query = "SELECT *
    FROM todo_reminders
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    AND reminder_at BETWEEN DATE_SUB(NOW(), INTERVAL 15 MINUTE) AND DATE_ADD(NOW(), INTERVAL 15 MINUTE)
    ";
    $stmt = $db->query($query);
    $tasks = $stmt->fetchAll(\PDO::FETCH_ASSOC);

}catch(\PDOException $e){
    echo "Error:" . $e->getMessage();
}
