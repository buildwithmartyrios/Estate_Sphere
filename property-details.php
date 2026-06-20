<?php include('header.php'); ?>

<?php
// ==========================================
// Form Submission Logic for Saving Properties
// ==========================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_property'])) {
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('Please login to save properties to your portfolio.'); window.location.href='login.php';</script>";
    } else {
        $user_id = $_SESSION['user_id'];
        $prop_id = mysqli_real_escape_string($conn, $_POST['property_id']);
        
        $check_query = "SELECT * FROM favourites WHERE user_id='$user_id' AND prop_id='$prop_id'";
        $check_result = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($check_result) == 0) {
            $insert_query = "INSERT INTO favourites (user_id, prop_id) VALUES ('$user_id', '$prop_id')";
            if(mysqli_query($conn, $insert_query)) {
                echo "<script>alert('Property successfully added to your Saved Portfolio! ❤️');</script>";
            } else {
                echo "<script>alert('Error saving property.');</script>";
            }
        } else {
            echo "<script>alert('This property is already in your saved list!');</script>";
        }
    }
}
?>

<?php
if (isset($_GET['id'])) {
    $property_id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "SELECT * FROM properties WHERE prop_id = '$property_id'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $property = mysqli_fetch_assoc($result);
        
        // --- DYNAMIC CURRENCY LOGIC ---
        $current_currency = $_SESSION['currency'] ?? 'LKR';
        $exchange_rate = 0.0033;
        
        if ($current_currency === 'USD') {
            $price_display = "$ " . number_format($property['price'] * $exchange_rate, 2);
        } else {
            $price_display = "Rs. " . number_format($property['price']);
        }
?>

<div class="container" style="margin-top: 40px; margin-bottom: 60px;">
    <p style="color: #777; font-size: 0.9rem; margin-bottom: 20px;">
        <a href="properties.php" style="color: var(--navy); text-decoration: none;">Properties</a> > 
        <?php echo htmlspecialchars($property['title']); ?>
    </p>

    <div style="display: flex; gap: 40px; flex-wrap: wrap;">
        <main style="flex: 2; min-width: 300px;">
            <div style="border-radius: 15px; overflow: hidden; height: 400px; margin-bottom: 20px; box-shadow: var(--shadow);">
                <img src="<?php echo htmlspecialchars($property['image_path']); ?>" alt="Property Image" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            
            <h1 style="color: var(--navy); margin-top: 0; margin-bottom: 5px;"><?php echo htmlspecialchars($property['title']); ?></h1>
            <p style="color: #666; font-size: 1.1rem; margin-top: 0;">📍 <?php echo htmlspecialchars($property['location']); ?></p>

            <h3 style="color: var(--navy);">Description</h3>
            <p style="color: #555; line-height: 1.8;"><?php echo nl2br(htmlspecialchars($property['description'])); ?></p>
            
            <?php if (!empty($property['verification_doc'])): ?>
                <div style="margin-top: 20px; margin-bottom: 20px;">
                    <a href="/estate_sphere/<?php echo htmlspecialchars($property['verification_doc']); ?>" download class="btn-main" style="background: #27ae60; text-decoration: none; font-size: 0.95rem; padding: 12px 20px; display: inline-block;">
                        📥 Download Legal Vetting Report (PDF)
                    </a>
                </div>
            <?php endif; ?>

            <div style="background: var(--light); padding: 30px; border-radius: 15px; margin-top: 40px; border-left: 5px solid var(--navy);">
                <h3 style="color: var(--navy); margin-top: 0;">ROI Investment Calculator</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <input type="number" id="purchasePrice" class="form-control" placeholder="Purchase Price (LKR)">
                    <input type="number" id="monthlyRent" class="form-control" placeholder="Exp. Monthly Rent (LKR)">
                    <input type="number" id="expenses" class="form-control" placeholder="Annual Expenses (Optional)">
                </div>
                <button onclick="calculateROI()" class="btn-main" style="width: 100%; margin-top: 10px;">Calculate Return</button>
                <div id="roiResult" style="display:none; margin-top:20px; padding:15px; background:white; border-radius:8px;">
                    Estimated Annual ROI: <strong id="roiPercentage" style="color:var(--crimson); font-size: 1.2rem;"></strong>
                    <p id="roiText" style="margin: 5px 0 0 0;"></p>
                </div>
            </div>

            <?php 
            // --- BULLETPROOF MAP COMPONENT ---
            if(!empty(trim($property['map_link']))): 
                $map_input = trim($property['map_link']);
                $final_map_url = "";
                
                if(strpos($map_input, '<iframe') !== false) {
                    preg_match('/src="([^"]+)"/', $map_input, $matches);
                    if(isset($matches[1])) {
                        $final_map_url = $matches[1];
                    }
                } else {
                    $final_map_url = $map_input;
                }

                if(!empty($final_map_url)):
            ?>
                <div style="margin-top: 40px; background: white; padding: 20px; border-radius: 15px; box-shadow: var(--shadow);">
                    <h3 style="color: var(--navy); margin-bottom: 20px;">Property Location</h3>
                    <div style="width: 100%; height: 400px; border-radius: 10px; overflow: hidden;">
                        <iframe src="<?php echo htmlspecialchars($final_map_url); ?>" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            <?php 
                endif;
            endif; 
            ?>
            </main>

        <aside style="flex: 1; min-width: 300px;">
            <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: var(--shadow); position: sticky; top: 100px; border-top: 5px solid var(--crimson);">
                <p style="color: #666; margin: 0; font-weight: bold; text-transform: uppercase; font-size: 0.8rem;">Asking Price</p>
                <h2 style="color: var(--crimson); font-size: 2.5rem; margin: 5px 0 20px 0;"><?php echo $price_display; ?></h2>
                
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    
                    <a href="checkout.php" class="btn-main" style="display: block; background: #27ae60; text-align: center; text-decoration: none; padding: 15px; font-size: 1.1rem; width: auto; box-sizing: border-box;">
                        ✅ Proceed to Checkout
                    </a>

                    <form action="cart.php" method="POST" style="margin: 0;">
                        <input type="hidden" name="property_id" value="<?php echo $property['prop_id']; ?>">
                        <button type="submit" class="btn-main" style="width: 100%; padding: 15px; font-size: 1.1rem; cursor: pointer;">🛒 Add to Cart</button>
                    </form>
                    
                    <form action="" method="POST" style="margin: 0;">
                        <input type="hidden" name="property_id" value="<?php echo $property['prop_id']; ?>">
                        <button type="submit" name="save_property" style="width: 100%; background: var(--light); border: 1px solid var(--navy); color: var(--navy); padding: 15px; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 1.1rem; transition: 0.3s;">❤️ Save Property</button>
                    </form>
                    
                </div>
            </div>
        </aside>
    </div>
</div>

<?php
    } else {
        echo "<div class='container' style='text-align:center; padding: 100px 0;'><h2>Property Not Found</h2></div>";
    }
} else {
    echo "<script>window.location.href = 'properties.php';</script>";
}
?>

<div class="container" style="margin-top: 80px; margin-bottom: 60px;">
    <h2 style="color: var(--navy); border-left: 5px solid var(--crimson); padding-left: 15px;">Similar Properties You Might Like</h2>
    
    <div class="property-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; margin-top: 30px;">
        <?php
        // Grab the current property's ID, Location, and Bedrooms (Assumes $id or $_GET['id'] is already set at the top of your file)
        $current_prop_id = mysqli_real_escape_string($conn, $_GET['id']);
        
        // Fetch current property details to use for matching
        $current_details = mysqli_fetch_assoc(mysqli_query($conn, "SELECT location, bedrooms FROM properties WHERE prop_id = '$current_prop_id'"));
        $current_loc = mysqli_real_escape_string($conn, $current_details['location']);
        $current_beds = (int)$current_details['bedrooms'];

        // THE ALGORITHM: Find active properties in the same area OR with the same bedrooms, EXCLUDING the one currently being viewed.
        $sim_query = "SELECT * FROM properties 
                      WHERE status = 'available' 
                      AND prop_id != '$current_prop_id' 
                      AND (location LIKE '%$current_loc%' OR bedrooms = $current_beds) 
                      ORDER BY RAND() 
                      LIMIT 3";
                      
        $sim_result = mysqli_query($conn, $sim_query);

        if(mysqli_num_rows($sim_result) > 0):
            while($sim_row = mysqli_fetch_assoc($sim_result)): ?>
                
                <div class="property-card" style="background:white; padding:20px; border-radius:12px; box-shadow:var(--shadow); display: flex; flex-direction: column; height: 100%; box-sizing: border-box;">
                    
                    <div>
                        <img src="<?php echo htmlspecialchars($sim_row['image_path']); ?>" alt="Property Image" style="width:100%; border-radius:8px; height:200px; object-fit:cover;">
                        
                        <h3 style="margin: 15px 0 10px 0; color: var(--navy); font-size: 1.2rem;"><?php echo htmlspecialchars($sim_row['title']); ?></h3>
                        
                        <p style="color: #666; font-size: 0.85rem; margin-bottom: 10px;">
                            📍 <?php echo htmlspecialchars($sim_row['location']); ?> | 🛏️ <?php echo $sim_row['bedrooms']; ?> Beds
                        </p>
                        
                        <p style="color: var(--crimson); font-weight: bold; font-size: 1.1rem; margin-bottom: 15px;">
                            <?php 
                            if (isset($_SESSION['currency']) && $_SESSION['currency'] === 'USD') {
                                $converted_price = $sim_row['price'] * 0.0033; 
                                echo "$ " . number_format($converted_price, 2);
                            } else {
                                echo "Rs. " . number_format($sim_row['price']);
                            }
                            ?>
                        </p>
                    </div>
                    
                    <div style="display: flex; justify-content: center; width: 100%; margin-top: auto; padding-top: 10px;">
                        <a href="property-details.php?id=<?php echo $sim_row['prop_id']; ?>" class="btn-main" style="width: max-content !important; padding: 10px 35px !important; text-decoration: none; border-radius: 50px; margin: 0; background: var(--navy);">
                            View Details
                        </a>
                    </div>
                    
                </div>
                
            <?php endwhile; 
        else: ?>
            <p style="text-align:center; width:100%; color:#666; grid-column: 1 / -1;">No similar properties available right now.</p>
        <?php endif; ?>
    </div>
</div>

<?php include('footer.php'); ?>