 <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <!-- <h3>MenStyle</h3> -->
                    <a class="m-auto d-block text-center" href="<?php echo BASEPATH ?>"><img src="<?php echo SRCIMG.$data['siteinfo']['contact']['footer_logo'] ?>" alt="" style="width: 100px; height: 100px;"></a>
                    <p>Your destination for premium men's fashion. Quality, style, and comfort in every piece.</p>
                    <div class="social-links">
                        
                        <!-- <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a> -->


                        <?php if($data['siteinfo']['contact']['facebook']!='') { ?>
                        <a href="<?php echo $data['siteinfo']['contact']['facebook'] ?>" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <?php } ?>
                        <?php if($data['siteinfo']['contact']['twitter']!='') { ?>
                        <a href="<?php echo $data['siteinfo']['contact']['twitter'] ?>" target="_blank"><i class="fab fa-twitter"></i></a>
                        <?php } ?>
                        <?php if($data['siteinfo']['contact']['googleplus']!='') { ?>
                        <a href="<?php echo $data['siteinfo']['contact']['googleplus'] ?>" target="_blank"><i class="ion-social-googleplus"></i></a>
                        <?php } ?>
                        <?php if($data['siteinfo']['contact']['youtube']!='') { ?>
                        <a href="<?php echo $data['siteinfo']['contact']['youtube'] ?>" target="_blank"><i class="ion-social-youtube"></i></a>
                        <?php } ?>
                        <?php if($data['siteinfo']['contact']['linkedin']!='') { ?>
                        <a href="<?php echo $data['siteinfo']['contact']['linkedin'] ?>" target="_blank"><i class="ion-social-linkedin"></i></a>
                        <?php } ?>
                        <?php if($data['siteinfo']['contact']['instagram']!='') { ?>
                        <a href="<?php echo $data['siteinfo']['contact']['instagram'] ?>" target="_blank"><i class="fab fa-instagram"></i></a>
                        <?php } ?>
                        <?php if($data['siteinfo']['contact']['pinterest']!='') { ?>
                        <a href="<?php echo $data['siteinfo']['contact']['pinterest'] ?>" target="_blank"><i class="ion-social-pinterest-outline"></i></a>
                        <?php } ?>
                        <?php if($data['siteinfo']['contact']['rss']!='') { ?>
                        <a href="<?php echo $data['siteinfo']['contact']['rss'] ?>" target="_blank"><i class="ion-social-rss-outline"></i></a>
                        <?php } ?>


                    </div>
                </div>
                
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="<?php echo BASEPATH ?>">Home</a></li>
                        <li><a href="<?php echo BASEPATH ?>product">Shop</a></li>
                        <li><a href="<?php echo BASEPATH ?>aboutus">About Us</a></li>
                        <li><a href="<?php echo BASEPATH ?>contact">Contact</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Extras</h4>
                    <ul>
                        <?php echo $data['legal_pages']; ?>
                        <!-- <li><a href="<?php echo BASEPATH ?>product">Shirts</a></li>
                        <li><a href="<?php echo BASEPATH ?>product">Pants</a></li>
                        <li><a href="<?php echo BASEPATH ?>product">Jackets</a></li>
                        <li><a href="<?php echo BASEPATH ?>product">Accessories</a></li> -->
                    </ul>
                </div>
                
                <!-- <div class="footer-section">
                    <h4>Customer Service</h4>
                    <ul>
                        <li><a href="#">Size Guide</a></li>
                        <li><a href="#">Shipping Info</a></li>
                        <li><a href="#">Returns</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div> -->
                
                <div class="footer-section">
                    <h4>Newsletter</h4>
                    <p>Subscribe to get updates on new arrivals and exclusive offers.</p>
                    <div class="newsletter-form">
                        <form id="subscriberInfo" class="form footer-newsletter" method="POST" action="#" enctype="multipart/form-data">
                            <!-- <input type="text" placeholder="Your email address" autocomplete="off"> -->
                            <input  type="text" name="email" id="email" autocomplete="off" placeholder="Enter you email address here..." />
                            <button type="submit">Subscribe</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <!-- <p>&copy; 2024 MenStyle. All rights reserved.</p> -->
                <p class="text-white"> &copy; <?php echo date("Y") ?> <a href="<?php echo BASEPATH ?>"><?php echo $data['siteinfo']['contact']['company_name'] ?></a> All Right Reserved. Developed by <a target="_blank" href="https://demo.com/">Demo</a>.</p>
            </div>
        </div>
    </footer>
    <script src="<?php echo JSPATH ?>jquery-3.4.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="<?php echo JSPATH ?>jquery.star-rating-svg.js"></script>
    <script src="<?php echo JSPATH ?>validate.min.js"></script>
    <script src="<?php echo PLUGINS ?>noty/noty.min.js"></script>
    <script src="<?php echo JSPATH ?>script.js"></script>
    <script src="<?php echo JSPATH ?>custom-script.js"></script>   
    <script>

        document.addEventListener('DOMContentLoaded', function() {
            initializeAuth();
            animateStats();
        });

        function initializeAuth() {
            const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
            const loginLink = document.querySelector('.login-link');
            const accountLink = document.querySelector('.account-link');
            const logoutLink = document.querySelector('.logout-link');

            if (isLoggedIn) {
                if (loginLink) loginLink.style.display = 'none';
                if (accountLink) accountLink.style.display = 'block';
                if (logoutLink) logoutLink.style.display = 'block';
            }
        }

        function animateStats() {
            const statItems = document.querySelectorAll('.stat-item h3');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const target = entry.target;
                        const finalValue = target.textContent;
                        const numericValue = parseInt(finalValue.replace(/[^\d]/g, ''));
                        
                        if (numericValue) {
                            animateNumber(target, 0, numericValue, finalValue);
                        }
                        
                        observer.unobserve(target);
                    }
                });
            });

            statItems.forEach(item => observer.observe(item));
        }

        function animateNumber(element, start, end, finalText) {
            const duration = 2000;
            const increment = end / (duration / 16);
            let current = start;

            const timer = setInterval(() => {
                current += increment;
                if (current >= end) {
                    element.textContent = finalText;
                    clearInterval(timer);
                } else {
                    const suffix = finalText.replace(/[\d,]/g, '');
                    element.textContent = Math.floor(current).toLocaleString() + suffix;
                }
            }, 16);
        }

    </script>
    <script> 
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
    <script>
    function quickView(productId) {
        window.location.href = `<?php echo BASEPATH ?>product/details?id=${productId}`;
    }

    $("#subscriberInfo").validate({
        rules: {
            email: {
                required: true,
                email: true
            }
        },
        messages: {

            email: {
                required: "Email ID cannot be empty",
                email:"Please enter a valid Email ID"
            }
        },
       submitHandler: function (form) {
            var content = $(form).serialize();
            $.ajax({
                type: "POST",
                url: base_path + "contact/api/subscriberInfo",
                dataType: "html",
                data: content,
                beforeSend: function () {
                    $(".page_loading").show();
                },
                success: function (data) {
                    $(".page_loading").hide();
                     data  = data.split("`");
                    if (data[0] == 1) {
                        new Noty({
                        text: '<strong>Thank you for subscribing to our newsletter!</strong>',
                        type: 'success',
                        theme: 'relax',
                        layout: 'bottomCenter',
                        timeout: 3000,
                        callbacks: {
                            onClose: function () {
                                window.location = window.location.href + "?sb=success";
                            }
                        }
                        }).show();
                    }  else if (data[0] == 0) {
                        setTimeout(function() {
                            new Noty({
                                text: '<strong>'+ data[1] +'</strong>!',
                                type: 'warning',
                                theme: 'relax',
                                layout: 'bottomCenter',
                                timeout: 3000
                            }).show();
                        }, 300);
                    }
                }
            });
            return false;
        }
    });
    </script>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const tabButtons = document.querySelectorAll(".tab-btn");
        const products = document.querySelectorAll(".product-card");

        function showTab(tabName) {
            tabButtons.forEach(btn => {
            btn.classList.toggle("active", btn.dataset.tab === tabName);
            });

            products.forEach(product => {
            const groups = product.dataset.tabGroup.split(" ");
            if (groups.includes(tabName)) {
                product.classList.add("active");
            } else {
                product.classList.remove("active");
            }
            });
        }

        tabButtons.forEach(btn => {
            btn.addEventListener("click", () => {
            showTab(btn.dataset.tab);
            });
        });

        showTab("featured_product");
    });

    function toggleMobileFilters() {
        const sidebar = document.getElementById('filtersSidebar');
        sidebar.classList.toggle('active');
    }

    // Price Slider Initialization
    $(document).ready(function() {
        // Get min and max prices from PHP data
        var minPrice = <?php echo isset($data['min_price']) ? $data['min_price'] : 0; ?>;
        var maxPrice = <?php echo isset($data['max_price']) ? $data['max_price'] : 10000; ?>;
        
        // Check if there's already a price filter in the URL
        var urlParams = new URLSearchParams(window.location.search);
        var currentPrice = urlParams.get('price');
        var initialMin = minPrice;
        var initialMax = maxPrice;
        
        if (currentPrice) {
            var priceRange = currentPrice.split('-');
            initialMin = parseInt(priceRange[0]);
            initialMax = parseInt(priceRange[1]);
        }
        
        // Initialize the price slider
        $(".slider-range").slider({
            range: true,
            min: minPrice,
            max: maxPrice,
            values: [initialMin, initialMax],
            slide: function(event, ui) {
                $(".amount").val("₹" + ui.values[0] + " - ₹" + ui.values[1]);
            }
        });
        
        // Set initial display value
        $(".amount").val("₹" + $(".slider-range").slider("values", 0) + " - ₹" + $(".slider-range").slider("values", 1));
        
        // Handle filter button click
        $("#filter_Price").click(function(e) {
            e.preventDefault();
            var values = $(".slider-range").slider("values");
            var priceRange = values[0] + "-" + values[1];
            
            // Get current URL parameters
            var urlParams = new URLSearchParams(window.location.search);
            urlParams.set('price', priceRange);
            
            // Redirect to the filtered URL
            window.location.href = window.location.pathname + '?' + urlParams.toString();
        });
    });
    </script>

</body>
</html>
