<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<!--    <link rel="stylesheet" href="/app/css/style.css">-->
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
                <li class="nav-item">
                    <a href="/" class="nav-link " aria-current="page">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="/"></use></svg>
                        Home
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/users" class="nav-link text-white">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="/users"></use></svg>
                        Users
                    </a>
                </li>
                <li class="nav-item">
                    <a href="index.php?page=roles" class="nav-link text-white">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="index.php?page=users"></use></svg>
                        Roles
                    </a>
                </li>
                <li class="nav-item">
                    <a href="index.php?page=pages" class="nav-link text-white">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="index.php?page=users"></use></svg>
                        Pages
                    </a>
                </li>
                <li class="nav-item">
                    <a href="index.php?page=register" class="nav-link text-white">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="index.php?page=users"></use></svg>
                        Register
                    </a>
                </li>
                <li class="nav-item">
                    <a href="index.php?page=login" class="nav-link text-white">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="index.php?page=users"></use></svg>
                        Login
                    </a>
                </li>
                <li class="nav-item">
                    <a href="index.php?page=logout" class="nav-link text-white">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="index.php?page=users"></use></svg>
                        Logout
                    </a>
                </li>
            </ul>
                <hr>
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <img class="rounded-circle me-2" src="https://github.com/VikaBukach/crm_for_teleqram" alt="" width="32" height="32">
                        <strong>mdo</strong>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                        <li><a class="dropdown-item active" href="#">New project...</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Sign out</a></li>
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
</body>
</html>