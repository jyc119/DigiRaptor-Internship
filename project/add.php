<?php
include('../config/db_connect.php');

function compareID($database_array, $x){

}

$errors = array('leaderid' => '' , 'name' => '', 'description' => '' , 'startdate' => '', 'enddate' => '');

// Query to get all staff ids
$sql= "SELECT * FROM staff";
$results = mysqli_query($conn,$sql);
$staff_lib = mysqli_fetch_all($results, MYSQLI_ASSOC);
$size = count($staff_lib);

if(isset($_POST['submit'])){

  // check project id
  $id = $_POST['id'];

  // check id
  $teamleaderid = $_POST['teamleaderid'];
  
  if($teamleaderid == 'Default'){
    $errors['leaderid'] = 'Please select an option';
  }else{
    foreach ($_POST['teamleaderid'] as $subject){
      print "You selected $subject<br/>";
    }
  }
  
  //////////////////// 

  /*
  // Check if form is submitted successfully
    
  // Check if any option is selected
  if(isset($_POST["subject"]))
  {
      // Retrieving each selected option
      foreach ($_POST['subject'] as $subject)
          print "You selected $subject<br/>";
  }
  else{
  echo "Select an option first !!";
  }  
  */
  ////////////////////  

  // check name
  if(empty($_POST['name'])){
  $errors['name'] = 'A name is required <br />';
  }else{
  $name = $_POST['name'];
  if(!preg_match('/^[a-zA-Z\s]+$/', $name)){
    $errors['name'] = 'Names must be letters and spaces only';
  }
  }

  // check description
  if(empty($_POST['description'])){
  $errors['description'] = 'A description is required <br />';
  }else{
  $description = $_POST['description'];
  if(!preg_match('/^[a-zA-Z\s]+/', $description)){
    $errors['description'] = 'Description must be letters and numbers only';
  }
  }

  // check startdate
  if(empty($_POST['startdate'])){
  $errors['startdate'] = 'A start date is required <br />';
  }else{
    $startdate = $_POST['startdate'];
    if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$_POST['startdate'])){
      $errors['startdate'] = 'Start date must be a valid date in form YYYY-MM-DD';
    }
  }

  // check endate
  if(empty($_POST['enddate'])){
  $errors['enddate'] = 'An end date is required <br />';
  }else{
    $enddate = $_POST['enddate'];
    if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$_POST['enddate'])){
      $errors['enddate'] = 'End date must be a valid date in form YYYY-MM-DD';
    }
  }

  if (!array_filter($errors)){
  
  echo $name;
  echo "teamleader: '$teamleaderid'";
  echo $description;
  echo $startdate;
  echo $enddate;  
  
  
  // create sql
  $sql = "INSERT INTO project(name,description,startdate,enddate) VALUES('$name', '$description', '$startdate','$enddate') ";
  
   // save to db and check
  
  if(mysqli_query($conn, $sql)){
    // create sql to 
    $last_id = mysqli_insert_id($conn);

    foreach ($_POST['teamleaderid'] as $subject){
      $next_sql = "INSERT INTO project_staff(teamleader_id, project_id) VALUES('$subject', '$last_id')";
      mysqli_query($conn, $next_sql);
    }

    header('Location: project.php');

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
  <h4 class="center">Add a project</h4>
  <form class="white" action="add.php" method="POST" enctype="multipart/form-data">

  <input type="hidden" name = "id" value = ""> 
  
  <!-- Name Field -->
  <label>Project Name:</label>
  <input type="text" name = "name" value = ""> 
  <div style="color:red"><?php echo $errors['name']; ?></div>

  <br>
  <br>

  <!-- Project Team leader id Field -->
  <label for="teamleaderid">Project Team Leader ID:</label>
  <select name="teamleaderid[]" id="teamleaderid" multiple>

    <option value="Default"></option>

    <?php 
      for($x = 0; $x < $size; $x+=1){
        echo "<option value='". $staff_lib[$x]['id'] ."'>" .$staff_lib[$x]['name'] . "(".$staff_lib[$x]['id'].")". "</option>";
      }
    ?>

  </select>

  <div style="color:red"><?php echo $errors['leaderid']; ?></div>

  <br>
  <br>

  <!-- Project Description -->
  <label>Project Description:</label>
  <input type="text" name = "description" value = ""> 
  <div style="color:red"><?php echo $errors['description']; ?></div>

  <br>
  <br>

  <!-- Project Start date -->
  <label>Project Start Date:</label>
  <input type="date" name = "startdate" value = ""> 
  <div style="color:red"><?php echo $errors['startdate']; ?></div>

  <br>
  <br>

  <!-- Project end date -->
  <label>Project End Date:</label>
  <<input type="date" name = "enddate" value = ""> 
  <div style="color:red"><?php echo $errors['enddate']; ?></div>

  <br>
  <br>

  <div class="form-group">
    <button class="btn btn-primary" type="submit" name="submit">UPLOAD</button>
  </div>
  </form>
</section>

</html> 