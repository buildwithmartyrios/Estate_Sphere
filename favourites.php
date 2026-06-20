<?php 
include('header.php'); 
if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
$uid = $_SESSION['user_id'];
?>

<div class="container" style="margin-top: 50px;">
    <div style="text-align: center; margin-bottom: 50px;">
        <h2 style="color: var(--navy);">My Saved Portfolio</h2>
        <?php
        $val_res = mysqli_query($conn, "SELECT SUM(p.price) as total FROM properties p JOIN favourites f ON p.prop_id = f.prop_id WHERE f.user_id = $uid");
        $val_data = mysqli_fetch_assoc($val_res);
        ?>
        <h1 style="color: var(--navy); font-size: 3.5rem;">LKR <?php echo number_format($val_data['total'] ?? 0); ?></h1>
        <p style="color: #666;">Total Estimated Market Value of your saved properties</p>
    </div>

    <div class="property-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 25px;">
        <?php
        $fav_query = "SELECT p.* FROM properties p JOIN favourites f ON p.prop_id = f.prop_id WHERE f.user_id = $uid";
        $res = mysqli_query($conn, $fav_query);
        if(mysqli_num_rows($res) > 0):
            while($row = mysqli_fetch_assoc($res)): ?>
                <div class="property-card" style="background:white; padding:20px; border-radius:15px; box-shadow:var(--shadow);">
                    <img src="uploads/<?php echo $row['image_name']; ?>" style="width:100%; height:180px; object-fit:cover; border-radius:10px;">
                    <h3 style="margin-top:15px;"><?php echo $row['title']; ?></h3>
                    <p style="color:var(--crimson); font-weight:bold;">LKR <?php echo number_format($row['price']); ?></p>
                    <a href="product_detail.php?id=<?php echo $row['prop_id']; ?>" class="btn-main" style="display:block; text-align:center;">View Listing</a>
                </div>
            <?php endwhile;
        else: ?>
            <p style="text-align:center; grid-column: 1/-1;">Your portfolio is currently empty.</p>
        <?php endif; ?>
    </div>
</div>
<?php include('footer.php'); ?>