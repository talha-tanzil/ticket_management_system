<?php
include 'db.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    // Optional: Delete uploaded attachment file from folder
    $res = mysqli_query($conn, "SELECT attachment FROM ticket WHERE id = $id");
    $row = mysqli_fetch_assoc($res);
    if ($row['attachment'] != "") {
        @unlink("uploads/" . $row['attachment']);
    }
    
    mysqli_query($conn, "DELETE FROM ticket WHERE id = $id");
}

header("Location: dashboard.php");
exit();
?>