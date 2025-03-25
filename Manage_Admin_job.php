<?php
session_start();  

include_once 'Admin_header.php';


$host = "localhost";
$user = "root";
$password = "";
$db = "job_mart";


$conn = mysqli_connect($host, $user, $password, $db);


if ($conn === false) {
    die("Connection failed: " . mysqli_connect_error());
}


$sql = "SELECT id, title, description, location, category, jobType, jobDate, jobEndDate, fee, status FROM job_posting WHERE status = 'approved'";
$result = $conn->query($sql);

?>

<h2>Approved Job Postings</h2>

<?php

if (isset($_SESSION['message'])) {
    $msg_type = $_SESSION['msg_type'] == 'success' ? 'alert-success' : 'alert-error';
    echo "<div class='alert $msg_type'>" . $_SESSION['message'] . "</div>";
    
   
    unset($_SESSION['message']);
    unset($_SESSION['msg_type']);
}
?>

<?php

if ($result->num_rows > 0) {
    echo "<table class='job-table'>";
    echo "<tr>
            <th>Job ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Location</th>
            <th>Category</th>
            <th>Job Type</th>
            <th>Job Date</th>
            <th>End Date</th>
            <th>Fee</th>
            <th>Action</th>
          </tr>";

    
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["title"] . "</td>
                <td>" . $row["description"] . "</td>
                <td>" . $row["location"] . "</td>
                <td>" . $row["category"] . "</td>
                <td>" . $row["jobType"] . "</td>
                <td>" . $row["jobDate"] . "</td>
                <td>" . $row["jobEndDate"] . "</td>
                <td>" . $row["fee"] . "</td>
                <td>
                    <form method='POST' action='Admin_delete_job_post.php'>
                        <input type='hidden' name='job_id' value='" . $row["id"] . "'>
                        <button type='submit' class='delete-btn'>Delete</button>
                    </form>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No approved job posts found.</p>";
}


$conn->close();
?>

<?php include_once 'Admin_footer.php'; ?>
