<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo IMGPATH ?>favicon.png">
    <!-- meta tag -->
    <?php if ($data['meta']!='static'){ ?>
         <title><?php echo $data['siteinfo']['seo']['meta_title'] ?></title>
        <meta name="description" content="<?php echo $data['siteinfo']['seo']['meta_description'] ?>">
        <meta name="keywords" content="<?php echo $data['siteinfo']['seo']['meta_keyword'] ?>"> 
        <meta property="og:title" content="<?php echo $data['siteinfo']['seo']['meta_title'] ?>">
        <meta property="og:description" content="<?php echo $data['siteinfo']['seo']['meta_title'] ?>">
        <meta property="og:image" content="<?php echo $data['siteinfo']['seo']['image']!="" ? SRCIMG.$data['siteinfo']['seo']['image'] : ASSETS_PATH."logo.png" ?>">
    <?php } else { ?>
        <title><?php echo $data['meta_title'] ?></title>
        <meta name="description" content="<?php echo $data['meta_description'] ?>">
        <meta name="keywords" content="<?php echo $data['meta_keywords'] ?>"> 
        <meta property="og:title" content="<?php echo $data['meta_title'] ?>">
        <meta property="og:description" content="<?php echo $data['meta_description'] ?>">
        <meta property="og:image" content="<?php echo ASSETS_PATH."logo.png" ?>">
    <?php } ?>
    
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSSPATH ?>cart/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo CSSPATH ?>cart/all.min.css">
    <link rel="stylesheet" href="<?php echo CSSPATH ?>cart/cartcustom.css">
    <link rel="stylesheet" href="<?php echo PLUGINS ?>sweetalert/sweetalert.css">
    <link rel="stylesheet" href="<?php echo PLUGINS ?>noty/noty.css">
    <link rel="stylesheet" href="<?php echo CSSPATH ?>style.css">
    <link rel="stylesheet" href="<?php echo CSSPATH ?>custom.css">
    <link rel="stylesheet" href="<?php echo CSSPATH ?>responsive.css">

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

<div class="container-fluid web_header">
  <nav class="navbar navbar-expand-lg navbar-light pt-3 pb-3">
    <div class="container-fluid">
      <a class="navbar-brand" href="<?php echo BASEPATH ?>"><img src="<?php echo IMGPATH ?>img/logo.png" class="img-fluid" alt=""></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav m-auto mb-2 mb-lg-0 justify-content-center">
          <li class="nav-item">
            <a class="nav-link  <?php echo ($data['active_menu']=='index')? 'active' : ''; ?> " aria-current="page" href="<?php echo BASEPATH ?>cartdetails">Cart <i class="fas fa-shopping-cart ps-2 fs-5"></i></a>
          </li>
          <li class="wizard_tab"></li>
          <li class="nav-item <?php echo ($data['vendor_status_check'])? '' : 'pointer_events_none'; ?>">
            <a class="nav-link <?php echo ($data['active_menu']=='address')? 'active' : ''; ?>   "  href="<?php echo BASEPATH ?>cartdetails/address">Address <i class="fas fa-map-marker-alt ps-2 fs-4"></i></a>
          </li>
          <li class="wizard_tab"></li>
          <li class="nav-item <?php echo ($data['vendor_status_check'])? '' : 'pointer_events_none'; ?>">
            <a class="nav-link <?php echo ($data['active_menu']=='orderconfirm')? 'active' : ''; ?>   " href="<?php echo BASEPATH ?>cartdetails/orderconfirmation">Payment <i class="fas fa-money-check ps-2 fs-5"></i></a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</div>
<div class="mobile_header">
  <div class="container-fluid">
    <a class="logo_m" href="<?php echo BASEPATH ?>"><img src="<?php echo IMGPATH ?>img/logo.png" class="img-fluid" alt=""></a>
    <div class="d-flex justify-content-between align-items-center">
      <ul class="m-auto mb-2 mb-lg-0 justify-content-center">
        <li class="">
          <a class="<?php echo ($data['active_menu']=='index')? 'active' : ''; ?> " aria-current="page" href="<?php echo BASEPATH ?>cartdetails">Cart <i class="fas fa-shopping-cart ps-2 fs-5"></i></a>
        </li>
        <li class="wizard_tab_m"></li>
        <li class="">
          <a class="<?php echo ($data['active_menu']=='address')? 'active' : ''; ?> " href="<?php echo BASEPATH ?>cartdetails/address">Address <i class="fas fa-map-marker-alt ps-2 fs-4"></i></a>
        </li>
        <li class="wizard_tab_m"></li>
        <li class="">
          <a class="<?php echo ($data['active_menu']=='orderconfirm')? 'active' : ''; ?> " href="<?php echo BASEPATH ?>cartdetails/orderconfirmation">Payment <i class="fas fa-money-check ps-2 fs-5"></i></a>
        </li>
      </ul>
    </div>
  </div>
</div>


