<?php

include('../config/db_connect.php');

// Determines the query to update table
function sqlFilterQuery(){
  $age = $_POST['age'];
  $email = $_POST['email'];
  $name = $_POST['name'];

  if(!empty($age)){
    return "age";
  }else if(!empty($email)){
    return "email";
  }else if(!empty($name)){
    return "name";
  }

  return "yea no come on man";

}

$sql= "SELECT id, name, email, age FROM newstaff";
// Make query and get result
$results = mysqli_query($conn,$sql);

 // fetch the resulting rows as an array
$staff_lib = mysqli_fetch_all($results, MYSQLI_ASSOC);

// For this iteration of records, set the records to another variable
$staff_current = $staff_lib;
$getError = False;
$size = count($staff_lib);

$errors = array('email' => '' , 'name' => '', 'age' => '', 'allempty' => '');

if(isset($_POST['userid'])){

  // A field must be filled 
  if(empty($_POST['email']) && (empty($_POST['name'])) && (empty($_POST['age']))){
    $errors['allempty'] = 'A field needs to be updated';
  }

  // check email
  if(!empty($_POST['email'])){
    $email = $_POST['email'];
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      $errors['email'] = 'Email must be a valid email address';
    }
  }

  // check name
  if(!empty($_POST['name'])){
    $name = $_POST['name'];
    if(!preg_match('/^[a-zA-Z\s]+$/', $name)){
      $errors['name'] = 'Names must be letters and spaces only';
    }
  }


  // check age
  if(!empty($_POST['age'])){
    $age = $_POST['age'];
    if(!filter_var($age, FILTER_VALIDATE_INT)){
      $errors['age'] = 'Age is a number only';
    }
  }

  if (!array_filter($errors)){

    $str = sqlFilterQuery();
    echo $str;

    if($str == "name"){
      $sql_filter = "SELECT id, name, email, age FROM newstaff WHERE name LIKE '%".$_POST['name']."%' ";
    }elseif($str == "age"){
      $sql_filter = "SELECT id, name, email, age FROM newstaff WHERE age = '".$_POST['age']."' ";
    }elseif($str == "email"){
      $sql_filter = "SELECT id, name, email, age FROM newstaff WHERE email LIKE '%".$_POST['email']."%' ";
    }

    $filter_result = mysqli_query($conn,$sql_filter);

    // fetch the resulting rows as an array
    $filter_staff = mysqli_fetch_all($filter_result, MYSQLI_ASSOC);
    echo "<br><br>FILTER DONE<br><br>";
    } else{
    // error
    echo 'querry error: ' . mysqli_error($conn);
    }
    
  }else{
  }

?>

<!DOCTYPE html>
<html>

<body>
<style>  
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
</style>
</body>

<br>
<br>

<!-- Styling the table -->
<head>
<style>

.content {
  max-width: 1000px;
  margin: auto;
  background: white;
  padding: 10px;
}

table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
</head>

<!-- Form -->
<div class="content">
<section class="container grey-text">
<h4 class="center">Filter Staff Details</h4>
<form class="white" action="filter.php" method="POST">

  <label>Name:</label>
  <input type="hidden" name="userid">
  <input type="text" name = "name" > 
  <div style="color:red"><?php echo $errors['name']; ?></div>

  <label>Email:</label>
  <input type="text" name = "email" > 
  <div style="color:red"><?php echo $errors['email']; ?></div>

  <label>Age:</label>
  <input type="text" name = "age" > 
  <div style="color:red"><?php echo $errors['age']; ?></div>
  <div style="color:red"><?php echo $errors['allempty']; ?></div>

  <div class="center">
  <input type="submit" name = "submit" value = "update" class="btn brand z-depth-0">
  </div>
</form>

<!-- Table for staff information -->
<h1 class="center grey-text">Staff Information</h1>

  <table>
    <tr>
      <th>Name</th>
      <th>Email</th>
      <th>Age</th>
    </tr>

  
  <?php 
  
    if(isset($_POST['userid']) && !array_filter($errors)){
      foreach($filter_staff as $result){
        echo "<tr><td>" . $result['name'] . "</td><td> " . $result['email'] . "</td><td> " . $result['age'] .  "</td>";
      }
    }else{
      foreach($staff_lib as $result){ 
      echo "<tr><td>" . $result['name'] . "</td><td> " . $result['email'] . "</td><td> " . $result['age'] .  "</td>";
      }
    }

  ?>

  </table>
<div class="card-action right-align">
  <a href="filter.php" class="brand-text">Reset Filter</a>
</div>

<div class="card-action right-align">
  <a href="index.php" class="brand-text">Back to Mainpage</a>
</div>
</section> 
</div>


<div class="navbar">
  <a href="/demo/staff/staffPage.php" class="brand-text">Staff Table</a>
  <a href="/demo/index.php" class="brand-text">Main Page</a>
</div>  

</html> 