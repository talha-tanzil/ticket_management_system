<?php
include 'db.php';
session_start();

if (!isset($_SESSION["user_level"]) || $_SESSION["user_level"] != '0') {
    header("Location: dashboard.php");
    exit();
}

if (isset($_POST["action_type"]) && $_POST["action_type"] == "CREATE_SUBCATEGORY") {
    $category_id = mysqli_real_escape_string($conn, $_POST["category_id"]);
    $subcategory_name = mysqli_real_escape_string($conn, $_POST["sub_category_name"]);

    if ($category_id != "" && $subcategory_name != "") {
        $check_sql = "SELECT * FROM sub_category WHERE category_id = '$category_id' AND sub_category_name = '$subcategory_name'";
        $check_res = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_res) > 0) {
            echo "Error: This subcategory already exists under the selected category!";
        } else {
            $insert_sql = "INSERT INTO sub_category (category_id, sub_category_name, is_status) VALUES ('$category_id', '$subcategory_name', '1')";
            if (mysqli_query($conn, $insert_sql)) {
                echo "SUCCESS";
            } else {
                echo "Error: Could not create subcategory.";
            }
        }
    } else {
        echo "Error: Please select a category and enter a subcategory name.";
    }

    exit();
}

if (isset($_POST['type']) && $_POST['type'] == 'CREATE_MODAL_OPEN') { ?>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="addSubcategoryModalLabel">Add New Subcategory</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row justify-content-center">
                <div class="col-md-11">
                    <div id="modal_subcategory_msg" class="alert alert-danger d-none p-2"></div>
                    <div class="card p-3 shadow-sm">
                        <form id="createSubcategoryForm" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select name="category_id" class="form-control" required>
                                    <option value="">Select Category</option>
                                    <?php
                                    $cat_res = mysqli_query($conn, "SELECT * FROM category WHERE is_status = '1'");
                                    while ($c = mysqli_fetch_assoc($cat_res)) {
                                        echo "<option value='{$c['id']}'>{$c['category_name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Subcategory Name</label>
                                <input type="text" name="sub_category_name" class="form-control" required>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" form="createSubcategoryForm" name="add_subcategory" class="btn btn-primary btn-sm">Create Subcategory</button>
        </div>
    </div>
    <?php
}
?>