<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["ids"]) && !empty($_GET["ids"])){
    $invoices = [];
    $invoice_ids = [];
    $customer_ids = [];
    $rs=doquery("select * from invoice where id in (".slash($_GET["ids"]).") order by datetime_added", $dblink);
    if(numrows($rs)>0){
        while($invoice=dofetch($rs)){
            $invoices[] = $invoice;
            $invoice_ids[] = $invoice["id"];
            $customer_ids[] = $invoice["customer_id"];
        }
    }
    $customer=dofetch(doquery("select * from customer where id = '".$invoices[0]["customer_id"]."'", $dblink));
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
            <p>FROM: <?php echo date_convert($invoices[0]["date_from"])?> TO: <?php echo date_convert($invoices[count($invoices)-1]["date_to"])?></p>
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
                <p>DATE: <?php echo datetime_convert($invoices[0]["datetime_added"])?></p>
                <p>Invoice # <?php echo implode(", ", $invoice_ids)?></p>
            </div>
        </div>
        <table width="100%" cellpadding="0" cellspacing="0">
        	<thead>
                <tr>
                    <th width="12%" class="text-right nastaleeq"> ٹوٹل رقم</th>
                    <th width="12%" class="text-right nastaleeq"> پیکج</th>
                    <th width="12%" class="text-right nastaleeq"> وائرس رقم</th>
                    <th width="12%" class="text-right nastaleeq"> اضافی رعایت</th>
                    <th width="8%" class="text-right nastaleeq">کلیم/RF/ڈسکاؤنٹ/نقصان</th>
                    <th width="8%" class="text-right nastaleeq">جمع</th>
                    <th width="8%" class="text-right nastaleeq">نام</th>
                    <th width="5%" class="text-right nastaleeq">ریٹ</th>
                    <th width="5%" class="text-right nastaleeq">تعداد</th>
                    <th width="5%" class="text-right nastaleeq">وائرس</th>
                    <th width="10%" class="text-right nastaleeq">آئٹم</th>
                    <th width="10%" class="text-right nastaleeq">پلانٹ</th>
                    <th width="8%" class="text-right nastaleeq">گیٹ پاس</th>
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
            $total_extra_discount = 0;
            $total_virus = 0;
            $total_package = 0;
            $total_credit_discount = 0;
            $sql="select sum(amount) as amount from (select sum(unit_price * quantity) as amount from delivery a left join delivery_items b on a.id = b.delivery_id where customer_id in (".implode(",", $customer_ids).") and date<'".date('Y-m-d',strtotime($invoices[0]["date_from"]))."' union select -sum(amount)-sum(discount) as amount from customer_payment where customer_id in (".implode(",", $customer_ids).") and datetime_added<='".date('Y-m-d',strtotime($invoices[0]["date_from"]))." 00:00:00') as transactions ";
            $balance=dofetch(doquery($sql,$dblink));
            $customer_balance=doquery("select * from customer where id in (".implode(",", $customer_ids).")", $dblink);
            $balances = 0;
            if(numrows($customer_balance)>0){
                while($customer_bal = dofetch($customer_balance)){
                    $balances +=  $customer_bal["balance"];
                }
            }
            $balance = $balances+$balance[ "amount" ];
            ?>
            <tr>
                <td class="text-right"><?php echo curr_format($balance) ?></td>
                <td class="text-left" colspan="10"><strong class="nastaleeq">سابقہ</strong></td>
            </tr>
                <?php
                $sql = "select date as datetime_added, gatepass_id, title_urdu, sum(quantity) as quantity, unit_price, unit_price*sum(quantity) as debit, 0 as credit, 0 as discount, 0 as extra_discount, 0 as virus, 0 as package, '' as details, 0 as claim, b.machine_id from delivery a left join delivery_items b on a.id = b.delivery_id left join color c on b.color_id = c.id where customer_id in (".implode(",", $customer_ids).") and date>='".date('Y-m-d',strtotime($invoices[0]["date_from"]))." 00:00:00' and date<'".date('Y-m-d',strtotime($invoices[count($invoices)-1]["date_to"]))." 23:59:59' group by delivery_id,color_id union select datetime_added as datetime_added, '', title_urdu, 0, 0, 0, amount as credit, discount as discount, extra_discount as extra_discount, virus as virus, package as package, details, claim, a.machine_id from customer_payment a left join account c on a.account_id = c.id where customer_id in (".implode(",", $customer_ids).") and datetime_added>='".date('Y-m-d',strtotime($invoices[0]["date_from"]))." 00:00:00' and datetime_added<'".date('Y-m-d',strtotime($invoices[count($invoices)-1]["date_to"]))." 23:59:59' order by datetime_added";
                //echo $sql;die;
                $rs=doquery($sql,$dblink);
			    if(numrows($rs)>0){
                    $sn=1;
                    $accounts = [];
                    while($r=dofetch($rs)){   
                        //print_r($r);
                        $total_quantity += $r["quantity"];
                        $total_claim += $r["claim"];
                        $total_debit += $r["debit"];
                        $total_credit += $r["credit"];
                        $total_discount += $r["discount"];
                        $total_credit_discount = $r["credit"]-$r["discount"];
                        $balance += $r["debit"]-$r["credit"]-$r["discount"]-$r["extra_discount"]-$r["virus"]-$r["package"];
                        $total_extra_discount += $r["extra_discount"];
                        $total_virus += $r["virus"];
                        $total_package += $r["package"];
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
                            <td class="text-right"><?php echo curr_format($r["package"])?></td>
                            <td class="text-right"><?php echo curr_format($r["virus"])?></td>
                            <td class="text-right"><?php echo curr_format($r["extra_discount"])?></td>
                            <td class="text-right"><?php echo curr_format($r["discount"])?></td>
                            <td class="text-right"><?php echo curr_format($r["credit"])?></td>
                            <td class="text-right"><?php echo curr_format($r["debit"])?></td>
                            <td class="text-right"><?php echo curr_format($r["unit_price"])?></td>
                            <td class="text-right"><?php echo $r["quantity"]?></td>
                            <td class="text-right"><?php echo $r["claim"]?></td>
                            <td class="nastaleeq"><?php echo unslash($r["title_urdu"]);?><span style="display: block;font-size: 12px;"><?php echo unslash($r["details"]);?></span></td>
                            <td class="nastaleeq"><?php echo get_field($r["machine_id"], "machine", "title");?></td>
                            <td><?php echo $r["gatepass_id"]?></td>
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
                        <td colspan="12"  class="no-record" style="color: #fff">No Result Found</td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <th class="text-right"><?php echo curr_format($balance)?></th>
                    <th class="text-right"><?php echo curr_format($total_package)?></th>
                    <th class="text-right"><?php echo curr_format($total_virus)?></th>
                    <th class="text-right"><?php echo curr_format($total_extra_discount)?></th>
                    <th class="text-right"><?php echo curr_format($total_discount)?></th>
                    <th class="text-right"><?php echo curr_format($total_credit)?></th>
                    <th class="text-right"><?php echo curr_format($total_debit)?></th>
                    <th class="text-right"></th>
                    <th class="text-right"><?php echo $total_quantity?></th>
                    <th class="text-right"><?php echo $total_claim?></th>
                    <th class="text-left" colspan="4"><span class="nastaleeq">ٹوٹل</span></th>
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
