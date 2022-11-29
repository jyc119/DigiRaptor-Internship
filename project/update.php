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

//------------ Query to initialise the current record that needs to be updated--------------

echo 'The id for this staff is: ' . $id;
$sql_name = "SELECT * FROM project where id = $id; ";

 // Make query and get result
$results_originalname = mysqli_query($conn,$sql_name);

 // fetch the resulting rows as an array
$project_lib = mysqli_fetch_all($results_originalname, MYSQLI_ASSOC);
if(!empty($project_lib)){
  $curproject = $project_lib[0];
}else{
  echo "<br><br>invalid ID";
  exit;
}
echo '<pre>',print_r($curproject,1),'</pre>';

//-------------------------------------------------------------------------------------------


//------------ Query to obtain the teamleader id from project_staff--------------
echo "Hello testing";
$staffproj = "SELECT * FROM project_staff where project_id = $id; ";
$res_staffproj = mysqli_query($conn,$staffproj);
$staffproject_lib = mysqli_fetch_all($res_staffproj, MYSQLI_ASSOC);
$tleaderid_arr = array();
foreach ($staffproject_lib as $result){
  print "The teamleaderid for this project is: " .$result['teamleader_id'];
  array_push($tleaderid_arr, $result['teamleader_id']);
}

"<br>
<br>";

print_r($tleaderid_arr);
echo '<pre>',print_r($staffproject_lib,1),'</pre>';

//-------------------------------------------------------------------------------------------

//------------ Query to obtain all the staff from staff table--------------

$sql= "SELECT * FROM staff";
$results = mysqli_query($conn,$sql);
$allstaff = mysqli_fetch_all($results, MYSQLI_ASSOC);
$size = count($allstaff);

echo '<pre>',print_r($allstaff,1),'</pre>';

//-------------------------------------------------------------------------------------------

//--------Testing in_array function--------------
for($x=0; $x<$size; $x+=1){
  if(in_array($allstaff[$x]['id'], $tleaderid_arr)){
    echo "This ID is TRUE: " . $allstaff[$x]['id'];
    "<br>";
    "<br>";
  }else{
    echo "This ID is FALSE: " . $allstaff[$x]['id'];
    "<br>";
    "<br>";
  }
}

//-------------------------------------------------------------------------------------------

$name = $leaderid = $description = $startdate = $enddate = '';
$errors = array('leaderid' => '' , 'name' => '', 'description' => '' , 'startdate' => '', 'enddate' => '');

if(isset($_POST['userid'])){

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

    // create sql
    echo $sql = "UPDATE project SET name = '".$_POST['name']."', description = '".$_POST['description']."' , startdate = '".$_POST['startdate']."' , enddate = '".$_POST['enddate']."' WHERE id = '" . $_POST['userid'] . "' ";
  
    // save to db and check

    if(mysqli_query($conn, $sql)){
    // success
      $delete_sql = "DELETE FROM project_staff WHERE project_id = $id";
      mysqli_query($conn, $delete_sql);

      foreach ($_POST['teamleaderid'] as $subject){

      $next_sql = "INSERT INTO project_staff(teamleader_id, project_id) VALUES('$subject', '$id')";
      
      mysqli_query($conn, $next_sql);
    }
      header('Location: project.php');
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

<section class="container grey-text">
  <h4 class="center">Update Project details</h4>
  <form class="white" action="update.php" method="POST" enctype="multipart/form-data">

    <label>Name:</label>
    <input type="hidden" name="userid" value="<?= $curproject['id']?>">
    <input type="text" name = "name" value="<?= $curproject['name'] ?>" > 
    <div style="color:red"><?php echo $errors['name']; ?></div>

    <br>
    <br>

    <!-- Project Team leader id Field -->
    <label for="teamleaderid">Project Team Leader ID:</label>
    <select name="teamleaderid[]" id="teamleaderid" multiple>

      <?php 
        for($x = 0; $x < $size; $x+=1){
          
          if(in_array($allstaff[$x]['id'], $tleaderid_arr)){
            echo "<option value='". $allstaff[$x]['id'] ."' selected >" .$allstaff[$x]['name'] . "(".$allstaff[$x]['id'].")". "</option>";
          }else{
            echo "<option value='". $allstaff[$x]['id'] ."'>" .$allstaff[$x]['name'] . "(".$allstaff[$x]['id'].")". "</option>";
          }
        }
      ?>

    </select>

  <div style="color:red"><?php echo $errors['leaderid']; ?></div>

    <br>
    <br>

    <label>Description:</label>
    <input type="text" name = "description" value="<?= $curproject['description'] ?>" > 
    <div style="color:red"><?php echo $errors['description']; ?></div>

    <br>
    <br>

    <label>Project Start Date:</label>
    <input type="date" name = "startdate" value = "<?= $curproject['startdate'] ?>"> 
  <div style="color:red"><?php echo $errors['startdate']; ?></div>

    <label>Project End Date:</label>
    <input type="date" name = "enddate" value = "<?= $curproject['enddate'] ?>"> 
  <div style="color:red"><?php echo $errors['enddate']; ?></div>

    <div class="center">
    <input type="submit" name = "submit" value = "update" class="btn brand z-depth-0">
    </div>
  </form>
</section> 

</html>