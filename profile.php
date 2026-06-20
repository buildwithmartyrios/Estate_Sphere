<?php 
include('header.php'); 
if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$uid = $_SESSION['user_id'];
$user_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE user_id=$uid"));

if(isset($_POST['update_profile'])) {
    $new_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    mysqli_query($conn, "UPDATE users SET full_name='$new_name' WHERE user_id=$uid");
    echo "<script>alert('Profile Updated!'); window.location.href='profile.php';</script>";
}
?>

<div class="container" style="margin-top: 50px;">
    <main style="max-width: 800px; margin: auto; background: white; padding: 30px; border-radius: 15px; box-shadow: var(--shadow);">
        <h2 style="color: var(--navy);">My Profile</h2>
        <form method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" class="form-control" value="<?php echo $user_data['full_name']; ?>">
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="text" class="form-control" value="<?php echo $user_data['email']; ?>" disabled>
                <small>Email cannot be changed.</small>
            </div>
            <button type="submit" name="update_profile" class="btn-main" style="width: auto;">Save Changes</button>
        </form>
    </main>
</div>

<?php include('footer.php'); ?>