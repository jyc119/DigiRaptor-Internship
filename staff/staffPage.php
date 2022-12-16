<?php 

  include('../config/db_connect.php');

 // Write query for all data
  $sql = 'SELECT * FROM newstaff ORDER BY id';

 // Make query and get result
  $results = mysqli_query($conn,$sql);

 // fetch the resulting rows as an array
  $staff = mysqli_fetch_all($results, MYSQLI_ASSOC);

  $datetoday = date("Y-m-d");

  if(isset($_POST['checkin'])){

    $id = $_POST['inid'];

    // check if record already exists
    $query = "SELECT * FROM attendance WHERE curdate = '$datetoday' AND staffid = $id ";

    $sql_query = mysqli_query($conn,$query);

    // fetch the resulting rows as an array
    $project_lib = mysqli_fetch_all($sql_query, MYSQLI_ASSOC);

    echo '<pre>', print_r($project_lib, 1),'</pre>';
    echo sizeof($project_lib);
    
    if(sizeof($project_lib) == 0){
      // Write query for all data
      $sql = "INSERT INTO attendance(curdate, staffid) VALUES ('$datetoday', '$id')";

      // save to db and check
      if(mysqli_query($conn, $sql)){
        // success
        header('Location: staffPage.php');
      } else{
        // error
        echo 'querry error: ' . mysqli_error($conn);
      }
    } else{
      echo "Alrdy checkin bro";
    }
    
  }

  else if(isset($_POST['checkout'])){

    $id = $_POST['outid'];
    // Write query for all data
    $sql = "UPDATE attendance SET check_out=now() WHERE staffid = '".$_POST['outid']."' AND curdate = '$datetoday'";

    // save to db and check
    if(mysqli_query($conn, $sql)){
      // success
      header('Location: staffPage.php');
    } else{
      // error
      echo 'querry error: ' . mysqli_error($conn);
    }
  }


?>

<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
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
<div class="header">
  <h1>Staff Information</h1>
</div>

<div class="content">
  <table id="staff">
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Age</th>
      <th>Projects</th>
      <th>Option</th>
      <th>Edited on</th>
      <th>Profile Pic</th>
      <th>Check-in</th>
      <th>Check-out</th>
    </tr>

    <?php foreach($staff as $result){ 

      //-----------Query to find the project associated with this staff info------------------
      $sql = "SELECT * FROM newproject_staff WHERE staff_id = '".$result['id']."' ";
      $results = mysqli_query($conn,$sql);
      $projects = mysqli_fetch_all($results, MYSQLI_ASSOC);
      $projectarray = array();
      foreach($projects as $project){
        $sqlstaff = "SELECT * FROM newproject WHERE id = '".$project['project_id']."'";
        $projectresults = mysqli_query($conn,$sqlstaff);
        $projectOnlymember = mysqli_fetch_all($projectresults, MYSQLI_ASSOC);
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
      <td><img src="../images/<?php echo $result['imgfile']; ?>" alt = "" style="max-height:100px;max-width:100px;"></td>
      <td>
        <form method="POST" action="staffPage.php" >
          <input type="hidden" name = "inid" value =  "<?php echo $result['id'] ?>">
          <button class="btn btn-primary" type="submit" name="checkin">Check-In</button>
        </form>
      </td>
      <td>
        <form method="POST" action="staffPage.php">
          <input type="hidden" name = "outid" value =  "<?php echo $result['id'] ?>"> 
          <button class="btn btn-primary" type="submit" name="checkout">Check-Out</button>
        </form> 
      </td></tr>
  <?php
    } 
    ?>
    </table>
</div>    

  <div class="navbar">
    <a href="/demo/index.php" class="brand-text">Main Webpage</a>
    <a href="/demo/staff/add.php" class="brand-text">Add a staff</a>
    <a href="/demo/staff/excelForm.php" class="brand-text">Update Staff</a>
    <a href="/demo/staff/filter.php" class="brand-text">Filter Staff members</a>
    <a href="/demo/staff/report.php" class="brand-text">Report</a>
    <a href="/demo/staff/barchart.php" class="brand-text">Bar Chart Attendance</a>
  </div>  

</html> 