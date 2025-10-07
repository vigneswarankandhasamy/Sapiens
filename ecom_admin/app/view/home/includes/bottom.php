    <!-- footer @s -->
            <div class="nk-footer nk-footer-fluid bg-lighter">
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
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <!-- JavaScript -->
    <script src="<?php echo JSPATH ?>bundle.js?ver=2.2.0"></script>
    <script src="<?php echo JSPATH ?>scripts.js?ver=2.2.0"></script>
    <script src="<?php echo JSPATH ?>charts/gd-default.js?ver=2.2.0"></script>
    <script src="<?php echo JSPATH ?>charts/gd-analytics.js?ver=2.2.0"></script>
    <script type="text/javascript" src="<?php echo JSPATH ?>jquery.star-rating-svg.js"></script>
    <script src="<?php echo JSPATH ?>admin.js"></script>


    <?php if ($data['scripts']=='addpayment' || $data['scripts']=='adduser' || $data['scripts']=='addblog' || $data['scripts']=='editblog' || $data['scripts']=='addtestimonials' || $data['scripts']=='edittestimonials' || $data['scripts']=='updateanalytics' || $data['scripts']=='addproduct'): ?>
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

    <?php if ($data['scripts']=='addproduct'): ?>
        <script src="<?php echo JSPATH ?>image-uploader/image-uploader.js"></script>
        <script src="<?php echo JSPATH ?>tags/bootstrap-tagsinput.min.js"></script>
        <script type="text/javascript">
            $(".tags_input").tagsinput('items');
        </script>
        <!-- Auto Complete -->
        <script type="text/javascript" src="<?php echo JSPATH ?>autocomplete/init.js"></script>

    <?php endif ?>

     <script type="text/javascript">
       $(document).on('keyup keypress', 'form input[type="text"]', function(e) {
          if(e.keyCode == 13) {
            e.preventDefault();
            return false;
          }
        });
     </script>

     <script type="text/javascript">
         function notificationTd(order_id) {
            window.location = core_path + "orders/notificationdetail/" + order_id;
        }

        $("body").on("click", ".close_modal", function(){
            var modal_id = $(this).data("modal_id");
            $('#'+modal_id).modal('hide');
        });
     </script>

     <script type="text/javascript">
         $(".td_click").on("click",function() {
                var data_id = $(this).data("data_id");
                var data_link = $(this).data("data_link");
                window.location = core_path+data_link+"/"+data_id;
         });
     </script>



</body>

</html>