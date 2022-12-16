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
  $sql = "INSERT INTO newstaff(email,name,age,imgfile) VALUES('$email', '$name', '$age' , '$filename') ";

   // save to db and check

  if(mysqli_query($conn, $sql)){
    // success
    move_uploaded_file($tempname, $folder);
    header('Location: staffPage.php');
  } else{
    // error
    echo 'querry error: ' . mysqli_error($conn);
  }

  }

 } // end of GET check

?>

<!DOCTYPE html>
<html>

<body>
<style>  

.content {
  max-width: 1000px;
  margin: auto;
  background: white;
  padding: 10px;
}

  .navbar {
  overflow: hidden;
  background-color: #333;
  position: fixed;
  top: 0;
  width: 100%;
}

.navbar a {
  float: left;
  display: block;
  color: #f2f2f2;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
}

.navbar a:hover {
  background: #ddd;
  color: black;
}

input[type=text], select {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

input[type=submit] {
  width: 100%;
  background-color: #4CAF50;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

input[type=submit]:hover {
  background-color: #45a049;
}

div {
  border-radius: 5px;
  background-color: #f2f2f2;
  padding: 20px;
}
</style>
</body>

<br>
<br>

<div class="content">
<section class="container grey-text">
  <br>
  <br>
  <h2 class="center">Add an employee</h2>
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

</div>  

<div class="navbar">
    <a href="/demo/staff/staffPage.php" class="brand-text">Staff Table</a>
    <a href="/demo/index.php" class="brand-text">Main Page</a>
  </div>  

</html> 