<?php
include 'db.php';
session_start();

if (!isset($_SESSION["user_level"]) || $_SESSION["user_level"] != '0') {
    header("Location: dashboard.php");
    exit();
}

if (isset($_POST["action_type"]) && $_POST["action_type"] == "CREATE_DEPARTMENT") {
    $dept_name = mysqli_real_escape_string($conn, $_POST["department_name"]);

    if ($dept_name != "") {
        $check_sql = "SELECT * FROM department WHERE department_name = '$dept_name'";
        $check_res = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_res) > 0) {
            echo "Error: This department name already exists!";
        } else {
            $insert_sql = "INSERT INTO department (department_name, is_status) VALUES ('$dept_name', '1')";
            if (mysqli_query($conn, $insert_sql)) {
                echo "SUCCESS";
            } else {
                echo "Error: Could not create department.";
            }
        }
    } else {
        echo "Error: Please fill in all required fields.";
    }

    exit();
}

if (isset($_POST['type']) && $_POST['type'] == 'CREATE_MODAL_OPEN') { ?>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="addDepartmentModalLabel">Add New Department</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row justify-content-center">
                <div class="col-md-11">
                    <div id="modal_department_msg" class="alert alert-danger d-none p-2"></div>
                    <div class="card p-3 shadow-sm">
                        <form id="createDepartmentForm" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Department Name</label>
                                <input type="text" name="department_name" class="form-control" required>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" form="createDepartmentForm" name="add_department" class="btn btn-primary btn-sm">Create Department</button>
        </div>
    </div>
    <?php
}
?>