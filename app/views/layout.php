<?php

//checking the active menu item
function is_active($path): string
{
    $currentPath = $_SERVER['REQUEST_URI'];
    return $path === $currentPath ? 'active' : '';
}
?>

<?php $user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'no-name'; ?>

<?php $user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : false; ?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/app/css/style.css">
    <script src="https://kit.fontawesome.com/d9195b3006.js" crossorigin="anonymous"></script>
    <!--    fullcalendar  https://fullcalendar.io/    -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
        <!--    flatpickr-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

</head>
<body>
<div class="container">
    <div class="row">
        <div class="sidebar col-md-3">
            <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="min-height: 900px;">
                 <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                      <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
                      <span class="fs-4">Mini CRM</span>
                 </a>
                <hr>

            <ul class="nav nav-pills flex-column mb-auto">

<!--                --><?php //if($user_role == 5 || !ENABLE_PERMISSION_CHECK): ?>

                <li class="nav-item">
                    <a href="/" class="nav-link <?= is_active('/') ?>" aria-current="page">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="/"></use></svg>
                        Home
                    </a>
                </li>
                <li>
                    <a href="/users" class="nav-link text-white <?= is_active('/users') ?>">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="/users"></use></svg>
                        Users
                    </a>
                </li>
                <li>
                    <a href="/roles" class="nav-link text-white <?= is_active('/roles') ?>">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="/roles"></use></svg>
                        Roles
                    </a>
                </li>
                <li>
                    <a href="/pages" class="nav-link text-white <?= is_active('/pages') ?>">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="/pages"></use></svg>
                        Pages
                    </a>
                </li>

<!--                --><?php //endif ?>
                <hr>

                <h4>Quiz</h4>
                <li>
                    <a href="/quiz" class="nav-link text-white <?= is_active('/quiz') ?>">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="/quiz"></use></svg>
                        Quiz
                    </a>
                </li>
                <li>
                    <a href="/quiz/create" class="nav-link text-white <?= is_active('/quiz/create') ?>">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="/quiz/create"></use></svg>
                        Quiz create
                    </a>
                </li>
                <li>
                    <hr>
                    <a href="/shortlink" class="nav-link text-white <?= is_active('/shortlink') ?>">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="/shortlink"></use></svg>
                        Shortlinks
                    </a>
                </li>

                <hr>
                <h4>To do list</h4>

                <li>
                    <a href="/todo/tasks" class="nav-link text-white <?= is_active('/todo/tasks') ?>">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="/todo/tasks"></use></svg>
                        Opened tasks
                    </a>
                </li>
                <li>
                    <a href="/todo/tasks/completed" class="nav-link text-white <?= is_active('/todo/tasks/completed') ?>">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="/todo/tasks/completed"></use></svg>
                        Completed tasks
                    </a>
                </li>
                <li>
                    <a href="/todo/tasks/expired" class="nav-link text-white <?= is_active('/todo/tasks/expired') ?>">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="/todo/tasks/expired"></use></svg>
                        Expired tasks
                    </a>
                </li>
                <li>
                    <a href="/todo/tasks/create" class="nav-link text-white <?= is_active('/todo/tasks/create') ?>">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="/todo/tasks/create"></use></svg>
                        Create task
                    </a>
                </li>
                <li>
                    <a href="/todo/category" class="nav-link text-white <?= is_active('/todo/category') ?>">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="/todo/category"></use></svg>
                        Category
                    </a>
                </li>

            </ul>
                <hr>
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <img class="rounded-circle me-2" src="https://github.com/VikaBukach/crm_for_teleqram" alt="" width="32" height="32">
                        <strong><?=$user_email; ?></strong>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                        <li><a class="dropdown-item active" href="#">New project...</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><a class="dropdown-item" href="/users/profile">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/auth/logout">Sign out</a></li>
                        <li><a class="dropdown-item" href="/auth/login">Sign in</a></li>
                    </ul>
                </div>
        </div>
        </div>

    <div class="article col-md-9">
        <div class="container mt-4">
              <?php echo $content; ?>
        </div>
    </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="/app/js/script.js"></script>

</body>
</html>