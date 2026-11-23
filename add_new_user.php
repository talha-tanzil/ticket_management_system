<?php
include 'db.php';
session_start();

if (!isset($_SESSION["user_level"]) || $_SESSION["user_level"] != '0') {
    header("Location: dashboard.php");
    exit();
}

// $user_msg = "";

// if (isset($_POST["add_user"])) {
//     $user_level = $_POST["user_level"];

//     if ($user_level == '0') {
//         $designation = "admin";
//     } else {
//         if ($user_level == '1') {
//             $designation = "Group Admin";
//         } else {
//             $designation = "user";
//         }
//     }

//     $user_name = $_POST["user_name"];
//     $user_id = $_POST["user_id"];
//     $email = $_POST["email"];
//     $password = $_POST["password"];
//     $department_id = $_POST["department_id"];

//     if ($user_name != "" && $user_id != "" && $email != "" && $password != "" && $department_id != "") {
//         $check_sql = "SELECT * FROM users WHERE email = '$email' OR user_id = '$user_id'";
//         $check_res = mysqli_query($conn, $check_sql);

//         if (mysqli_num_rows($check_res) > 0) {
//             $user_msg = "Error: This Email or User ID already exists!";
//         } else {
//             $insert_user_sql = "INSERT INTO users (user_level, designation, email, user_name, user_id, password, department_id, is_status) 
//                                 VALUES ('$user_level', '$designation', '$email', '$user_name', '$user_id', '$password', '$department_id', '1')";
//             mysqli_query($conn, $insert_user_sql);
//             header("Location: add_new_user.php");
//             exit();
//         }
//     }
// }

if (isset($_GET["toggle_user"])) {
    $id = $_GET["toggle_user"];
    $status = $_GET["status"];

    if ($status == '1') {
        $new_status = '0';
    } else {
        $new_status = '1';
    }

    mysqli_query($conn, "UPDATE users SET is_status = '$new_status' WHERE id = $id");
    header("Location: add_new_user.php");
    exit();
}

if (isset($_GET["delete_user"])) {
    $id = $_GET["delete_user"];
    mysqli_query($conn, "DELETE FROM users WHERE id = $id");
    header("Location: add_new_user.php");
    exit();
}

include 'header.php';
?>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<!-- <h2>Users Management</h2> -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Users Management</h2>
    <!-- Button to trigger the modal -->

    <!-- Button to trigger the modal (Removed data-bs-toggle to prevent empty flash) -->
<button type="button" class="btn btn-primary" title="Add New User" onclick="addUserModal()">
    <i class="fa-solid fa-plus"></i>
</button>
    <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal"
        data-bs-toggle="tooltip" data-bs-placement="top" title="Add New User" onclick="addUserModal()">
        <i class="fa-solid fa-plus"></i>
    </button> -->
</div>
<hr>
<div class="row mb-5">
    <!-- <div class="col-md-4">
        <div class="card p-3 shadow-sm">
            <h5>Create New User</h5>
            <?php if ($user_msg != "") { ?>
                <div class="alert alert-danger p-2"><?php echo $user_msg; ?></div>
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
                        <option value="">Select Role</option>
                        <option value="2">User (Level 2)</option>
                        <option value="1">Group Admin (Level 1)</option>
                        <option value="0">Admin (Level 0)</option>
                    </select>
                </div>
                <button type="submit" name="add_user" class="btn btn-primary btn-sm">Create User</button>
            </form>
        </div>
    </div> -->

    <div class="col-md-12">
        <table class="table table-bordered bg-white align-middle">
            <thead class="table-dark text-center">
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
            <tbody class="text-center">
                <?php
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

                    if ($u_row['user_level'] == '0') {
                        $role_text = "Admin";
                    } else {
                        if ($u_row['user_level'] == '1') {
                            $role_text = "Group Admin";
                        } else {
                            $role_text = "User";
                        }
                    }

                    echo "<tr>
                        <td>{$serial}</td>
                        <td>{$u_row['user_name']}</td>
                        <td>{$u_row['user_id']}</td>
                        <td>{$u_row['designation']}</td>
                        <td>{$u_row['password']}</td>
                        <td>{$u_row['department_name']}</td>
                        <td>{$st}</td>
                        <td>
                            <a href='add_new_user.php?toggle_user={$u_row['id']}&status={$u_row['is_status']}' class='btn {$cls} btn-sm'>$btn</a>
                            <a href='add_new_user.php?delete_user={$u_row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a>
                        </td>
                    </tr>";
                    $serial++;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Structure (Content can be added later) -->

<!-- removed aria-hidden="true" from div -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" >
    <div class="modal-dialog" id="add_user_modal_show">
        <!-- add new user dynamically in another page -->
    </div>
</div>

<!-- Initialize Bootstrap Tooltips via JS -->
<script>
    function addUserModal() {
        $.ajax({
            url: "action-form-user.php",
            type: "POST",
            data: {
                type: 'CREATE_MODAL_OPEN'
            },
            success: function (data) {
                console.log(data);

                $('#addUserModal').modal('show');
                $('#add_user_modal_show').html(data);
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: " + error);
            }
        });
    }

    $(document).on("submit", "#createUserForm", function (e) {
        e.preventDefault();

        var formData = $(this).serialize() + "&action_type=CREATE_USER";

        $.ajax({
            url: "action-form-user.php",
            type: "POST",
            data: formData,
            success: function (response) {
                var res = response.trim();
                if (res === "SUCCESS") {
                    $('#addUserModal').modal('hide');
                    window.location.href = "add_new_user.php";
                } else {
                    $('#modal_user_msg').removeClass('d-none').text(res);
                }
            }
        });
    });
</script>

<?php include 'footer.php'; ?>