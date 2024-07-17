<?php



$title = 'Task create';
ob_start();
?>


<h1 class="mb-4">Task create</h1>
<form method="POST" action="/todo/tasks/store">
    <div class="row">
        <div class="mb-3 col-12 col-md-12">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
    </div>
    <div class="row">
        <div class="mb-3 col-12 col-md-6">
             <label for="category_id">Category</label>
             <select class="form-control" id="category_id" name="category_id" required>
                 <?php foreach ($categories as $category):  ?>
                 <option value="<?= $category['id'] ?>"><?= $category['title'] ?></option>
                 <?php endforeach; ?>
             </select>
        </div>
        <div class="col-12 col-md-6 mb-3">
            <label for="finish_date">Finish date</label>
            <input type="datetime-local" class="form-control" id="finish_date" name="finish_date" placeholder="Select date hour">
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Create task</button>
</form>


<?php $content = ob_get_clean();

include 'app/views/layout.php';
?>



