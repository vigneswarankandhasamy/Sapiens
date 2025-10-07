    <!-- footer @s -->
            <div class="nk-footer nk-footer-fluid bg-lighter non-printable">
                <div class="container">
                    <div class="nk-footer-wrap">
                        <div class="nk-footer-copyright"> &copy; <?php echo date("Y")." ".COMPANY_NAME ?>. All rights reserved.
                        </div>
                        <div class="nk-footer-links">
                            <ul class="nav nav-sm">
                                <li class="nav-item"><a class="nav-link" href="#">Terms</a></li>
                                <li class="nav-item"><a class="nav-link" href="#">Privacy</a></li>
                                <li class="nav-item"><a class="nav-link" href="#">Help</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- footer @e -->
        </div>
        <!-- wrap @e -->
    </div>
    <!-- app-root @e -->
    <!-- JavaScript -->
    <script src="<?php echo JSPATH ?>bundle.js?ver=2.2.0"></script>
    <script src="<?php echo JSPATH ?>scripts.js?ver=2.2.0"></script>
    <script src="<?php echo JSPATH ?>charts/gd-default.js?ver=2.2.0"></script>
    <script src="<?php echo JSPATH ?>charts/gd-analytics.js?ver=2.2.0"></script>
    <script type="text/javascript" src="<?php echo JSPATH ?>jquery.star-rating-svg.js"></script>
    <script src="<?php echo JSPATH ?>admin.js"></script>


    <?php if ($data['scripts']=='addmigrations' || $data['scripts']=='adduser' || $data['scripts']=='product_request' || $data['scripts']=='addblog' || $data['scripts']=='editblog' || $data['scripts']=='addtestimonials' || $data['scripts']=='edittestimonials'): ?>
        <link rel="stylesheet" href="<?php echo CSSPATH ?>editors/summernote.css?ver=2.2.0">
        <script src="<?php echo JSPATH ?>libs/editors/summernote.js?ver=2.2.0"></script>
        <script type="text/javascript">
            var _basic = '.summernote-editor';
        if ($(_basic).exists()) {
            $(_basic).each(function(){
                $(this).summernote({
                    placeholder: '',
                    tabsize: 2,
                    height: 250,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'strikethrough', 'clear']],
                        ['font', ['superscript','subscript']],
                        ['fontsize', ['fontsize', 'height']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['view', ['fullscreen', 'codeview', 'help']],

                    ]
                });
            });
        }

        </script>
    <?php endif ?>

     <script type="text/javascript">
         function notificationTd(order_id) {
            window.location = core_path + "orders/notificationdetail/" + order_id;
        }
     </script>

     <script>
        $(document).ready(function (){

            //Update Vendor Active Status On Change Event

            $('#vendor_active_switch').on('change', function () {
              VendorActiveCheck();   
            });

            function VendorActiveCheck() {
                var check  = $('#vendor_active_switch').is(':checked');
                if(check){
                    var value = 1;
                }else{
                    var value = 0;
                }
                $.ajax({
                    type: "POST",
                    url: core_path + "home/api/UpdateVendorStatus",
                    dataType: "html",
                    data: { result: value },
                    beforeSend: function() {
                        $(".page_loading").show();
                    },
                    success: function(data) {
                        $(".page_loading").hide();
                        if(data == 1) {
                            $('#vendor_active_switch').prop("checked", true);
                            $('#vendor_active_switch_text').text('Active');
                        }else{
                            $('#vendor_active_switch').prop("checked", false);
                            $('#vendor_active_switch_text').text('Inactive');
                        }
                    }
                });
                return false; 
            }

        });
        
     </script>

     

</body>

</html>