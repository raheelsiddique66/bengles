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
    <div class="header clear">
        <div class="logo">
            <?php echo get_config("fees_chalan_header");?>
            <p>FROM: <?php echo date_convert($invoice["date_from"])?> TO: <?php echo date_convert($invoice["date_to"])?></p>
        </div>
        <div class="phone">
            <?php echo get_config("address_phone");?>
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
                    <th width="8%" class="text-right">Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "select date as datetime_added, gatepass_id, title, sum(quantity) as quantity, unit_price, unit_price*sum(quantity) as debit, 0 as credit from delivery a left join delivery_items b on a.id = b.delivery_id left join color c on b.color_id = c.id where customer_id = '".$invoice[ "customer_id" ]."' and date>='".date('Y-m-d',strtotime(date_convert($invoice["date_from"])))." 00:00:00' and date<'".date('Y-m-d',strtotime(date_convert($invoice["date_to"])))." 23:59:59' group by color_id,design_id union select datetime_added as datetime_added, '', title, 0, 0, 0, amount as credit from customer_payment a left join account c on a.account_id = c.id where customer_id = '".$invoice[ "customer_id" ]."' and datetime_added>='".date('Y-m-d',strtotime(date_convert($invoice["date_from"])))." 00:00:00' and datetime_added<'".date('Y-m-d',strtotime(date_convert($invoice["date_to"])))." 23:59:59'";
                $rs=doquery($sql,$dblink);
                $total_quantity = 0;
                $total_debit = 0;
                $total_credit = 0;
                $total_balance = 0;
			    if(numrows($rs)>0){
                    $sn=1;
                    $balance = get_customer_balance( $customer["id"], date_convert($invoice["date_from"]));
                    ?>
                    <tr>
                        <td class="text-right" colspan="8"><strong>BALANCE</strong></td>
                        <td class="text-right"><?php echo $balance ?></td>
                    </tr>
                    <?php
                    while($r=dofetch($rs)){   
                        $total_quantity += $r["quantity"];
                        $total_debit += $r["debit"];
                        $total_credit += $r["credit"];
                        $balance += $r["debit"]-$r["credit"];
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $sn?></td>
                            <td><?php echo date_convert($r["datetime_added"])?></td>
                            <td><?php echo $r["gatepass_id"]?></td>
                            <td><?php echo $r["title"]?></td>
                            <td class="text-right"><?php echo $r["quantity"]?></td>
                            <td class="text-right"><?php echo curr_format($r["unit_price"])?></td>
                            <td class="text-right"><?php echo curr_format($r["debit"])?></td>
                            <td class="text-right"><?php echo curr_format($r["credit"])?></td>
                            <td class="text-right"><?php echo curr_format($balance); ?></td>
                        </tr>
                        <?php 
                        $sn++;
                    }
                }
                else{	
                    ?>
                    <tr>
                        <td colspan="9"  class="no-record">No Result Found</td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <th class="text-right" colspan="4">TOTAL</th>
                    <th class="text-right"><?php echo $total_quantity?></th>
                    <th class="text-right"></th>
                    <th class="text-right"><?php echo curr_format($total_debit)?></th>
                    <th class="text-right"><?php echo curr_format($total_credit)?></th>
                    <th class="text-right"><?php echo curr_format($balance)?></th>
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