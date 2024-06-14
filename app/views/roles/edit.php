<?php

$title = 'Edit Role';
ob_start();
?>



<div class="row justify-content-center mt-5">
    <div class="col-lg-6 col-md-8 col-sm-10">
        <h1 class="text-center mb-4">Edit role</h1>
        <form method="POST" action="index.php?page=roles&action=update">
            <input type="hidden" name="id" value="<?= $role['id'] ?>">
            <div class="mb-3">
                <label for="role_name" class="form-label">Role name</label>
                <input type="text" class="form-control" id="role_name" name="role_name" value="<?= $role['role_name'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="role_description" class="form-label">Role Description</label>
                <textarea class="form-control" id="role_description" name="role_description" required><?= $role['role_description'] ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update role</button>
        </form>
    </div>
</div>

<?php $content = ob_get_clean();

include 'app/views/layout.php';
?>



