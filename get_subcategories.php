<?php
include 'db.php';

if (isset($_POST['category_id'])) {
    $category_id = $_POST['category_id'];

    if ($category_id != "") {
        // Query sub-categories matching the selected category ID and active status
        $query = "SELECT * FROM sub_category WHERE category_id = '$category_id' AND is_status = '1'";
        $result = mysqli_query($conn, $query);

        echo "<option value=''>Select Sub-Category</option>";
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='{$row['id']}'>{$row['sub_category_name']}</option>";
        }
    } else {
        echo "<option value=''>Select Category First</option>";
    }
}
?>