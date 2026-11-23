<?php
include 'db.php';
session_start();

if (!isset($_SESSION["user_level"]) || $_SESSION["user_level"] != '0') {
    header("Location: dashboard.php");
    exit();
}

// $dept_msg = "";

// if (isset($_POST["add_department"])) {
//     $dept_name = $_POST["department_name"];
    
//     if ($dept_name != "") {
//         $check_dept_sql = "SELECT * FROM department WHERE department_name = '$dept_name'";
//         $check_dept_res = mysqli_query($conn, $check_dept_sql);

//         if (mysqli_num_rows($check_dept_res) > 0) {
//             $dept_msg = "Error: This department name already exists!";
//         } else {
//             mysqli_query($conn, "INSERT INTO department (department_name, is_status) VALUES ('$dept_name', '1')");
//             header("Location: add_new_department.php");
//             exit();
//         }
//     }
// }

if (isset($_GET["toggle_dept"])) {
    $id = $_GET["toggle_dept"];
    $status = $_GET["status"];
    if ($status == '1') {
        $new_status = '0';
    } else {
        $new_status = '1';
    }
    mysqli_query($conn, "UPDATE department SET is_status = '$new_status' WHERE id = $id");
    header("Location: add_new_department.php");
    exit();
}

if (isset($_GET["delete_dept"])) {
    $id = $_GET["delete_dept"];
    mysqli_query($conn, "DELETE FROM department WHERE id = $id");
    header("Location: add_new_department.php");
    exit();
}

include 'header.php';
?>

<h2>Manage Departments</h2>
<hr>
<div class="row mb-5">
    <!-- <div class="col-md-4">
        <div class="card p-3 shadow-sm">
            <h5>Add Department</h5>
            <?php if ($dept_msg != "") { ?>
                <div class="alert alert-danger p-2"><?php echo $dept_msg; ?></div>
            <?php } ?>
            <form method="POST">
                <div class="mb-3">
                    <input type="text" name="department_name" class="form-control" placeholder="Department Name" required>
                </div>
                <button type="submit" name="add_department" class="btn btn-primary btn-sm">Add Department</button>
            </form>
        </div>
    </div> -->
    
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
                    echo "<tr>
                        <td>{$serial}</td>
                        <td>{$row['department_name']}</td>
                        <td>{$st}</td>
                        <td>
                            <a href='add_new_department.php?toggle_dept={$row['id']}&status={$row['is_status']}' class='btn {$cls} btn-sm'>$btn</a>
                            <a href='add_new_department.php?delete_dept={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
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