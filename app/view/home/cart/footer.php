<footer class="footer border-top">
<div class="fixed-bottom footer_bottom">
    <div class="row m-0">
        <div class="col-lg-6 col-md-6">
            <div class="copyright_area">
                <p class="text-white"> &copy; <?php echo date("Y") ?> <a href="<?php echo BASEPATH ?>"><?php echo $data['siteinfo']['contact']['company_name'] ?></a> All Right Reserved. Developed by <a target="_blank" href="https://venpep.com/">VenPep</a>.</p>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="footer_payment text-end">
                <a href="#"><img src="<?php echo IMGPATH ?>img/icon/payment.png" alt=""></a>
            </div>
        </div>
    </div>
  </div>
</footer>
<script src="<?php echo JSPATH ?>cart/bootstrap.min.js"></script>
<script src="<?php echo JSPATH ?>cart/all.min.js"></script>

<script src="<?php echo JSPATH ?>vendor/modernizr-3.7.1.min.js"></script>
    <!-- jQuery JS -->
    <script src="<?php echo JSPATH ?>vendor/jquery-3.4.1.min.js"></script>
    <!-- Popper JS -->
    <script src="<?php echo JSPATH ?>vendor/popper.min.js"></script>
    <!-- Bootstrap JS -->
    <!-- <script src="<?php echo JSPATH ?>vendor/bootstrap.min.js"></script> -->
     <script src="<?php echo PLUGINS ?>sweetalert/sweetalert.min.js"></script>
    <script src="<?php echo PLUGINS ?>noty/noty.min.js"></script>
    <script src="<?php echo JSPATH ?>validate.min.js"></script>
    <script type="text/javascript" src="<?php echo PLUGINS ?>autocomplete/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?php echo JSPATH ?>init.js"></script>
    <script type="text/javascript" src="<?php echo PLUGINS ?>jquery-search/jquery.hideseek.min.js"></script>
    <script>
    jQuery('#location_search').hideseek({
        navigation: true,
        highlight: false
    });
</script>
</body>
</html>
