<?php

//this file will be pulled from the task database every 30 minutes and sent to Telegram as a deadline reminder
require_once '../../local-config.php';
require_once '../../autoload.php';
require_once '../../models/telegram/TelegramBot.php';

use models\Database;
use models\telegram\TelegramBot;

$db = Database::getInstance()->getConnection();

try{
    //the field `created_at` not later than 7 days, <= 7 days
    //the field `reminder_at` corresponds to current date and time, with + 15 minutes
    $query = "SELECT tr.*, tl.title, tl.finish_date, ut.telegram_chat_id, ut.telegram_username
    FROM todo_reminders AS tr
    INNER JOIN todo_list AS tl ON tr.task_id = tl.id
    INNER JOIN user_telegrams AS ut ON tr.user_id = ut.user_id
    WHERE tr.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    AND tr.reminder_at BETWEEN DATE_SUB(NOW(), INTERVAL 15 MINUTE) AND DATE_ADD(NOW(), INTERVAL 15 MINUTE)
    ";
    $stmt = $db->query($query);
    $tasks = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    foreach($tasks as $task){
        $chatId = $task['telegram_chat_id'];
        $userTelegramName = $task['telegram_username'];
        $userTelegramId = $task['telegram_chat_id'];
        $taskTitle = $task['title'];
        $finishDate = $task['finish_date'];
        $taskId = $task['task_id'];
        $taskLink = 'https://crm-telegram.it-vimax.info/todo/tasks/task/' . $taskId;

        $text = "
Hello,  <b>$userTelegramName</b>
The task: <b>$taskTitle</b>
Dedlain: <b>$finishDate</b>
Link: $taskLink
        ";
//        tt($text);
        $telegramBot = new TelegramBot(TELEGRAM_BOT_API_KEY);
        $telegramBot->sendTelegramMessage($chatId, $text);
    }
    //recording logs
    $logFile = '../../logs/cron-task-30min.log';
    $fp = fopen($logFile, 'a');
    $date = date('Y-m-d H:i:s');
    fwrite($fp, $date . " (cron-task-30min.php script was worked)\n");
    fclose($fp);

}catch(\PDOException $e){
    echo "Error:" . $e->getMessage();
}
