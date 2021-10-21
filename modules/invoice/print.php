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
    <style>
        .nastaleeq {
            font-family: 'NafeesRegular';
            direction: rtl;
            unicode-bidi: embed;
            text-align: center;
            font-size: 16px;
            margin: 5px 0;
        }
        .summary{
            text-align: right;
        }
        td, th {
            border: solid 1px #000;
            padding: 6px 10px;
            text-align: left;
            font-size: 22px;
        }
    </style>
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
                    <div class="detail_box_inner">
                        <p><?php echo unslash($customer["address"]);?></p>
                        <p><?php echo unslash($customer["phone"]);?></p>
                    </div>
                </div>
                <div class="text-center" style="display: inline-block;width: 50%;"><h3 class="urdu_text"><?php echo unslash($customer["customer_name_urdu"]);?></h3></div>
            </div>
            <div class="order_number">
                <div class="sale_tax">
                    <h1> Bill</h1>
                </div>
                <p>DATE: <?php echo datetime_convert($invoice["datetime_added"])?></p>
                <p>Invoice # <?php echo $invoice["id"]?></p>
            </div>
        </div>
        <table width="100%" cellpadding="0" cellspacing="0">
        	<thead>
                <tr>
                    <th width="12%" class="text-right nastaleeq"> ٹوٹل رقم</th>
                    <?php if($_SERVER['SERVER_NAME'] != 'jibran.burhanpk.com'){?><th width="8%" class="text-right nastaleeq">کلیم/RF/ڈسکاؤنٹ/نقصان</th><?php }?>
                    <th width="8%" class="text-right nastaleeq">جمع</th>
                    <th width="8%" class="text-right nastaleeq">نام</th>
                    <th width="5%" class="text-right nastaleeq">ریٹ</th>
                    <th width="5%" class="text-right nastaleeq">تعداد</th>
                    <?php if($_SERVER['SERVER_NAME'] != 'jibran.burhanpk.com'){?><th width="5%" class="text-right nastaleeq">وائرس</th><?php }?>
                    <th width="10%" class="text-right nastaleeq">آئٹم</th>
                    <th width="10%" class="text-right nastaleeq">پلانٹ</th>
                    <?php if($_SERVER['SERVER_NAME'] != 'jibran.burhanpk.com'){?><th width="8%" class="text-right nastaleeq">گیٹ پاس</th><?php }?>
                    <th width="10%" class="text-right nastaleeq">تاریخ</th>
                    <th width="2%" class="text-center nastaleeq">سیریل</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $total_quantity = 0;
            $total_claim = 0;
            $total_debit = 0;
            $total_credit = 0;
            $total_balance = 0;
            $total_discount = 0;
            $balance = 0;
            $total_credit_discount = 0;
            $sql="select sum(amount) as amount from (select sum(unit_price * quantity) as amount from delivery a left join delivery_items b on a.id = b.delivery_id where customer_id = '".$customer[ "id" ]."'".($invoice["machine_id"]>0?" and b.machine_id='".$invoice["machine_id"]."'":"")." and date<'".date('Y-m-d',strtotime($invoice["date_from"]))."' union select -sum(amount)-sum(discount) as amount from customer_payment where customer_id = '".$customer[ "id" ]."'".($invoice["machine_id"]>0?" and machine_id='".$invoice["machine_id"]."'":"")." and datetime_added<='".date('Y-m-d',strtotime($invoice["date_from"]))." 00:00:00') as transactions ";
            $balance=dofetch(doquery($sql,$dblink));
            $balance = $customer["balance"]+$balance[ "amount" ];
            ?>
            <tr>
                <td class="text-right"><?php echo curr_format($balance) ?></td>
                <td class="text-left" colspan="10"><strong class="nastaleeq">سابقہ</strong></td>
            </tr>
                <?php
                $sql = "select date as datetime_added, gatepass_id, title_urdu, sum(quantity) as quantity, unit_price, unit_price*sum(quantity) as debit, 0 as credit, 0 as discount, '' as details, 0 as claim, b.machine_id from delivery a left join delivery_items b on a.id = b.delivery_id left join color c on b.color_id = c.id where customer_id = '".$invoice[ "customer_id" ]."'".($invoice["machine_id"]>0?" and b.machine_id='".$invoice["machine_id"]."'":"")." and date>='".date('Y-m-d',strtotime($invoice["date_from"]))." 00:00:00' and date<'".date('Y-m-d',strtotime($invoice["date_to"]))." 23:59:59' group by delivery_id,color_id union select datetime_added as datetime_added, '', title_urdu, 0, 0, 0, amount as credit, discount as discount, details, claim, '' from customer_payment a left join account c on a.account_id = c.id where customer_id = '".$invoice[ "customer_id" ]."'".($invoice["machine_id"]>0?" and a.machine_id='".$invoice["machine_id"]."'":"")." and datetime_added>='".date('Y-m-d',strtotime($invoice["date_from"]))." 00:00:00' and datetime_added<'".date('Y-m-d',strtotime($invoice["date_to"]))." 23:59:59' order by datetime_added";
                //echo $sql;die;
                $rs=doquery($sql,$dblink);

			    if(numrows($rs)>0){
                    $sn=1;
                    //print_r($invoice);die;

                    //$balance = get_customer_balance( $customer["id"], date('Y-m-d',strtotime($invoice["date_to"])));
                    ?>

                    <?php
                    $accounts = [];
                    while($r=dofetch($rs)){   
                        $total_quantity += $r["quantity"];
                        $total_claim += $r["claim"];
                        $total_debit += $r["debit"];
                        $total_credit += $r["credit"];
                        $total_discount += $r["discount"];
                        $total_credit_discount = $r["credit"]-$r["discount"];
//                        echo $total_credit_discount;
                        $balance += $r["debit"]-$r["credit"]-$r["discount"];

                        if(!isset($accounts[$r["title_urdu"].$r["unit_price"]])){
                            $accounts[$r["title_urdu"].$r["unit_price"]] = [
                                    "title_urdu" => $r["title_urdu"],
                                "rate" => $r["unit_price"],
                                "quantity" => 0
                            ];
                        }
                        $accounts[$r["title_urdu"].$r["unit_price"]]["quantity"] += $r["quantity"];
                        ?>
                        <tr>
                            <td class="text-right"><?php echo curr_format($balance); ?></td>
                            <?php if($_SERVER['SERVER_NAME'] != 'jibran.burhanpk.com'){?><td class="text-right"><?php echo curr_format($r["discount"])?></td><?php }?>
                            <td class="text-right"><?php echo curr_format($r["credit"])?></td>
                            <td class="text-right"><?php echo curr_format($r["debit"])?></td>
                            <td class="text-right"><?php echo curr_format($r["unit_price"])?></td>
                            <td class="text-right"><?php echo $r["quantity"]?></td>
                            <?php if($_SERVER['SERVER_NAME'] != 'jibran.burhanpk.com'){?><td class="text-right"><?php echo $r["claim"]?></td><?php }?>
                            <td class="nastaleeq"><?php echo unslash($r["title_urdu"]);?><span style="display: block;font-size: 12px;"><?php echo unslash($r["details"]);?></span></td>
                            <td class="nastaleeq"><?php echo get_field($r["machine_id"], "machine", "title");?></td>
                            <?php if($_SERVER['SERVER_NAME'] != 'jibran.burhanpk.com'){?><td><?php echo $r["gatepass_id"]?></td><?php }?>
                            <td><?php echo date_convert($r["datetime_added"])?></td>
                            <td class="text-center"><?php echo $sn?></td>
                        </tr>
                        <?php 
                        $sn++;
                    }
                }
                else{	
                    ?>
                    <tr>
                        <td colspan="11"  class="no-record" style="color: #fff">No Result Found</td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <th class="text-right"><?php echo curr_format($balance)?></th>
            <?php if($_SERVER['SERVER_NAME'] != 'jibran.burhanpk.com'){?> <th class="text-right"><?php echo curr_format($total_discount)?></th><?php }?>
                    <th class="text-right"><?php echo curr_format($total_credit)?></th>
                    <th class="text-right"><?php echo curr_format($total_debit)?></th>
                    <th class="text-right"></th>
                    <th class="text-right"><?php echo $total_quantity?></th>
                    <th class="text-right"><?php echo $total_claim?></th>
                    <th class="text-left" colspan="5"><span class="nastaleeq">ٹوٹل</span></th>
                </tr>
            </tbody>
        </table>
        <div class="summary" style="padding-top: 20px">
            <?php
                if(numrows($rs)>0){
                        foreach($accounts as $account){?>
                            <span class="nastaleeq"><?php echo unslash($account["title_urdu"]);?></span>
                            <?php
                            echo " (".$account["quantity"]."x".$account["rate"].") = ".curr_format($account["quantity"]*$account["rate"])."<br><br>";
                        }
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
