<?php

require_once '../../config.php';
require_once '../../autoload.php';
require_once '../../models/telegram/TelegramBot.php';

use models\Database;
use models\todo\tasks\TaskModel;
use models\telegram\TelegramBot;

$db = Database::getInstance()->getConnection();

try{

}catch(\PDOException $e){
    echo "Error:" . $e->getMessage();
}
