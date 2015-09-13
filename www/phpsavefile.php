<?php

$target_marker_path = "uploads/";

$target_marker_path = $target_marker_path . basename( $_FILES['upload']['name']); 

if(move_uploaded_file($_FILES['upload']['tmp_name'], $target_marker_path)) {
   echo "worked";
   mail("jeremy.callinan@gmail.com","New marker uploaded","There is a new marker uploaded, ". $_FILES['upload']['name']. " is the name.",null);
      mail("jcm66@pitt.edu","New marker uploaded","There is a new marker uploaded, ". $_FILES['upload']['name']. " is the name.",null); 
 
} else{
   echo "didn't work";
   mail("jeremy.callinan@gmail.com","Error - New marker uploaded","Error!",null); 
   mail("jcm66@pitt.edu","New marker uploaded","There is a new marker uploaded, ". $_FILES['upload']['name']. " is the name.",null); 
}



?>