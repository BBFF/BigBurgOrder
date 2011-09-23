<?php 

if($_SERVER['REQUEST_METHOD']=='GET')
{

$contact=$_GET['contact'];

$db=@mysql_connect("zhenlingtsaicom.fatcowmysql.com", "burger", "burger");
mysql_select_db("cs3216",$db);

$q=mysql_query("SELECT * FROM user where contact='$contact'");
while($e=mysql_fetch_assoc($q))
        $output[]=$e;
header( 'Content-Type: application/json' );
print(json_encode($output));

mysql_close();
}
else
{

   if($_SERVER['REQUEST_METHOD']=='POST')
   {
     $ctact=$_POST["contact"];
     $nm=$_POST["name"];
     $db=@mysql_connect("zhenlingtsaicom.fatcowmysql.com", "burger", "burger");
     mysql_select_db("cs3216",$db);

     $q=mysql_query("INSERT INTO  user (  id , name ,  contact ) 
     VALUES (NULL ,  '$nm',  '$ctact')");

     $t=mysql_query("SELECT LAST_INSERT_ID() FROM user");

     $e=mysql_fetch_assoc($t);

     $output['user_id']=$e['LAST_INSERT_ID()'];

     echo json_encode($output);
   }

   if($_SERVER['REQUEST_METHOD']=='PUT')
   {
       //parse_str(file_get_contents("php://input"),$data);
       //$data=json_decode(file_get_contents("php://input"),true);
       $data=readfile("php://input");
    //   $data=$this->request->body();
       $ctact=$data->contact;
       $nm=$data->name;
       $db=@mysql_connect("zhenlingtsaicom.fatcowmysql.com", "burger", "burger");
       mysql_select_db("cs3216",$db);
       $q=mysql_query("UPDATE  user set name='$nm' where contact='$ctact'");
    //   $arr = array('user_id' => $data);
     //  header( 'Content-Type: application/json' );
     //  echo json_encode($arr);
   }
}
?>