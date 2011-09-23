
<?php 

if($_SERVER['REQUEST_METHOD']=='GET')
{	$userid=$_GET['userid'];
$db=@mysql_connect("zhenlingtsaicom.fatcowmysql.com", "burger", "burger");
mysql_select_db("cs3216",$db);

$q=mysql_query("SELECT * from orders where user_id='$userid' order by time desc");
while($e=mysql_fetch_assoc($q))
        $output[]=$e;

echo json_encode($output);

mysql_close();

}
else
{

if($_SERVER['REQUEST_METHOD']=='POST')
{
$userid=$_POST["user_id"];
$dest=$_POST["destination"];

$db=@mysql_connect("zhenlingtsaicom.fatcowmysql.com", "burger", "burger");
mysql_select_db("cs3216",$db);
$q=mysql_query("INSERT INTO orders (  id , user_id ,  destination ) 
VALUES (NULL ,  '$userid',  '$dest' )");

$t=mysql_query("SELECT LAST_INSERT_ID() FROM orders");

$e=mysql_fetch_assoc($t);

$output['order_id']=$e['LAST_INSERT_ID()'];

echo json_encode($output);
}
}

?>