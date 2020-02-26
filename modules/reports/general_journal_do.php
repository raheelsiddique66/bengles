<?php
if(!defined("APP_START")) die("No Direct Access");
$extra='';
$is_search=true;
if(isset($_GET["date_from"])){
	$date_from=slash($_GET["date_from"]);
	$_SESSION["reports"]["general_journal"]["date_from"]=$date_from;
}
if(isset($_SESSION["reports"]["general_journal"]["date_from"]))
	$date_from=$_SESSION["reports"]["general_journal"]["date_from"];
else
	$date_from=date("01/m/Y");
if(isset($_GET["date_to"])){
	$date_to=slash($_GET["date_to"]);
	$_SESSION["reports"]["general_journal"]["date_to"]=$date_to;
}
if(isset($_SESSION["reports"]["general_journal"]["date_to"]))
	$date_to=$_SESSION["reports"]["general_journal"]["date_to"];
else
	$date_to=date("d/m/Y");
if(isset($_GET["account_id"])){
	$account_id=slash($_GET["account_id"]);
	$_SESSION["reports"]["general_journal"]["account_id"]=$account_id;
}
if(isset($_SESSION["reports"]["general_journal"]["account_id"]))
	$account_id=$_SESSION["reports"]["general_journal"]["account_id"];
else
	$account_id="";
if( !empty( $date_from ) && !empty( $date_from ) ) {
	$extra.=" and date BETWEEN '".date('Y-m-d',strtotime(date_dbconvert($date_from)))." 00:00:00' AND '".date('Y-m-d',strtotime(date_dbconvert($date_to)))." 23:59:59'";
}
if( !empty( $account_id ) ) {
	$account = dofetch( doquery( "select * from account where id='".$account_id."'", $dblink ) );
}
else {
	$account = dofetch( doquery( "select * from account where is_petty_cash='1'", $dblink ) );
	$account_id = $account[ "id" ];
}

$order_by = "date";
$order = "desc";
if( isset($_GET["order_by"]) ){
	$_SESSION["reports"]["general_journal"]["order_by"]=slash($_GET["order_by"]);
}
if( isset( $_SESSION["reports"]["general_journal"]["order_by"] ) ){
	$order_by = $_SESSION["reports"]["general_journal"]["order_by"];
}
if( isset($_GET["order"]) ){
	$_SESSION["reports"]["general_journal"]["order"]=slash($_GET["order"]);
}
if( isset( $_SESSION["reports"]["general_journal"]["order"] ) ){
	$order = $_SESSION["reports"]["general_journal"]["order"];
}
$orderby = $order_by." ".$order;
$main_sql = array();
$main_sql[] = "select date as date, concat( 'Delivery ', gatepass_id ) as details, unit_price as debit, 0 as credit from delivery_items a left join delivery b on a.delivery_id=b.id where b.status=1";
$main_sql[] = "select datetime_added as date, if(details='', concat( 'Paid ', title ), concat(title, ': ', details)) as details, 0 as debit, amount as credit from expense a left join expense_category b on a.expense_category_id=b.id where a.status=1".(!empty($account_id)?" and account_id='".$account_id."'":"");
$main_sql[] = "select datetime_added as date, if(details='', concat( 'Transfer from account ', title ), details) as details, amount as debit, 0 as credit from transaction a left join account b on a.reference_id=b.id where a.status=1".(!empty($account_id)?" and account_id='".$account_id."'":"");
$main_sql[] = "select datetime_added as date, if(details='', concat( 'Transfer to account ', title ), concat(title, ': ', details)) as details, 0 as debit, amount as credit from transaction a left join account b on a.account_id=b.id where a.status=1".(!empty($account_id)?" and reference_id='".$account_id."'":"");
$main_sql="(".implode( ' union ', $main_sql ).") as total_records";
$sql = "select * from ".$main_sql." where 1 $extra order by $orderby, details $order";

$balance = dofetch( doquery( "select sum(debit)-sum(credit) as balance from ".$main_sql." where date < '".date('Y-m-d',strtotime(date_dbconvert($date_from)))." 00:00:00'", $dblink ) );
if( $order == 'desc' ) {
	$balance = get_account_balance( $account_id, date_dbconvert($date_to)." 23:59:59" );
}
else{
	$balance = get_account_balance( $account_id, date_dbconvert($date_from) );
}
