<?php

include('../config/db_connect.php');

$name = $age = $email = '';
$errors = array('email' => '' , 'name' => '', 'age' => '');

if(isset($_POST['submit'])){

  $filename = $_FILES["uploadfile"]["name"];
  $staffid = $_POST['staffid'];
  $tempname = $_FILES["uploadfile"]["tmp_name"];
  $folder = "./images/" . $filename;

  // check email
  if(empty($_POST['email'])){
  $errors['email'] = 'An email is required <br />';
  }else{
  $email = $_POST['email'];
  if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $errors['email'] = 'Email must be a valid email address';
  }
  }

  // check name
  if(empty($_POST['name'])){
  $errors['name'] = 'A name is required <br />';
  }else{
  $name = $_POST['name'];
  if(!preg_match('/^[a-zA-Z\s]+$/', $name)){
    $errors['name'] = 'Names must be letters and spaces only';
  }
  }

  // check age
  if(empty($_POST['age'])){
  $errors['age'] = 'An age is required <br />';
  }else{
  $age = $_POST['age'];
  if(!filter_var($age, FILTER_VALIDATE_INT)){
    $errors['age'] = 'Age is a number only';
  }
  }

  if (!array_filter($errors)){

  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $id = mysqli_real_escape_string($conn, $_POST['id']);
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $age = mysqli_real_escape_string($conn, $_POST['age']);
  $deletefile = "./images/dalinar.jpg";

  unlink($deletefile);

   // create sql
  $sql = "INSERT INTO staff(email,name,age,imgfile) VALUES('$email', '$name', '$age' , '$filename') ";

   // save to db and check

  if(mysqli_query($conn, $sql)){
    // success
    move_uploaded_file($tempname, $folder);
    header('Location: index.php');
  } else{
    // error
    echo 'querry error: ' . mysqli_error($conn);
  }

  }

 } // end of GET check

?>

<!DOCTYPE html>
<html>

<section class="container grey-text">
  <h4 class="center">Add an employee</h4>
  <form class="white" action="add.php" method="POST" enctype="multipart/form-data">

  <input type="hidden" name = "id" value = "<?php echo $id ?>"> 
  
  <!-- Email Field -->
  <label>Your Email:</label>
  <input type="text" name = "email" value = "<?php echo $email ?>"> 
  <div style="color:red"><?php echo $errors['email']; ?></div>

  <br>
  <br>

  <!-- Employee name Field -->
  <label>Employee Name:</label>
  <input type="text" name = "name" value = "<?php echo $name ?>"> 
  <div style="color:red"><?php echo $errors['name']; ?></div>

  <br>
  <br>

  <!-- Employee age Field -->
  <label>Employee Age:</label>
  <input type="text" name = "age" value = "<?php echo $age ?>"> 
  <div style="color:red"><?php echo $errors['age']; ?></div>

  <br>
  <br>

  <!-- Employee Profile pic -->
  <label>Employee Profile Pic:</label>
  <div class="form-group">
    <input class="form-control" type="file" name="uploadfile" value="" />
  </div>

  <br>
  <br>

  <div class="form-group">
    <button class="btn btn-primary" type="submit" name="submit">UPLOAD</button>
  </div>
  </form>
</section>

</html> 