<?php

namespace models\telegram;

use models\users\User;
use models\todo\tasks\TaskModel;

class CommandHandler
{
    //methods that corresponds for processing the  commands for the telegram:
    public function handleHelpCommand()
    {
        return "List the commands: \n/start - start works\n/email - enter email\n/ - display help";
    }

    public function handleEmailCommand()
    {
        return "Enter email from your account miniCRM...";
    }
    public function handleStartCommand()
    {
        return "Do you want to use the bot? You must add your Telegram account to MiniCRM. Log in to the system in the profile section, then execute the command /email";
    }
    public function handleTaskCommand($chat_id)
    {
        $userModel = new User();
        $userTelegram = $userModel->getUserByTelegramChatId($chat_id);
        $user_id = $userTelegram['user_id'];

        $taskModel = new TaskModel();
        $tasks = $taskModel->getTaskCountAndStatusByUserId($user_id);
        $tasks = json_encode($tasks);
        $tasks = json_decode($tasks, true);
        $obj = $tasks[0];

//        $text = "✋Hello: <b>userTelegram['telegram_username']</b>
//        All tasks: $obj['all_tasks']
//        ";


        return "Hello: " . $userTelegram['telegram_username'] .
            "All tasks: " . $obj['all_tasks'] . "Completed: " . $obj['completed']
            . "Expired: " . $obj['expired'] . "Opened: " . $obj['opened'];
    }
}
