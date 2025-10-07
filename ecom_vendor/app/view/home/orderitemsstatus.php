<div class="nk-block">
                        <div class="row gy-5">
                            <div class="col-12">
                                <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h5 class="nk-block-title title">Order Items</h5>
                                        <input type="hidden" id="orderId" value="<?php echo $data['order_info']['id'] ?>">
                                    </div>
                                </div>
                                <div class="card card-bordered">
                                    <div class="card-inner">
                            <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                                <thead>
                                    <tr class="nk-tb-item nk-tb-head">
                                        <th class="nk-tb-col">Invoice No</span>
                                        </th>
                                         <th class="nk-tb-col tb-col-md">Order Date</span>
                                        </th>
                                        <th class="nk-tb-col"><span class="sub-text">Product Name</span>
                                        </th>
                                        <th class="nk-tb-col tb-col-mb"><span class="sub-text">QTY</span>
                                        </th>
                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">Commission</span>
                                        </th>
                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">Total</span>
                                        </th>
                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">Status</span>
                                        </th>
                                        <th class="nk-tb-col nk-tb-col-tools text-right">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo $data["ord_sts_items"]; ?>
                                </tbody>
                            </table>
                        </div>
                                </div>
                            </div><!-- .col -->
        
                        </div><!-- .row -->
                    </div><!-- .nk-block -->