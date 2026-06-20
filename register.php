<?php 
// 1. Connection must be available at the very top
include('header.php'); 
?>

<div class="container" style="max-width: 600px; margin-top: 50px; margin-bottom: 50px;">
    <div class="register-card" style="background: white; padding: 40px; border-radius: 15px; box-shadow: var(--shadow); border-top: 5px solid var(--navy);">
        <h2 style="color: var(--navy); margin-bottom: 25px;">Create Your Account</h2>
        
        <?php
        // 2. Process form only on POST request
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Sanitize inputs to prevent SQL Injection
            $name = mysqli_real_escape_string($conn, $_POST['full_name']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $raw_password = $_POST['password'];
            
            // 3. Secure Hashing (Required for your project evaluation)
            $hashed_pass = password_hash($raw_password, PASSWORD_DEFAULT);

            // 4. Check if email already exists in the 'users' table
            $check_query = "SELECT * FROM users WHERE email='$email'";
            $check_result = mysqli_query($conn, $check_query);

            if (mysqli_num_rows($check_result) > 0) {
                echo "<div style='background:#f8d7da; color:#721c24; padding:15px; border-radius:8px; margin-bottom:20px;'>
                        Error: This email address is already registered.
                      </div>";
            } else {
                // 5. Insert data into 'users' table
                $sql = "INSERT INTO users (full_name, email, password, role) VALUES ('$name', '$email', '$hashed_pass', 'user')";
                
                if (mysqli_query($conn, $sql)) {
                    echo "<div style='background:#d4edda; color:#155724; padding:15px; border-radius:8px; margin-bottom:20px;'>
                            Registration successful! <a href='login.php' style='font-weight:bold; color:#155724;'>Login here to continue.</a>
                          </div>";
                } else {
                    echo "<p style='color:red;'>Database Error: " . mysqli_error($conn) . "</p>";
                }
            }
        }
        ?>

        <form action="register.php" method="POST">
            <div style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom:8px; font-weight:bold; color:var(--navy);">Full Name</label>
                <input type="text" name="full_name" class="form-control" placeholder="Enter your full name" required 
                       style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px; box-sizing:border-box;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom:8px; font-weight:bold; color:var(--navy);">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="example@mail.com" required 
                       style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px; box-sizing:border-box;">
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display:block; margin-bottom:8px; font-weight:bold; color:var(--navy);">Password</label>
                <input type="password" name="password" class="form-control" minlength="8" placeholder="At least 8 characters" required 
                       style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px; box-sizing:border-box;">
                <small style="color:#888;">Keep your password secure and unique.</small>
            </div>

            <button type="submit" class="btn-main" style="width:100%; cursor:pointer;">Create Account</button>
            
            <div style="text-align: center; margin-top: 20px;">
                <p style="color:#666;">Already have an account? <a href="login.php" style="color:var(--crimson); text-decoration:none; font-weight:bold;">Login</a></p>
            </div>
        </form>
    </div>
</div>

<?php include('footer.php'); ?>