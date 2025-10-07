<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Example 1</title>
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
          border-top: 1px solid #C1CED9;
          padding: 8px 0;
          text-align: center;
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

        
    </style>
  </head>
  <body>
    <header class="clearfix">
      
      <h1 class="headding"><p>Tax invoice/Bill of Supply/Cash memo<br><span>Invoice : ORD001</span> <span>Date: 24-04-2021</span></p></h1>
     
      <div id="project" style="display: contents;">
        <div class="lable"> Sold By: Nukariti Enterprises</div>
            <div>Company Name</div>
            <div>455 Foggy Heights,<br /> AZ 85004, US</div>
            <div>(602) 519-0450</div>
            <div><a href="mailto:company@example.com">company@example.com</a></div>
        
      </div>
      <div id="project" style="display: inline-block;">
            <div class="lable"> Shipping Address: Thirumalai Raja</div>
            <div>No:5, Ganesh layout,9th street,</div>
            <div>ganapathy</div><div> Coimbatore,Tamil Nadu -641006</div>
            <div>Place of supply : Tamil Nadu</div>
      </div>
      
       <div id="project"  class="float-end">
            <div class="lable"> Shipping Address: Thirumalai Raja</div>
            <div>64, Sree Thiruvengada Nagar, Ganapathy</div>
            <div>Tamil Nadu</div>
            <div>GSTIN : 33AMNPT5850E1ZT</div>
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
          <tr>
            <td class="Item"><p>Cotton Steel Black Blue Slouchy Beanie Cap for Winter;Summer;Autumn & Spring Season;Can be usedas a Helmet Cap 2 pcs (Size = free size) HSN Code :6505</p> <span> Black Blue Beanie Cap 2 pcs _21147.3212 </span></td>
            <td class="qty">1</td>
            <td class="unit">₹ 40.00</td>
            <td class="qty">12% IGST</td>
            <td class="total">₹ 1,040.00</td>
          </tr>
          <tr>
            <td colspan="4">SUBTOTAL</td>
            <td class="total">₹ 5,200.00</td>
          </tr>
          <tr>
            <td colspan="4">TAX 25%</td>
            <td class="total">₹ 1,300.00</td>
          </tr>
          <tr>
            <td colspan="4" class="grand total">GRAND TOTAL</td>
            <td class="grand total">₹ 6,500.00</td>
          </tr>
        </tbody>
      </table>
      <div id="notices">
        <div class="lable" >Declaration:</div>
        <div class="notice">We declare that this invoice shows the actual price of the goodsdescribed above and that all particulars are true and correct</div>
      </div>
        <div class="shipping_charge">
            <h4>Receipt for Shipping charges </h4>
            <div class="header">
                <span class="inv_number">Order ID:7654740397</span>
                <div class="inv_detail">
                    <span>Serial No: 07B69793</span>
                    <span>Date: 16-03-2019</span>
                </div>
            </div>
           
        </div>

    </main>
    <div class="billing_info">
        <div id="project">
            <div class="lable"> Name and Address</div>
            <div>Company Name</div>
            <div>455 Foggy Heights,<br /> AZ 85004, US</div>
            <div>(602) 519-0450</div>
            <div><a href="mailto:company@example.com">company@example.com</a></div>
        </div>
        <div id="project" style=" margin-left: 70px; ">
            <div class="lable"> Shipping Address</div>
            <div>No:5, Ganesh layout,9th street,</div>
            <div>ganapathy</div><div> Coimbatore,Tamil Nadu -641006</div>
            <div>Place of supply : Tamil Nadu</div>
        </div>
        <div id="project"  class="float-end">
            <div class="lable"> Billing  Address</div>
            <div>64, Sree Thiruvengada Nagar, Ganapathy</div>
            <div>Tamil Nadu</div>
            <div>GSTIN : 33AMNPT5850E1ZT</div>
        </div>
    </div>
     

    <footer>
      Invoice was created on a computer and is valid without the signature and seal.
    </footer>
  </body>
</html>