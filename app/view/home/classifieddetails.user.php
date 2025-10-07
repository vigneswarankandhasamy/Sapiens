<?php require_once 'includes/top.php'; ?>
<style type="text/css">
    .classified-pt-dts-img img {
        width: auto;
        margin: 0px auto;
        height: 200px;
    }
</style>
<?php
if(1!=1){
?>
<!-- <div class="hire-banner otherpage-banner m-0">
     <img src="<?php echo $data['page_banner']!="" ? SRCIMG.$data['page_banner']['file_name'] : IMGPATH."hire-banner-3.jpg" ?>" alt="image" class="common-banner">
    <div class="other-banner-title">
        <p>Expert Details</p>
    </div>     
</div> -->
<?php } ?>
<div class="breadcrumbs_area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb_content">
                    <ul>
                        <li><a href="<?php echo BASEPATH ?>">Home</a></li>
                        <li><a href="<?php echo BASEPATH ?>hire/hirelist">Expert</a></li>
                        <li><a href="#">Expert Details</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!--shop  area start-->
     <div class="shop_area customer_detail">
        <div class="container-fluid">
            <div class="row">                
                <div class="col-lg-8 col-md-12">
                    <div class="row shop_wrapper g-4 grid_list">
                        <div class="col-12">
                            <div class="classified-pt-dts-img carousel" id="myCarousel">
                                <?php echo $data['projects_imgs'] ?>
                            </div>
                            <div class="classified-lists border-0 bg-white p-3 w-100 mb-3 clearfix">
                                <div class="classified-detailed-img">

                                    <img src="<?php echo SRCIMG ?><?php echo (($data['info']['file_name']!="")? $data['info']['file_name'] : "no_product.jpg" ) ?>" alt="" class="">
                                </div>
                                <div class="listing-classified-title-dt">
                                    <h3 class=""><?php echo ucfirst($data['info']['name']) ?></h3>
                                    <h3 class=""><?php echo $data['profile_types']; ?></h3>
                                    <?php if($data['info']['experience']!="") { ?>
                                    <span href="javascript:void()" class="">Experience : <?php echo $data['info']['experience']." ".$data['info']['experience_duration'] ?></span>
                                    <?php } ?>
                                    <span href="javascript:void()" class=""><?php echo $data['info']['city'] ?></span>
                                     <?php if($data['info']['profile_verified']==1) { ?>
                                        <span class='ion-android-checkmark-circle' style='color:green; font-size: 12px;'> Verified</span>
                                    <?php } ?>
                                </div>
                                <div class="btn-group float-end">
                                    <button type="button" class="btn text-black-50 btn-lg" data-bs-toggle="dropdown" aria-expanded="false"><span class="fa fa-share-alt"></span></button>
                                    <ul class="dropdown-menu dropdown-menu-end share_links">
                                        <li class="bg_fb"><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo BASEPATH."hire/details/".$data['info']['token'] ?>" class="share_icon"  rel="tooltip" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                                        <li class="bg_whatsapp"><a href="https://api.whatsapp.com/send?text=Hey, Checkout this site content here <?php echo BASEPATH ?> - We provide content here. <?php echo BASEPATH."hire/details/".$data['info']['token'] ?>" class="share_icon" rel="tooltip" title="Whatsapp"><i class="fab fa-whatsapp"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="classified-user-details">
                                <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                        <a class="nav-link active" aria-current="page" href="#classifiedabout">About Us</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#classifiedservice">Service</a>
                                    </li>
                                    <?php if($data['project_list']!="") { ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#classifiedproject">Projects</a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <div id="classifiedabout" class="mt-3 p-3">
                                <p class="fw-bold">Description</p>
                                <p><?php echo $data['info']['description'] ?></p>
                            </div>
                            <div id="classifiedservice" class="p-3">
                                <h4>Service</h4>  
                                <div class="row clear pt-3">
                                    <span class="tags_contractor">
                                        <?php echo $data['service_tags']; ?> 
                                    </span>
                                </div>                                
                            </div>
                            <?php if($data['project_list']!="") { ?>
                            <div id="classifiedproject" class="p-3">
                                <h4><?php echo $data['info']['total_projects'] ?> Projects</h4>
                                <div class="row">
                                    <?php echo $data['project_list'] ?>
                                </div>                                 
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <aside class="sidebar_widget">
                        <div class="widget_inner">
                            <div class="widget_list">
                                <div class="contact_message form">
                                    <h3>Enquiry Now </h3>
                                    <form id="contractorEnquiry" method="POST" action="https://demo.hasthemes.com/autima-preview/autima/assets/mail.php">	
                                    	<input type="hidden" name="session_token" value="<?php echo $data['token'] ?>" >
                                        <input type="hidden" name="profile_type" id="profileType" value="<?php echo $data['profile_type'] ?>">
                                    	<input type="hidden" name="page_token" id="pageToken" value="<?php echo $data['info']['token'] ?>" >
                                        <p>
                                            <label>Name <span class="text-danger">*</span></label>
                                            <input name="name" placeholder="Name" type="text">
                                        </p>
                                        <p>
                                            <label>Email <span class="text-danger">*</span></label>
                                            <input name="email" placeholder="Email" type="email">
                                        </p>
                                        <p>
                                            <label>Mobile Number <span class="text-danger">*</span></label>
                                            <input name="mobile" placeholder="Number" type="text">
                                        </p>
                                        <div class="contact_textarea">
                                            <label>Your Message <span class="text-danger">*</span></label>
                                            <textarea placeholder="Message" name="message" class="form-control2"></textarea>
                                        </div>
                                        <button type="submit"> Submit</button>
                                        <p class="form-messege"></p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </aside>                    
                    <aside class="sidebar_widget">  
                        <div class="widget_inner">
                            <div class="widget_list widget_categories">
                            <?php
                            if(isset($_GET['id'])){
                                if($_GET['id']==$data['token']) {
                            ?>  
                                    <input type="hidden" name="get_value" id="get_value" value="<?php echo $_GET['id']; ?>">
                                    <p><i class="fas fa-phone-alt me-2"></i><span><a href="javascript:void();">+91 - <?php echo $data['info']['mobile'];  ?></a></span></p>
                                    <p><i class="fas fa-envelope me-2"></i><span><a href="javascript:void();"><?php echo $data['info']['email'];  ?></a></span></p>
                                <?php }else{ ?>
                                    <p><i class="fas fa-phone-alt me-2"></i><span><a href="javascript:void();">+91 - XXXXXXXXXX</a></span></p>
                                    <p><i class="fas fa-envelope me-2"></i><span><a href="javascript:void();">XXXXXXXXXXXXX</a></span></p>
                                    <p><a href="javascript:void();" class="btn btn-primary btn-sm popup-modal-contact" data-bs-toggle="modal" data-bs-target="#view_contact">View Contact Details</a></p> 
                                <?php } ?>
                            <?php }else{ ?>
                                <p><i class="fas fa-phone-alt me-2"></i><span><a href="javascript:void();">+91 - XXXXXXXXXX</a></span></p>
                                <p><i class="fas fa-envelope me-2"></i><span><a href="javascript:void();">XXXXXXXXXXXXX</a></span></p>
                                <p><a href="javascript:void();" class="btn btn-primary btn-sm popup-modal-contact" data-bs-toggle="modal" data-bs-target="#view_contact">View Contact Details</a></p> 
                            <?php } ?>                               
                            </div>                          
                        </div>
                    </aside>                    
                </div>                 
            </div>
        </div>
    </div>
<!--shop  area end-->

<!--banner area start-->
<?php if($data['page_banner']!="") { ?>
    <section class="banner_area mb-50 d-none d-lg-block d-md-block">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="single_banner banner_fullwidth">
                        <div class="banner_thumb">
                                <?php echo $data['page_banner'] ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>
<!--banner area end-->

<!--banner area start-->
<?php if($data['page_banner']!="") { ?>
    <section class="banner_area mb-50 d-block d-lg-none d-md-none">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="single_banner banner_fullwidth">
                        <div class="banner_thumb">
                                <?php echo $data['page_banner'] ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>
<!--banner area end-->

<!--product area start-->
    <section class="product_area mb-50">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="section_title">
                        <h2><span> <strong>Best</strong>Sellers</span></h2>
                    </div>
                    <div class="product_carousel product_column5 owl-carousel">
                            <?php echo $data['best_seller_products'] ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<!--product area end-->


<!-- contact details view model -->

<div class="modal fade address_wrap_modal" id="view_contact" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ">Personal Details</h4>
            </div>
            <div class="address-form-error red"></div>
            <form id="viwed_details" method="POST" >
                <input type="hidden" name="session_token" value="<?php echo $data['token'] ?>" >
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group address_model" >
                                <label for="name">Name <span class="text-danger">*</span>
                                </label>
                                <br>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group address_model" >
                                <label for="mobile">Mobile <span class="text-danger">*</span>
                                </label>
                                <br>
                                <input type="text" name="mobile" id="mobile" placeholder="Mobile" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-hero rounded-pill">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- vendor details show model -->
<div class="modal fade address_wrap_modal" id="contractor_details_show" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>
                <div class="modal-body">
                    <div class="row">
                        <div class="single_product p-0 mb-3">
                            <h4 class="title_head">Contact Details</h4> 
                            <div class="p-3"> 
                            <?php if(isset($_GET['id'])) { ?> 
                                <?php   if($_GET['id']==$data['token']) { ?>  
                                    <p><span class="ion-ios-telephone"></span> &nbsp;<span>+91 - <?php echo $data['info']['mobile'];  ?></span></p>
                                    <p><span class="ion-ios-home"></span> &nbsp;<span><?php echo $data['info']['address'];  ?>.</span></p>
                                <?php } else { ?>
                                    <p><span class="ion-ios-telephone"></span> &nbsp;<span>+91 - XXXXXXXXXX</span></p>
                                    <p><span class="ion-ios-home"></span> &nbsp;<span>XXXXXXXXXXXXX</span></p>
                                <?php } ?>
                            <?php } else {?>
                                <p><span class="ion-ios-telephone"></span> &nbsp;<span>+91 - XXXXXXXXXX</span></p>
                                <p><span class="ion-ios-home"></span> &nbsp;<span>XXXXXXXXXXXXX</span></p>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                 <div class="modal-footer">
                    <button type="button" class="btn btn-hero" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="btn-close hire-close" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
        <div class="row align-items-center project_modal_content">
          
        </div>
      </div>
    </div>
  </div>
</div>
<?php require_once 'includes/bottom.php'; ?>

<script type="text/javascript">  

     $("body").on("click",".project_modal", function() {
        var value = $(this).data("project_id");
        $.ajax({
            type: "POST",
            url: base_path + "hire/api/projectInfo",
            dataType: "html",
            data: {
                result: value
            },
            beforeSend: function() {
            },
            success: function(data) {
                var data = $.parseJSON(data);
                $(".project_modal_content").html(data)
                $("#exampleModal").modal("show");
            }
        });
    });  

    $(document).ready(function(){
        var showModal = "<?php echo $data['token']; ?>";
        var get_value = $('#get_value').val();
        if(showModal==get_value){
            $("#contractor_details_show").modal("show");
        }
    });
    $(".share-classified").click(function() {
            $('.popup-sharehire').not($(this).next( ".popup-sharehire" )).each(function(){
                $(this).removeClass("active");
            });     
            $(this).next( ".popup-sharehire" ).toggleClass( "active" );
    });
    //View Contact Enquiry 
    $("#viwed_details").validate({
        rules: {
            name: {
                required: true
            },
            mobile: {
                required: true,
                digits: true,
                maxlength: 10,
                minlength: 10
            }
        },
        messages: {
            name: {
                required: "Name cannot be empty",
            },
            mobile: {
                required: "Mobile cannot be empty",
            }

        },
        submitHandler: function(form) {
        var content = $(form).serialize();
        var token = $('#pageToken').val();
        var profile_type = $("#profileType").val();
            $.ajax({
                type: "POST",
                url: base_path + "hire/api/viewdClassifiedDetails",
                dataType: "html",
                data: content,
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();
                    data = data.split("`");
                    if (data != 0) {
                    window.location = base_path + profile_type+"/details/"+token+"?id="+data;
                    }else {
                        $(".address-form-error").html(data);
                    }
                }
            });
            return false;
        }
    });
    //contcat Enquiry
    $("#contractorEnquiry").validate({
            rules: {
            name: {
                required: true
            },
            email: {
                required: true
            },
            mobile: {
                required: true,
                digits: true,
                maxlength: 10,
                minlength: 10
            },
            message: {
                required: true,
                maxlength: 500,
            }
            
        },
        messages: {
            name: {
                required: "Name cannot be empty",
            },
            
            email: {
                required: "Email ID cannot be empty",
            },
            mobile: {
                required: "Mobile number cannot be empty",
                maxlength: "Please enter valid 10 digit mobile number",
                minlength: "Please enter valid 10 digit mobile number",
                digits : "Please enter a valid mobile number"
            },
            message: {
                required: "Message cannot be empty",
            }
            
        },
        submitHandler: function (form) {
            var content = $(form).serialize();
            var token = $('#pageToken').val();
            var profile_type = $("#profileType").val();


            $.ajax({
                type: "POST",
                url: base_path + "hire/api/classifiedEnquiry",
                dataType: "html",
                data: content,
                beforeSend: function () {
                    $(".page_loading").show(); 
                },
                success: function (data) {
                    $(".page_loading").hide();
                    if (data == 1) {
                        window.location = base_path + profile_type+"/details/"+token+"?s=success";
                    } else {
                        setTimeout(function() {
                            new Noty({
                                text: '<strong>'+ data +'</strong>!',
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
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
    });  
</script>
<script>
    Fancybox.bind('[data-fancybox="gallery"]', {
    animated: false,
    showClass: false,
    hideClass: false,

    closeButton: "top",
    dragToClose: false,

    Image: {
        zoom: false,
    },
    Toolbar: false,
    Thumbs: false,
    Carousel: {
        Navigation: true,
        Dots: true,
    },
    // on: {
    //     initLayout: (fancybox) => {
    //     // Create main container for left panel and Fancybox carousel
    //     const $mainPanel = document.createElement("div");
    //     $mainPanel.classList.add("fancybox__main-panel");

    //     // Create left panel
    //     const $leftPanel = document.createElement("div");
    //     $leftPanel.classList.add("fancybox__left-panel");

    //     $leftPanel.innerHTML = document.getElementById("gallery-data").innerHTML;

    //     $mainPanel.appendChild($leftPanel);
    //     $mainPanel.appendChild(fancybox.$carousel);
    //     fancybox.$backdrop.after($mainPanel);
    //     },
    // },
    }); 

    const myCarousel = new Carousel(document.querySelector(".carousel"), {
    slides: [
    ],
    Dots: true,
    Navigation: true,
    });   
    $('.owl-carousel').owlCarousel({
        loop:true,
        nav:false,
        margin:10,
        autoplay:true,
        autoplayTimeout:4000,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:1
            },
            1000:{
                items:1
            }
        }
    });
</script>
    <?php if (isset($_GET['s'])): ?>
    <script type="text/javascript" charset="utf-8" async defer>
    setTimeout(function() {
        new Noty({
            text: '<strong>Thanks for contacting us!</strong>!',
            type: 'success',
            theme: 'relax',
            layout: 'bottomCenter',
            timeout: 3000
        }).show();
    }, 1500);
    history.pushState(null, "", location.href.split("?")[0]);
</script>
<?php endif ?>


    
