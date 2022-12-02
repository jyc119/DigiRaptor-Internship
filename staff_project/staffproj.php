<?php 

  include('../config/db_connect.php');

  // Write query for all data
  $sql = 'SELECT * FROM newproject_staff ORDER BY id';

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

<h1 class="center grey-text">Staff Project Information</h1>

<table>
    <tr>
      <th>ID</th>
      <th>Team Leader ID</th>
      <th>Project ID</th>
      <th>Option</th>
      <th>Edited on</th>
    </tr>

    <?php foreach($projects as $result){ 

      //-------------------Query to obtain all the project names----------------------

      $sqlProj = "SELECT * FROM newproject WHERE id = '".$result['project_id']."'";
      $projresults = mysqli_query($conn, $sqlProj);
      $proj = mysqli_fetch_all($projresults, MYSQLI_ASSOC);
      //------------------------------------------------------------------------------

      //-------------------Query to obtain all the staff names----------------------

      $sqlStaff = "SELECT * FROM newstaff WHERE id = '".$result['teamleader_id']."'";
      $staffresults = mysqli_query($conn, $sqlStaff);
      $staffconcern = mysqli_fetch_all($staffresults, MYSQLI_ASSOC);

      //------------------------------------------------------------------------------  

      echo "<tr><td>" . $result['id'] . "</td><td> " . $result['teamleader_id'] . "(" .$staffconcern[0]['name'].")". "</td><td>" . $result['project_id'] . "(" .$proj[0]['name'].")" . "</td>";
      echo "<td><a href='update.php?id=". $result['id'] . "'>Edit</a></td>";
      echo "<td>" . $result['edited_on'] . "</td><td>";
      ?> 
      </tr>
  <?php
    } 
    ?>
    </table>

</html> 
