<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


function tt($str) {
    echo "<pre>";
    print_r($str);
    echo "</pre>";
}

function tte($str) {
    echo "<pre>";
    print_r($str);
    echo "</pre>";
    exit();
}

//config.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

define('DB_HOST', 'mysql');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'crm_for_tgBot');


define('ENABLE_PERMISSION_CHECK', true); //setting the value to FALSE will disable permission checks in controllers

define('TELEGRAM_BOT_API_KEY', '7280417731:AAH81m6gu8BVol32murTcOG-tUttCQ6xGQw');


define('REMINDER_DATA', '+1 day'); //in how many days there will be a reminder about the tasks

define('TELEGRAM_CHAT_ID', '-20240912093707');  // telegram ID chat for questions

//link that know how is it ID group tg:
// 1- create bot, 2 add it in group, 3 - do this bot as admin, 4 - write text in group, 5 - see ID of group through link
//https://api.telegram.org/bot{{TOKEN}}/getUpdates