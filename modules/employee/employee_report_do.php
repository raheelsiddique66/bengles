<?php
if(isset($_GET["id"]) && $_GET["id"]!=""){
    $rs1=doquery("select * from employees where id='".slash($_GET["id"])."'",$dblink);
    if(numrows($rs1)>0){
        $employee=dofetch($rs1);
    }
}
$q="";
$extra='';
$is_search=false;
if(isset($_GET["date_from"])){
    $date_from=slash($_GET["date_from"]);
    $_SESSION["employee_manage"]["report"][ "date_from" ]=$date_from;
}
if(isset($_SESSION["employee_manage"]["report"][ "date_from" ]))
    $date_from=$_SESSION["employee_manage"]["report"][ "date_from" ];
else
    $date_from=date("01/m/Y");
if(!empty($date_from)){
    $extra.=" and date>='".date('Y-m-d',strtotime(date_dbconvert($date_from)))." 00:00:00'";
    $is_search=true;
}
if(isset($_GET["date_to"])){
    $date_to=slash($_GET["date_to"]);
    $_SESSION["employee_manage"]["report"][ "date_to" ]=$date_to;
}
if(isset($_SESSION["employee_manage"]["report"][ "date_to" ]))
    $date_to=$_SESSION["employee_manage"]["report"][ "date_to" ];
else
    $date_to=date("d/m/Y");
if(!empty($date_to)){
    $extra.=" and date<'".date('Y-m-d',strtotime(date_dbconvert($date_to)))." 23:59:59'";
    $is_search=true;
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