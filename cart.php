<?php 
include('header.php'); 

if(!isset($_SESSION['user_id'])) { 
    echo "<script>alert('Please login to use the shopping cart.'); window.location.href = 'login.php';</script>";
    exit(); 
}

$user_id = $_SESSION['user_id'];

// 1. ADD TO CART LOGIC
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['property_id'])) {
    $prop_id = mysqli_real_escape_string($conn, $_POST['property_id']);
    
    // Ensure you use 'prop_id' to match your properties table structure
    $check_cart = mysqli_query($conn, "SELECT * FROM cart WHERE user_id='$user_id' AND prop_id='$prop_id'");
    
    if(mysqli_num_rows($check_cart) == 0) {
        mysqli_query($conn, "INSERT INTO cart (user_id, prop_id) VALUES ('$user_id', '$prop_id')");
        echo "<script>alert('Property successfully added to your cart! 🛒');</script>";
    } else {
        echo "<script>alert('This property is already in your cart.');</script>";
    }
}

// 2. REMOVE FROM CART LOGIC
if (isset($_GET['remove'])) {
    $remove_id = mysqli_real_escape_string($conn, $_GET['remove']);
    mysqli_query($conn, "DELETE FROM cart WHERE prop_id='$remove_id' AND user_id='$user_id'");
    echo "<script>window.location.href='cart.php';</script>";
}
?>

<div class="container" style="margin-top: 50px; margin-bottom: 80px; min-height: 50vh;">
    <h2 style="color: var(--navy); border-bottom: 2px solid var(--crimson); padding-bottom: 10px; display: inline-block;">Your Cart 🛒</h2>
    
    <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: var(--shadow); margin-top: 20px;">
        <?php
        // 3. Fetch Cart Items
        $cart_query = "SELECT p.* FROM properties p JOIN cart c ON p.prop_id = c.prop_id WHERE c.user_id = '$user_id'";
        $res = mysqli_query($conn, $cart_query);
        $subtotal_value = 0;
        
        // Setup Currency Logic for the Cart
        $current_currency = $_SESSION['currency'] ?? 'LKR';
        $exchange_rate = 0.0033;
        
        if($res && mysqli_num_rows($res) > 0) {
            while($item = mysqli_fetch_assoc($res)) {
                $subtotal_value += $item['price'];
                
                // Format individual item price based on active currency
                if ($current_currency === 'USD') {
                    $item_price_display = "$ " . number_format($item['price'] * $exchange_rate, 2);
                } else {
                    $item_price_display = "Rs. " . number_format($item['price']);
                }
                ?>
                <div style='display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #eee; padding:20px 0;'>
                    <div style='display:flex; align-items:center; gap:20px;'>
                        <img src='<?php echo htmlspecialchars($item['image_path']); ?>' alt="Property Image" style='width: 100px; height: 75px; object-fit: cover; border-radius: 8px;'>
                        <div>
                            <h4 style='margin:0; color:var(--navy); font-size: 1.2rem;'><?php echo htmlspecialchars($item['title']); ?></h4>
                            <p style='margin:5px 0 0 0; color:var(--crimson); font-weight:bold;'><?php echo $item_price_display; ?></p>
                        </div>
                    </div>
                    <a href='cart.php?remove=<?php echo $item['prop_id']; ?>' class='btn-main' style='background:#f8d7da; color:#721c24; width:auto; text-decoration:none; padding: 10px 20px;'>Remove</a>
                </div>
                <?php
            }
            
            // --- NEW FEE MATH LOGIC FOR CART ---
            $fee_rate = 0.005; // 0.5%
            $service_fee = $subtotal_value * $fee_rate;
            $grand_total = $subtotal_value + $service_fee;

            // Format totals based on active currency
            if ($current_currency === 'USD') {
                $subtotal_display = "$ " . number_format($subtotal_value * $exchange_rate, 2);
                $fee_display = "$ " . number_format($service_fee * $exchange_rate, 2);
                $grand_total_display = "$ " . number_format($grand_total * $exchange_rate, 2);
            } else {
                $subtotal_display = "Rs. " . number_format($subtotal_value);
                $fee_display = "Rs. " . number_format($service_fee);
                $grand_total_display = "Rs. " . number_format($grand_total);
            }
            
            // Output Footer with Functional Breakdown
            echo "<div style='margin-top: 40px; text-align: right; border-top: 3px solid var(--navy); padding-top: 20px;'>
                    <p style='color: #666; font-size: 1.1rem; margin: 5px 0;'>Subtotal: <strong>{$subtotal_display}</strong></p>
                    <p style='color: #666; font-size: 1.1rem; margin: 5px 0;'>Processing Fee (0.5%): <strong style='color: var(--crimson);'>+ {$fee_display}</strong></p>
                    <h3 style='color: var(--navy); font-size: 1.8rem; margin-top: 15px; margin-bottom: 25px;'>Grand Total: {$grand_total_display}</h3>
                    <a href='checkout.php' class='btn-main' style='display: inline-block; width:auto; padding: 15px 50px; text-decoration: none;'>Proceed to Checkout ➔</a>
                  </div>";
        } else {
            echo "<p style='text-align:center; color: #666;'>Your cart is empty.</p>";
        }
        ?>
    </div>
</div>

<?php include('footer.php'); ?>