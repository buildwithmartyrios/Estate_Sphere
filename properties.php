<?php 
// 1. Connect to the database and start the session
include('db_config.php'); 

// 2. Include the head and navigation
include('header.php'); 
?>

<div class="container">
    <h2 style="margin-top:40px; color: var(--navy);">Available Listings</h2>
    
    <div style="margin-bottom: 40px;">
        <form method="GET" action="properties.php" style="background: white; padding: 25px; border-radius: 15px; box-shadow: var(--shadow); display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; align-items: flex-end;">
            <div>
                <label style="font-weight: bold; color: var(--navy); font-size: 0.9rem; display: block; margin-bottom: 5px;">Search Location</label>
                <input type="text" name="search_location" class="form-control" placeholder="e.g., Matale, Colombo" value="<?php echo htmlspecialchars($_GET['search_location'] ?? ''); ?>">
            </div>
            <div>
                <label style="font-weight: bold; color: var(--navy); font-size: 0.9rem; display: block; margin-bottom: 5px;">Min Bedrooms</label>
                <select name="min_beds" class="form-control">
                    <option value="">Any</option>
                    <?php for($i=1; $i<=5; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php echo (isset($_GET['min_beds']) && $_GET['min_beds'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?>+ Beds</option>
                    <?php endfor; ?>
                </select>
            </div>
            <div>
                <label style="font-weight: bold; color: var(--navy); font-size: 0.9rem; display: block; margin-bottom: 5px;">Max Price (LKR)</label>
                <input type="number" name="max_price" class="form-control" placeholder="e.g., 50000000" value="<?php echo htmlspecialchars($_GET['max_price'] ?? ''); ?>">
            </div>
            
            <div>
                <label style="font-weight: bold; color: var(--navy); font-size: 0.9rem; display: block; margin-bottom: 5px;">Sort Properties By</label>
                <select name="sort_by" class="form-control">
                    <option value="newest" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'newest') ? 'selected' : ''; ?>>Newest First</option>
                    <option value="oldest" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'oldest') ? 'selected' : ''; ?>>Oldest First</option>
                    <option value="price_low" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'price_low') ? 'selected' : ''; ?>>Price: Low to High</option>
                    <option value="price_high" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'price_high') ? 'selected' : ''; ?>>Price: High to Low</option>
                </select>
            </div>

            <div>
                <label style="font-weight: bold; font-size: 0.9rem; display: block; margin-bottom: 5px; visibility: hidden;">Submit</label>
                <button type="submit" class="btn-main" style="padding: 10px 20px; margin: 0; width: 100%; box-sizing: border-box;">🔍 Filter & Sort</button>
            </div>
        </form>
    </div>
    
    <div class="property-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px; margin-bottom: 50px;">
        <?php
        // Keep your original query structure
        $query = "SELECT * FROM properties WHERE status = 'available'"; 

        if (!empty($_GET['search_location'])) {
            $loc = mysqli_real_escape_string($conn, $_GET['search_location']);
            $query .= " AND location LIKE '%$loc%'";
        }

        if (!empty($_GET['min_beds'])) {
            $beds = (int)$_GET['min_beds'];
            $query .= " AND bedrooms >= $beds";
        }

        if (!empty($_GET['max_price'])) {
            $price = (float)$_GET['max_price'];
            $query .= " AND price <= $price";
        }

        $sort = $_GET['sort_by'] ?? 'newest';
        if ($sort === 'oldest') {
            $query .= " ORDER BY prop_id ASC";
        } elseif ($sort === 'price_low') {
            $query .= " ORDER BY price ASC";
        } elseif ($sort === 'price_high') {
            $query .= " ORDER BY price DESC";
        } else {
            $query .= " ORDER BY prop_id DESC"; 
        }

        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                
                // --- ULTIMATE GHOST ROW DEFENSE START ---
                
                // 1. Skip if ID is missing
                if (empty($row['prop_id'])) {
                    continue; 
                }
                
                // 2. Skip if price is 0 or missing (No real property is Rs. 0)
                if (empty($row['price']) || $row['price'] <= 0) {
                    continue;
                }
                
                // 3. Skip if title is empty or says "No Title Provided"
                $clean_title = trim($row['title']);
                if (empty($clean_title) || strtolower($clean_title) == 'no title provided') {
                    continue;
                }
                
                // --- ULTIMATE GHOST ROW DEFENSE END ---

                // Fallback for missing images
                $img_src = !empty($row['image_path']) ? htmlspecialchars($row['image_path']) : 'uploads/placeholder.jpg';
                ?>
                <div class="property-card" style="background: white; padding: 20px; border-radius: 12px; box-shadow: var(--shadow); display: flex; flex-direction: column; height: 100%; box-sizing: border-box;">
                    
                    <div>
                        <img src="<?php echo $img_src; ?>" alt="Property Image" style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px;">
                        
                        <h3 style="margin: 15px 0 10px 0; color: var(--navy);"><?php echo htmlspecialchars($row['title']); ?></h3>
                        
                        <p style="color: #666; font-size: 0.9rem; height: 50px; overflow: hidden; margin-bottom: 15px;">
                            <?php echo substr(htmlspecialchars($row['description']), 0, 100); ?>...
                        </p>
                        
                        <p style="color: var(--crimson); font-weight: bold; font-size: 1.2rem; margin-bottom: 10px;">
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
                    
                    <div style="display: flex; justify-content: center; width: 100%; margin-top: auto; padding-top: 15px;">
                        <a href="property-details.php?id=<?php echo $row['prop_id']; ?>" class="btn-main" style="width: max-content !important; padding: 12px 35px !important; text-decoration: none; border-radius: 50px; margin: 0;">
                            View Details
                        </a>
                    </div>
                    
                </div>
                <?php
            }
        } else {
            echo "<p style='grid-column: 1 / -1; text-align:center; padding:20px; color:#666;'>No matching properties found for your filters.</p>";
        }
        ?>
    </div>
</div>

<?php 
include('footer.php'); 
?>