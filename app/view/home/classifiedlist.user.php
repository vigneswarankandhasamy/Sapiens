<?php require_once 'includes/top.php'; ?>
<?php
    if(1!=1){
?>
        <!-- <div class="hire-banner otherpage-banner m-0">
            <img src="<?php echo $data['page_banner']!="" ? SRCIMG.$data['page_banner']['file_name'] : IMGPATH."hire-banner-2.jpg" ?>" alt="image" class="common-banner">
            <div class="other-banner-title">
                <p>Expert Listing</p>
            </div>     
        </div> -->
<?php
    }
?>

<div class="breadcrumbs_area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb_content">
                    <ul>
                        <li><a href="<?php echo BASEPATH ?>">Home</a></li>
                        <li><a href="<?php echo BASEPATH ?>hire/hirelist">Expert</a></li>
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
                <div class="col-sm-12">
                    <div class="shop_title mb-3 w-100" >
                          <div class=""><h4 class="fs-4">Expert listing</h4></div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 hirelg-col-side">
                    <aside class="sidebar_widget">
                        <div class="widget_inner">
                            <?php if(isset($_GET['hire']) || isset($_GET['location']) ) { ?>
                                <div class="widget_list widget_categories">
                                    <h2 class="text-capitalize">Search Field</h2>
                                <?php if(isset($_GET['hire'])) { ?>
                                    <h2 class="text-capitalize">Expert : <?php echo $_GET['hire']; ?></h2>
                                <?php } ?> 
                                <?php if(isset($_GET['experience'])) { ?>
                                    <h2 class="text-capitalize">Experience : <?php echo $_GET['experience']; ?> years & above</h2>
                                <?php } ?>        
                                <?php if(isset($_GET['location']) ) { ?>
                                    <h2 class="text-capitalize">Location : <?php echo $_GET['location']; ?></h2>
                                <?php } ?>        
                                <a href="<?php echo BASEPATH ?>hire/hirelist" class="btn btn-sm mt-4 theme-btn-dark" >Reset</a>
                                </div>

                            <?php } else { ?>
                                <div class="widget_list widget_categories">
                                    <h2 class="text-capitalize">Filter Expert</h2>
                                    <a href="<?php echo BASEPATH ?>hire/hirelist"><button class="btn filter-classified <?php echo (($data['token']=="")? "active" : "") ?> ">All</button></a>
                                    <?php echo $data['calssified_btns']; ?>
                                </div>
                            <?php } ?>                        
                        </div>
                    </aside>
                </div>
                <div class="col-lg-6 col-md-12 hirelg-col-mid">
                    <div class="row shop_wrapper g-4 grid_list">
                        <div class="col-12">
                            <?php echo $data['contractors']['layout']; ?>
                        </div>
                    </div>
                    <?php if($data['count']!=0 && $data['count']!=1 ) {?>
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
                </div>
                <div class="col-lg-3 col-md-12 hirelg-col-side">
                    <aside class="sidebar_widget">
                        <div class="widget_inner">
                            <div class="widget_list widget_categories">
                                <form id="contractorSearch" method="POST" >
                                    <h2 class="text-capitalize">Search Expert</h2>
                                    <div class="d-flex position-relative">
                                        <input class="form-control me-2" type="text" name="c_search_key" id="c_search_key" placeholder="Search Keywords" aria-label="Search" autocomplete="off" value="<?php echo ((isset($_GET['hire']))? ucfirst($_GET['hire']) : "") ?>">
                                         <div id="profileInputautocomplete" class="autocomplete-items display_none w-100"></div>
                                    </div>
                                    <div id="search_key_error"></div>

                                    <h2 class="text-capitalize mt-4">Experience</h2>
                                    <div class="d-flex position-relative">
                                        <input class="form-control me-2" type="text" name="c_experience" id="c_experience" placeholder="Enter experience" aria-label="Experience" autocomplete="off" value="<?php echo ((isset($_GET['experience']))? $_GET['experience'] : "") ?>">
                                    </div>
                                    <div id="experience_error"></div>

                                    <h2 class="text-capitalize mt-4">Location</h2>
                                    <div class="d-flex position-relative">
                                        <input class="form-control me-2" type="text" name="c_location_key" id="c_location_key" placeholder="Enter location" aria-label="Location" autocomplete="off" value="<?php echo ((isset($_GET['location']))? ucfirst($_GET['location']) : "") ?>">
                                        <div id="locateInputautocomplete" class="autocomplete-items display_none w-100"></div>
                                    </div>
                                    <div id="location_key_error"></div>
                                    <button class="btn mt-4 theme-btn-dark rounded-pill ps-3 pe-3" type="submit">Search</button>
                                </form>
                            </div>                          
                        </div>
                    </aside>                    
                </div>
            </div>
        </div>
    </div>
<!--shop  area end-->

<!--banner area start-->
<!-- <?php if($data['page_banner']!="") { ?>
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
<?php } ?> -->
<!--banner area end-->

<!--product area start-->
    <section class="product_area mt-50 mb-50">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="section_title">
                        <h2><span> <strong>Most Viewed</strong>Products</span></h2>
                    </div>
                    <div class="product_carousel product_column5 owl-carousel">
                            <?php echo $data['most_viewed_products'] ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<!--product area end-->

<div class="modal fade address_wrap_modal" id="view_contact" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ">Personal Details</h4>
            </div>
            <div class="address-form-error red"></div>
            <form id="viwed_details" method="POST" >
                <input type="hidden" name="session_token" id="hire_id_for_contact_info" value="" >
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


    $("#c_search_key").keyup(function() {
        var value = $(this).val();
        $.ajax({
            type: "POST",
            url: base_path + "hire/api/profileSearchItems",
            dataType: "html",
            data: {result: value},
            beforeSend: function () {
            },
            success: function (data) {
                console.log(data);
                $('#profileInputautocomplete').show();
                $('#profileInputautocomplete').html(data);

                if(value=="")
                {
                    $('#profileInputautocomplete').hide();
                }
            }
        });
        return false;
    });

    $("body").on("click",".profileSelect",function() {
        $("#c_search_key").val($(this).data("profile"));
        $('#profileInputautocomplete').hide();
    });

    $("#c_location_key").keyup(function() {
        var value = $(this).val();
        $.ajax({
            type: "POST",
            url: base_path + "hire/api/locationSearchItems",
            dataType: "html",
            data: {result: value},
            beforeSend: function () {
            },
            success: function (data) {
                console.log(data);
                $('#locateInputautocomplete').show();
                $('#locateInputautocomplete').html(data);

                if(value=="")
                {
                    $('#locateInputautocomplete').hide();
                }
            }
        });
        return false;
    });

    $("body").on("click",".locationSelect",function() {
        $("#c_location_key").val($(this).data("profile"));
        $('#locateInputautocomplete').hide();
    });

    (function($){
      $.fn.outside = function(ename, cb){
          return this.each(function(){
              var $this = $(this),
                  self = this;

              $(document).bind(ename, function tempo(e){
                  if(e.target !== self && !$.contains(self, e.target)){
                      cb.apply(self, [e]);
                      if(!self.parentNode) $(document.body).unbind(ename, tempo);
                  }
              });
          });
      };
    }(jQuery));

    $('#c_location_key').outside('click', function(e) {
        $('#locateInputautocomplete').hide();
    });

    $('#c_search_key').outside('click', function(e) {
        $('#profileInputautocomplete').hide();
    });



    //Contractor Search

    $.urlParam = function(name){
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if (results==null) {
         return null;
        }
        return decodeURI(results[1]) || 0;
    }

    $("#contractorSearch").validate({
        rules: {
            c_search_key: {
                required:  function(element){
                    if($("#c_location_key").val()=="" && $("#c_experience").val()=="" ){
                        return true;
                    } else {
                        return false;
                    }
                }
            },
            c_experience: {
                required:  function(element){
                    if($("#c_location_key").val()=="" && $("#c_search_key").val()=="" ){
                        return true;
                    } else {
                        return false;
                    }
                },
                number: true
            },
            c_location_key: {
                required:  function(element){
                    if($("#c_search_key").val()=="" && $("#c_experience").val()=="" ){
                        return true;
                    } else {
                        return false;
                    }
                },
            },
        },
        messages: {
            c_search_key: {
                required: "Search Keyword cannot be empty",
            },
            c_experience: {
                required: "Experience cannot be empty",
                number: "Please enter a valid experience year"
            },
            c_location_key: {
                required: "Location Keyword cannot be empty",
            },
        },
        errorPlacement: function(error, element) {
            if (element.attr("name") == "c_search_key") {
                    error.appendTo("#search_key_error");
                } else if (element.attr("name") == "c_location_key") {
                    error.appendTo("#location_key_error");
                } else if (element.attr("name") == "c_experience") {
                    error.appendTo("#experience_error");
                } else {
                    error.insertAfter(element);
                }
        },
        submitHandler: function(form) {
            var profile_search_key = $("#c_search_key").val();
            var profile_str = $.trim(profile_search_key);
            var profile_string = profile_str.toLowerCase().replace(/\s/g, "-"); 

            var experience = $("#c_experience").val();
            var experience_str = $.trim(experience);
            var experience_string = experience_str.toLowerCase().replace(/\s/g, "-");

            var location_search_key = $("#c_location_key").val();
            var location_str = $.trim(location_search_key);
            var location_string = location_str.toLowerCase().replace(/\s/g, ""); 


            var url        = "";
            
            if(profile_string=="") {
                $hire = "";
            }else if(profile_string!="") {
                var hire = profile_string;
            } else {
                var hire = $.urlParam('hire');
            }

            if(experience_string=="") {
                $experience = "";
            }else if(experience_string!="") {
                var experience = experience_string;
            } else {
                var experience = $.urlParam('experience');
            }

            if(location_string=="") {
                $location = "";
            }else if(location_string!="") {
                var location = location_string;
            } else {
                var location = $.urlParam('location');
            }


            if (hire) {   
                var url = url + "?hire=" + hire;
            }

            if(experience) {
                if(hire) 
                {
                   var q_or_and  = "&";
                } else {
        
                    q_or_and     = "?";
                }
                var url = url + q_or_and + "experience=" + experience;
            }

            if(location) {   
                if(hire || experience ) 
                {
                   var q_or_and  = "&";
                } else {
                    q_or_and     = "?";
                }
                var url = url + q_or_and + "location=" + location;
            }

            window.location = base_path + "hire/search" + url ;
        }
    });

    $("body").on("click",".share-classified",function() {
        var hire_id = $(this).data("share_hire_id");

        var share_class = ".hire_share_"+hire_id;

        $(share_class).addClass("active");

        // $('.popup-sharehire').not($(this).next( ".popup-sharehire" )).each(function(){
        //     $(this).removeClass("active");
        // });     
        // $(this).next( ".popup-sharehire" ).toggleClass( "active" );
    });

    $("body").mouseup(function() {
        $(".popup-sharehire").removeClass("active");
        
    });


    //View Contact Enquiry 

    $("body").on("click","#viewContatDetailsBtn",function() {
        var hire_id = $(this).data("encrypted_hire_id");
        $("#hire_id_for_contact_info").val(hire_id);
    });

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
                    var urlParams = new URLSearchParams(window.location.search);
                    var pagenum = urlParams.get('p');
                    if(pagenum){
                        page="p="+pagenum+"&";
                    }else{
                        page="";
                    }
                    if (data != 0) {
                        window.location = base_path + "hire/hirelist?"+page+"id="+data;
                    }else {
                        setTimeout(function() {
                            new Noty({
                                text: '<strong>'+ data +'</strong>!',
                                type: 'warning',
                                theme: 'relax',
                                layout: 'bottomCenter',
                                timeout: 3000
                            }).show();
                        }, 300);
                        // $(".address-form-error").html(data);
                    }
                }
            });
            return false;
        }
    });

    const myCarousel = new Carousel(document.querySelector(".carousel"), {
    slides: [
    ],
    Dots: false,
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
