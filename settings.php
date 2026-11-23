<?php
include 'db.php';
session_start();

// Restrict to Admin only
if (!isset($_SESSION["user_level"]) || $_SESSION["user_level"] != '0') {
    header("Location: dashboard.php");
    exit();
}

// Handle Insert Category

$cat_msg = "";

if (isset($_POST["add_category"])) {
    $cat_name = $_POST["category_name"];

    if ($cat_name != "") {

        // 1. Check if the department name already exists in the database
        $check_cat_sql = "SELECT * FROM category WHERE category_name = '$cat_name'";
        $check_cat_res = mysqli_query($conn, $check_cat_sql);

        if (mysqli_num_rows($check_cat_res) > 0) {
            // Duplicate found! Set a warning message
            $cat_msg = "Error: This category name already exists!";
        } else {
            // 2. No duplicate found, safe to insert
            mysqli_query($conn, "INSERT INTO category (category_name, is_status) VALUES ('$cat_name', '1')");
            header("Location: settings.php");
            exit();
        }
    }
}


// Handle Status Toggle (Activate/Deactivate)

if (isset($_GET["toggle_id"])) {
    $id = $_GET["toggle_id"];
    $current_status = $_GET["status"];
    if ($current_status == '1') {
        $new_status = '0'; // If it was Active, change it to inactive
    } else {
        $new_status = '1'; // If it was Inactive, change it to active
    }
    mysqli_query($conn, "UPDATE category SET is_status = '$new_status' WHERE id = $id");
    header("Location: settings.php");
    exit();
}

// Handle Delete of category
if (isset($_GET["delete_id"])) {
    $id = $_GET["delete_id"];
    mysqli_query($conn, "DELETE FROM category WHERE id = $id");
    header("Location: settings.php");
    exit();
}

// 2. Handle Sub-Category Actions

$sub_msg = "";

if (isset($_POST["add_sub_category"])) {
    $category_id = $_POST["category_id"];
    $sub_name = $_POST["sub_category_name"];
    if ($category_id != "" && $sub_name != "") {

        $check_sub_sql = "SELECT * FROM sub_category WHERE category_id = '$category_id' AND sub_category_name = '$sub_name'";
        $check_sub_res = mysqli_query($conn, $check_sub_sql);

        if (mysqli_num_rows($check_sub_res) > 0) {
            //duplicate pawa gese
            $sub_msg = "Error: This sub-category name already exists under this category!";
        } else {
            // Safe to insert (including category_id)
            mysqli_query($conn, "INSERT INTO sub_category (category_id, sub_category_name, is_status) VALUES ('$category_id', '$sub_name', '1')");
            header("Location: settings.php");
            exit();
        }

    }
}


if (isset($_GET["toggle_sub"])) {
    $id = $_GET["toggle_sub"];
    $status = $_GET["status"];
    if ($status == '1') {
        $new_status = '0';
    } else {
        $new_status = '1';
    }
    mysqli_query($conn, "UPDATE sub_category SET is_status = '$new_status' WHERE id = $id");
    header("Location: settings.php");
    exit();
}
if (isset($_GET["delete_sub"])) {
    $id = $_GET["delete_sub"];
    mysqli_query($conn, "DELETE FROM sub_category WHERE id = $id");
    header("Location: settings.php");
    exit();
}

// 3. Handle Department Actions
$dept_msg = "";

if (isset($_POST["add_department"])) {
    $dept_name = $_POST["department_name"];

    if ($dept_name != "") {

        // 1. Check if the department name already exists in the database
        $check_dept_sql = "SELECT * FROM department WHERE department_name = '$dept_name'";
        $check_dept_res = mysqli_query($conn, $check_dept_sql);

        if (mysqli_num_rows($check_dept_res) > 0) {
            // Duplicate found! Set a warning message
            $dept_msg = "Error: This department name already exists!";
        } else {
            // 2. No duplicate found, safe to insert
            mysqli_query($conn, "INSERT INTO department (department_name, is_status) VALUES ('$dept_name', '1')");
            header("Location: settings.php");
            exit();
        }
    }
}


if (isset($_GET["toggle_dept"])) {
    $id = $_GET["toggle_dept"];
    $status = $_GET["status"];
    if ($status == '1') {
        $new_status = '0';
    } else {
        $new_status = '1';
    }
    mysqli_query($conn, "UPDATE department SET is_status = '$new_status' WHERE id = $id");
    header("Location: settings.php");
    exit();
}
if (isset($_GET["delete_dept"])) {
    $id = $_GET["delete_dept"];
    mysqli_query($conn, "DELETE FROM department WHERE id = $id");
    header("Location: settings.php");
    exit();
}


// Handle User Status Toggle (Activate/Deactivate)
if (isset($_GET["toggle_user"])) {
    $id = $_GET["toggle_user"];
    $status = $_GET["status"];

    if ($status == '1') {
        $new_status = '0';
    } else {
        $new_status = '1';
    }

    mysqli_query($conn, "UPDATE users SET is_status = '$new_status' WHERE id = $id");

    // ADD THIS REDIRECT BACK:
    header("Location: settings.php");
    exit();
}

// Handle User Delete
if (isset($_GET["delete_user"])) {
    $id = $_GET["delete_user"];
    mysqli_query($conn, "DELETE FROM users WHERE id = $id");

    // ADD THIS REDIRECT BACK:
    header("Location: settings.php");
    exit();
}
?>

<?php
// Handle User Creation
$user_msg = "";

if (isset($_POST["add_user"])) {
    $user_level = $_POST["user_level"];

    if ($user_level == '0') {
        $designation = "admin";
    } else {
        if ($user_level == '1') {
            $designation = "Group Admin";
        } else {
            $designation = "user";
        }
    }

    $user_name = $_POST["user_name"];
    $user_id = $_POST["user_id"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $department_id = $_POST["department_id"];

    if ($user_name != "" && $user_id != "" && $email != "" && $password != "" && $department_id != "") {

        // 1. Check if email OR user_id already exists in the database
        $check_sql = "SELECT * FROM users WHERE email = '$email' OR user_id = '$user_id'";
        $check_res = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_res) > 0) {
            // Duplicate found! Set an error message instead of inserting
            $user_msg = "Error: This Email or User ID already exists!";
        } else {
            // 2. No duplicates found, safe to insert
            $insert_user_sql = "INSERT INTO users (user_level, designation, email, user_name, user_id, password, department_id, is_status) 
                                VALUES ('$user_level', '$designation', '$email', '$user_name', '$user_id', '$password', '$department_id', '1')";
            mysqli_query($conn, $insert_user_sql);
            header("Location: settings.php");
            exit();
        }
    }
}


?>

<?php include 'header.php'; ?>

<h2>Admin Settings - Manage everything</h2>
<hr>
<!-- categories section -->
<div class="row mb-5">
    <div class="col-md-4">
        <div class="card p-3 shadow-sm">
            <h5>Add New Category</h5>

            <?php if ($cat_msg != "") { ?>
                <div class="alert alert-danger p-2"><?php echo $cat_msg; ?></div>
            <?php } ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Category Name</label>
                    <input type="text" name="category_name" class="form-control" required>
                </div>
                <button type="submit" name="add_category" class="btn btn-success btn-sm">Add Category</button>
            </form>
        </div>
    </div>

    <div class="col-md-8">
        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>Serial</th>
                    <th>Category Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query($conn, "SELECT * FROM category");
                $serial = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['is_status'] == '1') {
                        $status_text = 'Active';
                    } else {
                        $status_text = 'Inactive';
                    }
                    if ($row['is_status'] == '1') {
                        $status_btn = 'Deactivate';
                    } else {
                        $status_btn = 'Activate';
                    }
                    if ($row['is_status'] == '1') {
                        $btn_color = 'btn-secondary';
                    } else {
                        $btn_color = 'btn-success';
                    }
                    // $status_text = ($row['is_status'] == '1') ? 'Active' : 'Inactive';
                    // $status_btn = ($row['is_status'] == '1') ? 'Deactivate' : 'Activate';
                    // $btn_color = ($row['is_status'] == '1') ? 'btn-secondary' : 'btn-success';
                    echo "<tr>
                        <td>{$serial}</td>
                        <td>{$row['category_name']}</td>
                        <td>{$status_text}</td>
                        <td>
                            <a href='settings.php?toggle_id={$row['id']}&status={$row['is_status']}' class='btn {$btn_color} btn-sm'>$status_btn</a>
                            <a href='settings.php?delete_id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure that you want to?\")'>Delete</a>
                        </td>
                    </tr>";
                    $serial++;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>


<!-- SUB-CATEGORIES SECTION -->
<div class="row mb-5">
    <div class="col-md-4">
        <div class="card p-3 shadow-sm">
            <h5>Add Sub-Category</h5>
            <!-- sub-category error message -->
            <?php if ($sub_msg != "") { ?>
                <div class="alert alert-danger p-2"><?php echo $sub_msg; ?></div>
            <?php } ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Select Category</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">Choose Category</option>
                        <?php
                        $cat_dropdown = mysqli_query($conn, "SELECT * FROM category WHERE is_status = '1'");
                        while ($cat = mysqli_fetch_assoc($cat_dropdown)) {
                            echo "<option value='{$cat['id']}'>{$cat['category_name']}</option>";
                        }
                        ?>

                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Sub-Category Name</label>
                    <input type="text" name="sub_category_name" class="form-control" placeholder="Sub-Category Name"
                        required>
                </div>
                <button type="submit" name="add_sub_category" class="btn btn-primary btn-sm">Add Sub-Category</button>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <table class="table table-bordered bg-white">
            <thead class="table-dark">
                <tr>
                    <th>Serial</th>
                    <th>Category</th>
                    <th>Sub-Category Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Join category table to show the category name instead of using the ID number
                $result = mysqli_query($conn, "SELECT sub_category.*, category.category_name FROM sub_category JOIN category ON sub_category.category_id = category.id");
                $serial = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['is_status'] == '1') {
                        $st = 'Active';
                        $btn = 'Deactivate';
                        $cls = 'btn-secondary';
                    } else {
                        $st = 'Inactive';
                        $btn = 'Activate';
                        $cls = 'btn-success';
                    }
                    echo "<tr>
                        <td>{$serial}</td>
                        <td>{$row['category_name']}</td>
                        <td>{$row['sub_category_name']}</td>
                        <td>{$st}</td>
                        <td>
                            <a href='settings.php?toggle_sub={$row['id']}&status={$row['is_status']}' class='btn {$cls} btn-sm'>$btn</a>
                            <a href='settings.php?delete_sub={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                        </td>
                    </tr>";
                    $serial++;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- SUB-CATEGORIES SECTION -->
<!-- <div class="row mb-5">
    <div class="col-md-4">
        <div class="card p-3 shadow-sm">
            <h5>Add Sub-Category</h5>
            <form method="POST">
                <div class="mb-3">
                    <input type="text" name="sub_category_name" class="form-control" placeholder="Sub-Category Name" required>
                </div>
                <button type="submit" name="add_sub_category" class="btn btn-primary btn-sm">Add Sub-Category</button>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <table class="table table-bordered bg-white">
            <thead class="table-dark"><tr><th>ID</th><th>Sub-Category Name</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
                <?php
                $result1 = mysqli_query($conn, "SELECT * FROM sub_category");
                while ($row1 = mysqli_fetch_assoc($result1)) {
                    if ($row1['is_status'] == '1') {
                        $st = 'Active';
                        $btn = 'Deactivate';
                        $cls = 'btn-secondary';
                    } else {
                        $st = 'Inactive';
                        $btn = 'Activate';
                        $cls = 'btn-success';
                    }
                    echo "<tr><td>{$row1['id']}</td><td>{$row1['sub_category_name']}</td><td>{$st}</td><td>
                        <a href='settings.php?toggle_sub={$row1['id']}&status={$row1['is_status']}' class='btn {$cls} btn-sm'>$btn</a>
                        <a href='settings.php?delete_sub={$row1['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure that you want to delete?\")'>Delete</a>
                    </td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div> -->

<!-- DEPARTMENTS SECTION -->

<div class="row mb-5">
    <div class="col-md-4">
        <div class="card p-3 shadow-sm">
            <h5>Add Department</h5>
            <?php if ($dept_msg != "") { ?>
                <div class="alert alert-danger p-2"><?php echo $dept_msg; ?></div>
            <?php } ?>
            <form method="POST">
                <div class="mb-3">
                    <input type="text" name="department_name" class="form-control" placeholder="Department Name"
                        required>
                </div>
                <button type="submit" name="add_department" class="btn btn-primary btn-sm">Add Department</button>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <table class="table table-bordered bg-white">
            <thead class="table-dark">
                <tr>
                    <th>Serial</th>
                    <th>Department Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $res = mysqli_query($conn, "SELECT * FROM department");
                $serial = 1;
                while ($row = mysqli_fetch_assoc($res)) {
                    if ($row['is_status'] == '1') {
                        $st = 'Active';
                        $btn = 'Deactivate';
                        $cls = 'btn-secondary';
                    } else {
                        $st = 'Inactive';
                        $btn = 'Activate';
                        $cls = 'btn-success';
                    }
                    echo "<tr><td>{$serial}</td><td>{$row['department_name']}</td><td>{$st}</td><td>
                        <a href='settings.php?toggle_dept={$row['id']}&status={$row['is_status']}' class='btn {$cls} btn-sm'>$btn</a>
                        <a href='settings.php?delete_dept={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                    </td></tr>";

                    $serial++;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>



<!-- USERS SECTION -->
<div class="row mb-5">
    <div class="col-md-4">
        <div class="card p-3 shadow-sm">
            <h5>Create New User</h5>

            <?php if ($user_msg != "") { ?>
                <div class="alert alert-danger p-2">
                    <?php echo $user_msg; ?>
                </div>
            <?php } ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="user_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">User ID (Unique)</label>
                    <input type="text" name="user_id" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email (Unique)</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Department</label>
                    <select name="department_id" class="form-control" required>
                        <option value="">Select Department</option>
                        <?php
                        $dept_res = mysqli_query($conn, "SELECT * FROM department WHERE is_status = '1'");
                        while ($d = mysqli_fetch_assoc($dept_res)) {
                            echo "<option value='{$d['id']}'>{$d['department_name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">User Level / Role</label>
                    <select name="user_level" class="form-control" required>
                        <option value="">Select User</option>
                        <option value="2">User (Level 2)</option>
                        <option value="1">Group Admin (Level 1)</option>
                        <option value="0">Admin (Level 0)</option>
                    </select>
                </div>
                <button type="submit" name="add_user" class="btn btn-primary btn-sm">Create User</button>
            </form>
        </div>
    </div>


    <!-- user_id -->
    <div class="col-md-8">
        <table class="table table-bordered bg-white">
            <thead class="table-dark">
                <tr>
                    <th>Serial</th>
                    <th>Name</th>
                    <th>User ID</th>
                    <th>Role</th>
                    <th>Password</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Join users and department tables to display the department name clearly
                $user_query = "SELECT users.*, department.department_name FROM users JOIN department ON users.department_id = department.id";
                $user_res = mysqli_query($conn, $user_query);
                $serial = 1;

                while ($u_row = mysqli_fetch_assoc($user_res)) {
                    if ($u_row['is_status'] == '1') {
                        $st = 'Active';
                        $btn = 'Deactivate';
                        $cls = 'btn-secondary';
                    } else {
                        $st = 'Inactive';
                        $btn = 'Activate';
                        $cls = 'btn-success';
                    }

                    // Format user level representation
                    if ($u_row['user_level'] == '0') {
                        $role_text = "Admin";
                    } else {
                        if ($u_row['user_level'] == '1') {
                            $role_text = "Group Admin";
                        } else {
                            $role_text = "User";
                        }
                    }

                    // <td>{$role_text}</td>
                
                    echo "<tr>
                        <td>{$serial}</td>
                        <td>{$u_row['user_name']}</td>
                        <td>{$u_row['user_id']}</td>
                        <td>{$u_row['designation']}</td>
                        <td>{$u_row['password']}</td>
                        <td>{$u_row['department_name']}</td>
                        <td>{$st}</td>
                        <td>
                            <a href='settings.php?toggle_user={$u_row['id']}&status={$u_row['is_status']}' class='btn {$cls} btn-sm'>$btn</a>
                            <a href='settings.php?delete_user={$u_row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a>
                        </td>
                    </tr>";
                    $serial++;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>