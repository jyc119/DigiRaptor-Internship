<?php 

  include('../config/db_connect.php');

 // Write query for all data
  $sql = 'SELECT * FROM newstaff ORDER BY age';

 // Make query and get result
  $results = mysqli_query($conn,$sql);

 // fetch the resulting rows as an array
  $staff = mysqli_fetch_all($results, MYSQLI_ASSOC);

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

<h1 class="center grey-text">Staff Information</h1>

  <table>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Age</th>
      <th>Option</th>
      <th>Edited on</th>
      <th>Profile Pic</th>
    </tr>

    <?php foreach($staff as $result){ 
      echo "<tr><td>" . $result['id'] . "</td><td> " . $result['name'] . "</td><td> " . $result['email'] . "</td><td> " . $result['age'] .  "</td>";
      echo "<td><a href='update.php?id=". $result['id'] . "'>Edit</a></td>";
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

</html> 