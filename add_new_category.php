<?php
include 'db.php';
session_start();

if (!isset($_SESSION["user_level"]) || $_SESSION["user_level"] != '0') {
    header("Location: dashboard.php");
    exit();
}

// $cat_msg = "";

// if (isset($_POST["add_category"])) {
//     $cat_name = $_POST["category_name"];
    
//     if ($cat_name != "") {
//         $check_cat_sql = "SELECT * FROM category WHERE category_name = '$cat_name'";
//         $check_cat_res = mysqli_query($conn, $check_cat_sql);

//         if (mysqli_num_rows($check_cat_res) > 0) {
//             $cat_msg = "Error: This category name already exists!";
//         } else {
//             mysqli_query($conn, "INSERT INTO category (category_name, is_status) VALUES ('$cat_name', '1')");
//             header("Location: add_new_category.php");
//             exit();
//         }
//     }
// }

if (isset($_GET["toggle_id"])) {
    $id = $_GET["toggle_id"];
    $current_status = $_GET["status"];
    if ($current_status == '1') {
        $new_status = '0';
    } else {
        $new_status = '1';
    }
    mysqli_query($conn, "UPDATE category SET is_status = '$new_status' WHERE id = $id");
    header("Location: add_new_category.php");
    exit();
}

if (isset($_GET["delete_id"])) {
    $id = $_GET["delete_id"];
    mysqli_query($conn, "DELETE FROM category WHERE id = $id");
    header("Location: add_new_category.php");
    exit();
}

include 'header.php';
?>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<div class="d-flex justify-content-between align-items-center mb-3">
<h2 class="mb-0">Categories Management</h2>
<button type="button" class="btn btn-primary" title="Add New Category" onclick="addCategoryModal()">
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
                    <th>Category Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php
                $result = mysqli_query($conn, "SELECT * FROM category");
                $serial = 1;
                while ($row = mysqli_fetch_assoc($result)) {
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
                        <td>{$row['category_name']}</td>
                        <td>{$status_text}</td>
                        <td>
                            <a href='add_new_category.php?toggle_id={$row['id']}&status={$row['is_status']}' class='btn {$btn_color} btn-sm'>$status_btn</a>
                            <a href='add_new_category.php?delete_id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
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
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" >
    <div class="modal-dialog" id="add_category_modal_show">
        <!-- add new user dynamically in another page -->
    </div>
</div>

<script>
    function addCategoryModal() {
        $.ajax({
            url: "action-form-category.php",
            type: "POST",
            data: {
                type: 'CREATE_MODAL_OPEN'
            },
            success: function (data) {
                console.log(data);

                $('#addCategoryModal').modal('show');
                $('#add_category_modal_show').html(data);
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: " + error);
            }
        });
    }

    $(document).on("submit", "#createCategoryForm", function (e) {
        e.preventDefault();

        var formData = $(this).serialize() + "&action_type=CREATE_CATEGORY";

        $.ajax({
            url: "action-form-category.php",
            type: "POST",
            data: formData,
            success: function (response) {
                var res = response.trim();
                if (res === "SUCCESS") {
                    $('#addCategoryModal').modal('hide');
                    window.location.href = "add_new_category.php";
                } else {
                    $('#modal_category_msg').removeClass('d-none').text(res);
                }
            }
        });
    });
</script>

<?php include 'footer.php'; ?>