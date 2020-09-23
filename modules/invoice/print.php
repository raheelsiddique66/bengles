<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
$invoices=doquery("select * from invoice where id='".slash($_GET["id"])."'", $dblink);
    if(numrows($invoices)>0){
        $invoice=dofetch($invoices);
    }
$customers=doquery("select * from customer where id='".slash($invoice["customer_id"])."'", $dblink);
    if(numrows($customers)>0){
        $customer=dofetch($customers);
    }
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
        <!--<div class="phone">
            <?php // echo get_config("address_phone");?>
        </div>!-->
    </div>
    <div class="content">
    	
        <div class="number clear">
        	<div class="invoice_number">
                <div class="detail_box">
                    <h3><?php echo unslash($customer["customer_name"]);?></h3>
                    <div class="detail_box_inner">
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
                    <th width="10%">Gatepass</th>
                    <th width="10%">Account</th>
                    <th width="5%" class="text-right">Qty</th>
                    <th width="5%" class="text-right">Rate</th>
                    <th width="8%" class="text-right">Debit</th>
                    <th width="8%" class="text-right">Credit</th>
                    <th width="8%" class="text-right">discount</th>
                    <th width="8%" class="text-right">Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "select date as datetime_added, gatepass_id, title, sum(quantity) as quantity, unit_price, unit_price*sum(quantity) as debit, 0 as credit, 0 as discount from delivery a left join delivery_items b on a.id = b.delivery_id left join color c on b.color_id = c.id where customer_id = '".$invoice[ "customer_id" ]."'".($invoice["machine_id"]>0?" and b.machine_id='".$invoice["machine_id"]."'":"")." and date>='".date('Y-m-d',strtotime($invoice["date_from"]))." 00:00:00' and date<'".date('Y-m-d',strtotime($invoice["date_to"]))." 23:59:59' group by color_id,design_id union select datetime_added as datetime_added, '', title, 0, 0, 0, amount as credit, discount as discount from customer_payment a left join account c on a.account_id = c.id where customer_id = '".$invoice[ "customer_id" ]."'".($invoice["machine_id"]>0?" and a.machine_id='".$invoice["machine_id"]."'":"")." and datetime_added>='".date('Y-m-d',strtotime($invoice["date_from"]))." 00:00:00' and datetime_added<'".date('Y-m-d',strtotime($invoice["date_to"]))." 23:59:59' order by datetime_added";
                //echo $sql;die;
                $rs=doquery($sql,$dblink);
                $total_quantity = 0;
                $total_debit = 0;
                $total_credit = 0;
                $total_balance = 0;
                $total_discount = 0;
                $balance = 0;
                $total_credit_discount = 0;
			    if(numrows($rs)>0){
                    $sn=1;
                    //print_r($invoice);die;
                    $sql="select sum(amount) as amount from (select sum(unit_price * quantity) as amount from delivery a left join delivery_items b on a.id = b.delivery_id where customer_id = '".$customer[ "id" ]."'".($invoice["machine_id"]>0?" and b.machine_id='".$invoice["machine_id"]."'":"")." and date<'".date('Y-m-d',strtotime($invoice["date_from"]))."' union select -sum(amount) from customer_payment where customer_id = '".$customer[ "id" ]."'".($invoice["machine_id"]>0?" and machine_id='".$invoice["machine_id"]."'":"")." and datetime_added<='".date('Y-m-d',strtotime($invoice["date_from"]))." 00:00:00') as transactions ";
                    $balance=dofetch(doquery($sql,$dblink));
                    $balance = $customer["balance"]+$balance[ "amount" ];
                    //$balance = get_customer_balance( $customer["id"], date('Y-m-d',strtotime($invoice["date_to"])));
                    ?>
                    <tr>
                        <td class="text-right" colspan="9"><strong>BALANCE</strong></td>
                        <td class="text-right"><?php echo curr_format($balance) ?></td>
                    </tr>
                    <?php
                    $accounts = [];
                    while($r=dofetch($rs)){   
                        $total_quantity += $r["quantity"];
                        $total_debit += $r["debit"];
                        $total_credit += $r["credit"];
                        $total_discount += $r["discount"];
                        $total_credit_discount = $r["credit"]-$r["discount"];
                        echo $total_credit_discount;
                        $balance += $r["debit"]-$r["credit"]-$r["discount"];

                        if(!isset($accounts[$r["title"].$r["unit_price"]])){
                            $accounts[$r["title"].$r["unit_price"]] = [
                                    "title" => $r["title"],
                                "rate" => $r["unit_price"],
                                "quantity" => 0
                            ];
                        }
                        $accounts[$r["title"].$r["unit_price"]]["quantity"] += $r["quantity"];
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
                            <td class="text-right"><?php echo curr_format($r["discount"])?></td>
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
                    <th class="text-right"><?php echo curr_format($total_discount)?></th>
                    <th class="text-right"><?php echo curr_format($balance)?></th>
                </tr>
            </tbody>
        </table>
        <div class="summary" style="padding-top: 20px">
            <?php
            foreach($accounts as $account){
                echo $account["title"]. " (".$account["quantity"]."x".$account["rate"].") = ".curr_format($account["quantity"]*$account["rate"])."<br><br>";
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>
<?php
die;
}
