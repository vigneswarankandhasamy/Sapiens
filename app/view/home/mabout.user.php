<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Sapiens - Buy Building and Construction Materials</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo IMGPATH ?>favicon.png">

     <!-- CSS 
    ========================= -->
     <!--bootstrap min css-->
    <link rel="stylesheet" href="<?php echo CSSPATH ?>bootstrap.min.css">
    <!--owl carousel min css-->
    <link rel="stylesheet" href="<?php echo CSSPATH ?>owl.carousel.min.css">
    <!--slick min css-->
    <link rel="stylesheet" href="<?php echo CSSPATH ?>slick.css">
    <!--magnific popup min css-->
    <link rel="stylesheet" href="<?php echo CSSPATH ?>magnific-popup.css">
    <!--font awesome css-->
    <link rel="stylesheet" href="<?php echo CSSPATH ?>font.awesome.css">
    <!--ionicons min css-->
    <link rel="stylesheet" href="<?php echo CSSPATH ?>ionicons.min.css">
    <!--animate css-->
    <link rel="stylesheet" href="<?php echo CSSPATH ?>animate.css">
    <!--jquery ui min css-->
    <link rel="stylesheet" href="<?php echo CSSPATH ?>jquery-ui.min.css">
    <!--slinky menu css-->
    <link rel="stylesheet" href="<?php echo CSSPATH ?>slinky.menu.css">
    <!-- Plugins CSS -->
    <link rel="stylesheet" href="<?php echo CSSPATH ?>plugins.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.css">
    
    <!-- Main Style CSS -->
    <link rel="stylesheet" href="<?php echo CSSPATH ?>style.css">
    <link rel="stylesheet" href="<?php echo CSSPATH ?>custom.css">
    <link rel="stylesheet" href="<?php echo PLUGINS ?>sweetalert/sweetalert.css">
    <link rel="stylesheet" href="<?php echo PLUGINS ?>noty/noty.css">
    
    
    <!--modernizr min js here-->
    <script type="text/javascript">
        var base_path = "<?php echo BASEPATH ?>";
    </script>

</head>
<body>
     
    <div class="page-loader"></div>
    <div class="page_loading">
        <div class="loading_image">
            <p class="load_img"><img  src="<?php echo IMGPATH ?>loader.gif" width=""></p>
            <p class="load_text">Loading Please Wait...</p>
        </div>
    </div>

    <!--about section area -->
    
    <div class="about_section mt-4">
        <div class="container-fluid">   
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="about_thumb">
                        <img src="<?php echo IMGPATH ?>about-img.jpg" alt="">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="about_content mt-4">
                        <h1>Welcome to Sapiens</h1>
                        <p>Whenever you think of construction, think Sapiens, for we have all your construction needs under one platform. Sapiens India Private Limited based at Coimbatore, the Manchester of India, is an online platform that serves to quench the need for construction material. The main motive of the company would be to ensure transparency between the vendors and the service providers and to cut out middlemen. It acts as a medium of communication in the construction material niche connecting buyers with sellers and vice versa. The services catered by the platform are not just confined materialistically but also caters Architects, Designers, Contractors and Engineers based on the customers requirement. Though based out of the southern city – Coimbatore, Sapiens promises to deliver diligent service around the country with easy transportation and seamless connectivity along with guaranteed returns.</p>
                        <a class="button" href="<?php echo BASEPATH ?>contact">Explore Now</a>
                    </div>                
                </div>    
            </div>
        </div>    
    </div>

    <!--chose us area start-->
    <div class="choseus_area mt40">
        <div class="container-fluid">
            <div class="row">
               <div class="col-12">
                   <div class="chose_title">
                       <h1 class="mb-4">Why Choose Us?</h1>
                   </div>
               </div>
                <div class="col-lg-4 col-md-12">
                    <div class="single_chose">
                        <div class="chose_icone">
                            <img src="<?php echo IMGPATH ?>tubes.png" alt="">
                        </div>
                        <div class="about_gallery_content mt-3">
                            <h3>lorem ipsum</h3>
                            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nostrum earum culpa porro, laudantium quibusdam illo aspernatur ipsum rem impedit omnis sit excepturi enim consectetur ipsa soluta quae recusandae quas. Obcaecati!</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="single_chose">
                        <div class="chose_icone">
                            <img src="<?php echo IMGPATH ?>brick.png" alt="">
                        </div>
                        <div class="about_gallery_content mt-3">
                            <h3>lorem ipsum</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolorem neque excepturi dolore asperiores omnis adipisci quasi rem quisquam voluptates? Necessitatibus culpa impedit qui nobis quidem explicabo, commodi quam voluptatibus fugit!</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="single_chose">
                        <div class="chose_icone">
                            <img src="<?php echo IMGPATH ?>paint-roller.png" alt="">
                        </div>
                        <div class="about_gallery_content mt-3">
                            <h3>lorem ipsum</h3>
                            <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quae molestias eaque obcaecati veritatis magnam. Ad accusamus dolore laborum impedit earum cumque doloremque, soluta eius reprehenderit quibusdam itaque ut dolor voluptatibus!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--chose us area end-->
    <!--testimonials section start-->
    <div class="testimonial_are">
        <div class="bg-light pt-5 pb-5">
            <div class="row m-0">
                <div class="col-12">
                    <div class="testimonial_titile">
                        <h1>What Our Custumers Say ?</h1>
                    </div>
                </div>
                <div class="testimonial_active owl-carousel">
                     <?php echo $data['testimonials'] ?>
                </div>
            </div>
        </div>
    </div>
    <!--testimonials section end-->


<!-- JS
============================================ -->
<!--jquery min js-->
<script src="<?php echo JSPATH ?>vendor/jquery-3.4.1.min.js"></script>
 <script src="<?php echo JSPATH ?>vendor/modernizr-3.7.1.min.js"></script>
<!--popper min js-->
<script src="<?php echo JSPATH ?>popper.js"></script>
<!--bootstrap min js-->
<script src="<?php echo JSPATH ?>bootstrap.min.js"></script>
<!--owl carousel min js-->
<script src="<?php echo JSPATH ?>owl.carousel.min.js"></script>
<!--slick min js-->
<script src="<?php echo JSPATH ?>slick.min.js"></script>
<!--magnific popup min js-->
<script src="<?php echo JSPATH ?>jquery.magnific-popup.min.js"></script>
<!--jquery countdown min js-->
<script src="<?php echo JSPATH ?>jquery.countdown.js"></script>
<!--jquery ui min js-->
<script src="<?php echo JSPATH ?>jquery.ui.js"></script>
<!--jquery elevatezoom min js-->
<script src="<?php echo JSPATH ?>jquery.elevatezoom.js"></script>
<!--isotope packaged min js-->
<script src="<?php echo JSPATH ?>isotope.pkgd.min.js"></script>
<!--slinky menu js-->
<script src="<?php echo JSPATH ?>slinky.menu.js"></script>
<!-- Plugins JS -->
<script src="<?php echo JSPATH ?>plugins.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.js"></script>

<!-- Main JS -->
<script src="<?php echo JSPATH ?>main.js"></script>
<script src="<?php echo JSPATH ?>validate.min.js"></script>
<script src="<?php echo PLUGINS ?>sweetalert/sweetalert.min.js"></script>
<script src="<?php echo PLUGINS ?>noty/noty.min.js"></script>
<script type="text/javascript" src="<?php echo PLUGINS ?>autocomplete/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo JSPATH ?>init.js"></script>
<script type="text/javascript" src="<?php echo JSPATH ?>jquery.star-rating-svg.js"></script>
<script type="text/javascript" src="<?php echo PLUGINS ?>jquery-search/jquery.hideseek.min.js"></script>
</body>
</html>


