<?php 
 
include_once 'main_header.php'; 

$host = "localhost";
$user = "root";
$password = "";
$db = "job_mart";


$conn = mysqli_connect($host, $user, $password, $db);

if ($conn === false) {
    die("Connection failed: " . mysqli_connect_error());
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';

if ($username === 'Guest') {
    header("Location: login.php");
    exit();
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 account-sidebar">
            <h4>Account</h4>
            <p><?php echo $username; ?></p>
            <a href="#" id="myOngoingJobsBtn">My Ongoing Jobs</a>
            <a href="#" id="myProfileBtn">My Profile</a>
            <a href="#" id="upcomingJobsBtn">My Jobs</a>
            <a href="#" id="myPerformanceBtn">My Performance</a> 
            <a href="#" id="myWalletBtn">My Wallet</a>
            <a href="#" id="paymentHistoryBtn">Payment History</a>
            <a href="#">My Review</a>
            <a href="#">Settings</a>
            
            
            
            <h4 class="mt-4">Jobs</h4>
            <a href="#">Profile Database</a>
        </div>

        <div class="col-md-9 main-content" id="mainContainer" style="max-width: 70%;">
            <div class="no-ads-container">
                <h3>Welcome! Please select an option from the sidebar.</h3>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>
    $(document).ready(function(){
        
        $('#myProfileBtn').click(function(e) {
            e.preventDefault();
            $.ajax({
                url: 'fetch_profile.php',
                type: 'GET',
                success: function(data) {
                    const profile = JSON.parse(data);
                    $('#mainContainer').html('');

                    if (profile) {
                        $('#mainContainer').append(`
                            <div class="profile-container">
                                <h3>My Profile</h3>
                                <div class="profile-info"><strong>First Name:</strong> ${profile.first_name}</div>
                                <div class="profile-info"><strong>Last Name:</strong> ${profile.last_name}</div>
                                <div class="profile-info"><strong>Username:</strong> ${profile.username}</div>
                                <div class="profile-info"><strong>Email:</strong> ${profile.email}</div>
                                <div class="profile-info"><strong>Mobile Number:</strong> ${profile.mobile_number}</div>
                                <div class="profile-info"><strong>Address:</strong> ${profile.address}</div>
                                <div class="profile-info"><strong>District:</strong> ${profile.district}</div>
                                <div class="profile-info"><strong>Job category:</strong> ${profile.job_category}</div>
                                <div class="profile-info"><strong>Account Created At:</strong> ${profile.created_at}</div>
                            </div>
                        `);
                    } else {
                        $('#mainContainer').append('<h3>No profile information found.</h3>');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error fetching profile:', textStatus, errorThrown);
                    $('#mainContainer').html('<h3>Error fetching profile. Please try again later.</h3>');
                }
            });
        });

        
        

$('#myOngoingJobsBtn').click(function(e) {
    e.preventDefault();
    $.ajax({
        url: 'fetch_ongoing_jobs.php',
        type: 'GET',
        success: function(data) {
            const jobs = JSON.parse(data);
            $('#mainContainer').html(''); 

            if (jobs.length > 0) {
                jobs.forEach(job => {
                    
                    const isJobStarted = job.Start_job !== null && job.Start_job !== '';

                    $('#mainContainer').append(`
                        <div class="job-container row mb-4">
                            <div class="col-md-3">
                                ${job.job_image ? `<img src="uploads/${job.job_image}" class="img-fluid" alt="Job Image" style="height: 150px; width: 150px; object-fit: cover;">` : '<img src="placeholder.jpg" class="img-fluid" alt="Placeholder Image" style="height: 150px; width: 150px; object-fit: cover;">'}
                            </div>
                            <div class="col-md-9 position-relative">
                                <h4>${job.job_title}</h4>
                                <p><strong>Category:</strong> ${job.job_category}</p>
                                <p><strong>Posted By:</strong> ${job.poster_username}</p>
                                <p><strong>Location:</strong> ${job.job_location}</p>
                                <p><strong>Job Date:</strong> ${job.job_date} to ${job.job_end_date}</p>
                                <p><strong><i class="fas fa-phone-alt"></i> Contact:</strong> ${job.C_mobile_number}</p>
                                <p><strong><i class="fas fa-envelope"></i> Email:</strong> ${job.email}</p>
                                <p><strong><i class="fas fa-home"></i> Address:</strong> ${job.address}</p>
                                <p><strong>Total Amount:</strong> Rs: ${job.total_amount}.00</p>

                                <!-- Button Container -->
                                <div class="button-container d-flex justify-content-center position-absolute" style="top: 58%; transform: translateY(-50%); width: 153%;">
                                    <button class="btn btn-success btn-lg custom-btn mx-2 start-job-btn" data-id="${job.id}" ${isJobStarted ? 'disabled' : ''}>Start Job</button>
                                    <button class="btn btn-warning btn-lg custom-btn mx-2 job-done-btn" data-id="${job.id}" ${isJobStarted ? '' : 'disabled'}>Job Done</button>
                                </div>
                            </div>
                        </div>
                        <hr/>
                    `);
                });

                
                $('.start-job-btn').click(function() {
                    const jobId = $(this).data('id');
                    const startButton = $(this);
                    const doneButton = $(this).siblings('.job-done-btn');

                    $.ajax({
                        url: 'update_job_status.php',
                        type: 'POST',
                        data: {
                            job_id: jobId,
                            action: 'start'
                        },
                        success: function(response) {
                            const res = JSON.parse(response);
                            alert(res.message);

                            if (res.message === 'Job started successfully!') {
                                
                                startButton.prop('disabled', true);
                                doneButton.prop('disabled', false);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error('Error updating job status:', textStatus, errorThrown);
                        }
                    });
                });

                
                $('.job-done-btn').click(function() {
                    const jobId = $(this).data('id');
                    $.ajax({
                        url: 'update_job_status.php',
                        type: 'POST',
                        data: {
                            job_id: jobId,
                            action: 'done'
                        },
                        success: function(response) {
                            const res = JSON.parse(response);
                            alert(res.message);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error('Error updating job status:', textStatus, errorThrown);
                        }
                    });
                });
            } else {
                $('#mainContainer').append('<h3 class="no-jobs-found">No ongoing jobs found.</h3>');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error fetching ongoing jobs:', textStatus, errorThrown);
            $('#mainContainer').html('<h3 class="no-jobs-found">Error fetching ongoing jobs. Please try again later.</h3>');
        }
    });
});




        
        $('#upcomingJobsBtn').click(function(e) {
    e.preventDefault();
    $.ajax({
        url: 'fetch_upcoming_jobs.php',
        type: 'GET',
        data: { username: '<?php echo $username; ?>' }, 
        success: function(data) {
            const jobs = JSON.parse(data);
            $('#mainContainer').html(''); 

            if (jobs.length > 0) {
                jobs.forEach(job => {
                    let amountHtml = '';

                    
                    if (job.total_amount === null || job.total_amount == 0) {
                        amountHtml = `
                            <div id="amount-container-${job.post_id}">
                                <div class="amount-input">
                                    <label for="amount-${job.post_id}">Enter your amount:</label>
                                    <input type="number" id="amount-${job.post_id}" class="form-control" placeholder="Enter amount">
                                </div>
                                <button class="btn btn-primary submit-amount-btn" data-job-id="${job.post_id}">Submit</button>
                            </div>
                        `;
                    } else {
                        amountHtml = `<p><strong>Amount Submitted:</strong> Rs: ${job.total_amount}.00</p>`;
                    }

                    $('#mainContainer').append(`
                        <div class="job-container row">
                            <div class="col-md-3">
                                ${job.job_image ? `<img src="uploads/${job.job_image}" class="img-fluid" alt="Ad Image" style="height: 150px; width: 150px; object-fit: cover;">` : '<img src="placeholder.jpg" class="img-fluid" alt="Placeholder Image" style="height: 150px; width: 150px; object-fit: cover;">'}
                            </div>
                            <div class="col-md-9">
                                <h4>${job.job_title}</h4>
                                <p><strong>Category:</strong> ${job.job_category}</p>
                                <p><strong>Posted By:</strong> ${job.poster_username}</p>
                                <p><strong>Location:</strong> ${job.job_location}</p>
                                <p><strong>Job Date:</strong> ${job.job_date} to ${job.job_end_date}</p>
                                <p><strong><i class="fas fa-phone-alt"></i> Contact:</strong> ${job.C_mobile_number}</p>
                                <p><strong><i class="fas fa-envelope"></i> Email:</strong> ${job.email}</p>
                                <p><strong><i class="fas fa-home"></i> Address:</strong> ${job.address}</p>
                                ${amountHtml}
                            </div>
                        </div>
                        <hr/>
                    `);
                });
            } else {
                $('#mainContainer').append('<h3 class="no-jobs-found">No upcoming jobs found.</h3>');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error fetching upcoming jobs:', textStatus, errorThrown);
            $('#mainContainer').html('<h3 class="no-jobs-found">Error fetching upcoming jobs. Please try again later.</h3>');
        }
    });
});


        $(document).on('click', '#viewMoreBtn', function() {
            alert('View More functionality to be implemented!');
        });

        
        $(document).on('click', '.submit-amount-btn', function() {
            const jobId = $(this).data('job-id');
            const amount = $(`#amount-${jobId}`).val(); 

            
            console.log('Submitting Job ID:', jobId);
            console.log('Submitting Amount:', amount);

            if (amount && jobId) {
                $.ajax({
                    url: 'submit_amount.php', 
                    type: 'POST',
                    data: { job_id: jobId, amount: amount },
                    success: function(response) {
                        const data = JSON.parse(response);
                        if (data.status === 'success') {
                            $(`#amount-container-${jobId}`).html(`<p><strong>Amount Submitted:</strong> Rs: ${amount}.00</p>`);
                            alert(`Amount Rs: ${amount}.00 submitted successfully`);
                        } else {
                            alert('Failed to submit amount: ' + data.message);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error submitting amount:', textStatus, errorThrown);
                        alert('Error submitting amount. Please try again later.');
                    }
                });
            } else {
                alert('Please enter an amount before submitting.');
            }
        });

       
$('#myWalletBtn').click(function(e) {
    e.preventDefault();
    $.ajax({
        url: 'fetch_wallet.php',
        type: 'GET',
        success: function(data) {
            const response = JSON.parse(data);
            $('#mainContainer').html(''); 

            if (response.status === 'success') {
                $('#mainContainer').append(`
    <div class="wallet-container">
        <h3>My Wallet</h3>
        <p><strong>withdrawable Amount (Your Amount):</strong> Rs: ${response.total_income}.00</p>
        <p><strong>Total Commission Fee(Job Mart Commission):</strong> Rs: ${response.total_commission}.00</p>
        <hr>
        <p><strong>Overall Income (Income + Commission):</strong> Rs: ${response.overall_total}.00</p>

        <!-- Withdraw input and button -->
        <div class="withdraw-container">
            <label for="withdrawAmount">Enter amount to withdraw:</label>
            <input type="number" id="withdrawAmount" class="form-control custom-width" min="1" max="${response.total_income}" placeholder="Enter amount" required>
            <button class="btn btn-primary mt-2" id="withdrawBtn">Withdraw</button>
        </div>
    </div>
`);

            } else {
                $('#mainContainer').append(`
                    <h3>Error: ${response.message}</h3>
                `);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error fetching wallet data:', textStatus, errorThrown);
            $('#mainContainer').html('<h3>Error fetching wallet data. Please try again later.</h3>');
        }
    });
});


$(document).on('click', '#withdrawBtn', function() {
    const withdrawAmount = parseFloat($('#withdrawAmount').val()); 

    if (isNaN(withdrawAmount) || withdrawAmount <= 0) {
        alert('Please enter a valid withdrawal amount.');
        return;
    }

    const confirmation = confirm(`Are you sure you want to withdraw Rs: ${withdrawAmount}?`);
    
    if (confirmation) {
        $.ajax({
            url: 'withdraw_amount.php', 
            type: 'POST',
            data: {
                username: '<?php echo $username; ?>', 
                amount: withdrawAmount 
            },
            success: function(response) {
                const res = JSON.parse(response);
                if (res.status === 'success') {
                    alert('Withdrawal successful. Amount: Rs: ' + res.withdrawn_amount + '.00');
                    
                    $('#mainContainer').html(`<h3>Rs: ${res.withdrawn_amount} withdrawn from your account.</h3>`);
                } else {
                    alert('Withdrawal failed: ' + res.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error processing withdrawal:', textStatus, errorThrown);
                alert('Error processing withdrawal. Please try again later.');
            }
        });
    }
});


$('#paymentHistoryBtn').click(function(e) {
    e.preventDefault();
    $.ajax({
        url: 'fetch_payment_history.php',
        type: 'GET',
        success: function(data) {
            const response = JSON.parse(data);
            $('#mainContainer').html(''); 

            if (response.status === 'success') {
                if (response.data.length > 0) {
                    let paymentHistoryHtml = `<h3>Payment History</h3>`;
                    paymentHistoryHtml += `
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Post ID</th>
                                   
                                    <th>Job Category</th>
                                    <th>Posted By</th>
                                    <th>Location</th>
                                    
                                    <th>Total Amount</th>
                                   
                                    <th>Job Mart Commission</th>
                                    <th>Employee Amount</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;
                    response.data.forEach(payment => {
                        paymentHistoryHtml += `
                            <tr>
                                <td>${payment.id}</td>
                                <td>${payment.post_id}</td>
                               
                                <td>${payment.job_category}</td>
                                <td>${payment.poster_username}</td>
                                <td>${payment.job_location}</td>
                               
                                <td>Rs: ${payment.total_amount}.00</td>
                                
                                <td>Rs: ${payment.job_mart_commission}.00</td>
                                <td>Rs: ${payment.emp_amount}.00</td>
                                <td>${payment.created_at}</td>
                            </tr>
                        `;
                    });
                    paymentHistoryHtml += `
                            </tbody>
                        </table>
                    `;
                    $('#mainContainer').append(paymentHistoryHtml);
                } else {
                    $('#mainContainer').append('<h3>No payment history found.</h3>');
                }
            } else {
                $('#mainContainer').append('<h3>Error fetching payment history: ' + response.message + '</h3>');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error fetching payment history:', textStatus, errorThrown);
            $('#mainContainer').html('<h3>Error fetching payment history. Please try again later.</h3>');
        }
    });
});



$('#myPerformanceBtn').click(function(e) {
    e.preventDefault();
    $.ajax({
        url: 'fetch_performance_data.php', 
        type: 'GET',
        data: { username: '<?php echo $username; ?>' }, 
        success: function(data) {
            const performanceData = JSON.parse(data);
            $('#mainContainer').html(''); 

            if (performanceData.length > 0) {
                
                const labels = performanceData.map(item => item.created_at); 
                const earnings = performanceData.map(item => item.emp_amount); 

                
                $('#mainContainer').append('<canvas id="performanceChart"></canvas>');
                const ctx = document.getElementById('performanceChart').getContext('2d');
                const performanceChart = new Chart(ctx, {
                    type: 'line', 
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Employee Earnings',
                            data: earnings,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            } else {
                $('#mainContainer').append('<h3>No performance data found.</h3>');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error fetching performance data:', textStatus, errorThrown);
            $('#mainContainer').html('<h3>Error fetching performance data. Please try again later.</h3>');
        }
    });
});

$('#myPerformanceBtn').click(function(e) {
    e.preventDefault();
    $.ajax({
        url: 'fetch_performance.php',
        type: 'GET',
        success: function(data) {
            const performanceData = JSON.parse(data);
            
           
            const aggregatedData = {};
            performanceData.forEach(item => {
                const date = item.date.split(' ')[0]; 
                if (!aggregatedData[date]) {
                    aggregatedData[date] = 0;
                }
                aggregatedData[date] += item.amount;
            });

            
            const labels = Object.keys(aggregatedData);
            const amounts = Object.values(aggregatedData);

            
            $('#mainContainer').html(`
                <h3>My Performance</h3>
                <canvas id="performanceChart" width="400" height="200"></canvas>
            `);

            const ctx = document.getElementById('performanceChart').getContext('2d');
            const performanceChart = new Chart(ctx, {
                type: 'bar', 
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Earnings Amount',
                        data: amounts,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)', 
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Amount (Rs)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        }
                    }
                }
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error fetching performance data:', textStatus, errorThrown);
            $('#mainContainer').html('<h3>Error fetching performance data. Please try again later.</h3>');
        }
    });
});







    });
</script>

<?php include_once 'main_footer.php'; ?>
