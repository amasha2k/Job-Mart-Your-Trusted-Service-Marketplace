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


session_start();  
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';


$sql = "SELECT report_id, employee_id, employee_name, reported_by_username, reported_by_mobile, report_time, report_reason FROM employee_reports";
$result = $conn->query($sql);
?>

<h2>Employee Reporting</h2>

<?php

if ($result->num_rows > 0) {
    echo "<table class='report-table'>";
    echo "<tr>
            <th>Report ID</th>
            <th>Employee ID</th>
            <th>Employee Name</th>
            <th>Report Customer Name</th>
            <th>Report Customer Mobile</th>
            <th>Report Time</th>
            <th>Report Reason</th>
          </tr>";

    
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["report_id"] . "</td>
                <td>" . $row["employee_id"] . "</td>
                <td>" . $row["employee_name"] . "</td>
                <td>" . $row["reported_by_username"] . "</td>  <!-- No space before the key -->
                <td>" . $row["reported_by_mobile"] . "</td>    <!-- No space before the key -->
                <td>" . $row["report_time"] . "</td>
                <td>" . $row["report_reason"] . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No reports found.</p>";
}


$conn->close();
?>

<?php include_once 'Admin_footer.php'; ?>
