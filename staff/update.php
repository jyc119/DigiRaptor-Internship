<?php

include('../config/db_connect.php');
//to see wat passed in
echo '<pre>',print_r($_POST,1),'</pre>';
echo "<hr>";

 // Write query to get the relevant record that is updated

 // u need add where condition to get that specific id data
 // $sql_name = 'SELECT name, email, age FROM staff where id = '.$id.'; ';
 // or if using " " , the php parameter can diferent use in the " "
$id = '';
if(isset($_POST['userid'])){
  $id = $_POST['userid'];
}
if(isset($_GET['id'])){
  $id = $_GET['id'];
}
echo 'The id for this staff is: ' . $id;
$sql_name = "SELECT * FROM newstaff where id = $id; ";

 // Make query and get result
$results_originalname = mysqli_query($conn,$sql_name);

 // fetch the resulting rows as an array
$staff_lib = mysqli_fetch_all($results_originalname, MYSQLI_ASSOC);
if(!empty($staff_lib)){
  $staff = $staff_lib[0];
}else{
  echo "<br><br>invalid ID";
  exit;
}
echo '<pre>',print_r($staff,1),'</pre>';


$name = $age = $email = '';
$errors = array('email' => '' , 'name' => '', 'age' => '');

if(isset($_POST['userid'])){

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

    // create sql
    echo $sql = "UPDATE newstaff SET email = '".$_POST['email']."' , name = '".$_POST['name']."' , age = '".$_POST['age']."' , imgfile = '".$_FILES['uploadfile']['name']."' WHERE id = '" . $_POST['userid'] . "' ";
  
    // save to db and check

    if(mysqli_query($conn, $sql)){
    // success
      move_uploaded_file($tempname, $folder);
      header('Location: index.php');
      echo "<br><br>UPDATE DONE<br><br>";
    } else{
    // error
    echo 'querry error: ' . mysqli_error($conn);
    }
  }else{
    print_r($errors);
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

<section class="container grey-text">
  <h4 class="center">Update staff details</h4>
  <form class="white" action="update.php" method="POST" enctype="multipart/form-data">

    <label>Name:</label>
    <input type="hidden" name="userid" value="<?= $staff['id']?>">
    <input type="text" name = "name" value="<?= $staff['name'] ?>" > 
    <div style="color:red"><?php echo $errors['name']; ?></div>

    <br>
    <br>

    <label>Email:</label>
    <input type="text" name = "email" value="<?= $staff['email'] ?>" > 
    <div style="color:red"><?php echo $errors['email']; ?></div>

    <br>
    <br>

    <label>Age:</label>
    <input type="text" name = "age" value="<?= $staff['age'] ?>" > 
    <div style="color:red"><?php echo $errors['age']; ?></div>

    <br>
    <br>

    <!-- Employee Profile pic -->
    <label>Employee Profile Pic:</label>
    <div class="form-group">
      <input class="form-control" type="file" name="uploadfile" value="<?= $staff['imgfile'] ?>" />
    </div>

    <div class="center">
    <input type="submit" name = "submit" value = "update" class="btn brand z-depth-0">
    </div>
  </form>
</section> 

</html>