<?php

namespace models\telegram;

use models\users\User;
use models\todo\tasks\TaskModel;

 class CommandHandler
{
    //methods that corresponds for processing the  commands for the telegram:
    public function handleHelpCommand()
    {
        return "List the commands: \n/start - start work\n/addaccount - attach the Telegram\n/task - task statuses";
    }

    public function handleEmailCommand()
    {
        return "Enter email from your account CRM...";
    }
    public function handleStartCommand()
    {
        return "To be able to use this bot, you need to link your Telegram account to your crm account. First, go to https://crm-telegram.it-vimax.info and follow the instructions in the profile section. ";
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

        $userTelegram = $userTelegram['telegram_username'];
        $allTasks = $tasks['all_tasks'];
        $opened = $tasks['opened'];
        $completed =  $tasks['completed'];
        $expired = $tasks['expired'];

        $text = "
👋 Hello,  <b>$userTelegram</b>

👨‍💻Your all tasks: <b>$allTasks</b>

❗️Opened: <b>$opened</b>

✅Completed:  <b>$completed</b>

👎Expired:  <b>$expired</b>
        ";

        return $text;
    }
}
