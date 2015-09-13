<?php
$hostname_trailsDB= "trailsnc1.db.6131010.hostedresource.com";
$database_trailsDB = "trailsnc1";
$username_trailsDB = "trailsnc1";
$password_trailsDB = "cold187Um!";
 
$con=mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name")or die("cannot select DB");
$sql = "select * from servers"; 
$result = mysql_query($sql);
$json = array();
 
if(mysql_num_rows($result)){
while($row=mysql_fetch_assoc($result))
                $output[]=$row;
                print(json_encode($output));
}
mysql_close($con);

?> 