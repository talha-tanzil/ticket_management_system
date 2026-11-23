<?php
include 'db.php';
session_start();

if (!isset($_SESSION["user_level"]) || $_SESSION["user_level"] != '0') {
    header("Location: dashboard.php");
    exit();
}

// Handle saving new category (via AJAX or POST)
if (isset($_POST["action_type"]) && $_POST["action_type"] == "CREATE_CATEGORY") {
    $cat_msg = "";

    $cat_name = $_POST["category_name"];

    if ($cat_name != "") {
        $check_cat_sql = "SELECT * FROM category WHERE category_name = '$cat_name'";
        $check_cat_res = mysqli_query($conn, $check_cat_sql);

        if (mysqli_num_rows($check_cat_res) > 0) {
            echo $cat_msg = "Error: This category name already exists!";
        } else {
            $insert_category_sql= "INSERT INTO category (category_name, is_status) VALUES ('$cat_name', '1')";
            if (mysqli_query($conn, $insert_category_sql)) {
                echo "SUCCESS";
            } else {
                echo "ERROR: Could not create category.";
            }
        }
    } else {
        echo "ERROR: Please fill in the required field.";
    }
    exit();

}

if (isset($_POST['type']) && $_POST['type'] == 'CREATE_MODAL_OPEN') { ?>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row justify-content-center">
                <div class="col-md-11">
                    <div id="modal_category_msg" class="alert alert-danger d-none p-2"></div>
                    <div class="card p-3 shadow-sm">
                        <!-- <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5> -->
                        <form id="createCategoryForm" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Category Name</label>
                                <input type="text" name="category_name" class="form-control" required>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" form="createCategoryForm" name="add_category" class="btn btn-primary btn-sm">Create Category</button>
        </div>
    </div>
    <?php
}
?>