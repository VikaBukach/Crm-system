<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <nav class="navbar navbar-expand-lg" style="background-color: #e3f2fd;">
        <a class="navbar-brand" href="index.php">Mini CRM</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=pages">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=users">Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=roles">All Roles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=roles&action=create">Create Role</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=register">Register</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=login">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=logout">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-4">
        <?php echo $content; ?>
    </div>
</div>




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>