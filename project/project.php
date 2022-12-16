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

<head>
<style>

.content {
  max-width: 1000px;
  margin: auto;
  background: white;
  padding: 10px;
}

#staff {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#staff td, #staff th {
  border: 1px solid #ddd;
  padding: 8px;
}

#staff tr:nth-child(even){background-color: #f2f2f2;}

#staff tr:hover {background-color: #ddd;}

#staff th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #04AA6D;
  color: white;
}

</style>
</head>
<div class="content">
<h1 class="center grey-text">Project Information</h1>

<table id="staff">
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
</div>
  <div class="navbar">
    <a href="/demo/index.php" class="brand-text">Main Webpage</a>
    <a href="/demo/project/add.php" class="brand-text">Add a Project</a>
  </div>  

</html> 
