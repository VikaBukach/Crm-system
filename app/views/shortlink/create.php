<?php

$title = 'Create shortlink';
ob_start();

?>
<h1 class="mb-4">Create shortlink</h1>

<form method="POST" action="/shortlink/store">
    <div class="mb-3">
        <label for="title_link" class="form-label">Title Link</label>
        <input type="text" class="form-control" id="title_link" name="title_link" required>
        <input type="hidden" name="user_id" value="<?=$userId;?>">
    </div>
    <div class="mb-3">
        <label for="original_url" class="form-label">Original URL</label>
        <input type="url" class="form-control" id="original_url" name="original_url" required>
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
        <input type="text" class="form-control" id="short_code" name="short_code">
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
    <button type="submit" class="btn btn-primary">Create shortlink</button>
</form>

<script>

//        Processing the short link creation form:
//original_url
const originalUrlInput = document.getElementById('original_url');
originalUrlInput.addEventListener('blur', (event)=>{
    const value = event.target.value.trim();

    if(value.length < 13 || !value.startsWith('https://') || value.indexOf('.') === -1 || value.indexOf('/') === -1){
        event.target.setCustomValidity('Check if the specified URL meets the requirements')
    }else{
        event.target.setCustomValidity('')
    }
});
//short_url
const shortCodeInput = document.getElementById('short_code');
shortCodeInput.addEventListener("input", () => {
    const shortCode = shortCodeInput.value;
    const regex = /^[a-zA-Z][a-zA-Z0-9-]{5,}$/;

    if(!regex.test(shortCode)){
        shortCodeInput.setCustomValidity("Check if the specified code the requirements");
    }else{
        shortCodeInput.setCustomValidity("");
    }
    shortCodeInput.reportValidity();
});


</script>

<?php $content = ob_get_clean();

include 'app/views/layout.php';
?>




