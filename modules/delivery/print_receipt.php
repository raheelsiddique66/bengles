<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$delivery=dofetch(doquery("select * from delivery where id='".slash($_GET["id"])."'", $dblink));
	?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Gatepass</title>
<style>
@font-face {
    font-family: 'NafeesRegular';
    src: url('fonts/NafeesRegular.ttf') format('truetype');
    font-weight: normal;
    font-style: normal;

}
.clearfix:after {
	content: "";
	display: table;
	clear: both;
}
#main {
width:71mm;
border:0;
}
a {
	color: #5D6975;
	text-decoration: underline;
}
body {
	position: relative;
	margin: 0;
	color: #000;
	font-size: 12px;
	font-family: Arial, Helvetica, sans-serif;
	padding: 0px
}
p{margin:0 0 5px 0}
#logo img {
    width: 140px;
}
table {
	width: 100%;
	border-collapse: collapse;
	border-spacing: 0;
	margin-bottom: 10px;
}
table tr:nth-child(2n-1) td {
	background: #F5F5F5;
}
table th, table td {
	text-align: left;
}
table th {
    border: 1px solid #fff;
    color: #fff;
    font-weight: bold;
    line-height: 0.9em;
    padding: 10px 0;
    text-align: center;
	background-color:#000;
    white-space: nowrap;
}
.data-table td{border:1px solid #afafaf;}
.data-table td strong{text-align:right;display:block}
.tableamount {
	text-align: right;
}
table .service, table .desc {
	text-align: left;
}
table td {
	text-align: right;
	padding-top: 8px;
	padding-right: 2px;
	padding-bottom: 8px;
	padding-left: 2px;
	font-size:11px;
}
table tr{ font-size:10px}
table td.service, table td.desc {
	vertical-align: top;
}
#signcompny {
    border-top: thin solid #000;
    margin: 12px 0 0;
    padding-top: 5px;
    text-align: center;
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
.contentbox{display:block}

#logo {
    border-radius: 3px;
    display: block;
    font-size: 26px;
    font-weight: bold;
    padding: 2px 15px;
	text-align: center;
}
#receipt {
    border: 1px solid;
    border-radius: 3px;
    display: block;
    font-size: 14px;
    font-weight: bold;
    line-height: 13px;
    margin: 10px auto 16px;
    padding: 5px;
    text-align: center;
    width: 82px;
	text-transform: uppercase;
}
#logo span {
    line-height: 20px;
}
.address{
	text-align:center;
	display:block;
	font-size:11px;
}
</style>
		<script>
		function print_page(){
			if(jsPrintSetup){
				printer = '<?php echo get_config( 'thermal_printer_title' );?>';
				printers = jsPrintSetup.getPrintersList().split(",");
				if( printers.indexOf( printer ) !== -1 ) {
					jsPrintSetup.setPrinter( printer );
					jsPrintSetup.setOption('orientation', jsPrintSetup.kPortraitOrientation);
					// set top margins in millimeters
					jsPrintSetup.setOption('marginTop', 0);
					jsPrintSetup.setOption('marginBottom', 0);
					jsPrintSetup.setOption('marginLeft', 0);
					jsPrintSetup.setOption('marginRight', 0);
					// set page header
					jsPrintSetup.setOption('headerStrLeft', '');
					jsPrintSetup.setOption('headerStrCenter', '');
					jsPrintSetup.setOption('headerStrRight', '');
					// set empty page footer
					jsPrintSetup.setOption('footerStrLeft', '');
					jsPrintSetup.setOption('footerStrCenter', '');
					jsPrintSetup.setOption('footerStrRight', '');
					jsPrintSetup.setOption('printBGColors', 1);
					// Suppress print dialog
					jsPrintSetup.setSilentPrint(true);
					// Do Print
					jsPrintSetup.printWindow(window);
					// Restore print dialog
					//jsPrintSetup.setSilentPrint(false);
				}
				else {
					alert( printer + " is not installed." );
				}
			}
			else{
				window.print();
			}
		}
        </script>
</head>
<body onload="print_page();">
<div id="main">
    <div id="logo">
    	<?php $reciept_logo=get_config("reciept_logo"); if(empty($reciept_logo)) echo $site_title; else { ?><img src="<?php echo $file_upload_root;?>config/<?php echo $reciept_logo?>" /><?php }?>
    </div>
    <span class="address"><?php echo get_config("address_phone")?></span>
    <div id="receipt" style="width: 160px">Gatepass No: <strong><?php echo $delivery["id"]; ?></div>
    <div class="contentbox">
        <p>Date: <strong style="float:right"><?php echo date_convert($delivery["date"]); ?></strong></p>
		<p>Customer: <strong style="float:right"><?php echo get_field($delivery["customer_id"], "customer", "customer_name" ); ?></strong></p>
		<p>Labour: <strong style="float:right"><?php echo get_field( unslash($delivery["labour_id"]), "labour", "name" ); ?></strong></p>
        <table cellpadding="0" cellspacing="0" align="center" width="800" border="0" class="items">
            <tr>
                <th width="5%">S#</th>
                <th width="20%">Color</th>
                <th width="20%">Design</th>
				<th width="20%">Size</th>
                <th width="10%">Qty</th>
				<th width="10%">Price</th>
				<th width="10%">Total</th>
            </tr>
            <?php
            $items=doquery("select * from delivery_items where delivery_id='".$delivery["id"]."' order by id", $dblink);
            if(numrows($items)>0){
				$sn=1;
				$total_quantity = 0;
				$total_price = 0;
				$total = 0;
                while($item=dofetch($items)){
					$total_quantity += $item["quantity"];
					$total = $item["unit_price"]*$item["quantity"];
					$total_price += $total;
                    ?>
                    <tr>
                    	<td style="text-align:center"><?php echo $sn++?></td>
                        <td style="text-align:left;"><?php echo get_field($item["color_id"], "color", "title" );?></td>
						<td style="text-align:left;"><?php echo get_field($item["design_id"], "design", "title" );?></td>
						<td style="text-align:left;"><?php echo get_field($item["size_id"], "size", "title" );?></td>
                        <td style="text-align:center; font-size:9px;"><?php echo $item["quantity"]?></td>
						<td style="text-align:center; font-size:9px;"><?php echo $item["unit_price"]?></td>
						<td style="text-align:center; font-size:9px;"><?php echo $total?></td>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>
		<hr style="border:0; border-top:1px solid #999">
        <p><strong>TOTAL Qty</strong><strong style="float:right"><?php echo $total_quantity?></strong></p>
		<p><strong>TOTAL Price</strong><strong style="float:right"><?php echo $total_price?></strong></p>
    </div>
    <div id="signcompny">Software developed by wamtSol http://wamtsol.com/ - 0346 3891 662</div> 
</div>
</body>
</html>
<?php
die;
}