<?php

session_start();

require_once 'config.php';
require_once 'autoload.php';
require_once 'models/telegram/TelegramBot.php';

use models\Check;
use models\users\User;
use models\telegram\TelegramBot;

$botApiKey = '7280417731:AAH81m6gu8BVol32murTcOG-tUttCQ6xGQw';
$telegramBot = new TelegramBot($botApiKey);

$content = file_get_contents('php://input');
$update = json_decode($content, true);

$telegramBot->handleUpdate($update);
