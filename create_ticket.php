<?php
include 'db.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = $_POST["customer_name"];
    $category_id = $_POST["category_id"];
    $sub_category_id = $_POST["sub_category_id"];
    $department_id = $_POST["department_id"];
    $users_id = $_POST["users_id"];
    $status_id = $_POST["status_id"];
    $problem_details = $_POST["problem_details"];

    $attachment = "";
    if (isset($_FILES["attachment"]) && $_FILES["attachment"]["error"] == 0) {
        $file_name = $_FILES["attachment"]["name"];
        $file_size = $_FILES["attachment"]["size"];
        $file_tmp = $_FILES["attachment"]["tmp_name"];

        if ($file_size <= 5 * 1024 * 1024) {
            $target_dir = "uploads/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $attachment = time() . "_" . $file_name;
            move_uploaded_file($file_tmp, $target_dir . $attachment);
        } else {
            $message = "File size must be less than 5MB.";
        }
    }

    if ($message == "") {
        $sql = "INSERT INTO ticket (customer_name, category_id, sub_category_id, department_id, users_id, status_id, problem_details, attachment) 
                VALUES ('$customer_name', '$category_id', '$sub_category_id', '$department_id', '$users_id', '$status_id', '$problem_details', '$attachment')";

        if (mysqli_query($conn, $sql)) {
            $message = "Ticket created successfully!";
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<?php include 'header.php'; ?>

<h2>Create New Ticket</h2>
<hr>
<?php if ($message != "") { ?>
    <div class="alert alert-info"><?php echo $message; ?></div>
<?php } ?>

<form method="POST" enctype="multipart/form-data" class="bg-white p-4 shadow-sm rounded">
    <div class="mb-3">
        <label class="form-label">Customer Name</label>
        <input type="text" name="customer_name" class="form-control" required>
    </div>


    <div class="mb-3">
    <label class="form-label">Category</label>
    <!-- Added id="category_select" -->
    <select name="category_id" id="category_select" class="form-control" required>
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
    <label class="form-label">Sub-Category</label>
    <!-- Added id="subcategory_select" -->
    <select name="sub_category_id" id="subcategory_select" class="form-control" required>
        <option value="">Select Category First</option>
    </select>
</div>

    

    <div class="mb-3">
        <label class="form-label">Department</label>
        <select name="department_id" id="department_select" class="form-control" required>
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
        <label class="form-label">Assign User (Loaded via AJAX)</label>
        <select name="users_id" id="user_select" class="form-control" required>
            <option value="">First select a department</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status_id" class="form-control" required>
            <?php
            $st_res = mysqli_query($conn, "SELECT * FROM status");
            while ($st = mysqli_fetch_assoc($st_res)) {
                echo "<option value='{$st['id']}'>{$st['status_name']}</option>";
            }
            ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Problem Details</label>
        <textarea name="problem_details" class="form-control" rows="3" required></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Attachment (Max 5MB)</label>
        <input type="file" name="attachment" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Submit Ticket</button>
</form>

<!-- jQuery AJAX to load users based on selected department -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {

        // When category selection changes
        $('#category_select').on('change', function () {
            var category_id = $(this).val();

            if (category_id != "") {
                // Send AJAX request to get_subcategories.php
                $.ajax({
                    url: "get_subcategories.php",
                    method: "POST",
                    data: { category_id: category_id },
                    success: function (data) {
                        // Load the returned options into the sub-category dropdown
                        $('#subcategory_select').html(data);
                    }
                });
            } else {
                // Reset if no category is selected
                $('#subcategory_select').html('<option value="">Select Category First</option>');
            }
        });


        $("#department_select").change(function () {
            var deptId = $(this).val();
            if (deptId != "") {
                $.ajax({
                    url: "get_users.php",
                    type: "GET",
                    data: { department_id: deptId },
                    success: function (data) {
                        $("#user_select").html(data);
                    }
                });
            } else {
                $("#user_select").html("<option value=''>First select a department</option>");
            }
        });
    });
</script>

<?php include 'footer.php'; ?>