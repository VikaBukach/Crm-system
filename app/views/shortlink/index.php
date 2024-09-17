<?php

$title = 'Short links';
ob_start();
tt($short_links);
?>

<h1 class="mb-4">Short links</h1>

<a href="/shortlink/create" class="mb-3 btn btn-primary" role="button">
    Shortlinks create
</a>


<?php $content = ob_get_clean();

include 'app/views/layout.php';
?>





