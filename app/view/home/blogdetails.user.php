<?php require_once 'includes/top.php'; ?>
<?php if(1!=1) { ?>
<!-- <div class="blog-banner otherpage-banner m-0">
    <img src="<?php echo IMGPATH ?>blog-banner.jpg" alt="">
    <div class="other-banner-title">
        <p>Blog Details</p>
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
<div class="blog_details blog_padding mt-23">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4 col-md-12">
                <div class="blog_sidebar_widget">
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
                <div class="blog_details_wrapper">
                    <div class="blog_thumb">
                        <img src="<?php echo $data['pic'] ?>" class="blog_details_page_img" alt="">
                    </div>
                    <div class="blog_content">
                        <h3 class="post_title"><?php echo $data['info']['title'] ?></h3>
                        <div class="post_meta">
                            <span><i class="fas fa-calendar-alt" aria-hidden="true"></i>  Posted on  <?php echo date("F d, Y", strtotime($data['info']['created_at']))  ?></span>
                        </div>
                        <div class="post_content">
                            <p><?php echo $data['info']['description'] ?></p>
                            <blockquote>
                                <p><?php echo $data['info']['short_description'] ?></p>
                            </blockquote>
                            
                        </div>
                        <div class="entry_content">
                            <div class="post_meta">
                                <!-- <span>Tags: </span>
                                <span><a href="<?php echo BASEPATH ?>#">Hammer</a></span>
                                <span><a href="<?php echo BASEPATH ?>#">, Axes</a></span>
                                <span><a href="<?php echo BASEPATH ?>#">, Screw Driver</a></span> -->
                            </div>
                            <div class="social_sharing">
                                <h3>share this post:</h3>
                                <ul>
                                    <li><a href="http://www.facebook.com/sharer.php?u=<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>" target="blank" title="facebook"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="https://twitter.com/intent/tweet?url=<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>" target="blank" title="twitter"><i class="fab fa-twitter"></i></a></li>
                                    <li><a href="https://www.instagram.com/?url=<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>" target="blank" title="instagram"><i class="fab fa-instagram"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="related_posts">
                        <h3>Related posts</h3>
                        <div class="row">
                            <?php echo $data['related_blogs']; ?>
                        </div>
                    </div>                       
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once 'includes/bottom.php'; ?>