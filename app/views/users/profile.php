<?php

$title = 'Profile';
ob_start();

?>

<!--main content -->
<div class="container mt-5">
<h1 class=" mb-4">User`s profile</h1>
    <div class="card">
        <div class="card-body">
            <table class="table table-hover table-responsive">
                <tbody>
                <tr>
                    <th scope="row">ID</th>
                    <td><?php echo $user['id']; ?></td>
                </tr>
                <tr>
                    <th scope="row">User`s name</th>
                    <td><?php echo $user['username']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Email</th>
                    <td><?php echo $user['email_verification'] ? 'Yes' : 'No'; ?></td>
                </tr>
                <tr>
                <tr>
                    <th scope="row">The account was linked to Telegram</th>
                    <td><?php echo $isUserTelegram['telegram_username'] ? $isUserTelegram['telegram_username'] : 'No'; ?></td>
                </tr>
                <tr>
                    <th scope="row">Admin</th>
                    <td><?php echo $user['is_admin'] ? 'Yes' : 'No'; ?></td>
                </tr>
                <tr>
                    <th scope="row">Role</th>
                    <td><?php echo $user['role']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Created date</th>
                    <td><?php echo $user['created_at']; ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <?php if(!$isUserTelegram): ?>
    <hr class="mt-5">

    <h3 class="mb-4">One-time password generation</h3>
    <h5>Yor otp code: <?=$otp;?></h5>
    <ul class="list-group">
        <li class="list-group-item">Press<strong>Save the password</strong></li>
        <li class="list-group-item">Go to in the Telegram and search the bot: <a target="_blank" href="https://t.me/myDevCRM_bot">@myDevCRM_bot</a>.</li>
        <li class="list-group-item">Enter the command /email/</li>
        <li class="list-group-item">The bot will request the OTP code, here is: <strong><?php echo $user['email']; ?></strong> </li>
        <li class="list-group-item">The bot will request your email, here is: <strong><?php echo $otp; ?></strong> </li>
        <li class="list-group-item">If you have done this, your accounts will be linked</li>
    </ul>

    <?php if($visible): ?>
    <p style="color:olivedrab"> This OTP code will writting in DB before "Save the password" and will be available for authorization 1 hour</p>

    <form action="/users/otpstore" method="POST">
        <input type="hidden" name="otp" value="<?=$otp;?>">
        <input type="hidden" name="user_id" value="<?=$_SESSION['user_id'];?>">
        <button class="btn btn-primary" type="submit">Save the password</button>
    </form>
    <?php endif ?>
    <?php endif ?>
</div>

<?php $content = ob_get_clean();

include 'app/views/layout.php';
?>

