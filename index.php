<?php include('particle-front/menu.php'); ?>

<!-- HERO SECTION -->
<section class="hero-section text-center">
    <div class="container">
        <h1 class="hero-title">Dive Into Delicious <br> Meal Dishes</h1>

        <div class="hero-slider-container">
            <div class="hero-slider">
                <!-- Slider Items -->
                <?php
                $slider_images = glob('images/food/*.{jpg,jpeg,png,webp}', GLOB_BRACE);
                if ($slider_images) {
                    foreach ($slider_images as $image) {
                        $img_name = basename($image);
                        echo '<div class="slider-item">';
                        echo '<img src="' . SITEURL . 'images/food/' . $img_name . '" alt="Food">';
                        echo '</div>';
                    }
                    // Duplicate for infinite scroll effect
                    foreach ($slider_images as $image) {
                        $img_name = basename($image);
                        echo '<div class="slider-item">';
                        echo '<img src="' . SITEURL . 'images/food/' . $img_name . '" alt="Food">';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No images found for slider.</p>';
                }
                ?>
            </div>
        </div>
    </div>
</section>

<!-- MENU SECTION -->
<section class="menu-section" id="menu">
    <div class="container">
        <div class="section-header text-center">
            <span class="icon-header">🍽️</span>
            <h2>Our Special Menu</h2>
        </div>

        <div class="menu-list">
            <?php
            try {
                //Getting Foods from Database that are active
                $collection = $conn->selectCollection('foods');
                $cursor = $collection->find(['active' => 'Yes']); // No limit, show all or logical limit
                $foods = iterator_to_array($cursor);

                if (count($foods) > 0) {
                    foreach ($foods as $food) {
                        $foodID = mongoIdToString($food['_id']);
                        $title = $food['title'];
                        $price = $food['price'];
                        $description = $food['description'];
                        $image_name = $food['image_name'];
                        ?>
                        <div class="menu-item-row">
                            <div class="menu-item-img">
                                <?php if ($image_name && file_exists("images/food/" . $image_name)): ?>
                                    <img src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>" alt="<?php echo $title; ?>">
                                <?php else: ?>
                                    <div class="no-img">No Image</div>
                                <?php endif; ?>
                            </div>

                            <div class="menu-item-details">
                                <div class="menu-item-header">
                                    <h4 class="menu-item-title"><?php echo $title; ?></h4>
                                    <span class="menu-item-price">$<?php echo $price; ?></span>
                                </div>
                                <div class="menu-item-desc">
                                    <p><?php echo $description; ?></p>
                                </div>
                            </div>

                            <div class="menu-item-action">
                                <a href="<?php echo SITEURL; ?>add-to-cart.php?id=<?php echo $foodID; ?>" class="btn-order">Add to
                                    Cart</a>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<div class='error text-center'>Food not available.</div>";
                }
            } catch (Exception $e) {
                echo "<div class='error'>Error: " . $e->getMessage() . "</div>";
            }
            ?>
        </div>
    </div>
</section>

<!-- CONTACT / FOOTER SECTION -->
<section class="footer-section" id="contact">
    <div class="container text-center">
        <div class="footer-content">
            <h3>Contact Us</h3>
            <p>For reservations and orders:</p>
            <a href="mailto:wowfood@gmail.com" class="contact-email">wowfood@gmail.com</a>
            <div class="social-links">
                <!-- Add social icons if needed -->
            </div>
            <p class="copyright">© 2026 Wow Foods. All rights reserved.</p>
        </div>
    </div>
</section>

</body>

</html>