<?php require_once 'includes/top.php'; ?>

     <!-- Backdrop (ensure only one exists on page) -->
    <div class="nav-backdrop" id="navBackdrop"></div>
    <!-- <div class="nav-backdrop"></div> -->

    <!-- Hero Section with Video Slider -->
    <section class="hero-section">
        <div class="custom-carousel">
            <?php echo $data['banner'] ?>
            <!-- Controls -->
            <div class="custom-carousel-controls">
                <div class="custom-carousel-dots">
                    <div class="custom-carousel-dot active"></div>
                    <div class="custom-carousel-dot"></div>
                    <div class="custom-carousel-dot"></div>
                    <div class="custom-carousel-dot"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Showcase Section -->
    <div class="marquee mt-4">
        <h1 class="gradient-text">Watch n' shop</h1>
        <div class="marquee-inner">
            <video src="<?php echo IMGPATH ?>/3206300-hd_1920_1080_25fps - Copy.mp4" autoplay muted loop></video>
            <video src="<?php echo IMGPATH ?>/WhatsApp Video 2025-08-30 at 20.45.47.mp4" autoplay muted loop></video>
            <video src="<?php echo IMGPATH ?>/WhatsApp Video 2025-08-30 at 20.45.47.mp4" autoplay muted loop></video>
            <video src="<?php echo IMGPATH ?>/WhatsApp Video 2025-08-30 at 20.45.53.mp4" autoplay muted loop></video>
            <video src="<?php echo IMGPATH ?>/WhatsApp Video 2025-08-30 at 20.45.47.mp4" autoplay muted loop></video>
            <video src="<?php echo IMGPATH ?>/WhatsApp Video 2025-08-30 at 20.45.47.mp4" autoplay muted loop></video>
            <video src="<?php echo IMGPATH ?>/WhatsApp Video 2025-08-30 at 20.45.47.mp4" autoplay muted loop></video>
            <video src="<?php echo IMGPATH ?>/WhatsApp Video 2025-08-30 at 20.45.47.mp4" autoplay muted loop></video>
        </div>
    </div>

    <!-- Categories Section -->
    <section class="categories-section">
        <div class="container">
            <h2 class="section-title">Shop by Category</h2>
            <div class="categories-grid">
                <?php echo $data['category_frame'] ?>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="products-section">
        <div class="container">
            <h2 class="section-title">Our Products</h2>
            <div class="product-tabs" style="display: flex !important;">
                <button class="tab-btn active" data-tab="featured_product">Featured Products</button>
                <button class="tab-btn" data-tab="new_arrival">Most View Products</button>
                <button class="tab-btn" data-tab="best_seller">Bestseller Products</button>
            </div>
            
            <div class="products-grid">
                <!-- <div class="product-card" data-product-id="1" data-tab-group="best_seller">
                    <div class="product-image min-img-card">
                        <img src="<?php echo IMGPATH ?>/d1.jpg" alt="Premium Cotton Dress Shirt" onclick="quickView(1)">
                        <span class="product-badge">Best Seller</span>
                        <div class="product-actions">
                        <button class="action-btn wishlist-action" onclick="toggleWishlist(1)">
                            <i class="fas fa-heart"></i>
                        </button>
                        <button class="action-btn quick-view" onclick="quickView(1)">
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                        </div>
                    </div>
                    <div class="product-info" onclick="quickView(1)">
                        <h3 class="product-title">Premium Cotton Dress Shirt</h3>
                        <div class="product-price">
                        <span class="current-price">$89.99</span>
                        <span class="original-price">$119.99</span>
                        </div>
                    </div>
                </div> -->
                <?php echo $data['best_seller_products']['sample_product'] ?>

                <!-- <div class="product-card" data-product-id="2" data-tab-group="hot_deals">
                    <div class="product-image min-img-card">
                        <img src="<?php echo IMGPATH ?>/IMG_0283.jpg" alt="Slim Fit Dark Jeans" onclick="quickView(2)">
                        <span class="product-badge">Popular</span>
                        <div class="product-actions">
                        <button class="action-btn wishlist-action" onclick="toggleWishlist(2)">
                            <i class="fas fa-heart"></i>
                        </button>
                        <button class="action-btn quick-view" onclick="quickView(1)">
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                        </div>
                    </div>
                    <div class="product-info" onclick="quickView(2)">
                        <h3 class="product-title">Slim Fit Dark Jeans</h3>
                        <div class="product-price">
                        <span class="current-price">$79.99</span>
                        <span class="original-price">$99.99</span>
                        </div>
                    </div>
                </div> -->

                <?php echo $data['most_view_products']['sample_product'] ?>

                <!-- <div class="product-card" data-product-id="2" data-tab-group="featured_product">
                    <a href=''>
                    <div class="product-image min-img-card">
                        <img src="<?php echo IMGPATH ?>/IMG_0283.jpg" alt="Slim Fit Dark Jeans" onclick="quickView(2)">
                        <span class="product-badge">Popular</span>
                        <div class="product-actions">
                        <button class="action-btn wishlist-action" onclick="toggleWishlist(2)">
                            <i class="fas fa-heart"></i>
                        </button>
                        <button class="action-btn quick-view" onclick="quickView(1)">
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                        </div>
                    </div>
                    <div class="product-info" onclick="quickView(2)">
                        <h3 class="product-title">Slim Fit Dark Jeans</h3>
                        <div class="product-price">
                        <span class="current-price">$79.99</span>
                        <span class="original-price">$99.99</span>
                        </div>
                    </div>
                    </a>
                </div> -->
                <?php echo $data['feature_products']['sample_product'] ?>
            </div>
            <!-- <div>
               

            </div> -->
        </div>
    </section>

    <!-- Hero Banner -->
    <!-- <section class="hot-sale-section" style="background-image: url('<?php echo IMGPATH ?>Pasted_image.png');">
        <h1>New Season Arrivals</h1>
        <p class="banner-message">Upgrade your wardrobe with fresh styles handpicked for comfort and confidence.</p>
        <button class="cta-btn" onclick="location.href='<?php echo BASEPATH ?>product'">Shop Now</button>
    </section> -->
    <?php echo $data['offer_banner'] ?>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <h2 class="section-title">Happy Customers</h2>
            <div class="testimonials-slider">
                <div class="testimonial-track">

                    <?php echo $data['testimonials'] ?>

                </div>
            </div>
        </div>
    </section>

<?php require_once 'includes/bottom.php'; ?>

