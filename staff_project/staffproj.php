<?php 

  include('../config/db_connect.php');

  // Write query for all data
  $sql = 'SELECT * FROM project_staff ORDER BY id';

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

<h1 class="center grey-text">Staff Project Information</h1>

<table>
    <tr>
      <th>ID</th>
      <th>Team Leader ID</th>
      <th>Staff ID</th>
      <th>Project ID</th>
      <th>Option</th>
      <th>Edited on</th>
    </tr>

    <?php foreach($projects as $result){ 
      echo "<tr><td>" . $result['id'] . "</td><td> " . $result['teamleader_id'] . "</td><td>" . $result['staff_id'] . "</td><td> " . $result['project_id'] . "</td>";
      echo "<td><a href='update.php?id=". $result['id'] . "'>Edit</a></td>";
      echo "<td>" . $result['edited_on'] . "</td><td>";
      ?> 
      </tr>
  <?php
    } 
    ?>
    </table>

</html> 
