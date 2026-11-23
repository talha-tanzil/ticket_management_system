<?php
include 'db.php';
session_start();

if (!isset($_SESSION["user_level"]) || $_SESSION["user_level"] != '0') {
    header("Location: dashboard.php");
    exit();
}

// Handle saving new user (via AJAX or POST)
if (isset($_POST["action_type"]) && $_POST["action_type"] == "CREATE_USER") {
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

    $user_name = mysqli_real_escape_string($conn, $_POST["user_name"]);
    $user_id = mysqli_real_escape_string($conn, $_POST["user_id"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $department_id = mysqli_real_escape_string($conn, $_POST["department_id"]);
    $user_level = mysqli_real_escape_string($conn, $user_level);
    $designation = mysqli_real_escape_string($conn, $designation);

    if ($user_name != "" && $user_id != "" && $email != "" && $password != "" && $department_id != "") {
        $check_sql = "SELECT * FROM users WHERE email = '$email' OR user_id = '$user_id'";
        $check_res = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_res) > 0) {
            echo "ERROR: This Email or User ID already exists!";
        } else {
            $insert_user_sql = "INSERT INTO users (user_level, designation, email, user_name, user_id, password, department_id, is_status) 
                                VALUES ('$user_level', '$designation', '$email', '$user_name', '$user_id', '$password', '$department_id', '1')";
            if (mysqli_query($conn, $insert_user_sql)) {
                echo "SUCCESS";
            } else {
                echo "ERROR: Could not create user.";
            }
        }
    } else {
        echo "ERROR: Please fill in all required fields.";
    }
    exit();
}

if (isset($_POST['type']) && $_POST['type'] == 'CREATE_MODAL_OPEN') { ?>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row justify-content-center">
                <div class="col-md-11">
                    <div id="modal_user_msg" class="alert alert-danger d-none p-2"></div>
                    <form id="createUserForm" method="POST">
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
                                <option value="">Select Role</option>
                                <option value="2">User (Level 2)</option>
                                <option value="1">Group Admin (Level 1)</option>
                                <option value="0">Admin (Level 0)</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" form="createUserForm" name="add_user" class="btn btn-primary btn-sm">Create User</button>
        </div>
    </div>
    <?php
}
?>