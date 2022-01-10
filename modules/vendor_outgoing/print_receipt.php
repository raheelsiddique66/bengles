<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
    $colors = [];
    $rs2 = doquery("select * from color order by sortorder", $dblink);
    while($r2=dofetch($rs2)){
        $colors[$r2["id"]] = unslash($r2["title_urdu"]);
    }
    $designs = [];
    $rs3 = doquery("select * from design order by sortorder", $dblink);
    while($r3=dofetch($rs3)){
        $designs[$r3["id"]] = unslash($r3["title_urdu"]);
    }
    $sizes = [];
    $rs4 = doquery("select * from size order by sortorder", $dblink);
    while($r4=dofetch($rs4)){
        $sizes[$r4["id"]] = unslash($r4["title"]);
    }
	$vendor_outgoing=dofetch(doquery("select * from vendor_outgoing where id='".slash($_GET["id"])."'", $dblink));
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
.nastaleeq{font-family: 'NafeesRegular'; direction:rtl; unicode-bidi: embed; text-align:right; font-size: 14px;  }
.clearfix:after {
	content: "";
	display: table;
	clear: both;
}
#main {
width:95mm;
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
    <div id="receipt" style="width: 160px">Gatepass No: <strong><?php echo $vendor_outgoing["gatepass_id"]; ?></div>
    <div class="contentbox">
        <p>Date: <strong style="float:right"><?php echo date_convert($vendor_outgoing["date"]); ?></strong></p>
        <p class="">Vendor: <span class="nastaleeq"><strong style="float:right"><?php echo get_field($vendor_outgoing["vendor_id"], "vendor", "vendor_name_urdu" ); ?></strong></span></p>
		<p>Labour: <strong style="float:right"><?php echo get_field( unslash($vendor_outgoing["labour_id"]), "labour", "name" ); ?></strong></p>
        <table class="table table-hover list">
            <thead>
            <tr>
                <td style="text-align: right">Color</td>
                <td style="text-align: right">Design</td>
                <?php
                foreach($sizes as $size){
                    ?>
                    <th class="text-center"><?php echo $size;?></th>
                    <?php
                }
                ?>
                <th class="color3-bg text-center">Total</th>
            </tr>
            </thead>
            <?php
            $rs1 = doquery( "select *, group_concat(concat(size_id, 'x', quantity)) as sizes from vendor_outgoing_items where vendor_outgoing_id='".$vendor_outgoing[ "id" ]."' group by color_id,design_id", $dblink );
            if(numrows($rs1)>0){
                $totals = [];
                foreach($sizes as $size_id => $size){
                    $totals[$size_id] = 0;
                }
                while($r1=dofetch($rs1)){
                    ?>
                    <tr>
                        <td class="nastaleeq"><?php echo $colors[$r1["color_id"]]?></td>
                        <td class="nastaleeq"><?php echo $designs[$r1["design_id"]]?></td>
                        <?php
                        $quantities = [];
                        $t = 0;
                        foreach(explode(",", $r1["sizes"]) as $size){
                            $size = explode("x", $size);
                            $quantities[$size[0]]=$size[1];
                            $t += $size[1];
                        }
                        foreach($sizes as $size_id => $size){
                            $totals[$size_id] += isset($quantities[$size_id])?$quantities[$size_id]:0;
                            ?>
                            <td class="text-right"><?php echo isset($quantities[$size_id])?$quantities[$size_id]:"--";?></td>
                            <?php
                        }
                        ?>
                        <td class="text-right color3-bg"><?php echo $t?></td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td colspan="2">Total</td>
                    <?php
                    $t = 0;
                    foreach($totals as $total){
                        $t += $total;
                        ?>
                        <td class="text-right color3-bg"><?php echo $total?></td>
                        <?php
                    }
                    ?>
                    <td class="text-right color3-bg"><?php echo $t?></td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
<!--    <div id="signcompny">Software developed by wamtSol http://wamtsol.com/ - 0346 3891 662</div> -->
</div>
</body>
</html>
<?php
die;
}
