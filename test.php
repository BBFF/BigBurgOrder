<?php

?>
<form name="form1" method="post" action="">
<input type="text" name="textfield"><br />
<input type="submit" name="Submit" value="post">
</form>

<form name="form1" method="put" action="">
<input type="text" name="textfield"><br />
<input type="submit" name="Submit" value="put">
</form>

<form name="form1" method="get" action="">
<input type="text" name="textfield"><br />
<input type="submit" name="Submit" value="get">
</form>

<form name="form1" method="delete" action="">
<input type="text" name="textfield"><br />
<input type="submit" name="Submit" value="delete">
</form>
<?php

{
$roughHTTPPOST = readfile("php://input");
echo $roughHTTPPOST;
}
?>