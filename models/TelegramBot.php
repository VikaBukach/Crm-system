<?php

namespace models;

use models\users\User;

class TelegramBot{
 private $botApiKey;

 public function __construct(){
     $this->botApiKey = $botApiKey;

 }

 //method for sending message in the chat with ID and text
    public function sendTelegramMessage($chatId, $text){
     //  URL generation for a request to the Telegram API:
        $url = "https://api.telegram.org/bot{$this->botApiKey}/sendMessage";

        //generation data for POST request:
        $postData = [
            'chat_id' => $chatId,
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
            return;
        }

        //get data from the message:
        $message = $update['message'];
        $chatId =  $message['chat']['id'];
        $text = $message['text'];
        $username = $message['from']['username'];

        $userModel = new User();

        try{
            //get current state of user
           $userState = $userModel->getUserState($chatId);
           $currentState = $userState ? $userState['state'] : '';
           $user_id = $userState ? $userState['user_id'] : null;

           //processing commands and text
            switch ($text) {
                case '/start':
                    $response = $this->handleStartCommand($chatId);
                    $userModel->setUserState($chatId, 'start');
                    break;
                case '/email':
                    $response = $this->handleEmailCommand($chatId);
                    $userModel->setUserState($chatId, 'email');
                    break;
                case '/help':
                    $response = $this->handleHelpCommand($chatId);
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
                $response = 'Enter your OTP code to profile...oly numbers';
                $userModel->setUserState($chatId, 'otp', $user_id);
            }else{
                $response = 'No user with this email was found';
            }

        }elseif($currentState === 'otp' && preg_match('/^\d{7}$/', $text)){
            $otpCode = intval($text);
            $otpInfo = $userModel->getOtpInfoByUserIdAndCode($user_id, $otpCode);

            if($otpInfo){
                $userModel->createUserTelegram($user_id, $chatId, $username);
                $response = 'Your code was confirmed and your accounts linked';
                $userModel->setUserState($chatId, ''); // clean state
            }else {
                $response = 'The entered code is invalid or ...';
            }
        } else {
            $response = 'I`m understand your your command. ' . $currentState;
        }
        return $response;
    }

    //methods that corresponds for processing the  commands for the telegram:
    private function handleHelpCommand($chatId)
    {
        return "List if the commands: \n/start - start works\n/email - enter email\n/ - display help";
    }

    private function handleEmailCommand($chatId)
    {
        return "Enter email from your account miniCRM...";
    }
    private function handleStartCommand($chatId)
    {
        return "When you want to use the Bot? your should added the account the tg in the MiniCRM. Will you authorization in the system and go to your profile";
    }



}