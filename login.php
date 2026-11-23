<?php
include 'db.php';
session_start();

if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST["user_id"];
    $password = $_POST["password"];

    if ($user_id == "" || $password == "") {
        $message = "User ID or Password cannot be blank.";
    } else {
        $sql = "SELECT * FROM users WHERE user_id = '$user_id' AND password = '$password'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            
            // Check if user is active (is_status = '1')
            if ($row['is_status'] == '1') {
                $_SESSION["user_id"] = $row["user_id"];
                $_SESSION["user_name"] = $row["user_name"];
                $_SESSION["user_level"] = $row["user_level"]; // 0: Admin, 1: Group Admin, 2: User
                $_SESSION["department_id"] = $row["department_id"];
                $_SESSION["db_id"] = $row["id"];

                header("Location: dashboard.php");
                exit();
            } else {
                $message = "Your account is inactive. Contact Administrator.";
            }
        } else {
            $message = "Invalid User ID or Password.";
        }
    }
}
?>

<?php include 'header.php'; ?>

<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-body p-4">
                <h3 class="text-center mb-4">System Login</h3>
                <?php if ($message != "") { ?>
                    <div class="alert alert-danger"><?php echo $message; ?></div>
                <?php } ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">User ID</label>
                        <input type="text" name="user_id" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>