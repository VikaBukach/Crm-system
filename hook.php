<?php

require_once 'vendor/autoload.php';

use Telegram\Bot\Api;

$botApiKey = '7280417731:AAH81m6gu8BVol32murTcOG-tUttCQ6xGQw';
$botUsername  = '@myDevCRM_bot';
$telegram = new Api($botApiKey);
$update = $telegram->getWebhookUpdate();
$chatId = $update->getMessage()->getChat()->getId();
$text = $update->getMessage()->getText();
$username = $update->getMessage()->getForm()->getUsername;

//create directory 'logs' if it doesn`t create for logs

if(!file_exists('logs')){
    mkdir('logs', 0755, true);
}

// specify the name of the file with the year
$logFileName  = sprintf('logs/%s_telegram_bot_user_message.log', date('Y_m'));

//recording log with inform about appeal:
$logMessage = sprint(
    "[%] User: %s (ID: %d)  sent message: %s\n",
    date('Y-m-d H:i:s'),
    $username,
    $chatId,
    $text
);
error_log($logMessage,3, $logFileName);

switch ($text){
    case '/start':
        $response = 'You are welcome ' . $username  . 'in telegram bot: ' .$botUsername;
        break;
    case '/validate':
        $response = 'You need to pass validation, enter the code:';
        break;
    default:
        $response = 'I don`t understand you, please again the code';
}

$telegram->sendMessage([
    'chat_id' => $chatId,
    'text' => $response
]);
