<?php require_once 'includes/top.php'; ?> 

    <!-- content @s -->
    <div class="nk-content nk-content-fluid">
        <div class="container-xl wide-xl">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title"><?php echo $data['page_title'] ?></h3>
                                    <div class="nk-block-des">
                                        <nav>
                                           <ul class="breadcrumb breadcrumb-arrow">
                                                <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>">Home</a></li>
                                                <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>orders">Orders</a></li>
                                                <li class="breadcrumb-item active"><?php echo $data['page_title'] ?></li>
                                            </ul>
                                        </nav>
                                    </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                    <div class="toggle-wrap nk-block-tools-toggle">
                                        <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                        <div class="toggle-expand-content" data-content="pageMenu">
                                            <ul class="nk-block-tools g-3">
                                                <?php if($data['respone_status']) { ?>
                                                <li class="nk-block-tools-opt"><a href="<?php echo COREPATH ?>orders/previewvendorinvoice/<?php echo $data['vendor_order_item']['id'] ?>" class="btn btn-success" target="_blank"><em class="icon ni ni-file"></em><span>Order Invoice</span></a>
                                                </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                            </div>
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="row gy-5">
                            <div class="col-lg-6">
                                <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h5 class="nk-block-title title">Vendor Information</h5>
                                    </div>
                                </div><!-- .nk-block-head -->
                                <div class="card card-bordered">
                                    <ul class="data-list is-compact ">
                                        <li class="data-item ">
                                            <div class="data-col ">
                                                <div class="data-label vendor_info_width">Name</div>
                                                <div class="data-value"><?php echo $data['vendor_info']['company']; ?></div>
                                            </div>
                                        </li>
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label vendor_info_width">Mobile Number</div>
                                                <div class="data-value"><?php echo $data['vendor_info']['mobile']; ?></div>
                                            </div>
                                        </li>
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label vendor_info_width">Email</div>
                                                <div class="data-value"><?php echo $data['vendor_info']['email']; ?></div>
                                            </div>
                                        </li>
                                        <?php if($data['vendor_info']['gst_no']!="") { ?>
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label vendor_info_width">GST No</div>
                                                <div class="data-value"><?php echo $data['vendor_info']['gst_no']; ?></div>
                                            </div>
                                        </li>
                                        <?php } ?>
                                         <?php if($data['vendor_info']['address']!="") { ?>
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label vendor_info_width">Address</div>
                                                <div class="data-value"><?php echo $data['vendor_info']['address']; ?></div>
                                            </div>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div><!-- .card -->
                            </div>
                            <div class="col-lg-6">
                                <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h5 class="nk-block-title title">Order Information</h5>
                                    </div>
                                </div><!-- .nk-block-head -->
                                <div class="card card-bordered">
                                    <ul class="data-list is-compact">
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">Order Invoice Id</div>
                                                <div class="data-value"><a href='<?php echo COREPATH ?>orders/orderdetails/<?php echo $data['v_order_info']['id']; ?>'><?php echo $data['v_order_info']['order_uid']; ?></a></div>

                                            </div>
                                        </li>
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">Order Date</div>
                                                <div class="data-value"><?php echo date('d/m/Y',strtotime($data['v_order_info']['created_at'])) ; ?></div>
                                            </div>
                                        </li>
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">Payment Method</div>
                                                <div class="data-value"><?php echo ($data['order_info']['payment_type']=='cod')? "Cash On Delivery" : "Online Payment"; ?></div>
                                            </div>
                                        </li>
                                        <?php if($data['respone_status']) { ?>
                                        <li class="data-item">
                                            <div class="data-col">
                                                <div class="data-label">Status</div>
                                                <div class="data-value">
                                                    <?php if($data['inprocess']==true ) { ?>
                                                        Inprocess
                                                    <?php } elseif ($data['shipped']==true ) { ?>
                                                        Shipment Out for delivery
                                                    <?php } elseif ( $data['delivered']==true) { ?>
                                                        Delivered
                                                    <?php } elseif ( $data['returned']==true) { ?>
                                                        Returned
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </li>
                                        <?php } else { ?>
                                            <li class="data-item">
                                                <div class="data-col">
                                                    <div class="data-label">Status</div>
                                                    <div class="data-value">
                                                        <?php if($data['v_order_info']['vendor_response']==0 ) { ?>
                                                            Not Seen
                                                        <?php } elseif ($data['v_order_info']['vendor_accept_status']==1 ) { ?>
                                                            Accepted
                                                        <?php } else { ?>
                                                            Rejected
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </li>
                                            <?php if ($data['v_order_info']['vendor_accept_status']==0 && $data['v_order_info']['vendor_response']==1 ) { ?>
                                             <li class="data-item">
                                                <div class="data-col">
                                                    <div class="data-label">Reason</div>
                                                    <div class="data-value"><?php echo $data['v_order_info']['response_notes'] ?></div>
                                                </div>
                                            </li>
                                             <?php } ?>
                                        <?php } ?>
                                    </ul>
                                </div><!-- .card -->
                            </div>
                            </div>
                            </div><!-- .col -->
                        </div><!-- .row -->
                    </div>

                    <div class="headding">
                        <h3 class="nk-block-title page-title">Order Invoice</h3>
                    </div>

                    <div class="nk-block">
                    <?php echo $data['ven_order_invoice'];  ?>

                    <!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
    <!-- content @e -->

<?php require_once 'includes/bottom.php'; ?>
<!-- star rating script start -->
<script type="text/javascript">
    $(".star-rating").starRating({
        totalStars: 5,
        starShape: 'rounded',
        starSize: 20,
        emptyColor: '#ddd',
        hoverColor: '#ffc107',
        activeColor: '#ffc107',
        initialRating: 5,
        useGradient: false,
        useFullStars: true,
        disableAfterRate: false,
        onHover: function(currentIndex, currentRating, $el) {
            //$('.live-rating').text(currentIndex);
        },
        onLeave: function(currentIndex, currentRating, $el) {
            //$('.live-rating').text(currentRating);
        },
        callback: function(currentRating, $el) {
            //alert('rated '+currentRating);

            $("#rating_input").val(currentRating);
        }
    });

    $(".yellow-rating").starRating({
        totalStars: 5,
        starShape: 'rounded',
        starSize: 20,
        emptyColor: '#ccc',
        hoverColor: '#ffc107',
        activeColor: '#ffc107',
        useGradient: false,
        readOnly: true
    });

    var setRating = function() {
        Array.from($('.rating_data')).forEach((ele, index) => {
            let star_elem = $(".my-rating-7")[index];

            $(star_elem).starRating({
                readOnly: true,
                totalStars: 5,
                starShape: 'rounded',
                starSize: 20,
                emptyColor: '#ddd',
                hoverColor: '#ffc107',
                activeColor: '#ffc107',
                initialRating: ele.value,
                useGradient: false,
                disableAfterRate: false,
                callback: function(currentRating, $el) {
                    //alert('rated '+currentRating);
                    $("#rating_data").val(currentRating);
                }
            });
        })
    }

    setRating();

    var setRatingCount = function() {
        var tot = $('#total_cot').val();
        $(".my-rating-9").starRating({
            readOnly: true,
            initialRating: $('#total_cot').val(),
            starShape: 'rounded',
            starSize: 20,
            emptyColor: '#ddd',
            hoverColor: '#ffc107',
            activeColor: '#ffc107',
            disableAfterRate: false
        });
        $('.live-rating').text(tot);
    }

    setRatingCount();
</script>
<!-- star rating script end -->