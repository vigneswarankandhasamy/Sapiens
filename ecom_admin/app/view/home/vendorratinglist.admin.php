<?php require_once 'includes/top.php'; ?> 

    <!-- content @s -->
    <div class="nk-content nk-content-fluid">
        <div class="container-fluid wide-xl">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title"> <?php echo $data['page_title'] ?></h3>
                                <div class="nk-block-des">
                                    <nav>
                                        <ul class="breadcrumb breadcrumb-arrow">
                                            <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>">Home</a></li>
                                            <li class="breadcrumb-item"><a href="<?php echo COREPATH ?>vendorsettings">Vendors</a></li>
                                            <li class="breadcrumb-item active"><?php echo $data['page_title'] ?></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-shadow">
                            <div class="card-inner">
                                <table class="datatable-init nk-tb-list nk-tb-ulist is-compact" data-auto-responsive="false">
                                    <thead>
                                        <tr class="nk-tb-item nk-tb-head">
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">#</span></th>
                                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">Vendor</span></th>
                                            <th class="nk-tb-col"><span class="sub-text">Overall Ratings</span></th>
                                            <th class="nk-tb-col tb-col-md"><span class="sub-text">Overall Rating Point</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo $data['list'] ?>
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- .card-preview -->
                    </div>
                </div>
            </div>
        </div>
    </div>

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

</script>
<!-- star rating script end -->