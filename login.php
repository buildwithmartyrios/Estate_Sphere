<?php include('header.php'); ?>

<div class="container" style="max-width: 450px; margin-top: 60px;">
    <div style="background: white; padding: 40px; border-radius: 15px; box-shadow: var(--shadow);">
        <h2 style="text-align: center; color: var(--navy);">Welcome Back</h2>
        
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $password = $_POST['password'];

            $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
            if ($row = mysqli_fetch_assoc($result)) {
                if (password_verify($password, $row['password'])) {
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['full_name'] = $row['full_name'];
                    $_SESSION['role'] = $row['role'];
                    echo "<script>window.location.href = 'index.php';</script>";
                } else {
                    echo "<p style='color:red; text-align:center;'>Invalid password.</p>";
                }
            } else {
                echo "<p style='color:red; text-align:center;'>User not found.</p>";
            }
        }
        ?>

        <form action="login.php" method="POST" autocomplete="off">
            
            <label style="font-weight: bold; display: block; margin-bottom: 5px;">Email Address</label>
            <input type="email" name="email" class="form-control" required autocomplete="off">
            
            <label style="font-weight: bold; display: block; margin-top: 15px; margin-bottom: 5px;">Password</label>
            <input type="password" name="password" class="form-control" required autocomplete="new-password">
            
            <button type="submit" class="btn-main" style="margin-top: 20px; width: 100%;">Login</button>
        </form>
        
        <div style="text-align: center; margin-top: 20px;">
            <small>Don't have an account? <a href="register.php" style="color: var(--crimson);">Register here</a></small>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>