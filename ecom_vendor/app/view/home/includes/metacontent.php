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
    <link id="skin-default" rel="stylesheet" href="<?php echo CSSPATH ?>theme.css">
    <link rel="stylesheet" href="<?php echo CSSPATH ?>custom.css">


    <!-- vendor inventory product table start -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js">
    </script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css"> -->

    <script type="text/javascript">
        function showHideRow(row) {
            $("#" + row).toggle();
        }
    </script>
    <style type="text/css">
        #table_detail .hidden_row {
            display: none;
        }
    </style>
    <!-- vendor inventory product table end -->

    <!-- Vendor invoice print css start -->
    <style type="text/css">
        #printable { display: none; }

        @media print
        {
            .non-printable { display: none; }
            #printable { display: block; }
        }
    </style>
    <!-- Vendor invoice print css end -->

    <script type="text/javascript">
        var core_path = "<?php echo COREPATH ?>";
        var base_path = "<?php echo BASEPATH ?>";
    </script>
    
</head>