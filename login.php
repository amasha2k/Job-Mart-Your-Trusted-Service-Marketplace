<?php 

ob_start(); 
include_once 'Main_header.php'; 

$host = "localhost";
$user = "root";
$password = "";
$db = "job_mart"; 

$data = mysqli_connect($host, $user, $password, $db);

if ($data === false) {
    die("Connection failed: " . mysqli_connect_error());
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = isset($_POST['username']) ? mysqli_real_escape_string($data, $_POST['username']) : '';
    $password = isset($_POST['password']) ? mysqli_real_escape_string($data, $_POST['password']) : '';

    
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($data, $sql);
    
    if ($result) {
        $row = mysqli_fetch_array($result);
        
        if ($row) {
            
            $usertype = $row['user_type'];
            $mobilenumber = $row['mobile_number'];  
            $reviewrating = $row['review_rating'];
            $jobcategory = $row['job_category']; 
            $address = $row['address'];
            $email = $row['email']; 

            
            $_SESSION["username"] = $username;
            $_SESSION["user_type"] = $usertype;
            $_SESSION["mobile_number"] = $mobilenumber;
            $_SESSION["review_rating"] = $reviewrating;
            $_SESSION["job_category"] = $jobcategory; 
            $_SESSION["address"] = $address;
            $_SESSION["email"] = $email;
            
            
            if ($usertype == "customer") {
                header("Location: home.php");
                exit; 
            } elseif ($usertype == "employee") {
                header("Location: Jobs.php");
                exit; 
            } elseif ($usertype == "Admin") {
                header("Location: Adminhome.php");
                exit; 
            } else {
                $error_message = "Invalid user type";
            }
        } else {
            $error_message = "Invalid username or password";
        }
    } else {
        $error_message = "Error: " . mysqli_error($data);
    }
}
ob_end_flush(); 
?>

<div class="container_index" style="padding-top:80px;" align="center">
    <div class="left_side">
        <img src="./photos_img/1.png" alt="logo">
    </div>
    <div class="right_side" style="padding-top:1px">
        <br><h1 style="font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;">USER LOGIN</h1>
        
        <?php if (!empty($error_message)) { ?>
            <div class="error-message" style="color: red; margin-bottom: 10px;"><?php echo $error_message; ?></div>
        <?php } ?>

        <form action="" method="POST">
            <input name="username" type="text" class="form-controli" placeholder="Username" required>
            <input name="password" type="password" class="form-controli" placeholder="Password" required>
            <button type="submit" id="btn2i" class="btni" name="btnLogin" style="margin-top: 15px;">
                <i class="fas fa-sign-in-alt" style="padding-right: 5px;"></i> LOGIN
            </button>
        </form>
        <p>Don't have an account? <a href="Sign_up.php">Register now!</a></p>
    </div>
</div>

<?php include_once 'Main_footer.php'; ?>
