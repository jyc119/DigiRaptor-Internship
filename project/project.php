<?php 

  include('../config/db_connect.php');

  // Write query for all data
  $sql = 'SELECT * FROM newproject ORDER BY id';

 // Make query and get result
  $results = mysqli_query($conn,$sql);

 // fetch the resulting rows as an array
  $projects = mysqli_fetch_all($results, MYSQLI_ASSOC);

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

<h1 class="center grey-text">Project Information</h1>

<table>
    <tr>
      <th>ID</th>
      <th>Project Name</th>
      <th>Description</th>
      <th>Staff</th>
      <th>Start Date</th>
      <th>End Date</th>
      <th>Option</th>
      <th>Edited on</th>
    </tr>

    <?php foreach($projects as $result){ 

      //-----------Query to find the staff associated with this project info------------------
      $sql = "SELECT * FROM newproject_staff WHERE project_id = '".$result['id']."' ";
      $results = mysqli_query($conn,$sql);
      $staffmember = mysqli_fetch_all($results, MYSQLI_ASSOC);
      //echo '<pre>', print_r($staffmember,1),'</pre>';
      $staffarray = array();
      foreach($staffmember as $staff){
        $sqlstaff = "SELECT * FROM newstaff WHERE id = '".$staff['staff_id']."'";
        $staffresults = mysqli_query($conn,$sqlstaff);
        $staffOnlymember = mysqli_fetch_all($staffresults, MYSQLI_ASSOC);
        //echo '<pre>', print_r($staffOnlymember,1),'</pre>';
        array_push($staffarray, $staffOnlymember[0]['name']);
      }

      echo "<tr><td>" . $result['id'] . "</td><td> " . 
            $result['name'] . "</td><td>" . 
            $result['description'] . "</td><td> ";
            foreach($staffarray as $indivstaff){
              echo $indivstaff . "<br><br>";
            }
            
      //-------------------------------------------------------------------------------------- 
      echo "</td><td>" .$result['startdate'] . "</td><td>".
      $result['enddate'] . "</td>";
      echo "<td><a href='update.php?id=". $result['id'] . "'>Edit</a></td>";
      echo "<td>" . $result['edited_on'] . "</td>";
      ?> 
      </tr>
  <?php
    } 
    ?>
    </table>

  
  <div class="card-action right-align">
    <a href="add.php" class="brand-text">Add a project</a>
  </div>

</html> 
