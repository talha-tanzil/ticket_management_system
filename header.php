<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Management System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">i_ticket_tanzil</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION["user_id"])) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="create_ticket.php">Create Ticket</a>
                        </li>
                        <?php if ($_SESSION["user_level"] == '0') { ?>
                    

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    Settings
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="add_new_category.php">Categories</a></li>
                                    <li><a class="dropdown-item" href="add_new_subcategory.php">Subcategories</a></li>
                                    <li><a class="dropdown-item" href="add_new_department.php">Departments</a></li>
                                    <li><a class="dropdown-item" href="add_new_user.php"> Users</a></li>
                                </ul>
                            </li>

                        <?php } ?>

                        <li class="nav-item">


                            <a class="nav-link btn btn-danger text-white ms-2" href="logout.php">Logout</a>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">