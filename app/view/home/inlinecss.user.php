<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo $data['order_info']['order_uid'] ?></title>
    <link rel="stylesheet" href="style.css" media="all" />
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo IMGPATH ?>favicon.png">
    <style type="text/css">
        .clearfix:after {
          content: "";
          display: table;
          clear: both;
        }

        a {
          color: #5D6975;
          text-decoration: underline;
        }

        body {
          position: relative;
          width: 21cm;  
          height: 29.7cm; 
          margin: 0 auto; 
          color: #001028;
          background: #FFFFFF; 
          font-family: Arial, sans-serif; 
          font-size: 12px; 
          font-family: Arial;
        }

        header {
          padding: 10px 0;
          margin-bottom: 30px;
        }


        h1 {
          color: #5D6975;
          font-size: 1.2em;
          line-height: 1.4em;
          font-weight: 14px;
          text-align: left;
          margin: 0 0 20px 0;
          height: 60px;
          background: url(dimension.png);
        }

        #project {
          float: left;
          margin-top: 10px;
        }

        #project span {
          color: #5D6975;
          text-align: right;
          width: 52px;
          margin-right: 10px;
          display: inline-block;
          font-size: 0.8em;
        }

        .lable {
            font-size: 1.0em;
            margin-bottom: 3px;
            font-weight: 700;
        }

        #company {
          float: left;
        }

        #company div {
          white-space: nowrap; 
          padding-left: 30px;       
        }
        #project div {
          padding-left: 30px;       

        }

        #project div span {
            text-align: left;
        }


        table {
          width: 100%;
          border-collapse: collapse;
          border-spacing: 0;
          margin-bottom: 20px;
        }

        table tr:nth-child(2n-2) td {
          background: #F5F5F5;
        }

        table th,
        table td {
          text-align: center;
        }

        table th {
          padding: 5px 20px;
          color: #5D6975;
          border-bottom: 1px solid #C1CED9;
          white-space: nowrap;        
          font-weight: normal;
          font-size: 1.0em;
          font-weight: bold;
          background-color: #F5F5F5;
          height: 65px;

        }

        main {
          padding: 30px;
        }
        .bottom_lab {
          padding-bottom: 15px;
          border-bottom: 1px solid #C1CED9;
        }

        table .Item,
        table .desc {
          text-align: left;
        }

        table td {
          padding: 20px;
          text-align: right;
        }

        table td.Item,
        table td.desc {
          vertical-align: top;
        }

        table td p {
          font-weight: bold;
        }

        table td.unit,
        table td.qty,
        table td.total {
          /*font-size: 1.2em;*/
        }

        table td.grand {
          border-top: 1px solid #5D6975;;
        }

        #notices .notice {
          color: #5D6975;
          font-size: 1.2em;
        }

        footer {
          color: #5D6975;
          width: 100%;
          height: 30px;
          position: absolute;
          bottom: 0;
          border-top: 10px solid #C1CED9;
          padding: 8px 0;
          text-align: center;
          display: contents;
        }
        footer span {
         display: block;
          margin-top: 15px;
        }
        h1.headding  {
            background-color: #f3f0ec;
        }
         h1.headding p {
            padding-left:  30px ;
            padding-top:  10px ;
        }

        h1.headding p span {
            font-size: 0.8em;
            font-weight: normal;
            margin-right: 10px;
        }
        #shipping {
            padding-left: 30px;
            margin-top: 15px;
        }
        #billing {
             float: left;
        }
        .float-end {
            float: right !important;
            padding-right: 30px;
        }
        .item span {
            display: flex;
        }
        #notices {
            width: 400px;
        }
       
        .shipping_charge h4 {
            text-align: center;
        }
        
        .inv_number {
            float: left !important;
        }
        .inv_detail {
            display: inline-grid;
            float: right;

        }
        .add_info_down {
          width: 220px;
        }
        #aknowledgment {
         margin-top: 130px;
          margin-left: 25px;
        }
        .bottom_lab {
          margin-top: 25px;
          margin-left: 25px;
        }

        
    </style>
  </head>
  <body>
    <header class="clearfix">
      
      <h1 class="headding"><p>Tax invoice/Bill of Supply/Cash memo<br><span>Number : <?php echo $data['order_item_info']['vendor_invoice_number'] ?> / <?php echo date('d-m-Y', strtotime($data['order_info']['created_at'])); ?></span> <span class="float-end">Order Id : <?php echo $data['order_info']['order_uid'] ?> </span></p>  </h1>
     
      <div id="project" style="display: contents;">
        <div class="lable"> Sold By: <?php echo $data['vendor_info']['company'] ?></div>
            <div><?php echo $data['vendor_info']['company'] ?></div> 
            <div><?php echo $data['vendor_info']['address'] ?></div>
            <div>(+91) <?php echo $data['vendor_info']['mobile'] ?></div>
            <div><a href="mailto:<?php echo $data['vendor_info']['email'] ?>"><?php echo $data['vendor_info']['email'] ?></a></div>
        
      </div>
      <div id="project" style="display: inline-block;">
            <div class="lable"> Billing Address: <?php echo $data['ship']['user_name'] ?></div>
            <div><?php echo $data['ship']['address'] ?></div>
            <div><?php echo $data['ship']['city'] ?></div><div> <?php echo $data['ship']['state'] ?></div>
            <div>Place of supply : <?php echo $data['ship']['state'] ?></div>
      </div>
      
       <div id="project"  class="float-end">
            <div class="lable"> Shipping Address: <?php echo $data['ship']['user_name'] ?></div>
            <div><?php echo $data['ship']['address'] ?></div><div><?php echo $data['ship']['city'] ?>.</div>
            <div><?php echo $data['ship']['state'] ?></div>
            <?php if($data['ship']['gst_name']!="") { ?>
            <div>GST Name :<?php echo $data['ship']['gst_name'] ?></div>
            <?php } ?>
            <?php if($data['ship']['gstin_number']!="") { ?>
            <div>GST Name :<?php echo $data['ship']['gstin_number'] ?></div>
            <?php } ?>
      </div>
    </header>
    <main>
      <table>
        <thead>
          <tr>
            <th class="Item">ITEM</th>
            <th class="desc">QTY</th>
            <th>PRICE (INR)</th>
            <th>TAX RATE & TYPE(INR)</th>
            <th>TOTAL AMOUNT(INR)</th>
          </tr>
        </thead>
        <tbody>
          <?php echo $data['products'] ?>
        </tbody>
      </table>
      <div id="notices">
        <div class="lable" >Declaration:</div>
        <div class="notice">We declare that this invoice shows the actual price of the goodsdescribed above and that all particulars are true and correct</div>
      </div>
        <div class="shipping_charge">
            <h4>Receipt for Shipping charges </h4>
            <div class="header">
                <span class="inv_number">Order ID:<?php echo $data['order_info']['order_uid'] ?></span>
                <div class="inv_detail">
                    <span>Serial No: <?php echo $data['order_info']['order_uid'] ?></span>
                    <span>Date: <?php echo date('d-m-Y', strtotime($data['order_info']['created_at'])); ?></span>
                </div>
            </div>
           
        </div>

    </main>
     <div class="billing_info">
        <div id="project" class="add_info_down">
            <div class="lable"> Name and Address</div>
            <div><?php echo $data['vendor_info']['company'] ?></div>
            <div><?php echo $data['vendor_info']['address'] ?><br /> <?php echo $data['vendor_info']['city'] ?></div>
            <div>(+91) <?php echo $data['vendor_info']['mobile'] ?></div>
            <div><a href="mailto:<?php echo $data['vendor_info']['email'] ?>"><?php echo $data['vendor_info']['email'] ?></a></div>
        </div>
        <div id="project" class="add_info_down" style=" margin-left: 70px; ">
            <div class="lable"> Shipping Address</div>
            <div><?php echo $data['ship']['address'] ?></div>
            <div><?php echo $data['ship']['city'] ?>,</div><div> <?php echo $data['ship']['state'] ?></div>
            <div>Place of supply : <?php echo $data['ship']['state'] ?></div>
        </div>
        <div id="project"  class="float-end add_info_down">
            <div class="lable"> Billing  Address</div>
            <div><?php echo $data['ship']['address'] ?></div><div><?php echo $data['ship']['city'] ?>.</div>
            <div><?php echo $data['ship']['state'] ?></div>
            <?php if($data['ship']['gst_name']!="") { ?>
            <div>GST Name :<?php echo $data['ship']['gst_name'] ?></div>
            <?php } ?>
            <?php if($data['ship']['gstin_number']!="") { ?>
            <div>GST Name :<?php echo $data['ship']['gstin_number'] ?></div>
            <?php } ?>
        </div>
        <div></div>
    </div>
 
    <div id="aknowledgment">
        <div class="lable" >CUSTOMER ACKNOWLEDGEMENT:</div>
        <div class="notice">I  <?php  echo  $data['cus_info']['name'];  ?> confirm that the said products are being purchased for my internal/personal consumption and not for re-sale.</div>
      </div>
      <div class="lable bottom_lab">Zupply Order Id : <?php echo $data['order_info']['order_uid'] ?></div>

     

    <footer>
      <span>Invoice was created on a computer and is valid without the signature and seal.</span>
    </footer>
  </body>
</html>

