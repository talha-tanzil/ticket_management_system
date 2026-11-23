<?php
include 'db.php';
session_start();

if (!isset($_SESSION["user_level"]) || $_SESSION["user_level"] != '0') {
    header("Location: dashboard.php");
    exit();
}

// $sub_msg = "";

// if (isset($_POST["add_sub_category"])) {
//     $category_id = $_POST["category_id"];
//     $sub_name = $_POST["sub_category_name"];
    
//     if ($category_id != "" && $sub_name != "") {
//         $check_sub_sql = "SELECT * FROM sub_category WHERE category_id = '$category_id' AND sub_category_name = '$sub_name'";
//         $check_sub_res = mysqli_query($conn, $check_sub_sql);

//         if (mysqli_num_rows($check_sub_res) > 0) {
//             $sub_msg = "Error: This sub-category name already exists under this category!";
//         } else {
//             mysqli_query($conn, "INSERT INTO sub_category (category_id, sub_category_name, is_status) VALUES ('$category_id', '$sub_name', '1')");
//             header("Location: add_new_subcategory.php");
//             exit();
//         }
//     }
// }

if (isset($_GET["toggle_sub"])) {
    $id = $_GET["toggle_sub"];
    $status = $_GET["status"];
    if ($status == '1') {
        $new_status = '0';
    } else {
        $new_status = '1';
    }
    mysqli_query($conn, "UPDATE sub_category SET is_status = '$new_status' WHERE id = $id");
    header("Location: add_new_subcategory.php");
    exit();
}

if (isset($_GET["delete_sub"])) {
    $id = $_GET["delete_sub"];
    mysqli_query($conn, "DELETE FROM sub_category WHERE id = $id");
    header("Location: add_new_subcategory.php");
    exit();
}

include 'header.php';
?>

<h2>Manage Sub-Categories</h2>
<hr>
<div class="row mb-5">
    <!-- <div class="col-md-4">
        <div class="card p-3 shadow-sm">
            <h5>Add Sub-Category</h5>
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
                    <input type="text" name="sub_category_name" class="form-control" placeholder="Sub-Category Name" required>
                </div>
                <button type="submit" name="add_sub_category" class="btn btn-primary btn-sm">Add Sub-Category</button>
            </form>
        </div>
    </div> -->
    
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
                            <a href='add_new_subcategory.php?toggle_sub={$row['id']}&status={$row['is_status']}' class='btn {$cls} btn-sm'>$btn</a>
                            <a href='add_new_subcategory.php?delete_sub={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
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