<?php 
include('../db_connect.php'); 
include('../header.php');

// Security: Only allow admins
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Access Denied'); window.location.href='../index.php';</script>";
    exit();
}

// Logic to DELETE a property
if(isset($_GET['delete'])) {
    $del_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM properties WHERE prop_id = '$del_id'");
    echo "<script>alert('Property Deleted!'); window.location.href='manage-properties.php';</script>";
}
?>

<div class="container" style="margin-top: 80px;">
    <h2 style="color: var(--navy);">Manage Listings</h2>
    <a href="add-property.php" class="btn-main" style="width: auto; margin-bottom: 20px;">+ Add New Property</a>
    
    <table style="width:100%; border-collapse: collapse; background: white; box-shadow: var(--shadow);">
        <tr style="background: var(--navy); color: white;">
            <th style="padding: 15px;">Title</th>
            <th style="padding: 15px;">Price</th>
            <th style="padding: 15px;">Actions</th>
        </tr>
        <?php
        $res = mysqli_query($conn, "SELECT * FROM properties");
        while($row = mysqli_fetch_assoc($res)) {
            echo "<tr>
                <td style='padding:15px; border-bottom:1px solid #eee;'>{$row['title']}</td>
                <td style='padding:15px; border-bottom:1px solid #eee;'>LKR ".number_format($row['price'])."</td>
                <td style='padding:15px; border-bottom:1px solid #eee;'>
                    <a href='edit-property.php?id={$row['prop_id']}' style='color:blue; margin-right:10px;'>Edit</a>
                    <a href='manage-properties.php?delete={$row['prop_id']}' style='color:red;' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                </td>
            </tr>";
        }
        ?>
    </table>
</div>
<?php include('../footer.php'); ?>