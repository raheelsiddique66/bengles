<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
$invoice=dofetch(doquery("select * from invoice where id='".slash($_GET["id"])."'", $dblink));
$customer=dofetch(doquery("select * from customer where id='".slash($invoice["customer_id"])."'", $dblink));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Invoice</title>
<link rel="stylesheet" type="text/css" href="css/invoice.css">
</head>
<body>
<div class="wrapper">
    <div class="header">
        <div class="logo">
            <h1>MK COATING</h1>
            <p>FROM: <?php echo date_convert($invoice["date_from"])?> TO: <?php echo date_convert($invoice["date_to"])?></p>
        </div>
    </div>
    <div class="content">
    	
        <div class="number clear">
        	<div class="invoice_number">
                <div class="detail_box">
                    <h3>Customer</h3>
                    <div class="detail_box_inner">
                        <p><?php echo unslash($customer["customer_name"]);?></p>
                        <p><?php echo unslash($customer["address"]);?></p>
                        <p><?php echo unslash($customer["phone"]);?></p>
                    </div>
                </div>
            </div>
            <div class="order_number">
                <div class="sale_tax">
                    <h1> INVOICE</h1>
                </div>
                <p>DATE: <?php echo datetime_convert($invoice["datetime_added"])?></p>
                <p>Invoice # <?php echo $invoice["id"]?></p>
            </div>
        </div>
        <table width="100%" cellpadding="0" cellspacing="0">
        	<thead>
                <tr>
                    <th width="2%" class="text-center">S.No</th>
                    <th width="10%">Date</th>
                    <th width="10%">Narration</th>
                    <th width="10%">Account</th>
                    <th width="5%" class="text-right">Qty</th>
                    <th width="5%" class="text-right">Rate</th>
                    <th width="8%" class="text-right">Debit</th>
                    <th width="8%" class="text-right">Credit</th>
                    <th width="8%" class="text-right">Adjst</th>
                    <th width="8%" class="text-right">Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "select date as datetime_added, sum(quantity) as quantity, unit_price, unit_price*sum(quantity) as debit, 0 as credit from delivery a left join delivery_items b on a.id = b.delivery_id where customer_id = '".$invoice[ "customer_id" ]."' and date>='".date('Y-m-d',strtotime(date_convert($invoice["date_from"])))." 00:00:00' and date<'".date('Y-m-d',strtotime(date_convert($invoice["date_to"])))." 23:59:59' group by color_id,design_id union select datetime_added as datetime_added, 0, 0, 0, amount as credit from customer_payment where customer_id = '".$invoice[ "customer_id" ]."' and datetime_added>='".date('Y-m-d',strtotime(date_convert($invoice["date_from"])))." 00:00:00' and datetime_added<'".date('Y-m-d',strtotime(date_convert($invoice["date_to"])))." 23:59:59'";
                $rs=doquery($sql,$dblink);
			    if(numrows($rs)>0){
                    $sn=1;
                    while($r=dofetch($rs)){   
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $sn?></td>
                            <td><?php echo date_convert($r["datetime_added"])?></td>
                            <td>AE33</td>
                            <td>2000</td>
                            <td class="text-right"><?php echo $r["quantity"]?></td>
                            <td class="text-right"><?php echo $r["unit_price"]?></td>
                            <td class="text-right"><?php echo $r["debit"]?></td>
                            <td class="text-right"><?php echo $r["credit"]?></td>
                            <td class="text-right">2000</td>
                            <td class="text-right">2000</td>
                        </tr>
                        <?php 
                    $sn++;
                    }
                }
                else{	
                    ?>
                    <tr>
                        <td colspan="10"  class="no-record">No Result Found</td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td colspan="7" class="no-border"></td>
                    <td class="no-border text-right" colspan="2"><strong>NET TOTAL</strong></td>
                    <td class="text-right">2000</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
<?php
die;
}