<?php
include 'db.php';
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
include 'header.php';
?>

<h2>Ticket History</h2>
<hr>

<div id="history-container">
    <!-- AJAX data loads here -->
</div>

<script>
$(document).ready(function(){
    function loadHistory(page) {
        $.ajax({
            url: "fetch_history.php",
            type: "GET",
            data: {page: page},
            success: function(data) {
                $("#history-container").html(data);
            }
        });
    }

    // Initial load
    loadHistory(1);

    // Pagination click event using jQuery delegation
    $(document).on("click", ".pagination-btn", function(e){
        e.preventDefault();
        var page = $(this).data("page");
        loadHistory(page);
    });
});
</script>

<?php include 'footer.php'; ?>