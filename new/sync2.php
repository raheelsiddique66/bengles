<?php
include("include/db.php");
include("include/utility.php");
define("APP_START", 1);
if( isset( $_POST[ "sql" ] ) && !empty($_POST[ "sql" ]) ){
	file_put_contents( "sql.txt", $_POST[ "sql" ] );
	$s = mysqli_multi_query( $dblink, $_POST[ "sql" ]);
	echo "success";
	//var_dump($s);
}
