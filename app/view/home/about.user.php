<?php require_once 'includes/top.php'; ?>
    <!-- Backdrop (ensure only one exists on page) -->
    <div class="nav-backdrop" id="navBackdrop"></div>
    <!-- <div class="nav-backdrop"></div> -->


    <!-- Hero Section -->
    <section class="about-hero">
        <div class="container">
            <h1>About MenStyle</h1>
            <p>We're passionate about helping men express their unique style through premium quality clothing that combines comfort, sophistication, and contemporary design.</p>
        </div>
    </section>

    <!-- About Content -->
    <section class="ms-about-wrapper">
      <div class="ms-about-container">
        <div class="ms-about-row">
          <!-- Text Content -->
          <div class="ms-about-text">
            <h2 class="ms-about-title">Our Story</h2>
            <p class="ms-about-para">
              Founded in 2020, MenStyle began as a vision to revolutionize men's fashion by creating clothing that
              seamlessly blends style, comfort, and quality. What started as a small boutique has grown into a trusted brand
              serving thousands of customers worldwide.
            </p>
            <p class="ms-about-para">
              Our journey is rooted in the belief that every man deserves to feel confident and comfortable in what he
              wears. We carefully curate each piece in our collection, ensuring that it meets our high standards for
              craftsmanship, design, and durability.
            </p>
            <p class="ms-about-para">
              Today, we continue to push boundaries in men's fashion, constantly innovating and expanding our offerings to
              meet the evolving needs of the modern man.
            </p>
          </div>

          <!-- Image -->
          <div class="ms-about-image">
            <img src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?fit=crop&w=900&q=80"
              alt="Our Story - MenStyle" />
          </div>
        </div>
      </div>
    </section>


    <!-- Values Section -->
    <section class="values-section">
        <div class="container">
            <h2 class="section-title">Our Values</h2>
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-gem"></i>
                    </div>
                    <h3>Quality First</h3>
                    <p>We source only the finest materials and work with skilled craftsmen to ensure every piece meets our exacting standards for quality and durability.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-palette"></i>
                    </div>
                    <h3>Timeless Style</h3>
                    <p>Our designs blend contemporary trends with classic elements, creating pieces that remain stylish and relevant season after season.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Customer Focus</h3>
                    <p>Your satisfaction is our priority. We're committed to providing exceptional service and creating clothing that exceeds your expectations.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h3>Sustainability</h3>
                    <p>We're committed to responsible fashion practices, using eco-friendly materials and ethical manufacturing processes wherever possible.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <h3>50K+</h3>
                    <p>Happy Customers</p>
                </div>
                <div class="stat-item">
                    <h3>1000+</h3>
                    <p>Products Sold</p>
                </div>
                <div class="stat-item">
                    <h3>25+</h3>
                    <p>Countries Served</p>
                </div>
                <div class="stat-item">
                    <h3>4.8★</h3>
                    <p>Average Rating</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2>Ready to Elevate Your Style?</h2>
            <p>Join thousands of satisfied customers who trust MenStyle for their fashion needs. Discover our latest collection and find your perfect style today.</p>
            <div class="cta-buttons">
                <a href="<?php echo BASEPATH ?>product" class="cta-btn">Shop Now</a>
                <a href="<?php echo BASEPATH ?>contact" class="cta-btn secondary">Get in Touch</a>
            </div>
        </div>
    </section>
<?php require_once 'includes/bottom.php'; ?>