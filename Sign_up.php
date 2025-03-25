<?php include_once 'main_header.php'; ?>
<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "job_mart";


$data = mysqli_connect($host, $user, $password, $db);

if ($data === false) {
    die("Connection failed: " . mysqli_connect_error());
}

$error_message = ""; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_type = $_POST['user-type'];
    $first_name = $_POST['first-name'];
    $last_name = $_POST['last-name'];
    $address = $_POST['address'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $district = $_POST['district'];
    $job_category = isset($_POST['job-category']) ? $_POST['job-category'] : null; 
    $password = $_POST['password'];
    $mobile_number = $_POST['mobile_number'];

    // Check if username already exists
    $check_username_query = $data->prepare("SELECT id FROM users WHERE username = ?");
    $check_username_query->bind_param("s", $username);
    $check_username_query->execute();
    $check_username_query->store_result();

    if ($check_username_query->num_rows > 0) {
        $error_message = "Username already taken. Please choose another username."; 
        $check_username_query->close();
    } else {
        // Check if email already exists
        $check_email_query = $data->prepare("SELECT id FROM users WHERE email = ?");
        $check_email_query->bind_param("s", $email);
        $check_email_query->execute();
        $check_email_query->store_result();

        if ($check_email_query->num_rows > 0) {
            $error_message = "This email is already registered."; 
            $check_email_query->close();
        } else {
            
            $stmt = $data->prepare("INSERT INTO users (user_type, first_name, last_name, address, username, email, district, job_category, password, mobile_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssss", $user_type, $first_name, $last_name, $address, $username, $email, $district, $job_category, $password, $mobile_number);

            if ($stmt->execute()) {
                $_SESSION["email"] = $email;
                echo '<script type="text/javascript">
                        alert("You are now a Registered Member! Welcome to the Job Mart");
                        window.location.href = "login.php";
                      </script>';
            } else {
                $error_message = "Error: " . $stmt->error; // Set error message if insertion fails
            }

            $stmt->close();
        }
    }

    mysqli_close($data);
}
?>
<div class="container d-flex">
    <div class="container-sign">
        <form id="signup-form" method="post" novalidate>
            <h2>SignUp Form</h2>

            <!-- Display error message if set -->
            <?php if (!empty($error_message)): ?>
                <div style="color:red; font-weight:bold; margin-bottom:10px;"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <label for="user-type">User Type:</label>
            <select id="user-type" name="user-type" onchange="toggleJobCategory()" required>
                <option value="customer">Customer</option>
                <option value="employee">Employee</option>
            </select>

            <label for="first-name">First Name:</label>
            <input type="text" id="first-name" name="first-name" required>

            <label for="last-name">Last Name:</label>
            <input type="text" id="last-name" name="last-name" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>

            <label for="mobile_number">Mobile Number:</label>
            <input type="text" id="mobile_number" name="mobile_number" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="district">District:</label>
            <select id="district" name="district" required>
                <option value="colombo">Colombo</option>
                <option value="gampaha">Gampaha</option>
                <option value="kalutara">Kalutara</option>
                <option value="kandy">Kandy</option>
                <option value="matale">Matale</option>
                <option value="nuwaraeliya">Nuwara Eliya</option>
                <option value="galle">Galle</option>
                <option value="matara">Matara</option>
                <option value="hambantota">Hambantota</option>
                <option value="jaffna">Jaffna</option>
                <option value="kilinochchi">Kilinochchi</option>
                <option value="mannar">Mannar</option>
                <option value="vavuniya">Vavuniya</option>
                <option value="mullaitivu">Mullaitivu</option>
                <option value="batticaloa">Batticaloa</option>
                <option value="ampara">Ampara</option>
                <option value="trincomalee">Trincomalee</option>
                <option value="kurunegala">Kurunegala</option>
                <option value="puttalam">Puttalam</option>
                <option value="anuradhapura">Anuradhapura</option>
                <option value="polonnaruwa">Polonnaruwa</option>
                <option value="badulla">Badulla</option>
                <option value="monaragala">Monaragala</option>
                <option value="ratnapura">Ratnapura</option>
            </select>

            <div id="job-category-div" style="display: none;">
                <label for="job-category">Job Category:</label>
                <select id="job-category" name="job-category">
                    <option value="plumbing">Plumbing</option>
                    <option value="AC_repairs">AC Repairs</option>
                    <option value="cctv">CCTV</option>
                    <option value="construction">Construction</option>
                    <option value="electronic_repairs">Electronic Repairs</option>
                    <option value="glass">Glass</option>
                    <option value="aluminium">Aluminium</option>
                    <option value="iron_works">Iron Works</option>
                    <option value="plumbing">Plumbing</option>
                    <option value="wood_work">Wood Work</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required minlength="8">

            <button type="submit">Sign Up</button>
        </form>
    </div>

    <div class="left_sidee">
        <img src="./photos_img/21.jpg" alt="logo" class="img-fluid">
    </div>
</div>

<script>
function toggleJobCategory() {
    const userType = document.getElementById('user-type').value;
    const jobCategoryDiv = document.getElementById('job-category-div');

    if (userType === 'employee') {
        jobCategoryDiv.style.display = 'block';
        document.getElementById('job-category').setAttribute('required', true);
    } else {
        jobCategoryDiv.style.display = 'none';
        document.getElementById('job-category').removeAttribute('required');
    }
}

document.getElementById('signup-form').addEventListener('submit', function(event) {
    const form = event.target;
    if (!form.checkValidity()) {
        event.preventDefault(); 
        form.reportValidity(); 
    }
});
</script>

<?php include_once 'main_footer.php'; ?>
