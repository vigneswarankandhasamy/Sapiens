 <div style="padding: 30px;background-color: #f3f0ec;">
                <div style="padding-left: 30px;">Tax invoice/Bill of Supply/Cash memo</div>
             </div>
<div style="position: relative;width: 21cm;  height: 29.7cm; margin: 0 auto; color: #001028;background: #FFFFFF; font-family: Arial, sans-serif; font-size: 12px; font-family: Arial;">
    <div style="padding: 10px 0;margin-bottom: 30px;">

        <h1 class="headding" style="color: #5D6975;font-size: 1.2em;line-height: 1.4em;font-weight: 14px;text-align: left;margin: 0 0 20px 0;height: 60px;background-color: #f3f0ec;">
        <p style="padding-left:  30px ;padding-top:  10px ;">Tax invoice/Bill of Supply/Cash memo<br><span style="font-size: 0.8em;font-weight: normal;margin-right: 10px;">Number : <?php echo $data['order_item_info']['vendor_invoice_number'] ?> / <?php echo date('d-m-Y', strtotime($data['order_info']['created_at'])); ?></span> <span class="float-end" style="font-size: 0.8em;font-weight: normal;margin-right: 10px;float: right !important;padding-right: 30px;">Order Id : <?php echo $data['order_info']['order_uid'] ?> </span></p>  </h1>

        <div id="project" style="display: contents;float: left;margin-top: 10px;">
            <div style="padding-left: 30px">
                <div style="font-size: 1.0em;margin-bottom: 3px;font-weight: 700;"> Sold By:
                    <?php echo $data[ 'vendor_info'][ 'company'] ?>
                </div>
                <div>
                    <?php echo $data[ 'vendor_info'][ 'company'] ?>
                </div>
                <div>
                    <?php echo $data[ 'vendor_info'][ 'address'] ?>
                </div>
                <div>(+91)
                    <?php echo $data[ 'vendor_info'][ 'mobile'] ?>
                </div>
                <div>
                    <a href="mailto:<?php echo $data['vendor_info']['email'] ?>" style="color: #5D6975;text-decoration: underline;">
                        <?php echo $data[ 'vendor_info'][ 'email'] ?>
                    </a>
                </div>
            </div>

        </div>
        <div id="project" style="display: inline-block;float: left;margin-top: 10px;">
            <div style="padding-left: 30px">
                <div style="font-size: 1.0em;margin-bottom: 3px;font-weight: 700;"> Billing Address:
                    <?php echo $data[ 'ship'][ 'user_name'] ?>
                </div>
                <div>
                    <?php echo $data[ 'ship'][ 'address'] ?>
                </div>
                <div>
                    <?php echo $data[ 'ship'][ 'city'] ?>
                </div>
                <div>
                    <?php echo $data[ 'ship'][ 'state'] ?>
                </div>
                <div>Place of supply :
                    <?php echo $data[ 'ship'][ 'state'] ?>
                </div>
            </div>
        </div>

        <div id="project" class="float-end" style="float: left;margin-top: 10px;float: right !important;padding-right: 30px;">
            <div style="font-size: 1.0em;margin-bottom: 3px;font-weight: 700;"> Shipping Address:
                <?php echo $data[ 'ship'][ 'user_name'] ?>
            </div>
            <div>
                <?php echo $data[ 'ship'][ 'address'] ?>
            </div>
            <div>
                <?php echo $data[ 'ship'][ 'city'] ?>.</div>
            <div>
                <?php echo $data[ 'ship'][ 'state'] ?>
            </div>
            <?php if($data[ 'ship'][ 'gst_name']!="" ) { ?>
            <div>GST Name :
                <?php echo $data[ 'ship'][ 'gst_name'] ?>
            </div>
            <?php } ?>
            <?php if($data[ 'ship'][ 'gstin_number']!="" ) { ?>
            <div>GST Name :
                <?php echo $data[ 'ship'][ 'gstin_number'] ?>
            </div>
            <?php } ?>
        </div>
    </div>
    <div style="padding: 30px;display: inline-grid;width: 735px;">
        <table style="width: 100%;border-collapse: collapse;border-spacing: 0;margin-bottom: 20px;font-size: inherit;">
            <thead>
                <tr>
                    <th class="Item" style="text-align: left;padding: 5px 20px;color: #5D6975;border-bottom: 1px solid #C1CED9;white-space: nowrap; font-weight: normal;font-size: 1.0em;font-weight: bold;background-color: #F5F5F5;height: 65px; ">ITEM</th>
                    <th class="desc" style="text-align: left;padding: 5px 20px;color: #5D6975;border-bottom: 1px solid #C1CED9;white-space: nowrap; font-weight: normal;font-size: 1.0em;font-weight: bold;background-color: #F5F5F5;height: 65px;">QTY</th>
                    <th style="padding: 5px 20px;color: #5D6975;border-bottom: 1px solid #C1CED9;white-space: nowrap;font-weight: normal;font-size: 1.0em;font-weight: bold;background-color: #F5F5F5;height: 65px;">PRICE (INR)</th>
                    <th style="padding: 5px 20px;color: #5D6975;border-bottom: 1px solid #C1CED9;white-space: nowrap;font-weight: normal;font-size: 1.0em;font-weight: bold;background-color: #F5F5F5;height: 65px;">TAX RATE & TYPE(INR)</th>
                    <th style="padding: 5px 20px;color: #5D6975;border-bottom: 1px solid #C1CED9;white-space: nowrap;font-weight: normal;font-size: 1.0em;font-weight: bold;background-color: #F5F5F5;height: 65px;">TOTAL AMOUNT(INR)</th>
                </tr>
            </thead>
            <tbody>
                <?php echo $data[ 'products'] ?>
            </tbody>
        </table>
        <div id="notices" style="width: 400px;">
            <div style="font-size: 1.0em;margin-bottom: 3px;font-weight: 700;">Declaration:</div>
            <div class="notice" style="width: 735px;margin-left: 20px;">We declare that this invoice shows the actual price of the goodsdescribed above and that all particulars are true and correct</div>
        </div>
        <div class="shipping_charge" style="text-align: center;">
            <h4>Receipt for Shipping charges </h4>
            <div class="header">
                <span class="inv_number" style="float: left !important;">Order ID:<?php echo $data['order_info']['order_uid'] ?></span>
                <div class="inv_detail" style="display: inline-grid;float: right;text-align: right;">
                    <span>Serial No: <?php echo $data['order_info']['order_uid'] ?></span>
                    <span>Date: <?php echo date('d-m-Y', strtotime($data['order_info']['created_at'])); ?></span>
                </div>
            </div>

        </div>

    </div>
    <div class="billing_info" style="padding-left: 30px !important;">
        <div id="project" class="add_info_down" style="float: left;margin-top: 10px;width: 220px;">
            <div style="font-size: 1.0em;margin-bottom: 3px;font-weight: 700;"> Name and Address</div>
            <div>
                <?php echo $data[ 'vendor_info'][ 'company'] ?>
            </div>
            <div>
                <?php echo $data[ 'vendor_info'][ 'address'] ?>
                <br />
                <?php echo $data[ 'vendor_info'][ 'city'] ?>
            </div>
            <div>(+91)
                <?php echo $data[ 'vendor_info'][ 'mobile'] ?>
            </div>
            <div>
                <a href="mailto:<?php echo $data['vendor_info']['email'] ?>" style="color: #5D6975;text-decoration: underline;">
                    <?php echo $data[ 'vendor_info'][ 'email'] ?>
                </a>
            </div>
        </div>
        <div id="project" class="add_info_down" style=" margin-left: 70px; float: left;margin-top: 10px;width: 220px;">
            <div style="font-size: 1.0em;margin-bottom: 3px;font-weight: 700;"> Shipping Address</div>
            <div>
                <?php echo $data[ 'ship'][ 'address'] ?>
            </div>
            <div>
                <?php echo $data[ 'ship'][ 'city'] ?>,</div>
            <div>
                <?php echo $data[ 'ship'][ 'state'] ?>
            </div>
            <div>Place of supply :
                <?php echo $data[ 'ship'][ 'state'] ?>
            </div>
        </div>
        <div id="project" class="float-end add_info_down" style="float: left;margin-top: 10px;float: right !important;padding-right: 30px;width: 220px;">
            <div style="font-size: 1.0em;margin-bottom: 3px;font-weight: 700;"> Billing Address</div>
            <div>
                <?php echo $data[ 'ship'][ 'address'] ?>
            </div>
            <div>
                <?php echo $data[ 'ship'][ 'city'] ?>.</div>
            <div>
                <?php echo $data[ 'ship'][ 'state'] ?>
            </div>
            <?php if($data[ 'ship'][ 'gst_name']!="" ) { ?>
            <div>GST Name :
                <?php echo $data[ 'ship'][ 'gst_name'] ?>
            </div>
            <?php } ?>
            <?php if($data[ 'ship'][ 'gstin_number']!="" ) { ?>
            <div>GST Name :
                <?php echo $data[ 'ship'][ 'gstin_number'] ?>
            </div>
            <?php } ?>
        </div>
        <div></div>
    </div>

    <div id="aknowledgment" style="margin-top: 130px;margin-left: 25px;">
        <div style="font-size: 1.0em;margin-bottom: 3px;font-weight: 700;">CUSTOMER ACKNOWLEDGEMENT:</div>
        <div class="notice" style="width: 735px;margin-left: 20px;">I
            <?php echo $data[ 'cus_info'][ 'name']; ?> confirm that the said products are being purchased for my internal/personal consumption and not for re-sale.</div>
    </div>
    <div class="lable bottom_lab" style="padding-bottom: 15px;border-bottom: 1px solid #C1CED9;margin-top: 25px;margin-left: 25px;">Sapiens Order Id :
        <?php echo $data[ 'order_info'][ 'order_uid'] ?>
    </div>

    <div style="color: #5D6975;width: 100%;height: 30px;position: absolute;bottom: 0;border-top: 10px solid #C1CED9;padding: 8px 0;text-align: center;display: contents;">
        <span style="display: block;margin-top: 15px;">Invoice was created on a computer and is valid without the signature and seal.</span>
    </div>
</div>





