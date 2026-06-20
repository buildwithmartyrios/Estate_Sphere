<?php include('header.php'); ?>

<section style="background: linear-gradient(rgba(26, 42, 108, 0.8), rgba(26, 42, 108, 0.8)); padding: 120px 0; color: white; text-align: center;">
    <div class="container">
        <h1 style="font-size: 3.5rem; margin-top: 0;">Find Your Future in Sri Lanka</h1>
        <p style="font-size: 1.2rem; opacity: 0.9; margin-bottom: 30px;">Verified Residential, Commercial, and Industrial Listings</p>
        <a href="properties.php" class="btn-main" style="display:inline-block; margin-top:20px; width:auto; padding: 15px 40px; text-decoration: none; border-radius: 50px;">Explore All Properties</a>
    </div>
</section>

<div class="container" style="margin-top: 60px; margin-bottom: 60px;">
    <h2 style="color: var(--navy); border-left: 5px solid var(--crimson); padding-left: 15px;">Featured Listings</h2>
    
    <div class="property-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; margin-top: 30px;">
        <?php
        // Fetch available properties ordered by newest additions
        $featured = mysqli_query($conn, "SELECT * FROM properties WHERE status = 'available' ORDER BY prop_id DESC");
        
        $rendered_count = 0; // Track actual valid properties rendered
        $max_featured = 3;   // Maximum number of properties to show on homepage
        
        if (mysqli_num_rows($featured) > 0):
            while ($row = mysqli_fetch_assoc($featured)): 
                
                // --- ULTIMATE GHOST ROW DEFENSE START ---
                if (empty($row['prop_id'])) {
                    continue; 
                }
                
                if (!isset($row['price']) || $row['price'] <= 0) {
                    continue;
                }
                
                $clean_title = isset($row['title']) ? trim($row['title']) : '';
                if (empty($clean_title) || strtolower($clean_title) === 'no title provided') {
                    continue;
                }
                // --- ULTIMATE GHOST ROW DEFENSE END ---
                
                // If it passes defense checks, increment count and check limit
                $rendered_count++;
                if ($rendered_count > $max_featured) {
                    break; 
                }

                // Fallback for missing images
                $img_src = !empty($row['image_path']) ? htmlspecialchars($row['image_path']) : 'uploads/placeholder.jpg';
                ?>
                
                <div class="property-card" style="background:white; padding:20px; border-radius:12px; box-shadow:var(--shadow); display: flex; flex-direction: column; height: 100%; box-sizing: border-box;">
                    
                    <div>
                        <img src="<?php echo $img_src; ?>" alt="Property Image" style="width:100%; border-radius:8px; height:200px; object-fit:cover;">
                        
                        <h3 style="margin: 15px 0 10px 0; color: var(--navy);"><?php echo htmlspecialchars($row['title']); ?></h3>
                        
                        <p style="color: var(--crimson); font-weight: bold; font-size: 1.2rem; margin-bottom: 15px;">
                            <?php 
                            if (isset($_SESSION['currency']) && $_SESSION['currency'] === 'USD') {
                                $converted_price = $row['price'] * 0.0033; 
                                echo "$ " . number_format($converted_price, 2);
                            } else {
                                echo "Rs. " . number_format($row['price']);
                            }
                            ?>
                        </p>
                    </div>
                    
                    <div style="display: flex; justify-content: center; width: 100%; margin-top: auto; padding-top: 10px;">
                        <a href="property-details.php?id=<?php echo $row['prop_id']; ?>" class="btn-main" style="width: max-content !important; padding: 10px 35px !important; text-decoration: none; border-radius: 50px; margin: 0;">
                            View Details
                        </a>
                    </div>
                    
                </div>
                
            <?php endwhile; 
        endif; 

        // If no rows were valid or matching
        if ($rendered_count === 0): ?>
            <p style="text-align:center; width:100%; color:#666; grid-column: 1 / -1;">No featured properties available at the moment.</p>
        <?php endif; ?>
    </div>
</div>

<div style="background: #f4f7f6; padding: 80px 0; margin-top: 60px; border-top: 1px solid #e1e8ed;">
    <div class="container">
        <h2 style="text-align: center; color: var(--navy); margin-top: 0; margin-bottom: 10px;">What Our Clients Say</h2>
        <p style="text-align: center; color: #666; margin-bottom: 50px;">Trusted by hundreds of property buyers and investors across Sri Lanka.</p>
        
        <div style="display: flex; gap: 30px; flex-wrap: wrap; justify-content: center;">
            
            <div style="background: white; padding: 40px 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); flex: 1; min-width: 280px; max-width: 350px; text-align: center; border-bottom: 4px solid var(--crimson);">
                <div style="font-size: 1.5rem; color: #f1c40f; margin-bottom: 20px;">★★★★★</div>
                <p style="color: #555; font-style: italic; line-height: 1.6; margin-bottom: 25px;">
                    "Estate Sphere made finding our dream home in Colombo so easy. Mr. Kamal Perera handled the legal deed vetting perfectly. Highly trustworthy!"
                </p>
                <h4 style="color: var(--navy); margin: 0; font-size: 1.1rem;">Samantha W.</h4>
                <span style="font-size: 0.85rem; color: #888;">First-Time Homebuyer</span>
            </div>

            <div style="background: white; padding: 40px 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); flex: 1; min-width: 280px; max-width: 350px; text-align: center; border-bottom: 4px solid var(--navy);">
                <div style="font-size: 1.5rem; color: #f1c40f; margin-bottom: 20px;">★★★★★</div>
                <p style="color: #555; font-style: italic; line-height: 1.6; margin-bottom: 25px;">
                    "The user dashboard is incredibly transparent. I tracked my reservation from 'Pending Agent' to 'Completed' without making a single phone call."
                </p>
                <h4 style="color: var(--navy); margin: 0; font-size: 1.1rem;">Dinesh R.</h4>
                <span style="font-size: 0.85rem; color: #888;">Property Investor</span>
            </div>

            <div style="background: white; padding: 40px 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); flex: 1; min-width: 280px; max-width: 350px; text-align: center; border-bottom: 4px solid #27ae60);">
                <div style="font-size: 1.5rem; color: #f1c40f; margin-bottom: 20px;">★★★★★</div>
                <p style="color: #555; font-style: italic; line-height: 1.6; margin-bottom: 25px;">
                    "As an expat living abroad, the USD/LKR dynamic currency switcher was a lifesaver. It made calculating my investment budget incredibly simple."
                </p>
                <h4 style="color: var(--navy); margin: 0; font-size: 1.1rem;">Sarah J.</h4>
                <span style="font-size: 0.85rem; color: #888;">Foreign Investor</span>
            </div>

        </div>
    </div>
</div>
<?php include('footer.php'); ?>