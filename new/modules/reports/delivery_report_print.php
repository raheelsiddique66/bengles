<?php
if(!defined("APP_START")) die("No Direct Access");
$extra='';
$is_search=true;
if(isset($_GET["date_from"])){
	$date_from=slash($_GET["date_from"]);
	$_SESSION["reports"]["delivery_report"]["date_from"]=$date_from;
}

if(isset($_SESSION["reports"]["delivery_report"]["date_from"]))
	$date_from=$_SESSION["reports"]["delivery_report"]["date_from"];
else
	$date_from=date("01/m/Y");

if($date_from != ""){
	$extra.=" and date>='".date('Y-m-d',strtotime(date_dbconvert($date_from)))." 00:00:00'";
}
if(isset($_GET["date_to"])){
	$date_to=slash($_GET["date_to"]);
	$_SESSION["reports"]["delivery_report"]["date_to"]=$date_to;
}

if(isset($_SESSION["reports"]["delivery_report"]["date_to"]))
	$date_to=$_SESSION["reports"]["delivery_report"]["date_to"];
else
	$date_to=date("d/m/Y");

if($date_to != ""){
	$extra.=" and date<='".date('Y-m-d',strtotime(date_dbconvert($date_to)))." 23:59:59'";
}
if(isset($_GET["customer_id"])){
	$customer_id=slash($_GET["customer_id"]);
	$_SESSION["reports"]["delivery_report"]["customer_id"]=$customer_id;
}
if(isset($_SESSION["reports"]["delivery_report"]["customer_id"]))
	$customer_id=$_SESSION["reports"]["delivery_report"]["customer_id"];
else
	$customer_id="";
if($customer_id!=""){
	$extra.=" and customer_id='".$customer_id."'";
	$is_search=true;
}
?>
<style>
h1, h2, h3, p {
    margin: 0 0 10px;
}

body {
    margin:  0;
    font-family:  Arial;
    font-size:  11px;
}
.head th, .head td{ border:0;}
th, td {
    border: solid 1px #000;
    padding: 5px 5px;
    font-size: 11px;
	vertical-align:top;
}
table table th, table table td{
	padding:3px;
}
table {
    border-collapse:  collapse;
	max-width:1200px;
	margin:0 auto;
}
.text-center{ text-align:center}
.text-right{ text-align:right}
</style>
	<table width="100%" cellspacing="0" cellpadding="0">
        
    	<thead>
            <tr class="head">
                <th colspan="6">
                    <h1><?php echo get_config( 'site_title' )?></h1>
                    <h2>Report</h2>
                    <p>
                        <?php
                        if( !empty( $date_from ) || !empty( $date_to ) ){
                            echo "<br />Date";
                        }
                        if( !empty( $date_from ) ){
                            echo " from ".$date_from;
                        }
                        if( !empty( $date_to ) ){
                            echo " to ".$date_to."<br>";
                        }
                        if( !empty( $customer_id ) ){
                            echo " Customer: ".get_field($customer_id, "customer", "customer_name" );
                        }
                        ?>
                    </p>
                </th>
            </tr>
            <tr>
                <th width="5%" class="text-center">S.no</th>
                <th width="10%">Date</th>
                <th width="10%">Color</th>
                <th width="10%">Design</th>
                <th width="10%">Size</th>
                <th width="10%" class="text-right">Quantity</th>
            </tr>
            <tr>
                <th colspan="6">Incoming</th>
            </tr>
    	</thead>
    	<tbody>
            <?php 
            $sql = "select a.*, b.* from incoming a left join incoming_items b on a.id = b.incoming_id where a.status=1 $extra";
            $rs=doquery( $sql, $dblink );
            if(numrows($rs)>0){
                $sn=1;
				while($r=dofetch($rs)){             
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $sn;?></td>
                        <td><?php echo date_convert($r["date"]); ?></td>
                        <td><?php echo get_field($r["color_id"], "color", "title" ); ?></td>
                        <td><?php echo get_field($r["design_id"], "design", "title" ); ?></td>
                        <td><?php echo get_field($r["size_id"], "size", "title" ); ?></td>
                        <td class="text-right"><?php echo $r["quantity"]; ?></td>
                    </tr>
                    <?php 
                    $sn++;
                }	
            }
            else{	
                ?>
                <tr>
                    <td colspan="6"  class="no-record">No Incoming Found</td>
                </tr>
                <?php
            }
            ?>
    	</tbody>
  	</table>
    <table width="100%" cellspacing="0" cellpadding="0">
    	<thead>
            <tr>
                <th width="5%" class="text-center">S.no</th>
                <th width="10%">Date</th>
                <th width="10%">Color</th>
                <th width="10%">Design</th>
                <th width="10%">Size</th>
                <th width="10%" class="text-right">Quantity</th>
            </tr>
            <tr>
                <th colspan="6">Washing</th>
            </tr>
    	</thead>
    	<tbody>
            <?php 
            $sql = "select a.*, b.* from washing a left join washing_items b on a.id = b.washing_id where a.status=1 $extra";
            $rs=doquery( $sql, $dblink );
            if(numrows($rs)>0){
                $sn=1;
				while($r=dofetch($rs)){             
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $sn;?></td>
                        <td><?php echo date_convert($r["date"]); ?></td>
                        <td><?php echo get_field($r["color_id"], "color", "title" ); ?></td>
                        <td><?php echo get_field($r["design_id"], "design", "title" ); ?></td>
                        <td><?php echo get_field($r["size_id"], "size", "title" ); ?></td>
                        <td class="text-right"><?php echo $r["quantity"]; ?></td>
                    </tr>
                    <?php 
                    $sn++;
                }	
            }
            else{	
                ?>
                <tr>
                    <td colspan="6"  class="no-record">No Washing Found</td>
                </tr>
                <?php
            }
            ?>
    	</tbody>
  	</table>
    <table width="100%" cellspacing="0" cellpadding="0">
    	<thead>
            <tr>
                <th width="5%" class="text-center">S.no</th>
                <th width="10%">Date</th>
                <th width="10%">Color</th>
                <th width="10%">Design</th>
                <th width="10%">Size</th>
                <th width="10%" class="text-right">Quantity</th>
            </tr>
            <tr>
                <th colspan="6">Delivery</th>
            </tr>
    	</thead>
    	<tbody>
            <?php 
            $sql = "select a.*, b.* from delivery a left join delivery_items b on a.id = b.delivery_id where a.status=1 $extra";
            $rs=doquery( $sql, $dblink );
            if(numrows($rs)>0){
                $sn=1;
				while($r=dofetch($rs)){             
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $sn;?></td>
                        <td><?php echo date_convert($r["date"]); ?></td>
                        <td><?php echo get_field($r["color_id"], "color", "title" ); ?></td>
                        <td><?php echo get_field($r["design_id"], "design", "title" ); ?></td>
                        <td><?php echo get_field($r["size_id"], "size", "title" ); ?></td>
                        <td class="text-right"><?php echo $r["quantity"]; ?></td>
                    </tr>
                    <?php 
                    $sn++;
                }	
            }
            else{	
                ?>
                <tr>
                    <td colspan="6"  class="no-record">No Delivery Found</td>
                </tr>
                <?php
            }
            ?>
    	</tbody>
  	</table>
<?php
die;