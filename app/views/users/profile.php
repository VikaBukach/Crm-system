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

    <hr class="mt-5">

    <h3 class="mb-4">One-time password generation</h3>
    <h5>Yor otp code: <?=$otp;?></h5>
    <p>Go to link and will found the bot in search: <a target="_blank" href="https://t.me/myDevCRM_bot">@myDevCRM_bot</a>Enter the command <strong></strong></p>
    <?php if($visible): ?>
    <p>your otp code will writting in DB</p>

    <form action="/users/otpstore" method="POST">
        <input type="hidden" name="otp" value="<?=$otp;?>">
        <input type="hidden" name="user_id" value="<?=$_SESSION['user_id'];?>">
        <button class="btn btn-primary" type="submit">Save the password</button>
    </form>
    <?php endif ?>
</div>

<?php $content = ob_get_clean();

include 'app/views/layout.php';
?>

