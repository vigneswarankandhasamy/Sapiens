<?php require_once 'includes/top.php'; ?>
<div class="hire-banner otherpage-banner m-0">
     <img src="<?php echo $data['page_banner']!="" ? SRCIMG.$data['page_banner']['file_name'] : IMGPATH."hire-banner-4.jpg" ?>" alt="image" class="common-banner">
    <div class="other-banner-title">
        <p>Expert</p>
        <!-- <button type="button" class="btn btn-sm banner-btn rounded-pill"><a href="<?php echo BASEPATH ?>hire">View more</a></button> -->
    </div>     
</div>
<div class="breadcrumbs_area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb_content">
                    <ul>
                        <li><a href="<?php echo BASEPATH ?>">home</a></li>
                        <li><a href="<?php echo BASEPATH ?>hire">Expert</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!--shop  area start-->
    <div class="shop_area shop_fullwidth contractors">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!--shop wrapper start-->
                    <div class="shop_title mb-3 w-100" >
                        <div class=""><h3 ><?php echo ($data['profile_type'])  ?></h3></div>
                    </div>
                    <div class="row shop_wrapper g-4 w-100">
                        <?php echo $data['contractors']['layout'] ?>
                    </div>
                    <?php if ($data['count']!=0 && $data['count']!=1 ) {?>
                        <div class="shop_toolbar t_bottom">
                            <div class="pagination">
                                <ul>
                                   <li><a href="<?php echo $data['previous'] ?>"><<</a></li>
                                        <?php echo $data['page'] ?>
                                    <li><a href="<?php echo $data['next'] ?>">>></a></li>
                                </ul>
                            </div>
                        </div>
                    <?php } ?>
                    <!--shop wrapper end-->
                </div>
            </div>
        </div>
    </div>
<!--shop  area end-->

<?php require_once 'includes/bottom.php'; ?>


<script type="text/javascript">

     // Classified Tab 

     $(".classified_tab").click(function() {
       
        $(".classified_tab").removeClass("active");
        $(this).addClass("active");
        var classified_content = $(this).data("classified");
        
        $('.classified_content').hide();
        $('.'+classified_content).show();

        if(classified_content=="all") {
            $('.classified_content').show();
        }

     });
    
     //Contractor Search

     $("#contractorSearch").validate({
        rules: {
            c_search_key: {
                required: true
            },
        },
        messages: {
            c_search_key: {
                required: "",
            },
        },
        submitHandler: function(form) {
            var value = $("#c_search_key").val();
            var profile_type = $("#profileType").val();
            var str = $.trim(value);
            var string = str.toLowerCase().replace(/\s/g, "-"); 
            window.location = base_path +"hire/search/" + string;
        }
    });

</script>
