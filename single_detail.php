
<?php 

if($_SERVER['REQUEST_METHOD']=='GET')
{	$order_id=$_GET['orderid'];
$db=@mysql_connect("zhenlingtsaicom.fatcowmysql.com", "burger", "burger");
mysql_select_db("cs3216",$db);

$q=mysql_query("SELECT * FROM single where order_id='$order_id'");
while($e=mysql_fetch_assoc($q))
        $output[]=$e;

echo json_encode($output);

mysql_close();

}
else
{

if($_SERVER['REQUEST_METHOD']=='POST')
{
$orderid=$_POST["orderid"];
$content=$_POST["content"];
$cost=$_POST["cost"];

$db=@mysql_connect("zhenlingtsaicom.fatcowmysql.com", "burger", "burger");
mysql_select_db("cs3216",$db);
$q=mysql_query("INSERT INTO single (  id , order_id ,  content, cost ) 
VALUES (NULL ,  '$orderid',  '$content', '$cost' )");

$t=mysql_query("SELECT LAST_INSERT_ID() FROM orders");

$e=mysql_fetch_assoc($t);

$output['single_id']=$e['LAST_INSERT_ID()'];

echo json_encode($output);
}
}

?>