<?php
include 'db.php';

$limit = 5; // Rows per page
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$offset = ($page - 1) * $limit;

$result = mysqli_query($conn, "SELECT * FROM ticket_history LIMIT $offset, $limit");

echo "<table class='table table-bordered bg-white'>
        <thead>
            <tr>
                <th>ID</th>
                <th>Status ID</th>
                <th>Problem Details</th>
                <th>Agent Name</th>
            </tr>
        </thead>
        <tbody>";

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['status_id']}</td>
            <td>{$row['problem_details']}</td>
            <td>{$row['agent_name']}</td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='4' class='text-center'>No history records found.</td></tr>";
}
echo "</tbody></table>";

// Pagination links calculation
$result_total = mysqli_query($conn, "SELECT COUNT(*) AS total FROM ticket_history");
$row_total = mysqli_fetch_assoc($result_total);
$total_pages = ceil($row_total['total'] / $limit);

echo "<nav><ul class='pagination'>";
for ($i = 1; $i <= $total_pages; $i++) {
    if ($i == $page) {
        $active = "active";
    } else {
        $active = "";
    }
    echo "<li class='page-item {$active}'><a class='page-link pagination-btn' href='#' data-page='{$i}'>{$i}</a></li>";
}
echo "</ul></nav>";
?>