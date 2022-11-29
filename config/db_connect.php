<?php

//connect to database
$conn = mysqli_connect('localhost', 'jordan', 'test1234', 'jordandb');

 // check connection
if(!$conn){
  echo 'Connection error: ' . mysqli_connect_error();
}

?>