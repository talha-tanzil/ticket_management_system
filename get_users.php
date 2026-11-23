<?php
include 'db.php';
$dept_id = $_GET['department_id'];

$result = mysqli_query($conn, "SELECT * FROM users WHERE department_id = '$dept_id' AND is_status = '1'");
echo "<option value=''>Select Assigned User</option>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<option value='{$row['id']}'>{$row['user_name']}</option>";
}
?>