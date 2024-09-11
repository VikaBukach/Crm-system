<?php

namespace models\telegram;

use models\users\User;
use \models\telegram\CommandHandler;

class TelegramBot{
 private $botApiKey;

 public function __construct($botApiKey){
     $this->botApiKey = $botApiKey;
 }

 //method for sending message in the chat with ID and text
    public function sendTelegramMessage($chatId, $text){
     //  URL generation for a request to the Telegram API:
        $url = "https://api.telegram.org/bot{$this->botApiKey}/sendMessage";

        //generation data for POST request:
        $postData = [
            'chat_id' => $chatId,
            'parse_mode' => 'HTML',
            'text' => $text,
        ];

        //session initialization with cURL:
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

         // execution cURL request:
        $response = curl_exec($ch);
        curl_close($ch);

        // decode the string and send it return
        return json_decode($response,true);
    }

    //method for processing updates from Telegram
    public function handleUpdate($update)
    {
     //if not the message, stop processing
        if(!isset($update['message'])){
            file_put_contents('response.txt', ["if not the message, stop processing"], FILE_APPEND);
            return;
        }

        //get data from the message:
        $message = $update['message'];
        $chatId =  $message['chat']['id'];
        $text = $message['text'];
        $username = $message['from']['username'];

        $userModel = new User();
        $commandHandler = new CommandHandler();

        try{
            //get current user state
           $userState = $userModel->getUserState($chatId);
           $currentState = $userState ? $userState['state'] : '';
           $user_id = $userState ? $userState['user_id'] : null;

           //processing commands and text
//            file_put_contents('response.txt', ["//processing commands and text"], FILE_APPEND);
            switch ($text) {
                case '/start':
                    $response = $commandHandler->handleStartCommand($chatId);
                    $userModel->setUserState($chatId, 'start');
                    break;
                case '/addaccount':
                    $response = $commandHandler->handleEmailCommand($chatId);
                    $userModel->setUserState($chatId, 'email');
                    break;
                case '/help':
                    $response = $commandHandler->handleHelpCommand($chatId);
                    break;
                case '/task':
                    $response = $commandHandler->handleTaskCommand($chatId);
                    break;
                default:
                    $response = $this->handleMessage($text, $currentState, $chatId, $userModel, $user_id, $username);
            }
        }catch(\Exception $e){
            error_log("Error: " . $e->getMessage() . "\n", 3, 'logs/error.log');
            $response = 'Error. You should try again';
        }

        $this->sendTelegramMessage($chatId, $response);
    }

    private function handleMessage($text, $currentState, $chatId, $userModel, $user_id, $username)
    {
        if($currentState === 'email') {
            $user = $userModel->getUserByEmail($text);

            if($user){
                $user_id = $user['id'];
                $response = 'Enter your OTP code...';
                $userModel->setUserState($chatId, 'otp', $user_id);
            }else{
                $response = 'No user with this email was found';
            }

        }elseif($currentState === 'otp' && preg_match('/^\d{7}$/', $text)){
            $otpCode = intval($text);
            $otpInfo = $userModel->getOtpInfoByUserIdAndCode($user_id, $otpCode);

            if($otpInfo){
                $userModel->createUserTelegram($user_id, $chatId, $username);
                $response = 'Your code was confirmed and your accounts linked.';
                $userModel->setUserState($chatId, ''); // clean state
            }else {
                $response = 'The entered code is invalid or old.';
            }
        } else {
            $response = 'I`m understand your command. ' . $currentState;
        }
        return $response;
    }

    //method for sending quiz message
    public function sendTelegramQuizMessage($data){
        //  URL generation for a request to the Telegram API:
        $url = "https://api.telegram.org/bot{$this->botApiKey}/sendMessage";

        //generation data for POST request:
        $postData = [
            'chat_id' => $data['chat_id'],
            'parse_mode' => 'HTML',
            'question' => $data['question'],
            'options' => json_encode($data['options']),
            'is_anonymous' => $data['is_anonymous'], // true & false
            'allows_multiple_answers' => $data['allows_multiple_answers'],
            'type' => 'quiz',
            'correct_option_id' =>$data['correct_option_id'],
            'explanation' => $data['explanation']
        ];

        //session initialization with cURL:
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        // execution cURL request:
        $response = curl_exec($ch);
        curl_close($ch);

        // decode the string and send it return
        return json_decode($response,true);
    }


}