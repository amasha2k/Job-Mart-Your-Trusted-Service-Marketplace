<?php  
session_start();

$host = "localhost";
$user = "root";
$password = "";
$db = "job_mart"; 

$data = mysqli_connect($host, $user, $password, $db);

if ($data === false) {
    die("Connection failed: " . mysqli_connect_error());
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['title'])) {
    if (!isset($_SESSION['username'])) {
        echo "<script>alert('You must be logged in to post a job.'); window.location.href='login.php';</script>";
        exit;
    }

    $title = mysqli_real_escape_string($data, $_POST['title']);
    $description = mysqli_real_escape_string($data, $_POST['description']);
    $location = mysqli_real_escape_string($data, $_POST['location']);
    $category = mysqli_real_escape_string($data, $_POST['category']);
    $jobType = mysqli_real_escape_string($data, $_POST['jobType']);
    $jobDate = isset($_POST['jobDate']) ? mysqli_real_escape_string($data, $_POST['jobDate']) : NULL;
    $jobEndDate = isset($_POST['jobDate1']) ? mysqli_real_escape_string($data, $_POST['jobDate1']) : NULL;

    $image = $_FILES['image']['name'];
    $image_temp = $_FILES['image']['tmp_name'];
    $image_folder = "./uploads/".$image;
    move_uploaded_file($image_temp, $image_folder);

    $nameOnCard = isset($_POST['nameOnCard']) ? mysqli_real_escape_string($data, $_POST['nameOnCard']) : '';
    $cardNumber = isset($_POST['cardNumber']) ? mysqli_real_escape_string($data, $_POST['cardNumber']) : '';
    $expiryDate = isset($_POST['expiryDate']) ? mysqli_real_escape_string($data, $_POST['expiryDate']) : '';
    $cvv = isset($_POST['cvv']) ? mysqli_real_escape_string($data, $_POST['cvv']) : '';
    
    $fee = 1500;  

    
    $username = $_SESSION['username'];
    $mobile_number = $_SESSION['mobile_number']; 
    $email = $_SESSION['email']; 
    $address = $_SESSION['address']; 
    $status = 'pending';
    
    
    $sql = "INSERT INTO job_posting (title, description, location, category, image, jobType, jobDate, jobEndDate, nameOnCard, cardNumber, expiryDate, cvv, fee, username, mobile_number, email, address, status) 
            VALUES ('$title', '$description', '$location', '$category', '$image', '$jobType', '$jobDate', '$jobEndDate', '$nameOnCard', '$cardNumber', '$expiryDate', '$cvv', '$fee', '$username', '$mobile_number', '$email', '$address', '$status')";

    if (mysqli_query($data, $sql)) {
        echo "<script>alert('Your job Send To Admin For Review!'); window.location.href='home.php';</script>";
        exit;
    } else {
        $error_message = "Error: " . mysqli_error($data);
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Mart</title>
    <link href="./css/Style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
     
</head>
<body>
<header class="header">
    <div class="flex">
    <a href="index.php"><img id="logo" src="photos_img/logo1.PNG" alt="The Gallery Cafe" width="160" height="auto" /></a>

        <nav class="navbar">
            <a href="home.php">Home</a>
            <a><button id="openModalBtn">Post a Job</button></a>
            <a href="Jobs.php">Sheduled Jobs</a>
            <a href="Urgent_job.php">Quick Jobs</a>
        </nav>
          



        <a href="#" class="login" id="login-icon">
    <i class="fas fa-user"></i>
        <a href="login.php" class="login">
     <?php if(isset($_SESSION["username"])){ ?>
                <a class="login" href="Logout.php">Logout</a>
            <?php } else { ?>
              <a class="login" href="login.php">Login</a>
            <?php } ?>
        </a>
    </div>
</header>

<!-- Job Posting Modal -->
<div id="jobModal" class="modal">
    <div class="container-2">
        <span class="close-btn">&times;</span>
        <h2>Job Posting Form</h2>
        <h4 style="color:red;">*Charges Apply*</h4>
        <form id="jobForm" method="post" enctype="multipart/form-data">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" required>

            <label for="description">Description</label>
            <textarea id="description" name="description" required></textarea>

            <label for="location">Location</label>
            <select id="location" name="location" required>
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
                <option value="kegalle">Kegalle</option>
              
            </select>

            <label for="category">Select Category</label>
            <select id="category" name="category" required>
                <option value="ac_repairs">AC Repairs</option>
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

            <label for="image">Choose Image</label>
            <input type="file" id="image" name="image">

            <div>
                <input type="radio" id="scheduledJob" name="jobType" value="Scheduled Job" required>Scheduled Job
                <input type="radio" id="urgentJob" name="jobType" value="Urgent Job">Urgent Job
            </div>

            <div id="dateContainer" style="display: none;">
                <label for="jobDate">Job Date</label>
                <input type="date" id="jobDate" name="jobDate">

                <label for="jobDate1">Job End Date</label>
                <input type="date" id="jobDate1" name="jobDate1">
            </div>

            
            <input type="hidden" id="hiddenNameOnCard" name="nameOnCard">
            <input type="hidden" id="hiddenCardNumber" name="cardNumber">
            <input type="hidden" id="hiddenExpiryDate" name="expiryDate">
            <input type="hidden" id="hiddenCVV" name="cvv">

            <button type="submit">Next</button>
            <button type="reset" onclick="clearFormAndCloseModal()">Clear</button>
        </form>
    </div>
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="modal" style="display: none;">
    <div class="container-2">
        <span class="close-btn" onclick="closePaymentModal()">&times;</span>
        <h2>Job Mart</h2>
        <p  style="color:red;">ADD FEE: Rs 1500/-</p>
        <form id="paymentForm">
            <div id="errorMessages" style="color: red; margin-bottom: 10px;"></div>

            <label for="nameOnCard">Name on card:</label><br>
            <input type="text" id="nameOnCard" name="nameOnCard" required><br><br>

            <label for="cardNumber">Card Number:</label><br>
            <input type="text" id="cardNumber" name="cardNumber" required><br><br>

            <div class="form-row">
    <div>
        <label for="expiryDate">MM/YY:</label>
        <input type="text" id="expiryDate" name="expiryDate" required>
    </div>
    <div>
        <label for="cvv">CVV:</label>
        <input type="text" id="cvv" name="cvv" required>
    </div>
</div>


            <button type="submit">Pay</button>
            <button type="reset" onclick="closePaymentModal()">Cancel</button>
        </form>
    </div>
</div>

<script>
    
    var jobModal = document.getElementById('jobModal');
    var paymentModal = document.getElementById('paymentModal');

    
    var btn = document.getElementById('openModalBtn');

    
    var jobSpan = document.getElementsByClassName('close-btn')[0];
    var paymentSpan = document.getElementsByClassName('close-btn')[1];

    
    btn.onclick = function() {
        jobModal.style.display = 'block';
    }

    
    jobSpan.onclick = function() {
        jobModal.style.display = 'none';
    }

    
    window.onclick = function(event) {
        if (event.target == jobModal) {
            jobModal.style.display = 'none';
        }
    }

    
    document.querySelectorAll('input[name="jobType"]').forEach(function(el) {
        el.addEventListener('change', function() {
            var dateContainer = document.getElementById('dateContainer');
            if (this.value === 'Scheduled Job') {
                dateContainer.style.display = 'block';
            } else {
                dateContainer.style.display = 'none';
            }
        });
    });

    
    document.getElementById('jobForm').addEventListener('submit', function(event) {
        event.preventDefault();
        paymentModal.style.display = 'block';
        jobModal.style.display = 'none';
    });

   
    document.getElementById('paymentForm').addEventListener('submit', function(event) {
        event.preventDefault();

        var nameOnCard = document.getElementById('nameOnCard').value.trim();
        var cardNumber = document.getElementById('cardNumber').value.trim();
        var expiryDate = document.getElementById('expiryDate').value.trim();
        var cvv = document.getElementById('cvv').value.trim();
        var errorMessages = '';

        if (cardNumber.length !== 16 || isNaN(cardNumber)) {
            errorMessages += 'Card number must be 16 digits.<br>';
        }

        if (expiryDate.length !== 5 || expiryDate.indexOf('/') !== 2) {
            errorMessages += 'Expiry date must be in MM/YY format.<br>';
        }

        if (cvv.length !== 3 || isNaN(cvv)) {
            errorMessages += 'CVV must be 3 digits.<br>';
        }

        if (errorMessages === '') {
            
            document.getElementById('hiddenNameOnCard').value = nameOnCard;
            document.getElementById('hiddenCardNumber').value = cardNumber;
            document.getElementById('hiddenExpiryDate').value = expiryDate;
            document.getElementById('hiddenCVV').value = cvv;

            
            document.getElementById('jobForm').submit();
        } else {
            document.getElementById('errorMessages').innerHTML = errorMessages;
        }
    });

    function clearFormAndCloseModal() {
        document.getElementById('jobForm').reset();
        jobModal.style.display = 'none';
    }

    function closePaymentModal() {
        paymentModal.style.display = 'none';
    }

    document.getElementById('login-icon').addEventListener('click', function() {
    <?php
    if (isset($_SESSION['user_type'])) {
        $user_type = $_SESSION['user_type'];
        if ($user_type == "customer") {
            echo 'window.location.href = "Customer_pro.php";';
        } elseif ($user_type == "employee") {
            echo 'window.location.href = "Employe_pro.php";';
        } else {
            echo 'window.location.href = "login.php";';
        }
    } else {
        echo 'window.location.href = "login.php";';
    }
    ?>
});
</script>

