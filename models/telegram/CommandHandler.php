<?php

namespace models\telegram;

use models\users\User;
use models\todo\tasks\TaskModel;

class CommandHandler
{
    //methods that corresponds for processing the  commands for the telegram:
    public function handleHelpCommand()
    {
        return "List if the commands: \n/start - start works\n/email - enter email\n/ - display help";
    }

    public function handleEmailCommand()
    {
        return "Enter email from your account miniCRM...";
    }
    public function handleStartCommand()
    {
        return "When you want to use the Bot? your should added the account the tg in the MiniCRM. Will you authorization in the system and go to your profile";
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
