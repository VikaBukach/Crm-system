<?php

$title = 'Todo list completed';
ob_start();
?>

<div class="container">
    <h1 class="mb-4">Todo list completed</h1>
    <div class="flex justify-content-around row filter-priority">
        <a class="btn mb-3 col-2 sort-btn" data-priority="low" style="background: #51A5F4">Low</a>
        <a class="btn mb-3 col-2 sort-btn" data-priority="medium" style="background: #3C7AB5">Medium</a>
        <a class="btn mb-3 col-2 sort-btn" data-priority="high" style="background: #274F75">High</a>
        <a class="btn mb-3 col-2 sort-btn" data-priority="urgent" style="background: #122436">Urgent</a>
    </div>
    <div class="accordion" id="tasks-accordion">
        <?php foreach ($completedTasks as $task): ?>
            <?php
            $priorityColor ='';
            switch($task['priority']) {
                case 'low' :
                    $priorityColor = '#51A5F4';
                    break;
                case 'medium' :
                    $priorityColor = '#3C7AB5';
                    break;
                case 'high' :
                    $priorityColor = '#274F75';
                    break;
                case 'urgent' :
                    $priorityColor = '#122436';
                    break;
            }
            ?>
            <div class="accordion-item mb-2">
                <div class="accordion-header d-flex justify-content-between align-items-center row" id="task<?php echo $task['id']; ?>">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" style="background: <?=$priorityColor ?>;" data-bs-toggle="collapse" data-bs-target="#task-collapse<?php echo $task['id'];?>" aria-expanded="false" aria-controls="task-collapse<?php echo $task['id']; ?>" data-priority="<?php echo $task['priority']; ?>>
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
                            <a href="/todo/tasks/edit/<?php echo $task['id']; ?>" class="btn btn-primary me-2">Edit</a>
                            <a href="/todo/tasks/delete/<?php echo $task['id']; ?>" class="btn btn-danger me-2">Delete</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
    </div>
</div>

<script>    // countdown:
    function updateRemainingTime(){

        const dueDateElements = document.querySelectorAll('.due-date');

        const now = new Date();

        dueDateElements.forEach((element) => {
            const dueDate = new Date(element.textContent);
            const timeDiff = dueDate - now;

            if(timeDiff > 0) {
                const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));

                // element.textContent = `Days: ${days} Hours: ${hours} Minutes: ${minutes}`;
                element.textContent = `Days: ${days} Hours: ${hours}`;

            } else{
                element.textContent = 'Time is up';
            }
        });
    }

    updateRemainingTime();
    setInterval(updateRemainingTime, 60000); // update every minute
</script>

<?php $content = ob_get_clean();

include 'app/views/layout.php';
?>





