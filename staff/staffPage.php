<?php 

  include('../config/db_connect.php');

 // Write query for all data
  $sql = 'SELECT * FROM newstaff ORDER BY id';

 // Make query and get result
  $results = mysqli_query($conn,$sql);

 // fetch the resulting rows as an array
  $staff = mysqli_fetch_all($results, MYSQLI_ASSOC);

 //echo '<pre>', print_r($results,1),'</pre>';
 //echo '<pre>', print_r($studentrecs,1),'</pre>';
 //echo print_r($results,1);
 //echo print_r($studentrecs,1);


?>

<!DOCTYPE html>
<html>

<head>
<style>
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

<h1 class="center grey-text">Staff Information</h1>

  <table>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Age</th>
      <th>Projects</th>
      <th>Option</th>
      <th>Edited on</th>
      <th>Profile Pic</th>
    </tr>

    <?php foreach($staff as $result){ 

      //-----------Query to find the project associated with this staff info------------------
      $sql = "SELECT * FROM newproject_staff WHERE staff_id = '".$result['id']."' ";
      $results = mysqli_query($conn,$sql);
      $projects = mysqli_fetch_all($results, MYSQLI_ASSOC);
      //echo '<pre>', print_r($staffmember,1),'</pre>';
      $projectarray = array();
      foreach($projects as $project){
        $sqlstaff = "SELECT * FROM newproject WHERE id = '".$project['project_id']."'";
        $projectresults = mysqli_query($conn,$sqlstaff);
        $projectOnlymember = mysqli_fetch_all($projectresults, MYSQLI_ASSOC);
        //echo '<pre>', print_r($staffOnlymember,1),'</pre>';
        array_push($projectarray, $projectOnlymember[0]['name']);
      }  

      echo "<tr><td>" . $result['id'] . 
            "</td><td> " . $result['name'] . 
            "</td><td> " . $result['email'] . 
            "</td><td> " . $result['age'] .  "</td><td>";
            foreach($projectarray as $indivstaff){
              echo $indivstaff . "<br><br>";
            }      
      echo "</td><td><a href='update.php?id=". $result['id'] . "'>Edit</a></td>";
      echo "<td>" . $result['edited_on'] . "</td>";
      ?> 
      <td><img src="../images/<?php echo $result['imgfile']; ?>" alt = "" style="max-height:100px;max-width:100px;"></td></tr>
  <?php
    } 
    ?>
    </table>

  <div class="card-action right-align">
    <a href="/demo/staff/add.php" class="brand-text">Add a staff</a>
  </div>

  <div class="card-action right-align">
    <a href="/demo/staff/updateAll.php" class="brand-text">Update Staff</a>
  </div>

  <div class="card-action right-align">
    <a href="/demo/staff/excelForm.php" class="brand-text">Update Staff Form</a>
  </div>

  <div class="card-action right-align">
    <a href="/demo/staff/filter.php" class="brand-text">Filter</a>
  </div>

  <div class="card-action right-align">
    <a href="/demo/staff/report.php" class="brand-text">Report</a>
  </div>

</html> 