<?php
include('db_config.php');
include('header.php');

// THE VAULT DOOR: Only the Super Admin gets past this line
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'super_admin') {
    echo "<script>alert('SECURITY BREACH: You do not have Super Admin clearance.'); window.location.href='index.php';</script>";
    exit(); // Instantly stops the rest of the page from loading
}

// Logic to process role changes when the form below is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_role'])) {
    $target_user_id = mysqli_real_escape_string($conn, $_POST['target_user_id']);
    $new_role = mysqli_real_escape_string($conn, $_POST['new_role']);
    
    // Update the database
    $update_query = "UPDATE users SET role = '$new_role' WHERE user_id = '$target_user_id'";
    if(mysqli_query($conn, $update_query)) {
        echo "<script>alert('User role successfully updated!');</script>";
    }
}
?>
<div class="container" style="margin-top: 50px; margin-bottom: 50px;">
    <h2 style="color: var(--navy); border-left: 5px solid var(--crimson); padding-left: 15px;">Super Admin: User Management</h2>
    
    <table style="width: 100%; border-collapse: collapse; margin-top: 20px; background: white; box-shadow: var(--shadow);">
        <tr style="background: var(--navy); color: white;">
            <th style="padding: 15px; text-align: left;">User ID</th>
            <th style="padding: 15px; text-align: left;">Name</th>
            <th style="padding: 15px; text-align: left;">Email</th>
            <th style="padding: 15px; text-align: left;">Current Role</th>
            <th style="padding: 15px; text-align: center;">Action</th>
        </tr>

        <?php
        // Fetch all users EXCEPT yourself (you shouldn't be able to accidentally demote yourself!)
        $current_user = $_SESSION['user_id'];
        $user_query = mysqli_query($conn, "SELECT * FROM users WHERE user_id != '$current_user'");
        
        while ($user = mysqli_fetch_assoc($user_query)): ?>
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 15px;"><?php echo $user['user_id']; ?></td>
                <td style="padding: 15px;"><?php echo htmlspecialchars($user['full_name']); ?></td>
                <td style="padding: 15px;"><?php echo htmlspecialchars($user['email']); ?></td>
                <td style="padding: 15px;">
                    <strong style="color: <?php echo ($user['role'] == 'admin') ? 'var(--crimson)' : 'var(--navy)'; ?>;">
                        <?php echo strtoupper($user['role']); ?>
                    </strong>
                </td>
                <td style="padding: 15px; text-align: center;">
                    <form method="POST" action="" style="margin: 0; display: flex; gap: 10px; justify-content: center;">
                        <input type="hidden" name="target_user_id" value="<?php echo $user['user_id']; ?>">
                        
                        <select name="new_role" style="padding: 5px; border-radius: 5px; border: 1px solid #ccc;">
                            <option value="client" <?php echo ($user['role'] == 'client') ? 'selected' : ''; ?>>Client</option>
                            <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                        </select>
                        
                        <button type="submit" name="update_role" class="btn-main" style="padding: 5px 15px; margin: 0; font-size: 0.9rem;">Update</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include('footer.php'); ?>