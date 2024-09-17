<?php

$title = 'Edit shortlink';
ob_start();

?>

<h1 class="mb-4">Edit shortlink</h1>

<form method="POST" action="/shortlink/update">
    <div class="mb-3">
        <label for="title_link" class="form-label">Title Link</label>
        <input type="text" class="form-control" id="title_link" name="title_link" value="<?=$short_link['title_link'] ?>" required>
        <input type="hidden" name="short_link_id" value="<?=$short_link['id'];?>">
    </div>
    <div class="mb-3">
        <label for="original_url" class="form-label">Original URL</label>
        <input type="url" class="form-control" id="original_url" name="original_url" value="<?=$short_link['original_url'] ?>" required>
        <div class="form-text">
            <ul>
                <li>The field must contain at least 10 characters.</li>
                <li>The first characters of the field must always start with: "https://".</li>
                <li>The field must contain at least one period "." — this ensures that the domain and zone are separated by a period.</li>
                <li>The field must contain at least one "/" character.</li>
            </ul>
        </div>

    </div>
    <div class="mb-3">
        <label for="short_code" class="form-label">Short code</label>
        <input type="text" class="form-control" id="short_code" name="short_code" value="<?=$short_link['short_url'] ?>">
        <div class="form-text">
            <ul>
                <li>The field must contain only English characters.</li>
                <li>No special characters are allowed; only the "-" separator is permitted.</li>
                <li>The link must not contain any spaces.</li>
                <li>The first character must always be a letter, not a number.</li>
                <li>The length must be at least 5 characters.</li>
            </ul>
        </div>

    </div>
    <button type="submit" class="btn btn-primary">Update link</button>
</form>


<?php $content = ob_get_clean();

include 'app/views/layout.php';
?>





