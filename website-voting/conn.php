<?php
//$con = pg_connect("host=ec2-54-197-232-203.compute-1.amazonaws.com port=5432 dbname=dfr4hedmq20d1m user=kdyztookkckndr password=29846ef8b03eecd8ac209177adab65a0c73f8b3c4b92de2276dcd010b4f69539");
//$db_connection = pg_connect("host=ec2-54-197-232-203.compute-1.amazonaws.com dbname=dfr4hedmq20d1m user=kdyztookkckndr password=29846ef8b03eecd8ac209177adab65a0c73f8b3c4b92de2276dcd010b4f69539");
//$db_connection = pg_connect(getenv("DATABASE_URL"));

$db_url = getenv("DATABASE_URL") ?: "postgres://kdyztookkckndr:29846ef8b03eecd8ac209177adab65a0c73f8b3c4b92de2276dcd010b4f69539@ec2-54-197-232-203.compute-1.amazonaws.com:5432/dfr4hedmq20d1m
";
//echo "$db_url\n";

$db_connection = pg_connect($db_url);
if($db_connection) {echo "connected";} else {echo "not connected";}

$selectSql = "SELECT 1";
$result =  pg_query($db, $selectSql);

while ($row = pg_fetch_row($result)) {
    var_dump($row);
}
?>
