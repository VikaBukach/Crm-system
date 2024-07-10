<?php

$title = 'Edit Task';
ob_start();

?>


<h1 class="mb-4">Edit task</h1>
<form action="/todo/tasks/update" method="POST">
    <input type="hidden" name="id" value="<?= $task['id']; ?>">
    <div class="row">
        <div class="col-12 col-md-6 mb-3">
            <label for="title">Title</label>
            <input type="text" class="form-control" name="title" id="title" value="<?= htmlspecialchars($task['title']) ?>" required>
        </div>

        <div class="col-12 col-md-6 mb-3">
            <label for="reminder_at">Reminder At</label>
            <select class="form-control" name="reminder_at" id="reminder_at">
                <option value="30_minutes">30 minutes </option>
                <option value="1_hour">1 hour </option>
                <option value="2_hours">2 hours </option>
                <option value="12_hours">12 hours </option>
                <option value="24 hours">24 hours </option>
                <option value="7 days">7 days </option>
            </select>
        </div>

        <div class="row">
            <div class="col-12 col-md-6 mb-3">
            <label for="category_id">Category</label>
            <select class="form-control" name="category_id" id="category_id" required>
                <?php foreach ($categories as $category): ?>
                <option value="<?= $category['id'] ?>" <?= $category['id'] == $task['category_id'] ? 'selected' : '' ?>><?= $category['title'] ?></option>
                <?php endforeach; ?>
            </select>
            </div>
             <div class="col-12 col-md-6 mb-3">
                <label for="finish_date">Finish Date</label>
                <input class="form-control" type="datetime-local" name="finish_date" id="finish_date" value="<?= $task['finish_date'] !== null ? htmlspecialchars(str_replace('', 'T', $task['finish_date'])) : '' ?>">
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6 mb-3">
                <label for="status">Status</label>
                <select class="form-control" name="status" id="status">
                    <option value="new" <?= $task['status'] == 'new' ? 'selected' : '' ?>>New</option>
                    <option value="in_progress" <?= $task['status'] == 'in_progress' ? 'selected' : '' ?>>In progress</option>
                    <option value="completed" <?= $task['status'] == 'completed' ? 'selected': '' ?>>Completed</option>
                    <option value="on_hold" <?= $task['status'] == 'on_hold' ? 'selected' : '' ?>>On hold</option>
                    <option value="cancelled" <?= $task['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <label for="reminder_at">Priority</label>
                    <select class="form-control" name="priority" id="priority" required>
                         <option value="low" <?= $task['priority'] == 'low' ? 'selected' : '' ?>>Low</option>
                         <option value="medium" <?= $task['priority'] == 'medium' ? 'selected' : '' ?>>Medium</option>
                         <option value="high" <?= $task['priority'] == 'high' ? 'selected' : '' ?> >High</option>
                        <option value="urgent" <?= $task['priority'] == 'urgent' ? 'selected' : '' ?>>Urgent</option>
                     </select>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6 mb-3">
                <label for="tags">Tags</label>
                <div class="tags-container form-control">
                    <?php
                       $tagNames = array_map(function($tag) {
                        return $tag['name'];
                    }, $tags);
                    foreach ($tagNames as $tagName) {
                        echo "<div class='tag'>
                              <span>$tagName</span>
                              <button type='button'>x</button>
                              </div>";
                    }
                    ?>
                    <input class="form-control" type="text" id="tag-input">
                </div>
                <input class="form-control" type="hidden" id="hidden-tags" name="tags" value="<?= htmlspecialchars(implode(', ', $tagNames)) ?>">
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label for="description">Description</label>
                <textarea class="form-control" name="description" id="description" rows="3"><?php echo htmlspecialchars($task['description'] ?? ''); ?></textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <button class="btn btn-primary" type="submit">Update Task</button>
        </div>
    </div>
</form>
<script>
    const tagInput = document.querySelector('#tag-input');
    const tagsContainer = document.querySelector('.tags-container');
    const hiddenTags = document.querySelector('#hidden-tags');
    const existingTags = '<?= htmlspecialchars(isset($task['tags']) ?$task['tags'] : '') ?>';

    function createTag(text){
        const tag = document.createElement('div');
        tag.classList.add('tag');
        const tagText = document.createElement('span');
        tagText.textContent = text;

        const closeButton = document.createElement('button');
        closeButton.innerHTML = '&times';

        closeButton.addEventListener('click', () => {
            tagsContainer.removeChild(tag);
            updateHiddenTags();
        });

        tag.appendChild(tagText);
        tag.appendChild(closeButton);

        return tag;
    }

    function updateHiddenTags() {
        const tags = tagsContainer.querySelectorAll('.tag span');
        const tagText = Array.from(tags).map(tag => tag.textContent);
        hiddenTags.value = tagText.join(',');
    }

    tagInput.addEventListener('input', (e) => {
        if(e.target.value.includes(',')) {
            const tagText = e.target.value.slice(0, -1).trim();
            if (tagText.length > 1) {
                const tag = createTag(tagText);
                tagsContainer.insertBefore(tag, tagInput);
                updateHiddenTags();
            }
            e.target.value = '';
        }
    });

    tagsContainer.querySelectorAll('.tag button').forEach(button => {
        button.addEventListener('click', () => {
            tagsContainer.removeChild(button.parentElement);
            updateHiddenTags();
        });
    });

</script>




<?php $content = ob_get_clean();

include 'app/views/layout.php';
?>
