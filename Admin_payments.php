<?php

include_once 'Admin_header.php'; 


$host = "localhost";
$user = "root";
$password = "";
$db = "job_mart";


$conn = mysqli_connect($host, $user, $password, $db);


if ($conn === false) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to calculate the total Job Mart commission
$commissionQuery = "SELECT SUM(job_mart_commission) AS total_commission FROM payments";
$commissionResult = $conn->query($commissionQuery);

// Fetch the total commission
$totalCommission = 0;
if ($commissionResult->num_rows > 0) {
    $row = $commissionResult->fetch_assoc();
    $totalCommission = $row['total_commission'];
}


$paymentsQuery = "SELECT id, post_id, username, job_category, poster_username, job_location, job_image, email, total_amount, emp_email, created_at, emp_username, job_mart_commission, emp_amount FROM payments";
$paymentsResult = $conn->query($paymentsQuery);

?>


<div class="commission-container">
    <h2>Job Mart Account Balance</h2>
    <h3>Total Job Mart Commission: RS <?php echo number_format($totalCommission, 2); ?></h3>
</div>

<?php

if ($paymentsResult->num_rows > 0) {
    echo "<table class='payments-table'>";
    echo "<tr>
            
            <th>Post ID</th>
            <th>Costomer Name</th>
            <th>Job Category</th>
           
            <th>Job Location</th>
            
            <th>Total Amount</th>
           
            <th>Created At</th>
            <th>Employee name</th>
            <th>Job Mart Commission</th>
            <th>Employee Amount</th>
          </tr>";

    
    while ($row = $paymentsResult->fetch_assoc()) {
        echo "<tr>
                
                <td>" . $row["post_id"] . "</td>
                <td>" . $row["username"] . "</td>
                <td>" . $row["job_category"] . "</td>
                
                <td>" . $row["job_location"] . "</td>
               
                <td>RS " . number_format($row["total_amount"], 2) . "</td>
                
                <td>" . $row["created_at"] . "</td>
                <td>" . $row["emp_username"] . "</td>
                <td>RS " . number_format($row["job_mart_commission"], 2) . "</td>
                <td>RS " . number_format($row["emp_amount"], 2) . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No payment records found.</p>";
}


$conn->close();
?>

<?php include_once 'Admin_footer.php'; ?>
