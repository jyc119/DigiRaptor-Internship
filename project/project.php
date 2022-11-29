<?php 

  include('../config/db_connect.php');

  // Write query for all data
  $sql = 'SELECT * FROM project ORDER BY id';

 // Make query and get result
  $results = mysqli_query($conn,$sql);

 // fetch the resulting rows as an array
  $projects = mysqli_fetch_all($results, MYSQLI_ASSOC);

 //echo '<pre>', print_r($results,1),'</pre>';
 //echo '<pre>', print_r($studentrecs,1),'</pre>';
 //echo print_r($results,1);
 //echo print_r($studentrecs,1);

 // free result from memory
  mysqli_free_result($results);

 // close connection
  mysqli_close($conn);

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
      <th>Name</th>
      <th>Description</th>
      <th>Start Date</th>
      <th>End Date</th>
      <th>Option</th>
      <th>Edited on</th>
    </tr>

    <?php foreach($projects as $result){ 
      echo "<tr><td>" . $result['id'] . "</td><td> " . $result['name'] . "</td><td>" . $result['description'] . "</td><td> " . $result['startdate'] . "</td><td>" . $result['enddate'] . "</td>";
      echo "<td><a href='update.php?id=". $result['id'] . "'>Edit</a></td>";
      echo "<td>" . $result['edited_on'] . "</td><td>";
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
