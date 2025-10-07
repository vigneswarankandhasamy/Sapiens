<?php require_once 'includes/top.php'; ?>

<?php if(1!=1) { ?>
<!-- <div class="blog-banner otherpage-banner m-0">
     <img src="<?php echo $data['page_banner']!="" ? SRCIMG.$data['page_banner']['file_name'] : IMGPATH."blog-banner-1.jpg" ?>" alt="image" class="common-banner">
    <div class="other-banner-title">
        <p>Blog</p>
        <button type="button" class="btn btn-sm banner-btn rounded-pill"><a href="<?php echo BASEPATH ?>blog">View more</a></button>
    </div>      
</div> -->
<?php } ?>

<div class="breadcrumbs_area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb_content">
                    <ul>
                        <li><a href="<?php echo BASEPATH ?>">home</a></li>
                        <li><a href="<?php echo BASEPATH ?>blog">blog</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="shop_area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="section_title">
                    <h2><span> <strong>Blog</strong></span></h2>
                </div>
                <div class="blog_page_section mt-23">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-4 col-md-12">
                                <div class="blog_sidebar_widget">
                                    <div class="widget_list widget_post">
                                        <h3>Search Keywords</h3>
                                        <div class="search-container search_two">
                                        <form id="blogSearch">
                                            <div class="search_box blog_page_search_box_width">
                                                <input placeholder="Search entire blog here ..." autocomplete="off"  id="b_search_key" name="b_search_key" type="text">
                                                <button type="submit"><i class="ion-ios-search-strong"></i></button>
                                            </div>
                                        </form>

                                    </div>
                                    </div>
                                    <div class="widget_list widget_post">
                                        <h3>Recent Posts</h3>
                                        <?php echo $data['recent_blogs']; ?>
                                    </div>
                                    <div class="widget_list widget_categories">
                                        <h3>Categories</h3>
                                        <ul>
                                            <?php echo $data['blog_category']; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-12">
                                <div class="blog_wrapper">
                                    <?php echo $data['blog_list'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="shop_toolbar t_bottom">
                    <div class="pagination">
                        <ul>
                            <?php if($data['count']!=0 && $data['count']!=1 ) {?>
                                <li><a href="<?php echo $data['previous'] ?>"><<</a></li>
                                    <?php echo $data['page'] ?>
                                <li><a href="<?php echo $data['next'] ?>">>></a></li>
                            <?php  } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once 'includes/bottom.php'; ?>
<script type="text/javascript">
     $("#blogSearch").validate({
        rules: {
            b_search_key: {
                required: true
            },
        },
        messages: {
            b_search_key: {
                required: "",
            },
        },
        submitHandler: function(form) {
            var value = $("#b_search_key").val();
            var str = $.trim(value);
            var string = str.toLowerCase().replace(/\s/g, "-"); 
            window.location = base_path + "blog/search/" + string;
        }
    });
</script>