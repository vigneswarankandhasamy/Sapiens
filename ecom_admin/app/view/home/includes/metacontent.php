<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="../">
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?php echo COMPANY_NAME ?> Online Store">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="<?php echo ASSETS_PATH ?>favicon.png">
    <!-- Page Title  -->
    <title><?php echo $data['page_title']." | ".COMPANY_NAME  ?></title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="<?php echo CSSPATH ?>core.css?ver=2.2.0">
    <link id="skin-default" rel="stylesheet" href="<?php echo CSSPATH ?>theme.css?ver=2.2.0">
    <link rel="stylesheet" href="<?php echo CSSPATH ?>custom.css">
    
    <script type="text/javascript">
        var core_path = "<?php echo COREPATH ?>";
        var base_path = "<?php echo BASEPATH ?>";
    </script>

    <?php if ($data['scripts']=='addproduct'): ?>
        <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="<?php echo JSPATH ?>image-uploader/image-uploader.css">
        <link rel="stylesheet" href="<?php echo JSPATH ?>tags/bootstrap-tagsinput.css">
        <link rel="stylesheet" href="<?php echo JSPATH ?>autocomplete/jquery-ui.css">
    <?php endif ?>

</head>