<?php include_once 'Admin_header.php'; ?>
<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "job_mart";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$feedback_msg = "";


$customers_sql = "SELECT * FROM users WHERE user_type = 'Customer'";
$customers_result = $conn->query($customers_sql);


$employees_sql = "SELECT * FROM users WHERE user_type = 'Employee'";
$employees_result = $conn->query($employees_sql);


if (isset($_POST['update_user'])) {
    $id = $_POST['id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $username = $_POST['username'];
    $district = $_POST['district'];
    $job_category = $_POST['job_category'];
    $mobile_number = $_POST['mobile_number'];

    
    $update_sql = "UPDATE users 
                   SET first_name='$first_name', last_name='$last_name', email='$email', address='$address', 
                       username='$username', district='$district', job_category='$job_category', 
                       mobile_number='$mobile_number'
                   WHERE id='$id'";

    if ($conn->query($update_sql) === TRUE) {
        $feedback_msg = "User updated successfully!";
    } else {
        $feedback_msg = "Error updating user: " . $conn->error;
    }
}


if (isset($_POST['delete_user'])) {
    $id = $_POST['id'];
    $delete_sql = "DELETE FROM users WHERE id='$id'";
    $conn->query($delete_sql);
    header("Location: Admin_manage _Users.php");
}

?>


    <h2>Customer Data</h2>
    <?php
    if (!empty($feedback_msg)) {
        echo "<p style='color: green;'>$feedback_msg</p>";
    }
    ?>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Address</th>
                <th>Username</th>
                <th>District</th>
                <th>Job Category</th>
                <th>Mobile Number</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($customers_result->num_rows > 0) {
                while($row = $customers_result->fetch_assoc()) {
                    echo "<tr>
                        <td>" . $row['id'] . "</td>
                        <td>" . $row['first_name'] . "</td>
                        <td>" . $row['last_name'] . "</td>
                        <td>" . $row['email'] . "</td>
                        <td>" . $row['address'] . "</td>
                        <td>" . $row['username'] . "</td>
                        <td>" . $row['district'] . "</td>
                        <td>" . $row['job_category'] . "</td>
                        <td>" . $row['mobile_number'] . "</td>
                        <td>
                            <form method='POST' style='display:inline;'>
                                <input type='hidden' name='id' value='" . $row['id'] . "'>
                                <input type='text' name='first_name' value='" . $row['first_name'] . "' placeholder='First Name'>
                                <input type='text' name='last_name' value='" . $row['last_name'] . "' placeholder='Last Name'>
                                <input type='text' name='email' value='" . $row['email'] . "' placeholder='Email'>
                                <input type='text' name='address' value='" . $row['address'] . "' placeholder='Address'>
                                <input type='text' name='username' value='" . $row['username'] . "' placeholder='Username'>
                                <input type='text' name='district' value='" . $row['district'] . "' placeholder='District'>
                                <input type='text' name='job_category' value='" . $row['job_category'] . "' placeholder='Job Category'>
                                <input type='text' name='mobile_number' value='" . $row['mobile_number'] . "' placeholder='Mobile Number'>
                                <button type='submit' name='update_user'>Update</button>
                            </form>
                            <form method='POST' style='display:inline;'>
                                <input type='hidden' name='id' value='" . $row['id'] . "'>
                                <button type='submit' name='delete_user'>Delete</button>
                            </form>
                        </td>
                    </tr>";
                }
            }
            ?>
        </tbody>
    </table>

    <h2>Employee Data</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Address</th>
                <th>Username</th>
                <th>District</th>
                <th>Job Category</th>
                <th>Mobile Number</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($employees_result->num_rows > 0) {
                while($row = $employees_result->fetch_assoc()) {
                    echo "<tr>
                        <td>" . $row['id'] . "</td>
                        <td>" . $row['first_name'] . "</td>
                        <td>" . $row['last_name'] . "</td>
                        <td>" . $row['email'] . "</td>
                        <td>" . $row['address'] . "</td>
                        <td>" . $row['username'] . "</td>
                        <td>" . $row['district'] . "</td>
                        <td>" . $row['job_category'] . "</td>
                        <td>" . $row['mobile_number'] . "</td>
                        <td>
                            <form method='POST' style='display:inline;'>
                                <input type='hidden' name='id' value='" . $row['id'] . "'>
                                <input type='text' name='first_name' value='" . $row['first_name'] . "' placeholder='First Name'>
                                <input type='text' name='last_name' value='" . $row['last_name'] . "' placeholder='Last Name'>
                                <input type='text' name='email' value='" . $row['email'] . "' placeholder='Email'>
                                <input type='text' name='address' value='" . $row['address'] . "' placeholder='Address'>
                                <input type='text' name='username' value='" . $row['username'] . "' placeholder='Username'>
                                <input type='text' name='district' value='" . $row['district'] . "' placeholder='District'>
                                <input type='text' name='job_category' value='" . $row['job_category'] . "' placeholder='Job Category'>
                                <input type='text' name='mobile_number' value='" . $row['mobile_number'] . "' placeholder='Mobile Number'>
                                <button type='submit' name='update_user'>Update</button>
                            </form>
                            <form method='POST' style='display:inline;'>
                                <input type='hidden' name='id' value='" . $row['id'] . "'>
                                <button type='submit' name='delete_user'>Delete</button>
                            </form>
                        </td>
                    </tr>";
                }
            }
            ?>
        </tbody>
    </table>



<?php
$conn->close();
?>
<?php include_once 'Admin_footer.php'; ?>
