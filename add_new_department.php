<?php
include 'db.php';
session_start();

if (!isset($_SESSION["user_level"]) || $_SESSION["user_level"] != '0') {
    header("Location: dashboard.php");
    exit();
}

if (isset($_GET["toggle_id"])) {
    $id = $_GET["toggle_id"];
    $current_status = $_GET["status"];

    if ($current_status == '1') {
        $new_status = '0';
    } else {
        $new_status = '1';
    }

    mysqli_query($conn, "UPDATE department SET is_status = '$new_status' WHERE id = $id");
    header("Location: add_new_department.php");
    exit();
}

if (isset($_GET["delete_id"])) {
    $id = $_GET["delete_id"];
    mysqli_query($conn, "DELETE FROM department WHERE id = $id");
    header("Location: add_new_department.php");
    exit();
}

include 'header.php';
?>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Departments Management</h2>
    <button type="button" class="btn btn-primary" id="openDeptBtn" title="Add New Department" onclick="addDepartmentModal()">
        <i class="fa-solid fa-plus"></i>
    </button>
</div>
<hr>

<div class="row mb-5">
    <div class="col-md-12">
        <table class="table table-bordered bg-white align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>Serial</th>
                    <th>Department Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php
                $res = mysqli_query($conn, "SELECT * FROM department");
                $serial = 1;

                while ($row = mysqli_fetch_assoc($res)) {
                    if ($row['is_status'] == '1') {
                        $status_text = 'Active';
                        $status_btn = 'Deactivate';
                        $btn_color = 'btn-secondary';
                    } else {
                        $status_text = 'Inactive';
                        $status_btn = 'Activate';
                        $btn_color = 'btn-success';
                    }

                    echo "<tr>
                        <td>{$serial}</td>
                        <td>{$row['department_name']}</td>
                        <td>{$status_text}</td>
                        <td>
                            <a href='add_new_department.php?toggle_id={$row['id']}&status={$row['is_status']}' class='btn {$btn_color} btn-sm'>$status_btn</a>
                            <a href='add_new_department.php?delete_id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                        </td>
                    </tr>";
                    $serial++;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-labelledby="addDepartmentModalLabel">
    <div class="modal-dialog" id="add_department_modal_show"></div>
</div>

<script>
    function addDepartmentModal() {
        $.ajax({
            url: "action-form-department.php",
            type: "POST",
            data: {
                type: 'CREATE_MODAL_OPEN'
            },
            success: function (data) {
                $('#add_department_modal_show').html(data);
                $('#addDepartmentModal').modal('show');
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: " + error);
            }
        });
    }

    // $(document).ready(function () {
    //     $("#openDeptBtn").on("click", addDepartmentModal);
    // });
    $(document).on("submit", "#createDepartmentForm", function (e) {
        e.preventDefault();

        var formData = $(this).serialize() + "&action_type=CREATE_DEPARTMENT";

        $.ajax({
            url: "action-form-department.php",
            type: "POST",
            data: formData,
            success: function (response) {
                var res = response.trim();
                if (res === "SUCCESS") {
                    $('#addDepartmentModal').modal('hide');
                    window.location.href = "add_new_department.php";
                } else {
                    $('#modal_department_msg').removeClass('d-none').text(res);
                }
            }
        });
    });
</script>

<?php include 'footer.php'; ?>