<?php

$title = 'Information about navigation on a short URL';
ob_start();
//tte($informations);
?>

<h1 class="mb-4">Information about navigation on a short URL</h1>
<div class="table-responsive">
    <table class="table table-bordered information_click">
        <thead>
        <tr>
            <th>ID</th>
            <th>ID short link</th>
            <th>ID user</th>
            <th>User agent</th>
            <th>User referer</th>
            <th>Created at:</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($informations as $info): ?>
            <tr>
                <td><?php echo $info['id']; ?></td>
                <td><?php echo $info['id_short_links']; ?></td>
                <td><?php echo $info['ip_user']; ?></td>
                <td><?php echo $info['user_agent']; ?></td>
                <td><?php echo $info['user_referer']; ?></td>
                <td><?php echo $info['created_at']; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>

<?php $content = ob_get_clean();

include 'app1/views/layout.php';
?>