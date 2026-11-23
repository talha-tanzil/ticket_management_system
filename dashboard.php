<?php
include 'db.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_level = $_SESSION["user_level"];
$department_id = $_SESSION["department_id"];
$db_id = $_SESSION["db_id"];

// Role-based query filtering

if ($user_level == '0') {
    $sql = "SELECT ticket.*, status.status_name FROM ticket JOIN status ON ticket.status_id = status.id";
} elseif ($user_level == '1') {
    $sql = "SELECT ticket.*, status.status_name FROM ticket JOIN status ON ticket.status_id = status.id WHERE ticket.department_id = '$department_id'";
} else {
    $sql = "SELECT ticket.*, status.status_name FROM ticket JOIN status ON ticket.status_id = status.id WHERE ticket.users_id = '$db_id'";
}
// if ($user_level == '0') {
//     $sql = "SELECT * FROM ticket";
// } elseif ($user_level == '1') {
//     $sql = "SELECT * FROM ticket WHERE department_id = '$department_id'";
// } else {
//     $sql = "SELECT * FROM ticket WHERE users_id = '$db_id'";
// }

$result = mysqli_query($conn, $sql);
?>

<?php include 'header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Ticket Dashboard (Hey welcome, <?php echo $_SESSION["user_name"]; ?>)</h2>
    <a href="ticket_history.php" class="btn btn-info text-white">View Ticket History</a>
</div>

<table class="table table-bordered bg-white shadow-sm">
    <thead>
        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Problem Details</th>
            <th>Status</th>
            <th>Attachment</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $attachment_link = ($row['attachment'] != "") ? "<a href='uploads/{$row['attachment']}' target='_blank'>View File</a>" : "None";
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['customer_name']}</td>
                    <td>{$row['problem_details']}</td>
                    <td><span class='badge bg-info text-dark'>{$row['status_name']}</span></td>
                    <td>{$attachment_link}</td>
                    <td>
                        <a href='edit_ticket.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                        <a href='delete_ticket.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this ticket?\")'>Delete</a>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='4' class='text-center'>No tickets found.</td></tr>";
        }
        ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>