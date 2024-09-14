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

define('DB_HOST', '');
define('DB_USER', '');
define('DB_PASS', '');
define('DB_NAME', '');


define('ENABLE_PERMISSION_CHECK', true); //setting the value to FALSE will disable permission checks in controllers

define('TELEGRAM_BOT_API_KEY', '');


define('REMINDER_DATA', '+1 day'); //in how many days there will be a reminder about the tasks

define('TELEGRAM_CHAT_ID', '');  // telegram ID chat for questions

