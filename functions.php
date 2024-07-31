<?php

//One-time password generation for connect telegram account
function generateOTP(){
    $otp = rand(1000000, 999999);
    return $otp;
}

?>