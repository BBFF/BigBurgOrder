<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1"> 

	<link rel="stylesheet" href="css/jquery.mobile-1.0b3.min.css" />
	<link rel="stylesheet" href="css/style.css" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.3.min.js"></script>
	<script type="text/javascript" src="js/jquery.mobile-1.0b3.min.js"></script>

<script type="text/javascript">

<?php
$contact=$_GET['phone'];

$url="http://zhenlingtsai.com/cs3216_2/bigburger/user/".$contact;
$json = file_get_contents($url); 
$data = json_decode($json,TRUE);

echo "localStorage.setItem(\"destination\", \"".$_GET['adress1'].", ".$_GET['adress2']."\");";

if($data[0]["id"]==NULL)
{
   //post to database and get userid
 echo "$.ajax( {  type: \"POST\",url: \"user_detail.php\",data: { contact:\"".$contact."\" , name:\"".$_GET['name']."\"}, async:false, success:";
 echo "function(data){";
 echo "localStorage.setItem(\"user_id\",data.user_id);";
 echo "console.log(data.user_id); ";
 echo "}, dataType:\"json\"});";
}
else
{
   echo "localStorage.setItem(\"user_id\",\"".$data[0]["id"]."\");";
   //check if match
   if($data[0]["name"]==$_GET['name'])
   {
      //do nothing
      
   }
   else
   {
     //update
   }

}

?>
</script>
<script type="text/javascript">
//post order
if(localStorage.getItem("total_price") == 0 || localStorage.getItem("total_price") == null)
{
//do nothing
}
else
{

$.ajax({
   type: "POST",
   url: "orders_detail.php",
   data: { user_id: localStorage.getItem("user_id") , destination : localStorage.getItem("destination")},
   dataType:"json",
   async:false,
   success: function(data){
   console.log(data.order_id); 
   localStorage.setItem("order_id",data.order_id);
   }
 });
//$.post("orders_detail.php", { user_id: localStorage.getItem("user_id") , destination : //localStorage.getItem("destination")},
//function(data){
//console.log(data.order_id); 
//localStorage.setItem("order_id",data.order_id);
//}, "json");
}

//post singles
var orderid = localStorage.getItem("order_id");
var index_m=localStorage.getItem("index_m");
		for(var i=0;i<index_m;i++)
		{
                        var urll="single/"+orderid;
                        console.log(urll);
			var arr=localStorage.getItem("main"+i).split('||');
                          $.ajax({
                          type: "POST",
                          url: urll,
                          data: {content : arr[0] , cost : arr[1]},
                          dataType:"json",
                          async:false,
                          success: function(data){
                          console.log(data.single_id); 
                         }
                          });

                     //   $.post(url, {content : arr[0] , cost : arr[1]},
                    //    function(data){
                    //    console.log(data.single_id); 
                     //   }, "json");
                       localStorage.removeItem("main"+i);
		}
var index_d=localStorage.getItem("index_d");
		for(var i=0;i<index_d;i++)
		{
			var arr=localStorage.getItem("drink"+i).split('||');
                        $.post("single_detail.php", { order_id: orderid , content : arr[0] , cost : arr[1]},
                        function(data){
                        console.log(data.single_id); 
                        }, "json");
                       localStorage.removeItem("drink"+i);
		}
var index_dt=localStorage.getItem("index_dt");
		for(var i=0;i<index_dt;i++)
		{
			var arr=localStorage.getItem("desert"+i).split('||');
                        $.post("single_detail.php", { order_id: orderid , content : arr[0] , cost : arr[1]},
                        function(data){
                        console.log(data.single_id); 
                        }, "json");
                       localStorage.removeItem("desert"+i);
		}
var index_s=localStorage.getItem("index_s");
		for(var i=0;i<index_s;i++)
		{
			var arr=localStorage.getItem("side"+i).split('||');
                        $.post("single_detail.php", { order_id: orderid , content : arr[0] , cost : arr[1]},
                        function(data){
                        console.log(data.single_id); 
                        }, "json");
                       localStorage.removeItem("side"+i);
		}


localStorage.removeItem("index_m");
localStorage.removeItem("index_d");
localStorage.removeItem("index_s");
localStorage.removeItem("index_dt");
localStorage.removeItem("total_price");

</script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-25386055-2']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>
<body>
<div data-role="page" id="foo">

	<div data-role="header">
      <h1>Big Burg Fast Fries (BBFF)</h1>
    </div><!-- /header -->
<center>
<div id="msg">
</div>
<div id="status">
</div>
<a href="#" data-role="button" onClick="track()" id="track_button">Track progress</a>
    <div data-role="content">
</div>
</div>
<script type="text/javascript">
var div=document.getElementById('msg');
div.innerHTML="you order has been submitted <br> order id: "+orderid+"<br> destination: "+localStorage.getItem("destination");
function track(){
var button=document.getElementById('track_button');
button.innerHTML="Tracking..";
var stat=document.getElementById('status');
var u_id=localStorage.getItem("user_id");
$.get("orders_detail.php", { userid: u_id},
function(data){
console.log(data[0].status); 
if(data[0].status=="processing")
stat.innerHTML="your order is currently being put together by bob";
else
{
   if(data[0].status=="delivering")
   stat.innerHTML="your order is dispatched and on its way";
   else
   stat.innerHTML="your order has arrived";
}
}, "json");

button.innerHTML="Track progress";
}
</script>
</body>
</html>