<?php require_once 'includes/top.php'; ?>
    <!--breadcrumbs area start-->
    <div class="breadcrumbs_area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
                        <ul>
                            <li><a href="<?php echo BASEPATH ?>">home</a></li>
                            <li><a href="<?php echo BASEPATH ?>pages/details/<?php echo $data['info']['page_url']; ?>"><?php echo $data['info']['title']; ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--breadcrumbs area end-->
	<section class="ls ms s-pt-xl-200 s-pt-lg-230 s-pt-md-190 s-pt-160 s-pb-xl-100 s-pb-lg-200 s-pb-md-160 s-pb-130 c-mb-30">
	<?php
	$sitename = "Sapiens";
	$websiteaddress = "Sapiens.com";
	$mailid = "Sapiensecom@Sapiens.com";
	$country = "India";
	?>
	<div class="container-fluid">
		<div class="row mytermprivacy">
			<div class="col-md-12 col-xl-12 p-45 ls rounded mb-5 mt-4">
				<h2 class="special-heading text-center text-sm-left">
					<span class="text-capitalize">
						<span class="thin"><?php echo $data['info']['title']; ?></span>
					</span>
				</h2>
				<p class="legalpage_contant">	<?php echo $data['info']['content']; ?> </p>
			</div>
		</div>
	</div>
	</section>
    
    <?php require_once 'includes/bottom.php'; ?>