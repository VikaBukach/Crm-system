<?php

$title = 'Todo List';
ob_start();
?>

<div class="container">
    <h1 class="mb-4">Todo list</h1>
    <a href="/todo/tasks/create" class="btn btn-success mb-3">Create Task</a>
    <div class="accordion" id="tasks-accordion">
        <?php foreach ($tasks as $task): ?>
            <div class="accordion-item mb-2">
                 <div class="accordion-header d-flex justify-content-between align-items-center row" id="task<?php echo $task['id']; ?>">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#task-collapse<?php echo $task['id'];?>" aria-expanded="false" aria-controls="task-collapse<?php echo $task['id']; ?>">
                            <span class="col-12 col-md-6"><i class="fa-solid fa-square-up-right"></i><strong><?php echo $task['title']; ?></strong></span>
                            <span class="col-6 col-md-3 text-center"><i class="fa-solid fa-person-circle-question"></i><strong><?php echo $task['priority']; ?></strong></span>
                             <span class="col-6 col-md-3 text-center"><i class="fa-solid fa-hourglass-start"></i><span class="due-date"><?php echo $task['finish_date']; ?></span></span>
                        </button>
                    </h2>
                 </div>
                <div id="task-collapse<?php echo $task['id']; ?>" class="accordion-collapse collapse row" aria-labelledby="task<?php echo $task['id']; ?>" data-bs-parent="#task-accordion">
                  <div class="accordion-body">
                    <p><strong><i class="fa-solid fa-layer-group"></i>Category:</strong> <?php echo htmlspecialchars($task['category_name'] ?? 'N/A'); ?></p>
                    <p><strong><i class="fa-solid fa-battery-three-quarters"></i>Status:</strong> <?php echo htmlspecialchars($task['status']); ?></p>
                    <p><strong><i class="fa-solid fa-person-circle-question"></i>Priority:</strong> <?php echo htmlspecialchars($task['priority']); ?></p>
                    <p><strong><i class="fa-solid fa-hourglass-start"></i>Due Date:</strong> <?php echo htmlspecialchars($task['finish_date']); ?></p>
                    <p><strong><i class="fa-solid fa-file-prescription"></i>Description:</strong> <?php echo htmlspecialchars($task['description'] ?? ''); ?></p>
                     <div class="d-flex justify-content-end">
                        <a href="edit.php?id=<?php echo $task['id']; ?>" class="btn btn-primary me-2">Edit</a>
                        <button class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </div>
         </div>
        <?php endforeach;?>
    </div>
</div>


<?php $content = ob_get_clean();

include 'app/views/layout.php';
?>





