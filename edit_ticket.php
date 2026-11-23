<?php
include 'db.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$id = $_GET["id"];
$ticket_res = mysqli_query($conn, "SELECT * FROM ticket WHERE id = $id");
$ticket = mysqli_fetch_assoc($ticket_res);

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = $_POST["customer_name"];
    $problem_details = $_POST["problem_details"];
    $status_id = $_POST["status_id"];

    // File update check
    $attachment = $ticket['attachment'];
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

        $sql = "UPDATE ticket SET customer_name = '$customer_name', problem_details = '$problem_details', status_id = '$status_id', attachment = '$attachment' WHERE id = $id";
        if (mysqli_query($conn, $sql)) {
            header("Location: dashboard.php");
            exit();
        } else {
            $message = "Error updating ticket: " . mysqli_error($conn);
        }
    }
}
?>

<?php include 'header.php'; ?>

<h2>Edit Ticket #<?php echo $ticket['id']; ?></h2>
<hr>
<?php if ($message != "") { ?>
    <div class="alert alert-danger"><?php echo $message; ?></div>
<?php } ?>

<form method="POST" enctype="multipart/form-data" class="bg-white p-4 shadow-sm rounded">
    <div class="mb-3">
        <label class="form-label">Customer Name</label>
        <input type="text" name="customer_name" class="form-control" value="<?php echo $ticket['customer_name']; ?>"
            required>
    </div>
    <div class="mb-3">
        <label class="form-label">Problem Details</label>
        <textarea name="problem_details" class="form-control" rows="3"
            required><?php echo $ticket['problem_details']; ?></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Ticket Status</label>
        <select name="status_id" class="form-control" required>
            <?php
            $status_res = mysqli_query($conn, "SELECT * FROM status");
            while ($st = mysqli_fetch_assoc($status_res)) {

                // Standard if-else to pre-select the current status of the ticket
                if ($st['id'] == $ticket['status_id']) {
                    $selected = "selected";
                } else {
                    $selected = "";
                }

                echo "<option value='{$st['id']}' {$selected}>{$st['status_name']}</option>";
            }
            ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Current Attachment</label><br>
        <?php if ($ticket['attachment'] != "") { ?>
            <a href="uploads/<?php echo $ticket['attachment']; ?>" target="_blank">View File</a>
        <?php } else { ?>
            <span>No file uploaded</span>
        <?php } ?>
    </div>
    <div class="mb-3">
        <label class="form-label">Replace Attachment (Optional, Max 5MB)</label>
        <input type="file" name="attachment" class="form-control">
    </div>
    <button type="submit" class="btn btn-success">Update Ticket</button>
    <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
</form>

<?php include 'footer.php'; ?>