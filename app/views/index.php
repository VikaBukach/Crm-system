<?php

$title = 'Home page';
ob_start();

?>


<h1>Home page</h1>
<div id='calendar'></div>

<?php $path = '/todo/tasks/task/'; ?>

<script>
    //get data about tasks from php controller
    const tasksJson = <?= json_encode($tasksJson); ?>;
    const tasks = JSON.parse(tasksJson);   // tasks-it is the array of objects

    // Array data conversion in the task for calendar
    const events = tasks.map((task) => {
        return {
            title: task.title,
            start: new Date(task.created_at),
            end: new Date(task.finish_date),
            extendedProps: {
                task_id: task.id
            },
        };
    });

    //load event handler DOM
    document.addEventListener("DOMContentLoaded", function() {
        const calendarEl = document.getElementById('calendar');

        //  initializing the calendar
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: events,  //tasks in view events on the calendar
            eventClick: function(info) {
            const taskId = info.event.extendedProps.task_id;

            //  url the task
                const taskUrl = `<?=$path;?>${taskId}`;

                //link page of the task
                window.location.href = taskUrl;
        },
        });
        calendar.render();
    });
</script>


<?php $content = ob_get_clean();

include 'app1/views/layout.php';
?>

