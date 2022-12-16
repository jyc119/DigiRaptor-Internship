<?php

include('../config/db_connect.php');

function compareLib($database_array, $x){
  //return $database_array[$x]['age'] + 1;
  $compareAge = ($database_array[$x]['age'] == $_POST['age_'.$x]);
  $compareEmail = ($database_array[$x]['email'] == $_POST['email_'.$x]);
  $compareName = ($database_array[$x]['name'] == $_POST['name_'.$x]);

  return $compareAge && $compareEmail && $compareName;
}

  echo '<pre>',print_r($_POST,1),'</pre>';
  echo "<hr>";

$sql= "SELECT id, name, email, age FROM newstaff";
// Make query and get result
$results = mysqli_query($conn,$sql);

 // fetch the resulting rows as an array
$staff_lib = mysqli_fetch_all($results, MYSQLI_ASSOC);
$getError = False;
$size = count($staff_lib);
/*
$firststaff = $staff_lib[0];
$secondstaff = $staff_lib[1];

echo '<pre>',print_r($firststaff,1),'</pre>';
echo '<pre>',print_r($secondstaff,1),'</pre>';
*/ 

if(isset($_POST['userid_0'])){
  for ($x = 0; $x < $size; $x+=1) {

  $update = compareLib($staff_lib, $x);
  $errors[$x]['email'] = '';
  $errors[$x]['age'] = '';
  $errors[$x]['name'] = '';  

  $_POST['userid'] = '';
  $_POST['email'] = '';
  $_POST['name'] = '';
  $_POST['age'] = '';

  $_POST['userid'] = $_POST['userid_'.$x];
  $_POST['email'] = $_POST['email_'.$x];
  $_POST['name'] = $_POST['name_'.$x];
  $_POST['age'] = $_POST['age_'.$x];


  // $p_id = $_POST['userid_'.$x];
  // $p_email = $_POST['email_'.$x];
  // $p_name = $_POST['name_'.$x];
  // $p_age = $_POST['age_'.$x];
  
  if(isset($_POST['userid'])){

    // check email
    if(empty($_POST['email'])){
      $errors[$x]['email'] = 'An email is required <br />';
    }else{
      $email = $_POST['email'];
      if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      $errors[$x]['email'] = 'Email must be a valid email address';
      }
    }

    // check name
    if(empty($_POST['name'])){
      $errors[$x]['name'] = 'A name is required <br />';
    }else{
      $name = $_POST['name'];
      if(!preg_match('/^[a-zA-Z\s]+$/', $name)){
      $errors[$x]['name'] = 'Names must be letters and spaces only';
      }
    }
    

    // check age
    if(empty($_POST['age'])){
      $errors[$x]['age'] = 'An age is required <br />';
    }else{
      $age = $_POST['age'];
      if(!filter_var($age, FILTER_VALIDATE_INT)){
      $errors[$x]['age'] = 'Age is a number only';
      }
    }

    if (!array_filter($errors[$x]) && !$update ){

      echo "Name that is updated: " . $_POST['name']."" ;
      // create sql
      $sql = "UPDATE newstaff SET email = '".$_POST['email']."' , name = '".$_POST['name']."' , age = '".$_POST['age']."' WHERE id = '" . $_POST['userid'] . "' ";
    
      // save to db and check

      if(mysqli_query($conn, $sql)){
      } else{
      // error
      echo 'querry error: ' . mysqli_error($conn);
      }
    }elseif(array_filter($errors[$x])){
      $getError = True;
      print_r($errors); 
    }
  }
  }

  if ($getError == False){
    //success
    //header('Location: index.php');
    echo "<br><br>UPDATE DONE<br><br>";

  }
} 

?>

<!DOCTYPE html>
<html>

<style>
  .content {
  max-width: 1000px;
  margin: auto;
  background: white;
  padding: 10px;
}
</style>

<div class="content">
<section class="container grey-text">
<h4 class="center">Update staff details</h4>
<form class="white" action="updateAll.php" method="POST">

  <?php
    for ($x = 0; $x < $size; $x+=1) {
      echo "Name: ";
      echo "<input type='hidden' name='userid_".$x."' value='". $staff_lib[$x]['id'] ."'>";
      echo "<input type='text' name='name_".$x."' value='". $staff_lib[$x]['name'] ."'>";
      //echo "<div style='color:red'> '". $errors['name'] ."' </div>";
      if(isset($_POST['userid_0'])){
        echo "<div style='color:red'> ". $errors[$x]['name'] ." </div>";
      }

      echo "<br>";
      echo "<br>";
      
      echo "<label>Email: </label>"; 
      echo "<input type='text' name = 'email_".$x."' value='" . $staff_lib[$x]['email'] ."' >";
      if(isset($_POST['userid_0'])){
        echo "<div style='color:red'> ". $errors[$x]['email'] ." </div>";
      }

      echo "<br>";
      echo "<br>";

      echo "<label>Age: </label>"; 
      echo "<input type='text' name = 'age_".$x."' value='" . $staff_lib[$x]['age'] ."' > <br> <br>";
      if(isset($_POST['userid_0'])){
        echo "<div style='color:red'> ". $errors[$x]['age'] ." </div>";
      }

      echo "<br>";
      echo "<br>";
    }
  ?>

  <div class="center">
    <input type="submit" name = "submit" value = "update" class="btn brand z-depth-0">
  </div>
</form>
</div>
  
</html> 