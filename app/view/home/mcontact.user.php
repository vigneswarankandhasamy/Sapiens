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

    <!--contact map start-->
    <div class="contact_map mt-30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="mapouter"><div class="gmap_canvas"><iframe class="gmap_iframe" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=397&amp;height=350&amp;hl=en&amp;q=Vasantha mills, Singanallur, Coimbatore- 641005&amp;t=&amp;z=12&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe><a href="https://www.fridaynightfunkin.net/">Friday Night Funkin</a></div><style>.mapouter{position:relative;text-align:right;width:397px;height:350px;}.gmap_canvas {overflow:hidden;background:none!important;width:397px;height:350px;}.gmap_iframe {width:397px!important;height:350px!important;}</style></div>
                  <!--   <div class="map-area">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3916.4966034388112!2d77.06343691472314!3d11.001318258010679!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ba856e67c215f15%3A0x3522408aef7de9db!2sMeenakshi%20Amman%20Nagar%2C%20Kamachipuram%2C%20Coimbatore%2C%20Tamil%20Nadu%20641016!5e0!3m2!1sen!2sin!4v1608114743544!5m2!1sen!2sin" width="100%" height="350" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
    <!--contact map end-->    
        
    <!--contact area start-->
    <div class="contact_area mcontactuser">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="contact_message content">
                        <h3>contact us</h3>
                        <!-- <p>Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum. Mirum est notare quam littera gothica, quam nunc putamus parum claram anteposuerit litterarum formas human. qui sequitur mutationem consuetudium lectorum. Mirum est notare quam</p> -->
                        <i class="fa fa-home"></i>
                        <?php echo $data['siteinfo']['contact']['address_one'] ?><br>
                        <i class="fa fa-phone"></i> 
                        <a href="tel:<?php echo $data['siteinfo']['contact']['contact_no_one'] ?>">
                             <?php echo $data['siteinfo']['contact']['contact_no_one'] ?>
                        </a> 
                        <?php if($data['siteinfo']['contact']['contact_no_two']!="") { ?>, 
                            <a href="tel:+91<?php echo $data['siteinfo']['contact']['contact_no_two'] ?>">
                                +91 <?php echo $data['siteinfo']['contact']['contact_no_two'] ?>
                            </a>
                        <?php } ?> <br>
                        <i class="fa fa-envelope"></i>
                        <a href="mailto:<?php echo $data['siteinfo']['contact']['email_one'] ?>"> 
                            <?php echo $data['siteinfo']['contact']['email_one'] ?>
                        </a> <br>
                    </div>
                    <div class="contact_address_socail">
                        <!-- <ul>
                            <li><a><i class="fab fa-facebook-f"></i></a></li>
                            <li><a><i class="fab fa-twitter"></i></a></li>
                            <li><a><i class="fa fa-google-plus"></i></a></li>
                            <li><a><i class="fa fa-youtube-play"></i></a></li>
                        </ul> -->
                         <ul>
                                <?php if($data['siteinfo']['contact']['facebook']!='') { ?>
                                <li><a href="<?php echo $data['siteinfo']['contact']['facebook'] ?>" target="_blank"><i class="ion-social-facebook"></i></a></li>
                                <?php } ?>
                                <?php if($data['siteinfo']['contact']['twitter']!='') { ?>
                                <li><a href="<?php echo $data['siteinfo']['contact']['twitter'] ?>" target="_blank"><i class="ion-social-twitter"></i></a></li>
                                <?php } ?>
                                <?php if($data['siteinfo']['contact']['googleplus']!='') { ?>
                                <li><a href="<?php echo $data['siteinfo']['contact']['googleplus'] ?>" target="_blank"><i class="ion-social-googleplus"></i></a></li>
                                <?php } ?>
                                <?php if($data['siteinfo']['contact']['youtube']!='') { ?>
                                <li><a href="<?php echo $data['siteinfo']['contact']['youtube'] ?>" target="_blank"><i class="ion-social-youtube"></i></a></li>
                                <?php } ?>
                                <?php if($data['siteinfo']['contact']['linkedin']!='') { ?>
                                <li><a href="<?php echo $data['siteinfo']['contact']['linkedin'] ?>" target="_blank"><i class="ion-social-linkedin"></i></a></li>
                                <?php } ?>
                                <?php if($data['siteinfo']['contact']['instagram']!='') { ?>
                                <li><a href="<?php echo $data['siteinfo']['contact']['instagram'] ?>" target="_blank"><i class="ion-social-instagram"></i></a></li>
                                <?php } ?>
                                <?php if($data['siteinfo']['contact']['pinterest']!='') { ?>
                                <li><a href="<?php echo $data['siteinfo']['contact']['pinterest'] ?>" target="_blank"><i class="ion-social-pinterest-outline"></i></a></li>
                                <?php } ?>
                                <?php if($data['siteinfo']['contact']['rss']!='') { ?>
                                 <li><a href="<?php echo $data['siteinfo']['contact']['rss'] ?>" target="_blank"><i class="ion-social-rss-outline"></i></a></li>
                                <?php } ?>
                            </ul>
                    </div> 
                </div>
            </div>
        </div>
    </div>
    <!--contact area end-->

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



          


